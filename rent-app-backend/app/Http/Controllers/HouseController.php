<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\House;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Rental;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;

class HouseController extends Controller
{
    public function index(){
        $houses = House::all();
        return response()->json($houses);
    }

    public function show($id){
        $house = House::find($id);
        return response()->json($house);
    }

    public function getBookedDates($id){
        $house = House::findOrFail($id);
        $bookedDates = $house->rentals()->select('start_date', 'end_date')->get();
        return response()->json($bookedDates);
    }

    public function checkAvailability(Request $request, $id)
{
    // Валидация входных данных
    $validator = Validator::make($request->all(), [
        'start' => 'required|date',
        'end' => 'required|date|after_or_equal:start',
        'guests' => 'required|integer|min:1',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'available' => false,
            'message' => $validator->errors()->first(),
        ], 422);
    }

    // Находим дом
    $house = House::find($id);
    if (!$house) {
        return response()->json([
            'available' => false,
            'message' => 'Дом не найден',
        ], 404);
    }

    // Проверяем количество гостей
    if ($request->guests > $house->max_guests) {
        return response()->json([
            'available' => false,
            'message' => 'Превышено максимальное количество гостей',
        ], 400);
    }

    // Парсим даты
    $start = Carbon::parse($request->start)->startOfDay();
    $end = Carbon::parse($request->end)->endOfDay();

    // Проверяем пересечения с существующими бронированиями (используем rentals вместо bookings)
    $overlappingRentals = Rental::where('house_id', $id)
        ->where(function ($query) use ($start, $end) {
            $query->where('start_date', '<', $end)
                  ->where('end_date', '>', $start);
        })
        ->exists();

    if ($overlappingRentals) {
        return response()->json([
            'available' => false,
            'message' => 'Даты уже забронированы',
        ], 400);
    }

    // Рассчитываем общее количество дней
    $days = $start->diffInDays($end)-1; // Включая первый и последний день

    // Рассчитываем цену
    $totalPrice = ceil($days * $house->price_per_day);
    

    return response()->json([
        'available' => true,
        'total_price' => $totalPrice,
        'message' => 'Доступно для бронирования',
    ]);
}

protected function formatTelegramMessage(Rental $rental): string
{
    $house = $rental->house;
    $user = $rental->user;


    $start = $rental->start_date ? Carbon::parse($rental->start_date)->format('d.m.Y') : '—';
    $end = $rental->end_date ? Carbon::parse($rental->end_date)->format('d.m.Y') : '—';

    return "
<b>Новое бронирование!</b>

<b>Дом:</b> {$house->name}
<b>Адрес:</b> {$house->address}
<b>Клиент:</b> {$user->name}

<b>Заезд:</b> {$start}
<b>Выезд:</b> {$end}
<b>Гостей:</b> {$rental->number_of_guests}
<b>Сумма:</b> {$rental->total_price} ₽

<b>Статус:</b> <i>Ожидает подтверждения</i>

<a href='#'>Открыть в админке</a>
    ";
}

public function createBooking(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'start' => 'required|date',
        'end' => 'required|date|after_or_equal:start',
        'guests' => 'required|integer|min:1',
    ]);

    if ($validator->fails()) {
        return response()->json(['available' => false, 'message' => $validator->errors()->first()], 422);
    }

    $house = House::find($id);
    if (!$house) {
        return response()->json(['available' => false, 'message' => 'Дом не найден'], 404);
    }

    if ($request->guests > $house->max_guests) {
        return response()->json(['available' => false, 'message' => 'Превышено максимальное количество гостей'], 400);
    }

    $start = Carbon::parse($request->start)->startOfDay();
    $end = Carbon::parse($request->end)->endOfDay();

    $overlappingRentals = Rental::where('house_id', $id)
        ->where(function ($query) use ($start, $end) {
            $query->where('start_date', '<', $end)
                  ->where('end_date', '>', $start);
        })
        ->exists();

    if ($overlappingRentals) {
        return response()->json(['available' => false, 'message' => 'Даты уже забронированы'], 400);
    }

    $days = $start->diffInDays($end) - 1;
    $totalPrice = $days * $house->price_per_day;

    // Создаём бронирование для текущего пользователя
    $rental = Rental::create([
        'house_id' => $id,
        'user_id' => auth('sanctum')->id(),  // Из Sanctum (текущий пользователь)
        'start_date' => $start,
        'end_date' => $end,
        'number_of_guests' => $request->guests,
        'total_price' => $totalPrice,
        'status' => Rental::STATUS_PENDING, // Ожидает подтверждения (без оплаты)
    ]);

    try {
        Telegram::sendMessage([
            'chat_id' => '496280021',
            'text' => $this->formatTelegramMessage($rental),
            'parse_mode' => 'HTML'
        ]);
    } catch (\Exception $e) {
        Log::error('Telegram notification failed: ' . $e->getMessage());
    }

    return response()->json([
        'rental_id' => $rental->id,
        'message' => 'Бронирование создано! Ожидает подтверждения.'
    ]);
}

public function userBookings(Request $request)
    {
        $user = auth('sanctum')->id();
        $bookings = Rental::where('user_id', $user)
            ->with('house') // Загрузи связанные дома (name, photos и т.д.)
            ->orderBy('start_date', 'desc')
            ->get();

        return response()->json($bookings);
    }

   
   
}


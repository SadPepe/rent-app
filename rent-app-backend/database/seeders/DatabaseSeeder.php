<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\House;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

      User::factory(20)->create(); 
           User::firstOrCreate([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => '12345678',
            'role' => 1
        ]);

        User::firstOrCreate([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => '12345678',
            'role' => 0
        ]); 

             $houses = [
            [
                'name' => 'Уютный домик у озера',
                'description' => 'Идеальное место для семейного отдыха. Красивый вид на озеро, все удобства.',
                'address' => 'ул. Озерная, 1, г. Приозерск',
                'price_per_day' => 5000,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'max_guests' => 4,
                'amenities' => json_encode(['Wi-Fi', 'Парковка', 'Кухня']),
                'is_active' => true,
            ],
            [
                'name' => 'Современная вилла с бассейном',
                'description' => 'Просторная вилла с собственным бассейном и зоной для барбекю.',
                'address' => 'ул. Солнечная, 15, г. Сочи',
                'price_per_day' => 15000,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'max_guests' => 8,
                'amenities' => json_encode(['Бассейн', 'Кондиционер', 'Wi-Fi', 'Парковка']),
                'is_active' => true,
            ],
            [
                'name' => 'Лофт в центре города',
                'description' => 'Стильный лофт в самом центре. Рядом с основными достопримечательностями.',
                'address' => 'пр. Центральный, 10, г. Москва',
                'price_per_day' => 8000,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'max_guests' => 2,
                'amenities' => json_encode(['Wi-Fi', 'Кухня', 'Телевизор']),
                'is_active' => true,
            ],
            [
                'name' => 'Деревянный коттедж в лесу',
                'description' => 'Тихое и уединенное место для любителей природы. Вокруг только лес и свежий воздух.',
                'address' => 'Лесной массив, 5, Карелия',
                'price_per_day' => 6000,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'max_guests' => 6,
                'amenities' => json_encode(['Камин', 'Баня', 'Парковка']),
                'is_active' => true,
            ],
            [
                'name' => 'Квартира с видом на море',
                'description' => 'Светлая и уютная квартира с панорамным видом на море.',
                'address' => 'ул. Морская, 25, г. Ялта',
                'price_per_day' => 7500,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'max_guests' => 4,
                'amenities' => json_encode(['Кондиционер', 'Wi-Fi', 'Балкон']),
                'is_active' => true,
            ],
            [
                'name' => 'Дом на дереве',
                'description' => 'Уникальный опыт проживания в доме на дереве. Для настоящих искателей приключений.',
                'address' => 'Заповедник "Сказочный лес"',
                'price_per_day' => 10000,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'max_guests' => 2,
                'amenities' => json_encode(['Вид на природу', 'Терраса']),
                'is_active' => true,
            ],
            [
                'name' => 'Таунхаус в пригороде',
                'description' => 'Комфортабельный таунхаус с небольшим садом. Отлично подходит для семей с детьми.',
                'address' => 'ул. Яблоневая, 8, Подмосковье',
                'price_per_day' => 9000,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'max_guests' => 5,
                'amenities' => json_encode(['Сад', 'Парковка', 'Детская площадка']),
                'is_active' => true,
            ],
            [
                'name' => 'Апартаменты в небоскребе',
                'description' => 'Роскошные апартаменты на 50-м этаже с захватывающим видом на город.',
                'address' => 'Москва-Сити, Башня "Федерация"',
                'price_per_day' => 25000,
                'bedrooms' => 2,
                'bathrooms' => 2,
                'max_guests' => 4,
                'amenities' => json_encode(['Панорамные окна', 'Wi-Fi', 'Консьерж']),
                'is_active' => true,
            ],
            [
                'name' => 'Загородный дом с баней',
                'description' => 'Просторный дом для большой компании. Есть русская баня и бильярд.',
                'address' => 'д. Веселово, 12',
                'price_per_day' => 12000,
                'bedrooms' => 5,
                'bathrooms' => 3,
                'max_guests' => 10,
                'amenities' => json_encode(['Баня', 'Бильярд', 'Мангал']),
                'is_active' => true,
            ],
            [
                'name' => 'Студия у метро',
                'description' => 'Компактная и функциональная студия в двух шагах от метро.',
                'address' => 'ул. Студенческая, 3, г. Санкт-Петербург',
                'price_per_day' => 4000,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'max_guests' => 2,
                'amenities' => json_encode(['Wi-Fi', 'Кухня']),
                'is_active' => false,
            ],
        ];

        foreach ($houses as $house) {
            House::create($house);
        }

    $this->call([
            RentalSeeder::class,
        ]);
    }
}

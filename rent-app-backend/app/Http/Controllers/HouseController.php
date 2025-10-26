<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\House;

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
}

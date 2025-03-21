<?php

namespace App\Http\Controllers\APIs;

use App\Models\medicine;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class MedicineController extends Controller
{

    public function index()
    {
        $medicines=medicine::all();
        if($medicines->isEmpty())
        {
            return response()->json(
                [
                    'status'=>'400',
                    'message'=>'drug not found'
                ]
                );
        }
        else
        {
            return response()->json(
                [
                    'status'=>'200',
                    'data'=>$medicines,
                    'message'=>'data found'
                ]
                );
        }

    }

    public function show($medicine_name)
    {
        $medicine=medicine::where('MedicineName',$medicine_name)->first();
        //dd($medicine);
        if(!$medicine)
        {
            return response()->json(
                [
                    'status'=>'400',
                    'message'=>'drug not found'
                ]
                );
        }
        else
        {
            return response()->json(
                [
                    'status'=>'200',
                    'data'=>$medicine,
                    'message'=>'data found'
                ]
                );
        }
    }
    public function search($var)
    {
        $medicine=medicine::where('MedicineName','like','%'.$var.'%')->get();

        if ($medicine->isEmpty()) {
            return response()->json([
                "status" => 404,
                "message" => "No medicines found"
            ], 404);
        }

        return response()->json([
            "status" => 200,
            "data" => $medicine,
            "message" => "Medicines retrieved successfully"
        ], 200);



}
public function getRandomDrugs()
    {
        $drugs = Cache::remember('random_drugs', 60, function () {
            return medicine::inRandomOrder()->limit(7)->get();
        });

        if ($drugs->isEmpty()) {
            return response()->json([
                "status" => 404,
                "message" => "No medicines found"
            ], 404);
        }

        return response()->json([
            "status" => 200,
            "data" => $drugs,
            "message" => "Medicines retrieved successfully"
        ], 200);
    }



}

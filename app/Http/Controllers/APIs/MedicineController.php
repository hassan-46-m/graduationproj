<?php

namespace App\Http\Controllers\APIs;

use App\Models\medicine;
use Illuminate\Http\Request;
use App\Models\SearchHistory;
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
                    'user_id' => auth()->id(),
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
    $medicine = medicine::where('MedicineName', 'like', '%' . $var . '%')->get();

    if ($medicine->isEmpty()) {
        return response()->json([
            "status" => 404,
            "message" => "No medicines found"
        ], 404);
    }

    // ✅ move this here BEFORE the return
    if ($medicine->count() && auth()->check()) {
        $exists = SearchHistory::where('user_id', auth()->id())
                               ->where('medicine_id', $medicine->first()->id)
                               ->exists();

        if (! $exists) {
            SearchHistory::create([
                'user_id' => auth()->id(),
                'medicine_id' => $medicine->first()->id,
            ]);
        }
    }


    return response()->json([
        "status" => 200,
        'user_id' => auth()->id(),
        'medicine_id' => $medicine->first()->id,
        "data" => $medicine,
        "message" => "Medicines retrieved successfully"
    ], 200);
}



public function getSimilaruseMedicines($name)
{
    // هات الدوا اللي المستخدم بعت اسمه
    $medicine = Medicine::where('MedicineName', $name)->first();

    if (!$medicine) {
        return response()->json([
            'message' => 'Medicine not found'
        ], 404);
    }

    // هات كل الأدوية اللي ليها نفس الاستخدام بالظبط
    $similarMedicines = Medicine::where('Uses', $medicine->Uses)
        ->where('MedicineName', '!=', $name) // نستبعد الدوا نفسه
        ->get();

    return response()->json([
        'medicine' => $medicine,
        'similar_medicines' => $similarMedicines
    ]);
}
public function getSimilarCompositionMedicines($name)
{
    // هات الدوا اللي المستخدم بعت اسمه
    $medicine = Medicine::where('MedicineName', $name)->first();

    if (!$medicine) {
        return response()->json([
            'message' => 'Medicine not found'
        ], 404);
    }

    // هات كل الأدوية اللي ليها نفس الاستخدام بالظبط
    $similarMedicines = Medicine::where('Composition', $medicine->Composition)
        ->where('MedicineName', '!=', $name) // نستبعد الدوا نفسه
        ->get();

    return response()->json([
        'medicine' => $medicine,
        'similar_medicines' => $similarMedicines
    ]);
}
/*public function getRandomDrugs()
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
*/


}

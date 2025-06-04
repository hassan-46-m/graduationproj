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

            if ($medicine->count() && auth()->check()) {
                // نبحث إذا كان المستخدم ده بحث عن نفس الدواء قبل كده
                $existingSearch = SearchHistory::where('user_id', auth()->id())
                                               ->where('medicine_id', $medicine->id)
                                               ->first(); // لو لقيته، نعمل تحديث للوقت

                if ($existingSearch) {
                    // إذا كان في سجل، نعمل تحديث للوقت (مثلاً آخر وقت بحث)
                    $existingSearch->update([
                        'updated_at' => now(), // بنحدث الوقت الحالي
                    ]);
                } else {
                    // إذا مفيش سجل، نضيفه لأول مرة
                    SearchHistory::create([
                        'user_id' => auth()->id(),
                        'medicine_id' => $medicine->id,
                        'searched_at' => now(), // نضيف الوقت الحالي لأول مرة
                    ]);
                }
            }

            return response()->json([
                "status" => 200,
                'user_id' => auth()->id(),
                'medicine_id' => $medicine->id,
                "data" => $medicine,
                "message" => "Medicine retrieved successfully"
            ], 200);

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


}

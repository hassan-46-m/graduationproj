<?php

namespace App\Http\Controllers\APIs;




use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

use App\Models\Feedback;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $data= $request->validate([
            'the_feedback' => 'required|string'
        ]);

        $feed=Feedback::create([
            "user_id" => auth()->id(),
            "the_feedback"=>$data['the_feedback']

        ]);


        return response()->json(
            [
                'status'=>'200',
                'data' =>$feed,

                'message'=>' the feedback created succssfully'
            ]
            );

    }

    /**
     * Display the specified resource.
     */
    public function show(Feedback $feedback)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Feedback $feedback)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'the_feedback' => 'required|string'
        ]);

        $feed = Feedback::where('id', $id)
                        ->where('user_id', auth()->id())
                        ->firstOrFail();

        $feed->update([
            'the_feedback' => $data['the_feedback']
        ]);

        return response()->json([
            'status' => 200,
            'data' => $feed,
            'message' => 'Feedback updated successfully'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $feed = Feedback::where('id', $id)
                        ->where('user_id', auth()->id())
                        ->firstOrFail();

        $feed->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Feedback deleted successfully'
        ]);
    }

}

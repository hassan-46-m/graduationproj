<?php

namespace App\Http\Controllers\APIs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\Feedback;

class FeedbackController extends Controller
{
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

<?php

namespace App\Http\Controllers\APIs;

use App\Models\Favorites;
use Illuminate\Http\Request;
use App\Models\SearchHistory;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function getSearchHistory()
{
    $user = auth()->user();

    $user->load(['searchHistories.medicine']);

    if ($user->searchHistories->isEmpty()) {
        return response()->json([
            'status' => 404,
            'message' => 'No search history found for this user',
        ], 404);
    }

    return response()->json([
        'status' => 200,
        'data' => [
            'user' => $user,
        ],
        'message' => 'Search history retrieved successfully',
    ]);
}



    public function tofavorite(Request $request)
{
    $user = auth()->user();
    $medicineId = $request->input('medicine_id');

    $existing = Favorites::where('user_id', $user->id)
                        ->where('medicine_id', $medicineId)
                        ->first();

    if ($existing) {
        $existing->delete();
        return response()->json(['status' => 200, 'favorited' => false, 'message' => 'Removed from favorites']);
    } else {
        Favorites::create([
            'user_id' => $user->id,
            'medicine_id' => $medicineId,
        ]);
        return response()->json(['status' => 200, 'favorited' => true, 'message' => 'Added to favorites']);
    }
}



public function getFavorites()
{
    $user = auth()->user();

    $favorites = Favorites::with('medicine')
        ->where('user_id', $user->id)
        ->get()
        ->map(function ($fav) {
            return $fav->medicine;
        });

    return response()->json([
        'status' => 200,
        'data' => $favorites,
        'message' => 'Favorite medicines retrieved successfully',
    ]);
}



public function deleteAllHistory()
{
    $user = auth()->user();

    SearchHistory::where('user_id', $user->id)->delete();

    return response()->json([
        'status' => 200,
        'message' => 'All search history deleted successfully',
    ]);
}


}

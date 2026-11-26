<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TripType;
use Illuminate\Http\Request;

class TripTypeController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            TripType::where('user_id', $request->user()->id)->latest()->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
        ]);
        $data['user_id'] = $request->user()->id;
        return response()->json(TripType::create($data), 201);
    }

    public function show(Request $request, TripType $tripType)
    {
        abort_if($tripType->user_id !== $request->user()->id, 403);
        return response()->json($tripType);
    }

    public function update(Request $request, TripType $tripType)
    {
        abort_if($tripType->user_id !== $request->user()->id, 403);
        $data = $request->validate([
            'name' => 'required|string|max:100',
        ]);
        $tripType->update($data);
        return response()->json($tripType);
    }

    public function destroy(Request $request, TripType $tripType)
    {
        abort_if($tripType->user_id !== $request->user()->id, 403);
        $tripType->delete();
        return response()->json(['message' => 'deleted']);
    }
}

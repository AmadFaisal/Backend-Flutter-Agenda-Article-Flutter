<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HolidayPlans;
use App\Models\TripType;
use Illuminate\Http\Request;

class HolidayPlanController extends Controller
{
    public function index(Request $request)
    {
        $q = HolidayPlans::with('tripType')
            ->where('user_id', $request->user()->id);

        if ($request->filled('trip_type_id')) {
            $q->where('trip_type_id', $request->trip_type_id);
        }

        return response()->json($q->latest()->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'trip_type_id' => 'required|exists:trip_types,id',
            'title'        => 'required|string|max:150',
            'notes'        => 'nullable|string',
            'image_url'    => 'nullable|string',
            'holiday_date' => 'nullable|string',
        ]);

        // pastikan kategori milik user yang login
        abort_unless(
            TripType::where('id', $data['trip_type_id'])
                ->where('user_id', $request->user()->id)
                ->exists(),
            403
        );

        $data['user_id'] = $request->user()->id;
        return response()->json(HolidayPlans::create($data), 201);
    }

    public function show(Request $request, HolidayPlans $holidayPlan)
    {
        abort_if($holidayPlan->user_id !== $request->user()->id, 403);
        return response()->json($holidayPlan->load('tripType'));
    }

    public function update(Request $request, HolidayPlans $holidayPlan)
    {
        abort_if($holidayPlan->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'trip_type_id' => 'required|exists:trip_types,id',
            'title'        => 'required|string|max:150',
            'notes'        => 'nullable|string',
            'image_url'    => 'nullable|string',
            'holiday_date' => 'nullable|string',
        ]);

        abort_unless(
            TripType::where('id', $data['trip_type_id'])
                ->where('user_id', $request->user()->id)
                ->exists(),
            403
        );

        $holidayPlan->update($data);
        return response()->json($holidayPlan);
    }

    public function destroy(Request $request, HolidayPlans $holidayPlan)
    {
        abort_if($holidayPlan->user_id !== $request->user()->id, 403);
        $holidayPlan->delete();
        return response()->json(['message' => 'deleted']);
    }
}

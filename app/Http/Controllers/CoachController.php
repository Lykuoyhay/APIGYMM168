<?php
namespace App\Http\Controllers;

use App\Models\CoachStudent;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    public function storeCoachStudent(Request $request)
    {
        $validatedData = $request->validate([
            'coach_id' => 'required|exists:users,id',
            'student_id' => 'required|exists:users,id',
        ]);

        CoachStudent::create([
            'coach_id' => $validatedData['coach_id'],
            'student_id' => $validatedData['student_id'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Coach-Student relationship created successfully.',
        ], 201);
    }

    public function updateCoachStudent(Request $request, $id)
    {
        $validatedData = $request->validate([
            'coach_id' => 'required|exists:users,id',
            'student_id' => 'required|exists:users,id',
        ]);

        $coachStudent = CoachStudent::findOrFail($id);
        $coachStudent->update([
            'coach_id' => $validatedData['coach_id'],
            'student_id' => $validatedData['student_id'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Coach-Student relationship updated successfully.',
        ]);
    }

    public function deleteCoachStudent($id)
    {
        $coachStudent = CoachStudent::findOrFail($id);
        $coachStudent->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Coach-Student relationship deleted successfully.',
        ]);
    }
}

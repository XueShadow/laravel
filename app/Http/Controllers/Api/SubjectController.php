<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        return response()->json(Subject::where('is_active', true)->paginate(15));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string',
            'code'        => 'required|string|unique:subjects',
            'units'       => 'required|integer',
            'schedule'    => 'required|array',
            'description' => 'nullable|string',
        ]);
        return response()->json(Subject::create($data), 201);
    }

    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'name'        => 'sometimes|string',
            'code'        => 'sometimes|string|unique:subjects,code,' . $subject->id,
            'units'       => 'sometimes|integer',
            'schedule'    => 'sometimes|array',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);
        $subject->update($data);
        return response()->json($subject);
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function show(Subject $subject)
    {
        return response()->json($subject);
    }
}

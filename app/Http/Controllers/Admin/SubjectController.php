<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::paginate(15);
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('admin.subjects.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                   => 'required|string|max:255',
            'code'                   => 'required|string|max:20|unique:subjects',
            'units'                  => 'required|integer|min:1|max:6',
            'description'            => 'nullable|string',
            'is_active'              => 'nullable|boolean',
            'schedule'               => 'required|array|min:1',
            'schedule.*.day'         => 'required|string',
            'schedule.*.start_time'  => 'required|string',
            'schedule.*.end_time'    => 'required|string',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        Subject::create($data);
        return redirect()->route('admin.subjects.index')->with('success', 'Subject created successfully.');
    }

    public function edit(Subject $subject)
    {
        return view('admin.subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'name'                   => 'required|string|max:255',
            'code'                   => 'required|string|max:20|unique:subjects,code,' . $subject->id,
            'units'                  => 'required|integer|min:1|max:6',
            'description'            => 'nullable|string',
            'is_active'              => 'nullable|boolean',
            'schedule'               => 'required|array|min:1',
            'schedule.*.day'         => 'required|string',
            'schedule.*.start_time'  => 'required|string',
            'schedule.*.end_time'    => 'required|string',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $subject->update($data);
        return redirect()->route('admin.subjects.index')->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('admin.subjects.index')->with('success', 'Subject deleted.');
    }

    public function show(Subject $subject)
    {
        return view('admin.subjects.show', compact('subject'));
    }
}

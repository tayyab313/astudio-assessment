<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TimeSheet\FormValidationRequest;
use App\Models\Timesheet;
use Illuminate\Http\Request;

class TimeSheetController extends Controller
{
    public function index()
    {
        $timesheet = Timesheet::with(['user', 'project'])->get();

        return response()->success(
            $timesheet,
            'Timesheet retrieved successfully',
            200
        );
    }

    public function show($id)
    {

        try {
            $timesheet = Timesheet::with(['user', 'project'])->findOrFail($id);

            return response()->success(
                $timesheet,
                'Timesheet detail',
                200
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->error('Timesheet not found', 404);
        }
    }

    public function store(FormValidationRequest $request)
    {

        try {
            $timesheet = Timesheet::create($request->validated());
            return response()->success(
                $timesheet,
                'Timesheet created successfully',
                201
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->error('Timesheet not found', 404);
        }
    }

    public function update(FormValidationRequest $request, $id)
    {
        try {
            $timesheet = Timesheet::findOrFail($id);
            $timesheet->update($request->validated());

            return response()->success(
                $timesheet,
                'Timesheet updated successfully',
                200
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->error('Timesheet not found', 404);
        }
    }

    public function destroy($id)
    {
        try {
            $timesheet = Timesheet::findOrFail($id);
            $timesheet->delete();
            return response()->success(
                [],
                'Timesheet deleted successfully',
                200
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->error('Timesheet not found', 404);
        }

    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class ProjectController extends BaseController
{
    /**
     * Display a listing of projects.
     */
    public function index(Request $request)
    {

        $cacheKey = 'projects_' . md5(json_encode($request->query()));

        $projects = Cache::remember($cacheKey, 60, function () use ($request) {
            return Project::query()
                    ->with('creator') // eager load relationship
                    ->searchByTitle($request->query('title'))
                    ->dateBetween($request->query('start_date'), $request->query('end_date'))
                    ->paginate(10);
        });

        return $this->sendResponse($projects, 'Projects retrieved successfully.');
    }

    /**
     * Store a newly created project (admin only).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'required|date_format:m/d/Y',
            'end_date'    => 'required|date_format:m/d/Y|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $project = Project::create([
            'title'       => $request->title,
            'description' => $request->description,
            'start_date'  => \Carbon\Carbon::createFromFormat('m/d/Y', $request->start_date)->format('Y-m-d'),
            'end_date'    => \Carbon\Carbon::createFromFormat('m/d/Y', $request->end_date)->format('Y-m-d'),
            'created_by'  => $request->user()->id,
        ]);

        return $this->sendResponse($project, 'Project created successfully.');
    }

    /**
     * Display a specific project.
     */
    public function show($id)
    {
        $project = Project::with('creator', 'tasks')->find($id);

        if (is_null($project)) {
            return $this->sendError('Project not found.');
        }

        return $this->sendResponse($project, 'Project retrieved successfully.');
    }

    /**
     * Update the specified project (admin only).
     */
    public function update(Request $request, $id)
    {
        $project = Project::find($id);

        if (is_null($project)) {
            return $this->sendError('Project not found.');
        }

        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'required|date_format:m/d/Y',
            'end_date'    => 'required|date_format:m/d/Y|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $project->update([
            'title'       => $request->title,
            'description' => $request->description,
            'start_date'  => \Carbon\Carbon::createFromFormat('m/d/Y', $request->start_date)->format('Y-m-d'),
            'end_date'    => \Carbon\Carbon::createFromFormat('m/d/Y', $request->end_date)->format('Y-m-d'),
        ]);

        return $this->sendResponse($project, 'Project updated successfully.');
    }


    /**
     * Remove the specified project (admin only).
     */
    public function destroy($id)
    {
        $project = Project::find($id);

        if (is_null($project)) {
            return $this->sendError('Project not found.');
        }

        $project->delete();

        return $this->sendResponse([], 'Project deleted successfully.');
    }
}

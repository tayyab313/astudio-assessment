<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\CreateRequest;
use App\Http\Requests\Project\UpdateRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\FilterService;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected $filterService;
    protected $projectService;

    public function __construct(FilterService $filterService, ProjectService $projectService)
    {
        $this->filterService = $filterService;
        $this->projectService = $projectService;
    }

    public function index(Request $request)
    {
        $query = Project::query()->with('attributeValues.attribute');

        if ($request->has('filters')) {
            $query = $this->filterService->filter($query, $request->filters);
        }

        $projects = ProjectResource::collection($query->get());

        return response()->success($projects, 'Projects retrieved successfully', 200);
    }

    public function show(Project $project)
    {
        $project = new ProjectResource($project);
        if ($project->resource === null) {
            return response()->error('Project not found', 404);
        }
        return response()->success($project, 'Project Detail', 200);
    }

    public function store(CreateRequest $request)
    {
        $project = Project::create($request->only(['name', 'status']));

        if ($request->has('attributes')) {
            $attributes = $request->input('attributes', []);
            $this->projectService->saveAttributes($project, $attributes);
        }

        $project = new ProjectResource($project);

        return response()->success($project, 'Project created successfully', 201);
    }

    public function update(UpdateRequest $request, $id)
    {

        try {
            $project = Project::findOrFail($id); // Manually fetch project

            $project->update($request->only(['name', 'status']));

            if ($request->has('attributes')) {
                $attributes = $request->input('attributes', []);
                $this->projectService->saveAttributes($project, $attributes);
            }

            $project = new ProjectResource($project);
            return response()->success($project, 'Project updated successfully', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->error('Project not found', 404);
        }
    }

    public function destroy($id)
    {
        $project = Project::find($id);
        if ($project === null) {
            return response()->error('Project not found', 404);
        }

        // Delete associated attribute values first
        $project->attributeValues()->delete();

        // Now delete the project
        $project->delete();

        return response()->success([], 'Project deleted successfully', 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequestValidation;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    public function index()
    {
        return ProjectResource::collection(Project::all());
    }

    public function store(ProjectRequestValidation $request)
    {

        \Symfony\Component\HttpFoundation\Response::
        $project = Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'deadline' => $request->deadline,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'assigned_to' => $request->assigned_to,
        ]);

        if($request->hasFile('project_files')){
            $project_files = $request->file('project_files');
            foreach ($project_files as $project_file){
                $file_name = $request->title.'-'.$project_file->getClientOriginalName();
                ProjectFile::create([
                    'name' => $file_name,
                    'project_id' => $project_files->id,
                ]);
                $path = public_path().'/project_files';
                $project_file->move($path,$file_name);
            }
        }

        return response()->json('Project created successfully', 200);
    }

    public function update(ProjectRequestValidation $request, $projectId)
    {
        $project = Project::find($projectId);
        $project->update([
            'title' => $request->title ? $request->title : $project->title,
            'description' => $request->description ? $request->description : $project->description,
            'status' => $request->status ? $request->status : $project->status,
            'deadline' => $request->deadline ? $request->deadline : $project->deadline,
            'start_date' => $request->start_date ? $request->start_date : $project->start_date,
            'end_date' => $request->end_date ? $request->end_date : $project->end_date,
            'assigned_to' => $request->assigned_to ? $request->assigned_to : $project->assigned_to,
        ]);

        return response()->json('Project updated successfully', 200);
    }

    public function destroy($projectId)
    {
        $project = Project::find($projectId);
        if($project){
            $is_deleted = $project->delete();
            if($is_deleted){
                $message = "Project deleted successfully";
            } else {
                $message = "Project could not be deleted";
            }
        } else {
            $message = "Project could not be found";
        }

        return response()->json($message, 200);
    }

}

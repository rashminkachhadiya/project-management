<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Support\Facades\Cache;

class TaskController extends BaseController
{
    // GET /api/projects/{project_id}/tasks
    public function indexByProject(Request $request, $project_id)
    {
        $cacheKey = 'tasks_' . md5(json_encode($request->query()));
        $tasks = Cache::remember($cacheKey, 60, function () use ($request, $project_id) {
            return Task::where('project_id', $project_id)
                    ->filterByStatus($request->query('status'))
                    ->searchByTitle($request->query('title'))
                    ->dateBetween($request->query('start_date'), $request->query('end_date'))
                    ->with('assignee')
                    ->get();
        });

        return $this->sendResponse($tasks, 'Tasks retrieved successfully.');
    }

    // GET /api/tasks/{id}
    public function show($id)
    {
        $task = Task::with('project', 'assignee', 'comments')->find($id);

        if (is_null($task)) {
            return $this->sendError('Task not found.');
        }

        return $this->sendResponse($task, 'Task retrieved successfully.');
    }

    // POST /api/projects/{project_id}/tasks (manager only)
    public function store(Request $request, $project_id)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'in:pending,in-progress,done',
            'due_date'    => 'nullable|date_format:m/d/Y',
            'assigned_to' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $task = Task::create([
            'title'       => $request->title,
            'description' => $request->description,
            'status'      => $request->status ?? 'pending',
            'due_date'    => $request->due_date
                ? Carbon::createFromFormat('m/d/Y', $request->due_date)->format('Y-m-d')
                : null,
            'project_id'  => $project_id,
            'assigned_to' => $request->assigned_to,
        ]);

        $user = User::find($request->assigned_to);
        if ($user) {
            $user->notify(new TaskAssignedNotification($task));
        }

        return $this->sendResponse($task, 'Task created successfully.');
    }

    // PUT /api/tasks/{id} (manager/assigned user only)
    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if (is_null($task)) {
            return $this->sendError('Task not found.');
        }

        // Only manager or the assigned user can update
        if (!in_array($request->user()->role, ['manager', 'admin']) && $request->user()->id !== $task->assigned_to) {
            return $this->sendError('Forbidden', [], 403);
        }

        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'in:pending,in-progress,done',
            'due_date'    => 'nullable|date_format:m/d/Y',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $task->update([
            'title'       => $request->title,
            'description' => $request->description,
            'status'      => $request->status,
            'due_date'    => $request->due_date
                ? Carbon::createFromFormat('m/d/Y', $request->due_date)->format('Y-m-d')
                : $task->due_date,
        ]);

        return $this->sendResponse($task, 'Task updated successfully.');
    }

    // DELETE /api/tasks/{id} (manager only)
    public function destroy(Request $request, $id)
    {
        $task = Task::find($id);

        if (is_null($task)) {
            return $this->sendError('Task not found.');
        }

        if ($request->user()->role !== 'manager' && $request->user()->role !== 'admin') {
            return $this->sendError('Forbidden', [], 403);
        }

        $task->delete();
        return $this->sendResponse([], 'Task deleted successfully.');
    }
}

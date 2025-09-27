<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Validator;

class CommentController extends BaseController
{
    // GET /api/tasks/{task_id}/comments
    public function index($task_id)
    {
        $comments = Comment::where('task_id', $task_id)->with('user')->get();
        return $this->sendResponse($comments, 'Comments retrieved successfully.');
    }

    // POST /api/tasks/{task_id}/comments
    public function store(Request $request, $task_id)
    {
        $validator = Validator::make($request->all(), [
            'body' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $comment = Comment::create([
            'body' => $request->body,
            'task_id' => $task_id,
            'user_id' => $request->user()->id,
        ]);

        return $this->sendResponse($comment, 'Comment added successfully.');
    }
}

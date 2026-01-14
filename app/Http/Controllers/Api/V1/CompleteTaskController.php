<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompleteTaskRequest;
use App\Models\Task;
use App\Http\Resources\TaskResource;

class CompleteTaskController extends Controller
{
    /**
     * Handle the incoming request.
     */
        public function __invoke(CompleteTaskRequest $request, Task $task)
    {
        $task->is_completed = $request->is_completed;
        $task->save();

        return new TaskResource($task);
    }
}

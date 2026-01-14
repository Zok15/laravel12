<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Task::class);

        
        $tasks =  request()->user()
            ->tasks()
            ->get();
            

            return TaskResource::collection($tasks);
    }

    public function tasksTest()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'No authenticated user',
            ], 401);
        }

        return response()->json([
            'user' => $user->only('id', 'name', 'email'),
            'can_viewAny' => Gate::check('viewAny', Task::class),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        Gate::authorize('create', Task::class);
        
        $task = $request->user()->tasks()->create($request->validated());

        return $task->toResource();
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        Gate::authorize('view', $task);

        return $task->toResource();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        Gate::authorize('update', $task);

        $task->update($request->validated());

        return $task->toResource();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);

        $task->delete();

        return response()->noContent();
    }
}
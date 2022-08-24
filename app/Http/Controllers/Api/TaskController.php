<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $toDoTasks = Task::where('status', 'to_do')
            ->orderBy('sorting_order', 'ASC')
            ->get();
        $inProgressTasks = Task::where('status', 'in_progress')
            ->orderBy('sorting_order', 'ASC')
            ->get();
        $doneTasks = Task::where('status', 'done')
            ->orderBy('sorting_order', 'ASC')
            ->get();

        return response()
            ->json([
                'toDoTasks' => $toDoTasks,
                'inProgressTasks' => $inProgressTasks,
                'doneTasks' => $doneTasks,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $data = $request->only('title');
        $data['sorting_order'] = Task::getSortingOrder();

        Task::create($data);

        return response()
            ->json(['msg' => 'Task created successfully.']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:in_progress,done'
        ]);

        $task->update([
            'status' => $request->status
        ]);

        return response()
            ->json(['msg' => 'Task updated successfully.']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::paginate();
        return view('task.index', ['tasks' => $tasks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        $statuses = TaskStatus::all();
        $users = User::all();
        return view('task.create', ['statuses' => $statuses, 'users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|max:255',
                'status_id' => 'required',
            ],
            [
                'name.required' => __('app.required'),
                'status_id.required' => __('app.required')
            ]
        );
        $task = new Task();
        $task->name = $request->name;
        $task->description = $request->description;
        $task->status_id = $request->status_id;
        $task->created_by_id = Auth::id();
        $task->assigned_to_id = $request->assigned_to_id ? $request->assigned_to_id : null;
        if ($task->save()) {
            flash(__('app.task_stored'))->success();
        } else {
            flash(__('app.task_not_stored'))->error();
        }
        return redirect(route('tasks.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return view('task.show', ['task' => $task]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $statuses = TaskStatus::all();
        $users = User::all();
        return view('task.edit', ['statuses' => $statuses, 'users' => $users, 'task' => $task]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Task $task
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Task $task)
    {
        $request->validate(
            [
                'name' => 'required|max:255',
                'status_id' => 'required',
            ],
            [
                'name.required' => __('app.required'),
                'status_id.required' => __('app.required')
            ]
        );
        $task->name = $request->name;
        $task->description = $request->description;
        $task->status_id = $request->status_id;
        $task->assigned_to_id = $request->assigned_to_id ? $request->assigned_to_id : null;
        if ($task->save()) {
            flash(__('app.task_updated'))->success();
        } else {
            flash(__('app.task_not_updated'))->error();
        }
        return redirect(route('tasks.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function destroy(Task $task)
    {
        if ($task->created_by_id == Auth::id() && $task->delete()) {
            flash(__('app.task_deleted'))->success();
        } else {
            flash(__('app.task_not_deleted'))->error();
        }
        return redirect(route('tasks.index'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $statuses = TaskStatus::paginate();
        return view('task_status.index', ['statuses' => $statuses]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('task_status.create');
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
            ['name' => 'required|unique:task_statuses|max:255'],
            ['name.unique' => __('app.status_not_unique')]
        );
        $status = new TaskStatus();
        $status->name = $request->name;
        if ($status->save()) {
            flash(__('app.status_stored'))->success();
        } else {
            flash(__('app.status_not_stored'))->error();
        }
        return redirect(route('task_statuses.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\TaskStatus $taskStatus
     * @return \Illuminate\Http\Response
     */
    public function show(TaskStatus $taskStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\TaskStatus $taskStatus
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(TaskStatus $taskStatus)
    {
        return view('task_status.edit', ['status' => $taskStatus]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\TaskStatus $taskStatus
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, TaskStatus $taskStatus)
    {
        $request->validate(
            ['name' => ['required', Rule::unique('task_statuses')->ignore($taskStatus->id), 'max:255']],
            ['name.unique' => __('app.status_not_unique')]
        );
        $taskStatus->name = $request->name;
        if ($taskStatus->save()) {
            flash(__('app.status_updated'))->success();
        } else {
            flash(__('app.status_not_updated'))->error();
        }
        return redirect(route('task_statuses.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\TaskStatus $taskStatus
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function destroy(TaskStatus $taskStatus)
    {
        if ($taskStatus->tasks->count() == 0 && $taskStatus->delete()) {
            flash(__('app.status_deleted'))->success();
        } else {
            flash(__('app.status_not_deleted'))->error();
        }
        return redirect(route('task_statuses.index'));
    }
}

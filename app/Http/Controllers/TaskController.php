<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Task;
use App\Models\TaskLabel;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tasks = QueryBuilder::for(Task::class)->allowedFilters([
            AllowedFilter::exact('status_id'),
            AllowedFilter::exact('created_by_id'),
            AllowedFilter::exact('assigned_to_id')
        ])->paginate();
        $statuses = TaskStatus::all()->reduce(function ($arr, $item) {
            return $arr + [$item->id => $item->name];
        }, []);
        $users = User::all()->reduce(function ($arr, $item) {
            return $arr + [$item->id => $item->name];
        }, []);
        return view('task.index', ['tasks' => $tasks, 'statuses' => $statuses, 'users' => $users, 'filter' => $request->filter]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        $statuses = TaskStatus::all()->reduce(function ($arr, $item) {
            return $arr + [$item->id => $item->name];
        }, []);
        $users = User::all()->reduce(function ($arr, $item) {
            return $arr + [$item->id => $item->name];
        }, []);
        $labels = Label::all()->reduce(function ($arr, $item) {
            return $arr + [$item->id => $item->name];
        }, []);
        return view('task.create', ['statuses' => $statuses, 'users' => $users, 'labels' => $labels]);
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
                'name' => 'required|unique:tasks|max:255',
                'status_id' => 'required',
            ],
            [
                'name.required' => __('app.required'),
                'name.unique' => __('app.task_not_unique'),
                'status_id.required' => __('app.required')
            ]
        );
        $task = new Task();
        $task->name = $request->name;
        $task->description = $request->description;
        $task->status_id = $request->status_id;
        $task->created_by_id = (int)Auth::id();
        $task->assigned_to_id = $request->has('assigned_to_id') ? $request->assigned_to_id : null;
        if ($task->save() == true) {
            if ($request->has('labels') && is_array($request->labels)) {
                foreach ($request->labels as $label) {
                    if (!is_null($label)) {
                        $taskLabel = new TaskLabel();
                        $taskLabel->task_id = $task->id;
                        $taskLabel->label_id = $label;
                        $taskLabel->save();
                    }
                }
            }
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
        $statuses = TaskStatus::all()->reduce(function ($arr, $item) {
            return $arr + [$item->id => $item->name];
        }, []);
        $users = User::all()->reduce(function ($arr, $item) {
            return $arr + [$item->id => $item->name];
        }, []);
        $labels = Label::all()->reduce(function ($arr, $item) {
            return $arr + [$item->id => $item->name];
        }, []);
        $taskLabels = $task->labels->pluck('label_id')->toArray();
        return view('task.edit', ['statuses' => $statuses, 'users' => $users, 'task' => $task, 'labels' => $labels, 'task_labels' => $taskLabels]);
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
                'name' => ['required', Rule::unique('tasks')->ignore($task->id), 'max:255'],
                'status_id' => 'required',
            ],
            [
                'name.required' => __('app.required'),
                'name.unique' => __('app.task_not_unique'),
                'status_id.required' => __('app.required')
            ]
        );
        $task->name = $request->name;
        $task->description = $request->description;
        $task->status_id = $request->status_id;
        $task->assigned_to_id = $request->has('assigned_to_id') ? $request->assigned_to_id : null;
        if ($task->save() == true) {
            if ($request->has('labels') && is_array($request->labels)) {
                foreach ($request->labels as $label) {
                    foreach ($task->labels as $taskLabel) {
                        $taskLabel->delete();
                    }
                    if (!is_null($label)) {
                        $taskLabel = new TaskLabel();
                        $taskLabel->task_id = $task->id;
                        $taskLabel->label_id = $label;
                        $taskLabel->save();
                    }
                }
            }
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
        if ($task->created_by_id == Auth::id()) {
            foreach ($task->labels as $label) {
                $label->delete();
            }
            if ($task->delete() == true) {
                flash(__('app.task_deleted'))->success();
            } else {
                flash(__('app.task_not_deleted'))->error();
            }
        } else {
            flash(__('app.task_not_deleted'))->error();
        }
        return redirect(route('tasks.index'));
    }
}

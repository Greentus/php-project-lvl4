<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $labels = Label::paginate();
        return view('label.index', ['labels' => $labels]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('label.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|unique:labels|max:255'
            ],
            [
                'name.required' => __('app.required'),
                'name.unique' => __('app.label_not_unique')
            ]
        );
        $label = new Label();
        $label->name = $request->name;
        $label->description = $request->description;
        if ($label->save()) {
            flash(__('app.label_stored'))->success();
        } else {
            flash(__('app.label_not_stored'))->error();
        }
        return redirect(route('labels.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(Label $label)
    {
        return view('label.edit', ['label' => $label]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Label $label)
    {
        $request->validate(
            [
                'name' => ['required',Rule::unique('labels')->ignore($label->id),'max:255']
            ],
            [
                'name.required' => __('app.required'),
                'name.unique' => __('app.label_not_unique')
            ]
        );
        $label->name = $request->name;
        $label->description = $request->description;
        if ($label->save()) {
            flash(__('app.label_updated'))->success();
        } else {
            flash(__('app.label_not_updated'))->error();
        }
        return redirect(route('labels.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function destroy(Label $label)
    {
        if ($label->tasks->count() == 0 && $label->delete() == true) {
            flash(__('app.label_deleted'))->success();
        } else {
            flash(__('app.label_not_deleted'))->error();
        }
        return redirect(route('labels.index'));
    }
}

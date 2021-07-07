@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('app.link_task_update') }}</h1>
    {!! Form::open(['url' => route('tasks.update', ['task' => $task->id]), 'class' => 'w-50']) !!}
    {!! Form::hidden('_method', 'PATCH') !!}
    @csrf
    <div class="row mb-3">
        {!! Form::label('name', __('app.label_name'), ['class' => 'form-label']) !!}
        {!! Form::text('name', $errors->any() ? old('name') : $task->name, ['class' => 'form-control'.($errors->has('name') ? ' is-invalid' : '')]) !!}
        @error('name')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
    <div class="row mb-3">
        {!! Form::label('description',__('app.label_description'), ['class' => 'form-label']) !!}
        {!! Form::textarea('description', $errors->any() ? old('description') : $task->description, ['cols' => '50', 'rows' => '10', 'class' => 'form-control']) !!}
    </div>
    <div class="row mb-3">
        {!! Form::label('status_id',__('app.label_status'), ['class' => 'form-label']) !!}
        {!! Form::select('status_id', ['' => '----------'] + $statuses, $errors->has('status_id') ? old('status_id') : $task->status_id, ['class' => 'form-select'.($errors->has('status_id') ? ' is-invalid' : '')]) !!}
        @error('status_id')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
    <div class="row mb-3">
        {!! Form::label('assigned_to_id', __('app.label_user'), ['class' => 'form-label']) !!}
        {!! Form::select('assigned_to_id', ['' => '----------'] + $users, $errors->any() ? old('assigned_to_id') : $task->assigned_to_id, ['class' => 'form-select']) !!}
    </div>
    <div class="row mb-3">
        {!! Form::label('labels', __('app.label_labels'), ['class' => 'form-label']) !!}
        {!! Form::select('labels', ['' => ''] + $labels, $errors->any() ? old('labels') : $task_labels, ['name' => 'labels[]', 'class' => 'form-select', 'multiple' => '']) !!}
    </div>
    {!! Form::submit(__('app.button_update'), ['class' => 'btn btn-primary']) !!}
    {!! Form::close() !!}
@endsection

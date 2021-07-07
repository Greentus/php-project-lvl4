@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('app.link_status_update') }}</h1>
    {!! Form::open(['url' => route('task_statuses.update', ['task_status' => $status->id]), 'class' => 'w-50']) !!}
    {!! Form::hidden('_method', 'PATCH') !!}
    @csrf
    <div class="row mb-3">
        {!! Form::label('name', __('app.label_name'), ['class' => 'form-label']) !!}
        {!! Form::text('name', $errors->has('name') ? old('name') : $status->name, ['class' => 'form-control'.($errors->has('name') ? ' is-invalid' : '')]) !!}
        @error('name')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
    {!! Form::submit(__('app.button_update'), ['class' => 'btn btn-primary']) !!}
    {!! Form::close() !!}
@endsection

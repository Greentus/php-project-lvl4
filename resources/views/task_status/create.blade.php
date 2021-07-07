@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('app.button_status_create') }}</h1>
    {!! Form::open(['url' => route('task_statuses.store'), 'class'=>'w-50']) !!}
    @csrf
    <div class="row mb-3">
        {!! Form::label('name', __('app.label_name'), ['class' => 'form-label']) !!}
        {!! Form::text('name', old('name'), ['class' => 'form-control'.($errors->has('name') ? ' is-invalid' : '')]) !!}
        @error('name')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
    {!! Form::submit(__('app.button_create'), ['class' => 'btn btn-primary']) !!}
    {!! Form::close() !!}
@endsection

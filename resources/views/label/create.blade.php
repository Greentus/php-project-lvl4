@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('app.button_label_create') }}</h1>
    {!! Form::open(['url' => route('labels.store'), 'class' => 'w-50']) !!}
    @csrf
    <div class="row mb-3">
        {!! Form::label('name',__('app.label_name'),['class' => 'form-label']) !!}
        {!! Form::text('name', old('name'), ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : '')]) !!}
        @error('name')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
    <div class="row mb-3">
        {!! Form::label('description', __('app.label_description'),['class'=>'form-label']) !!}
        {!! Form::textarea('description', old('description'), ['cols' => '50', 'rows' => '10', 'class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : '')]) !!}
    </div>
    {!! Form::submit(__('app.button_create'),['class' => 'btn btn-primary']) !!}
    {!! Form::close() !!}
@endsection

@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('app.link_label_update') }}</h1>
    {!! Form::open(['url' => route('labels.update', ['label'=>$label->id]), 'class' => 'w-50']) !!}
    {!! Form::hidden('_method', 'PATCH') !!}
    @csrf
    <div class="row mb-3">
        {!! Form::label('name', __('app.label_name'),['class' => 'form-label']) !!}
        {!! Form::text('name', $errors->any() ? old('name') : $label->name, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : '')]) !!}
        @error('name')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
    <div class="row mb-3">
        {!! Form::label('description', __('app.label_description'),['class' => 'form-label']) !!}
        {!! Form::textarea('description', $errors->any() ? old('description') : $label->description, ['cols' => '50', 'rows' => '10', 'class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : '')]) !!}
        @error('description')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
    {!! Form::submit(__('app.button_update'), ['class' => 'btn btn-primary']) !!}
    {!! Form::close() !!}
@endsection

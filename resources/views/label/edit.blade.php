@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('app.link_label_update') }}</h1>
    <form method="POST" action="{{ route('labels.update',['label'=>$label->id]) }}" class="w-50">
        <input name="_method" type="hidden" value="PATCH">
        @csrf
        <div class="row mb-3">
            <label for="name" class="form-label">{{ __('app.label_name') }}</label>
            <input id="name" name="name" type="text" class="form-control  @error('name') is-invalid @enderror" value="{{ $errors->any() ? old('name') : $label->name }}" required>
            @error('name')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="row mb-3">
            <label for="description" class="form-label">{{ __('app.label_description') }}</label>
            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" cols="50" rows="10">{{ $errors->any() ? old('description') : $label->description }}</textarea>
            @error('description')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <input class="btn btn-primary" type="submit" value="{{ __('app.button_update') }}">
    </form>
@endsection

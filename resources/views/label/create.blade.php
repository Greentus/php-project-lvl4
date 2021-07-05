@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('app.button_label_create') }}</h1>
    <form method="POST" action="{{ route('labels.store') }}" class="w-50">
        @csrf
        <div class="row mb-3">
            <label for="name" class="form-label">{{ __('app.label_name') }}</label>
            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
            @error('name')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="row mb-3">
            <label for="description" class="form-label">{{ __('app.label_description') }}</label>
            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" cols="50" rows="10">{{ old('description') }}</textarea>
            @error('description')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <input class="btn btn-primary" type="submit" value="{{ __('app.button_create') }}">
    </form>
@endsection

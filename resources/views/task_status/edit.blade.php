@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('app.link_status_update') }}</h1>
    <form method="POST" action="{{ route('task_statuses.update',['task_status'=>$status->id]) }}" class="w-50">
        <input name="_method" type="hidden" value="PATCH">
        @csrf
        <div class="row mb-3">
            <label for="name">{{ __('app.label_name') }}</label>
            <input id="name" name="name" type="text" class="form-control  @error('name') is-invalid @enderror" value="{{ $errors->any() ? old('name') : $status->name }}" required>
            @error('name')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <input class="btn btn-primary" type="submit" value="{{ __('app.button_update') }}">
    </form>
@endsection

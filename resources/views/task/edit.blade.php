@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('app.link_task_update') }}</h1>
    <form method="POST" action="{{ route('tasks.update',['task'=>$task->id]) }}" class="w-50">
        <input name="_method" type="hidden" value="PATCH">
        @csrf
        <div class="row mb-3">
            <label for="name">{{ __('app.label_name') }}</label>
            <input id="name" name="name" type="text" class="form-control  @error('name') is-invalid @enderror" value="{{ $errors->any() ? old('name') : $task->name }}" required>
            @error('name')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="row mb-3">
            <label for="description" class="form-label">{{ __('app.label_description') }}</label>
            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" cols="50" rows="10">{{ $errors->any() ? old('description') : $task->description }}</textarea>
            @error('description')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="row mb-3">
            <label for="status_id" class="form-label">{{ __('app.label_status') }}</label>
            <select id="status_id" name="status_id" class="form-select @error('status_id') is-invalid @enderror">
                <option @if (empty($errors->any() ? old('status_id') : $task->status_id)) selected="selected" @endif value="">----------</option>
                @foreach($statuses as $status)
                    <option @if (($errors->any() ? old('status_id') : $task->status_id)==$status->id) selected="selected" @endif value="{{ $status->id }}">{{ $status->name }}</option>
                @endforeach
            </select>
            @error('status_id')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="row mb-3">
            <label for="assigned_to_id" class="form-label">{{ __('app.label_user') }}</label>
            <select id="assigned_to_id" name="assigned_to_id" class="form-select @error('assigned_to_id') is-invalid @enderror">
                <option @if (empty($errors->any() ? old('assigned_to_id') : $task->assigned_to_id)) selected="selected" @endif value="">----------</option>
                @foreach($users as $user)
                    <option @if (($errors->any() ? old('assigned_to_id') : $task->assigned_to_id)==$user->id) selected="selected" @endif value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            @error('assigned_to_id')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <input class="btn btn-primary" type="submit" value="{{ __('app.button_update') }}">
    </form>
@endsection

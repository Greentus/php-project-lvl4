@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('app.link_task_view') }}: {{ $task->name }} <a href="{{ route('tasks.edit',['task'=>$task->id]) }}" class="text-decoration-none">&#9881;</a></h1>
    <p>{{ __('app.label_name') }}: {{ $task->name }}</p>
    <p>{{ __('app.label_status') }}: {{ $task->status->name }}</p>
    <p>{{ __('app.label_description') }}: {{ $task->description }}</p>
@endsection

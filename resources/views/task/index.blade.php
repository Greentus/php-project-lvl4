@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('app.link_task') }}</h1>
    @auth
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">{{ __('app.button_task_create') }}</a>
    @endauth
    <table class="table table-hover mt-2">
        <thead>
        <tr>
            <th>{{ __('app.header_id') }}</th>
            <th>{{ __('app.header_status') }}</th>
            <th>{{ __('app.header_name') }}</th>
            <th>{{ __('app.header_author') }}</th>
            <th>{{ __('app.header_user') }}</th>
            <th>{{ __('app.header_created') }}</th>
            @auth
                <th>{{ __('app.header_actions') }}</th>
            @endauth
        </tr>
        </thead>
        <tbody>
        @foreach($tasks as $task)
            <tr>
                <td>{{ $task->id }}</td>
                <td>{{ $task->status->name }}</td>
                <td><a href="{{ route('tasks.show', ['task' => $task->id]) }}" class="text-decoration-none">{{ $task->name }}</a></td>
                <td>{{ $task->author->name }}</td>
                <td>{{ $task->user->name }}</td>
                <td>{{ $task->created_at ? $task->created_at->format('d.m.Y') : ''}}</td>
                @auth()
                    <td>
                        <a href="{{ route('tasks.edit', ['task' => $task->id]) }}" class="text-decoration-none">{{ __('app.button_change') }}</a>
                        &nbsp;
                        @if (Auth::id()==$task->created_by_id)
                            <a class="text-danger text-decoration-none" href="{{ route('tasks.destroy', ['task' => $task->id]) }}" data-confirm="Вы уверены?" data-method="delete">{{ __('app.button_delete') }}</a>
                        @endif
                    </td>
                @endauth
            </tr>
        @endforeach
        </tbody>
        <thead>
        </thead>
    </table>
    {{ $tasks->links() }}
@endsection

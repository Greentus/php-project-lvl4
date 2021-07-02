@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('app.link_status') }}</h1>
    @auth
        <a href="{{ route('task_statuses.create') }}" class="btn btn-primary">{{ __('app.button_status_create') }}</a>
    @endauth
    <table class="table table-hover mt-2">
        <thead>
        <tr>
            <th>{{ __('app.header_id') }}</th>
            <th>{{ __('app.header_name') }}</th>
            <th>{{ __('app.header_created') }}</th>
            @auth
                <th>{{ __('app.header_actions') }}</th>
            @endauth
        </tr>
        </thead>
        <tbody>
        @foreach($statuses as $status)
            <tr>
                <td>{{ $status->id }}</td>
                <td>{{ $status->name }}</td>
                <td>{{ $status->created_at ? $status->created_at->format('d.m.Y') : ''}}</td>
                @auth()
                    <td>
                        <a href="{{ route('task_statuses.edit', ['task_status' => $status->id]) }}" class="text-decoration-none">{{ __('app.button_change') }}</a>
                        &nbsp;
                        <a class="text-danger text-decoration-none" href="{{ route('task_statuses.destroy', ['task_status' => $status->id]) }}" data-confirm="Вы уверены?" data-method="delete">{{ __('app.button_delete') }}</a>
                    </td>
                @endauth
            </tr>
        @endforeach
        </tbody>
        <thead>
        </thead>
    </table>
    {{ $statuses->links() }}
@endsection

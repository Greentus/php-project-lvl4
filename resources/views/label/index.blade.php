@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('app.link_label') }}</h1>
    @auth
        <a href="{{ route('labels.create') }}" class="btn btn-primary">{{ __('app.button_label_create') }}</a>
    @endauth
    <table class="table table-hover mt-2">
        <thead>
        <tr>
            <th>{{ __('app.header_id') }}</th>
            <th>{{ __('app.header_name') }}</th>
            <th>{{ __('app.header_description') }}</th>
            <th>{{ __('app.header_created') }}</th>
            @auth
                <th>{{ __('app.header_actions') }}</th>
            @endauth
        </tr>
        </thead>
        <tbody>
        @foreach($labels as $label)
            <tr>
                <td>{{ $label->id }}</td>
                <td>{{ $label->name }}</td>
                <td>{{ $label->description }}</td>
                <td>{{ $label->created_at ? $label->created_at->format('d.m.Y') : ''}}</td>
                @auth()
                    <td>
                        <a href="{{ route('labels.edit', ['label' => $label->id]) }}" class="text-decoration-none">{{ __('app.button_change') }}</a>
                        &nbsp;
                        <a class="text-danger text-decoration-none" href="{{ route('labels.destroy', ['label' => $label->id]) }}" data-confirm="Вы уверены?" data-method="delete">{{ __('app.button_delete') }}</a>
                    </td>
                @endauth
            </tr>
        @endforeach
        </tbody>
        <thead>
        </thead>
    </table>
    {{ $labels->links() }}
@endsection

@extends('layouts.app')

@section('content')
    <h1>Menu List</h1>
    <a href="{{ route('menu.create') }}">Add New Menu</a>
    <ul>
        @foreach ($menus as $menu)
            <li>
                <h2>{{ $menu['name'] }}</h2>
                <p>{{ $menu['desc'] }}</p>
                <p>{{ $menu['price'] }}</p>
                <a href="{{ route('menu.edit', $menu['id']) }}">Edit</a>
                <form action="{{ route('menu.destroy', $menu['id']) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
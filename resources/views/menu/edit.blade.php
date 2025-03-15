@extends('layouts.app')

@section('content')
    <h1>Edit Menu</h1>
    <form action="{{ route('menu.update', $menu['id']) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="{{ $menu['name'] }}" required>
        <label for="desc">Description:</label>
        <input type="text" name="desc" id="desc" value="{{ $menu['desc'] }}" required>
        <label for="img">Image URL:</label>
        <input type="text" name="img" id="img" value="{{ $menu['img'] }}" required>
        <label for="rate">Rate:</label>
        <input type="number" name="rate" id="rate" value="{{ $menu['rate'] }}" required>
        <label for="price">Price:</label>
        <input type="number" name="price" id="price" value="{{ $menu['price'] }}" required>
        <button type="submit">Update Menu</button>
    </form>
@endsection 
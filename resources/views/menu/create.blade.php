@extends('layouts.app')

@section('content')
    <h1>Add New Menu</h1>
    <form action="{{ route('menu.store') }}" method="POST">
        @csrf
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>
        <label for="desc">Description:</label>
        <input type="text" name="desc" id="desc" required>
        <label for="img">Image URL:</label>
        <input type="text" name="img" id="img" required>
        <label for="rate">Rate:</label>
        <input type="number" name="rate" id="rate" required>
        <label for="price">Price:</label>
        <input type="number" name="price" id="price" required>
        <button type="submit">Add Menu</button>
    </form>
@endsection
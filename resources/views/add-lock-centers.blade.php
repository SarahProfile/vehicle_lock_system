@extends('layouts.app')

@section('content')
<form action="{{ route('centers.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="name">إسم المركز</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="vehicle_type">نوع المركبة</label>
        <input type="text" name="vehicle_type" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="lock_area">مكان الرفع</label>
        <input type="text" name="lock_area" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="price">Price:</label>
        <input type="number" name="price" step="0.01" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">إضافة المركز</button>
</form>

@endsection
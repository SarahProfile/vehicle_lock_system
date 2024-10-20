@extends('layouts.app')

@section('content')
<div class="content" >
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" style="padding: 10px">
<form action="{{ route('centers.store') }}" method="POST">
    @csrf
    <div class="form-group">
      
        <label for="name">إسم المركز</label>
        
        <input type="text" name="name" class="form-control" required>
    </div>
    <br>
       <button type="submit" class="btn btn-primary">إضافة المركز</button>
</form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
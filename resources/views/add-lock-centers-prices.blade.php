@extends('layouts.app')

@section('content')
<div class="content" >
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" style="padding: 10px">
<form action="{{ route('centers.price.store') }}" method="POST">
    @csrf
    <div class="form-group">
      
        <div class="form-group">
            <select name="center_id" class="form-select"  >
                <option value="" selected>اسم المركز</option>
                @foreach($centers as $center)
                <option value="{{$center->id}}">{{$center->name}}</option>
                @endforeach
            </select>
            <br>
            <div class="form-group">
      
                <label for="price1"> ادخل السعراذا كانت المركبة صغيرة و داخل المنطقة</label>
                
                <input type="text" name="price_small_inside" class="form-control" required>
            </div>
            <br>
            <div class="form-group">
      
                <label for="price2"> ادخل السعراذا كانت المركبة كبيرة و داخل المنطقة</label>
                
                <input type="text" name="price_big_inside" class="form-control" required>
            </div>
            <br>
            <div class="form-group">
      
                <label for="price3"> ادخل السعراذا كانت المركبة صغيرة و خارج المنطقة</label>
                
                <input type="text" name="price_small_outside" class="form-control" required>
            </div>
            <br>
            <div class="form-group">
      
                <label for="price4"> ادخل السعراذا كانت المركبة كبيرة و خارج المنطقة</label>
                
                <input type="text" name="price_big_outside" class="form-control" required>
            </div>
            <br>
            <div class="form-group">
      
                <label for="price4"> ادخل السعراذا كانت المركبة معدات و داخل المنطقة</label>
                
                <input type="text" name="price_equipment_inside" class="form-control" required>
            </div>
            <br>
            <div class="form-group">
      
                <label for="price4"> ادخل السعراذا كانت المركبة معدات و خارج المنطقة</label>
                
                <input type="text" name="price_equipment_outside" class="form-control" required>
            </div>
            <br>
        </div>
     
    </div>
    <br>
       <button type="submit" class="btn btn-primary">إضافة الاسعار</button>
</form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
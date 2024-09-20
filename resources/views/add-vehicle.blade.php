@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="content" >
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card" style="padding: 10px">

            <h2>تسجيل المركبة</h2>
            <form action="{{ route('vehicle.add' ) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <select name="vehicle_center" class="form-select">
                        @if(auth::user()->type=='مشرف المدينة')
                        <option value="المدينة" selected>المدينة</option>
                        @endif
                        @if(auth::user()->type=='مشرف الشرقية')
                        <option value="المنطقة الشرقية" selected>المنطقة الشرقية</option>
                        @endif
                        @if(auth::user()->type=='مشرف عام')
                        <option value="المدينة" selected>المدينة</option>
                        <option value="المنطقة الشرقية" >المنطقة الشرقية</option>
                        @endif
                    </select>
                </div>
                
                <br>
                <div class="form-group">
                    <label for="enter_date">تاريخ وزمن دخول المركبة</label>
                    <input type="datetime-local" name="enter_date" class="form-control" required>
                </div>
                <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
                <br>
                <div class="form-group">
                    <label for="lock_location">موقع الرفع</label>
                    <input type="text" name="lock_location" class="form-control" required>
                </div>
                <br>
                <div class="form-group">
                    <select name="lock_area" class="form-select">
                        <option value="مكان الحجز" selected>مكان السحب</option>
                        <option value="داخل المنطقة">داخل المنطقة</option>
                        <option value="خارج المنطقة">خارج المنطقة</option>
                    </select>
                </div>
                <br>
                <div class="form-group">
                    <label for="vehicle_number">رقم المركبة</label>
                    <input type="text" name="vehicle_number" class="form-control" required>
                </div>
                <br>
                <div class="form-group">
                    <label for="vehicle_type">نوع المركبة</label>
                    {{-- <input type="text" name="vehicle_type" class="form-control" required> --}}
                    <select name="vehicle_type" class="form-select">
                        <option value="نوع المركبة" selected>نوع المركبة</option>
                        <option value="صغيرة">صغيرة</option>
                        <option value="كبيرة">كبيرة</option>
                        <option value="المعدات">المعدات</option>
                    </select>
                </div>
                <br>
                <div class="form-group">
                    <label for="vehicle_model">موديل المركبة</label>
                    <input type="text" name="vehicle_model" class="form-control" required>
                </div>
                <br>
                <div class="form-group">
                    <label for="chassis_number">رقم الهيكل</label>
                    <input type="text" name="chassis_number" class="form-control" required>
                </div>
                <br>
                <div class="form-group">
                    <label for="vehicle_images">صور المركبة</label>
                    <input type="file" accept="image/*" name="vehicle_images[]" class="form-control" multiple required>
                </div>
                
                <br>
                <br>
                <a href="{{ route('home') }}" class="btn btn-primary">الرجوع</a>
                <button type="submit" class="btn btn-primary">تسجيل المركبة</button>
                @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
            </form>
        </div>
                </div>
            </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
@endsection
<script>
    flatpickr("#enter_date", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        position: "right" // Calendar dropdown on the right
    });
</script>
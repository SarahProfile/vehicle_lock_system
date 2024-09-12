@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="content" >
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card" style="padding: 10px">

            <h2>تسجيل المركبة</h2>
            <form action="{{ route('vehicle.update' ,['id'=>$vehicle->id])  }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <select name="vehicle_center" class="form-select">
                        @if(auth::user()->type=='مشرف المدينة')
                        <option  value="{{$vehicle->vehicle_center}}" selected>المدينة</option>
                        @endif
                        @if(auth::user()->type=='مشرف الشرقية')
                        <option value="{{$vehicle->vehicle_center}}" selected>المنطقة الشرقية</option>
                        @endif
                        @if(auth::user()->type=='مشرف عام')
                        <option value="{{$vehicle->vehicle_center}}" selected>المدينة</option>
                        <option value="{{$vehicle->lock_loction}}" >المنطقة الشرقية</option>
                        @endif
                    </select>
                </div>
                
                <br>
                <div class="form-group">
                    <label for="enter_date">تاريخ وزمن دخول المركبة</label>
                    <input type="datetime-local" name="enter_date" class="form-control" required value="{{$vehicle->enter_date}}">
                </div>
                <br>
                @if($vehicle->vehicle_status == 'خرجت')
                <div class="form-group">
                    <label for="vehicle_status">حالة المركبة</label>
                    <input type="text" name="vehicle_status" class="form-control" value="خرجت" readonly>
                </div>
                @endif
                <br>
                <div class="form-group">
                    <label for="lock_loction">موقع الرفع</label>
                    <input type="text" name="lock_location" class="form-control" required value="{{$vehicle->lock_location}}">
                </div>
                <br>
                <div class="form-group">
                    <label for="lock_area">مكان الحجز</label>
                    <select name="lock_area" class="form-select">
                        <option value="داخل المنطقة" @if($vehicle->lock_area == 'داخل المنطقة') selected @endif>داخل المنطقة</option>
                        <option value="خارج المنطقة" @if($vehicle->lock_area == 'خارج المنطقة') selected @endif>خارج المنطقة</option>
                    </select>
                </div>
                <br>
                <div class="form-group">
                    <label for="vehicle_number">رقم المركبة</label>
                    <input type="text" name="vehicle_number" class="form-control" required value="{{$vehicle->vehicle_number}}">
                </div>
                <br>
                <div class="form-group">
                    {{-- <label for="vehicle_type">نوع المركبة</label>
                    <input type="text" name="vehicle_type" class="form-control" > --}}
                    <label for="vehicle_type">نوع المركبة</label>
                    {{-- <input type="text" name="vehicle_type" class="form-control" required> --}}
                    <select name="vehicle_type" class="form-select">
                        <option value="نوع المركبة" >نوع المركبة</option>
                        <option value="صغيرة"   @if($vehicle->vehicle_type == 'صغيرة') selected @endif>صغيرة</option>
                        <option value="كبيرة"   @if($vehicle->vehicle_type == 'كبيرة') selected @endif>كبيرة</option>
                        <option value="المعدات" @if($vehicle->vehicle_type == 'المعدات') selected @endif>المعدات</option>
                    </select>
                </div>
                <br>
                <div class="form-group">
                    <label for="vehicle_model">موديل المركبة</label>
                    <input type="text" name="vehicle_model" class="form-control" required value="{{$vehicle->vehicle_model}}">
                </div>
                <br>
                <div class="form-group">
                    <label for="chassis_number">رقم الهيكل</label>
                    <input type="text" name="chassis_number" class="form-control" required  value="{{$vehicle->chassis_number}}">
                </div>
                <br>
                
                  <!-- حالة المركبة تظهر فقط إذا كانت الحالة 'خرجت' -->
                  @if($vehicle->vehicle_status == 'خرجت')

                  <!-- إضافة حقل تاريخ الخروج -->
                  <div class="form-group">
                      <label for="exit_date">تاريخ وزمن خروج المركبة</label>
                      <input type="datetime-local" id="exit_date" name="exit_date" class="form-control" required value="{{ $vehicle->exit_date}}">
                  </div>
                  <br>
                  
                  <!-- إضافة حقل سعر المركبة -->
                  <div class="form-group">
                      <label for="vehicle_price">سعر المركبة</label>
                      <input type="text" id="vehicle_price" name="vehicle_price" class="form-control" value="{{ $vehicle->vehicle_price }}" readonly>
                  </div>
                  <br>
                  
                  @endif
             
                <div class="form-group">
                    {{-- عرض الصور الحالية --}}
                    <label>الصور الحالية للمركبة</label><br>
                    @foreach($vehicle->images as $image)
                        <img src="{{ asset('images/'.$image->image_path) }}" alt="Vehicle Image" style="width:100px; height:100px;">
                    @endforeach
                    <br>
                
                    {{-- إدخال صور جديدة --}}
                    <label for="vehicle_images">إضافة صور جديدة للمركبة</label>
                    <input type="file" accept="image/*" name="vehicle_images[]" class="form-control" multiple>
                </div>
                <br>
                <a href="{{ route('home') }}" class="btn btn-primary">الرجوع</a>
                <button type="submit" class="btn btn-primary">تحديث تسجيل المركبة </button>
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
    document.addEventListener('DOMContentLoaded', function() {
        // قم بتحديد عناصر الحقول
        const exitDateInput = document.getElementById('exit_date');
        const priceInput = document.getElementById('vehicle_price');

        // استمع لتغييرات في حقل exit_date
        exitDateInput.addEventListener('change', function() {
            // احصل على قيمة تاريخ الدخول وتاريخ الخروج
            const enterDate = new Date("{{ $vehicle->enter_date }}");
            const exitDate = new Date(exitDateInput.value);

            // احسب الفرق بالوقت (بالساعات)
            const timeDifference = (exitDate - enterDate) / (1000 * 60 * 60);

            // احسب السعر بناءً على نوع المركبة ومكان الحجز
            let price = 0;
            const vehicleType = "{{ $vehicle->vehicle_type }}";
            const lockArea = "{{ $vehicle->lock_area }}";

            if (vehicleType === 'صغيرة' && lockArea === 'داخل المنطقة') {
                price = (timeDifference * 500) + (timeDifference * 2);
            } else if (vehicleType === 'صغيرة' && lockArea === 'خارج المنطقة') {
                price = (timeDifference * 800) + (timeDifference * 2);
            } else if (vehicleType === 'كبيرة' && lockArea === 'خارج المنطقة') {
                price = (timeDifference * 1500) + (timeDifference * 2) ;
            }
         else if (vehicleType === 'كبيرة' && lockArea === 'داخل المنطقة') {
                price = (timeDifference * 1000) + (timeDifference * 2);
            }
            else if (vehicleType === 'المعدات' && lockArea === 'داخل المنطقة') {
                price = (timeDifference * 2000) + (timeDifference * 2);
            }
            else if (vehicleType === 'المعدات' && lockArea === 'خارج المنطقة') {
                price = (timeDifference * 2700) + (timeDifference * 2);
            }
            // عرض السعر في حقل vehicle_price
            priceInput.value = price.toFixed(2);
        });
    });
</script>
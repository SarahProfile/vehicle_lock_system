@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="content" >
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card" style="padding: 10px">

            <h2>تسجيل المركبة</h2>
            <form action="{{ route('vehicle.update', ['id' => $vehicle->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    {{-- <label for="vehicle_center">مركز المركبة</label>
                    <select name="vehicle_center" class="form-select" required>
                        <option value="" disabled selected>اختر مركز المركبة</option>
                        @foreach($lock_centers as $center)
                            <option value="{{ $center->id }}" @if($vehicle->vehicle_center == $center->id) selected @endif>{{ $center->name }}</option>
                        @endforeach
                    </select> --}}
                    <div class="form-group">
                        <label for="vehicle_center">مركز المركبة</label>
                        <select class="form-select" name="vehicle_center_id" id="vehicle_center2">
                            @foreach($vehicleCenters as $center) 
                            @if(auth::user()->type=='مشرف المدينة')
                            <option value="{{ $center->id }}" @if($vehicle->vehicle_center_id == $center->id) selected @endif> @if($center->name =='المدينة') {{ $center->name }} @endif </option>
                            @endif
                            @if(auth::user()->type=='مشرف الشرقية')
                            <option value="{{ $center->id }}" @if($vehicle->vehicle_center_id == $center->id) selected @endif> @if($center->name =='المنطقة الشرقية'){{  $center->name}} @endif </option>
                            @endif
                            @if(auth::user()->type=='مشرف عام')
                            <option value="{{ $center->id }}" @if($vehicle->vehicle_center_id == $center->id) selected @endif>{{ $center->name }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <br>
                {{-- <div class="form-group">
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
                </div> --}}
                
                <br>
                <div class="form-group">
                    <label for="enter_date">تاريخ وزمن دخول المركبة</label>
                    <input type="datetime-local" name="enter_date" class="form-control" required value="{{$vehicle->enter_date}}">
                </div>
                <br>
                @if($vehicle->vehicle_status == 'خرجت')
                <div class="form-group">
                    <label for="vehicle_status">حالة المركبة</label>
                   
                    <select name="vehicle_status" class="form-select">
                        <option value="خرجت" @if($vehicle->vehicle_status =='خرجت') selected @endif>خرجت</option>
                        <option value="موجودة" @if($vehicle->vehicle_status =='موجودة') selected @endif>موجودة</option>
                    </select>
                </div>
                @endif
                <br>
                <div class="form-group">
                    <label for="lock_loction">موقع الرفع</label>
                    <input type="text" name="lock_location" class="form-control" required value="{{$vehicle->lock_location}}">
                </div>
                <br>
                <div class="form-group">
                    <label for="lock_area">مكان السحب</label>
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
                    <label for="vehicle_type">نوع المركبة</label>
                    <select name="vehicle_type" class="form-select" id="vehicle_type">
                        <option value="صغيرة" @if($vehicle->vehicle_type == 'صغيرة') selected @endif>صغيرة</option>
                        <option value="كبيرة" @if($vehicle->vehicle_type == 'كبيرة') selected @endif>كبيرة</option>
                        @if(auth::user()->type!='مشرف الشرقية' || auth::user()->type=='مشرف عام')
                        <option value="المعدات" id="equipment_option2" @if($vehicle->vehicle_type == 'المعدات') selected @endif>المعدات</option>
                        @endif
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
                <a href="{{ route('home') }}" class="btn btn-primary" style="background-color: #FC3D39; border-color:#E33437">الرجوع</a>
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
            let timeDifference = (exitDate - enterDate) / (1000 * 60 * 60);

            // إذا كان الفرق أقل من ساعة، نعتبره ساعة كاملة
            timeDifference = Math.ceil(timeDifference);

            // احسب السعر بناءً على نوع المركبة ومكان الحجز
            let price = 0;
            const vehicleType = "{{ $vehicle->vehicle_type }}";
            const lockArea = "{{ $vehicle->lock_area }}";

            if (vehicleType === 'صغيرة' && lockArea === 'داخل المنطقة') {
                price = (500) + (timeDifference * 2);
                price_after_vat = price + (price * 0.15);
            } else if (vehicleType === 'صغيرة' && lockArea === 'خارج المنطقة') {
                price = (800) + (timeDifference * 2);
                price_after_vat = price + (price * 0.15);
            } else if (vehicleType === 'كبيرة' && lockArea === 'خارج المنطقة') {
                price = (1500) + (timeDifference * 2);
                price_after_vat = price + (price * 0.15);
            } else if (vehicleType === 'كبيرة' && lockArea === 'داخل المنطقة') {
                price = (1000) + (timeDifference * 2);
                price_after_vat = price + (price * 0.15);
            } else if (vehicleType === 'المعدات' && lockArea === 'داخل المنطقة') {
                price = (2000) + (timeDifference * 2);
                price_after_vat = price + (price * 0.15);
            } else if (vehicleType === 'المعدات' && lockArea === 'خارج المنطقة') {
                price = (2700) + (timeDifference * 2);
                price_after_vat = price + (price * 0.15);
            }

            // عرض السعر في حقل vehicle_price
            priceInput.value = price_after_vat.toFixed(2);
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const vehicleCenterSelect = document.getElementById('vehicle_center2');
        const equipmentOption = document.getElementById('equipment_option2');
        const userType = "{{ auth()->user()->type }}";

        // تابع لفحص وتغيير الخيارات بناءً على اختيار المستخدم والمركز
        function handleCenterChange() {
            const selectedCenter = vehicleCenterSelect.options[vehicleCenterSelect.selectedIndex].text;
            
            if (userType === 'مشرف عام' && selectedCenter === 'المنطقة الشرقية') {
                // إخفاء خيار المعدات
                equipmentOption.style.display = 'none';
            } else {
                // إظهار خيار المعدات إذا لم يكن الشرط متحققاً
                equipmentOption.style.display = 'block';
            }
        }

        // فحص مبدئي عند تحميل الصفحة
        handleCenterChange();

        // الاستماع لتغيير المركز
        vehicleCenterSelect.addEventListener('change', handleCenterChange);
    });

    // تهيئة flatpickr
    flatpickr("#enter_date", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        position: "right"
    });
</script>
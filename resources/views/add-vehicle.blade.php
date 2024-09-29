@extends('layouts.app')

@section('content')
<div class="content-page">
    <div class="content" >
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card" style="padding: 10px">

            <h2>تسجيل المركبة</h2>
            <form id="vehicleForm" action="{{ route('vehicle.add' ) }}" method="POST" enctype="multipart/form-data">
                @csrf
                 <!-- اختيار مركز المركبة -->
                 <div class="form-group">
                    <label for="vehicle_center">مركز المركبة</label>
                    <select class="form-select" name="vehicle_center_id" id="vehicle_center">
                        @foreach($vehicleCenters as $center) 
                        @if(auth::user()->lock_center_id== 4)
                            <option value="{{ $center->id }}" > @if($center->id =='4') {{ $center->name }} @endif </option>
                        @endif
                        @if(auth::user()->lock_center_id== 7)
                            <option value="{{ $center->id }}" > @if($center->id =='7') {{ $center->name }} @endif </option>
                        @endif
                        @if(auth::user()->lock_center_id== 0)
                            <option value="{{ $center->id }}">{{ $center->name }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
                

                <br>
                {{-- <div class="form-group">
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
                
                <br> --}}
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
                    <input type="text" id="vehicle_number" name="vehicle_number" class="form-control" required>
                    <span id="vehicle_number_error" class="text-danger"></span>
                </div>
                
                <br>
                 <!-- اختيار نوع المركبة -->
                 <div class="form-group">
                    <label for="vehicle_type">نوع المركبة</label>
                    <select name="vehicle_type" class="form-select" id="vehicle_type">
                        <option value="صغيرة">صغيرة</option>
                        <option value="كبيرة">كبيرة</option>
                        @if(auth::user()->type!='مشرف الشرقية' || auth::user()->type=='مشرف عام')
                        <option value="المعدات" id="equipment_option">المعدات</option>
                        @endif
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
                    <input type="text" id="chassis_number" name="chassis_number" class="form-control" required>
                    <span id="chassis_number_error" class="text-danger"></span>
                </div>

                <br>
                <div class="form-group">
                    <label for="vehicle_images">صور المركبة</label>
                    <input type="file" accept="image/*" name="vehicle_images[]" class="form-control" multiple required>
                </div>
                
                <br>
                <br>
                <a href="{{ route('home') }}" class="btn btn-primary" style="background-color: #FC3D39; border-color:#E33437">الرجوع</a>
                <button type="submit" class="btn btn-primary" style="background-color:#46C263; border-color:#53D769">تسجيل المركبة</button>
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
        const vehicleCenterSelect = document.getElementById('vehicle_center');
        const equipmentOption = document.getElementById('equipment_option');
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const vehicleNumberInput = document.getElementById('vehicle_number');
        const chassisNumberInput = document.getElementById('chassis_number');
        const vehicleForm = document.getElementById('vehicleForm');

        // Function to check uniqueness
        function checkUniqueness(field, value, callback) {
            const url = "{{ route('vehicle.checkUniqueness') }}"; // Define route in web.php to check uniqueness
            fetch(`${url}?field=${field}&value=${value}`)
                .then(response => response.json())
                .then(data => {
                    callback(data.isUnique);
                });
        }

        // Check vehicle number uniqueness
        vehicleNumberInput.addEventListener('change', function() {
            const vehicleNumber = this.value;
            checkUniqueness('vehicle_number', vehicleNumber, function(isUnique) {
                const vehicleNumberError = document.getElementById('vehicle_number_error');
                if (!isUnique) {
                    vehicleNumberError.textContent = "رقم المركبة موجود بالفعل";
                } else {
                    vehicleNumberError.textContent = "";
                }
            });
        });

        // Check chassis number uniqueness
        chassisNumberInput.addEventListener('change', function() {
            const chassisNumber = this.value;
            checkUniqueness('chassis_number', chassisNumber, function(isUnique) {
                const chassisNumberError = document.getElementById('chassis_number_error');
                if (!isUnique) {
                    chassisNumberError.textContent = "رقم الهيكل موجود بالفعل";
                } else {
                    chassisNumberError.textContent = "";
                }
            });
        });

        // Prevent form submission if there are errors
        vehicleForm.addEventListener('submit', function(event) {
            const vehicleNumberError = document.getElementById('vehicle_number_error').textContent;
            const chassisNumberError = document.getElementById('chassis_number_error').textContent;

            if (vehicleNumberError || chassisNumberError) {
                event.preventDefault(); // Prevent form submission
                alert('يرجى تصحيح الأخطاء قبل المتابعة');
            }
        });
    });
</script>
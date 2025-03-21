@extends('layouts.app')

@section('content')
<div class="content-page">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="content" >
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card" style="padding: 10px">

            <h2>تسجيل المركبة</h2>
            <form id="vehicleForm2" action="{{ route('vehicle.update', ['id' => $vehicle->id]) }}" method="POST" enctype="multipart/form-data">
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
                            @if(auth::user()->lock_center_id==  $center->id )
                            <option value="{{ $center->id }}" @if($vehicle->vehicle_center_id == $center->id) selected @endif>  {{ $center->name }}  </option>
                            @endif
                            {{-- @if(auth::user()->lock_center_id==7)
                            <option value="{{ $center->id }}" @if($vehicle->vehicle_center_id == $center->id) selected @endif> @if($center->name =='المنطقة الشرقية'){{  $center->name}} @endif </option>
                            @endif --}}
                            @if(auth::user()->lock_center_id==0)
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
                    <label for="vehicle_number">رقم البلاغ</label>
                    <input type="text" id="report_number" name="report_number" class="form-control" required value="{{$vehicle->report_number}}">
                    <span id="report_number" class="text-danger"></span>
                </div>
                <br>
                <div class="form-group">
                    <label for="vehicle_number">لون المركبة</label>
                    <input type="text" id="vehicle_color" name="vehicle_color" class="form-control"  value="{{$vehicle->vehicle_color}}">
                    <span id="vehicle_color" class="text-danger"></span>
                </div>
                <br>
                <div class="form-group">
                    <label for="vehicle_number">رقم اللوحة</label>
                    <input type="text" name="vehicle_number" id="vehicle_number2" class="form-control" required value="{{$vehicle->vehicle_number}}">
                    <small id="vehicle_number_error" class="text-danger"></small>
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
                    <input type="text" name="chassis_number" id="chassis_number2" class="form-control" required value="{{$vehicle->chassis_number}}">
                    <small id="chassis_number_error" class="text-danger"></small>
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
                  <div class="form-group">
                    <label for="discount">نسبة الخصم (%)</label>
                    <input type="number" id="discount" name="discount" class="form-control" min="0" max="100" value="{{ $vehicle->discount }}">
                </div>
                <br>
                  @endif
             
                <div class="form-group">
                    {{-- عرض الصور الحالية --}}
                    @foreach($vehicle->images as $image)
                    <div class="image-container" style="display: inline-block; position: relative;">
                        <img src="{{ asset('images/'.$image->image_path) }}" alt="Vehicle Image" style="width:100px; height:100px;">
                        <button type="button" class="delete-image" data-id="{{ $image->id }}" 
                                style="position: absolute; top: 5px; right: 5px; background: red; color: white; border: none; border-radius: 50%;">
                            X
                        </button>
                    </div>
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
        // Select the exit date input and the price input fields
        const exitDateInput = document.getElementById('exit_date');
        const priceInput = document.getElementById('vehicle_price');
        const discountInput = document.getElementById('discount'); // New discount input field
        // Define the centerPrices data (assumed to be passed from the backend)
        const centerPrices = @json($centerPrices);

        // Listen for the change in the exit date input
        exitDateInput.addEventListener('input', function() {
            // Get the value of the enter date from the vehicle data
            const enterDate = new Date("{{ $vehicle->enter_date }}");

            // Get the selected exit date
            const exitDate = new Date(exitDateInput.value); 
           

            // Calculate the time difference in hours
            let timeDifference = (exitDate - enterDate) / (1000 * 60 * 60);

            // Round up to the nearest whole hour if the time difference is less than one hour
            timeDifference = Math.ceil(timeDifference);

            // Default price is zero
            let price = 0;

            // Get the vehicle type and lock area
            const vehicleType = "{{ $vehicle->vehicle_type }}";
            const lockArea = "{{ $vehicle->lock_area }}";
            
            // Loop through the center prices and calculate the appropriate price based on the conditions
            centerPrices.forEach(centerPrice => {
                if ("{{ $vehicle->vehicle_center_id }}" === centerPrice.center_id.toString()) {
                    // Calculate the price based on the vehicle type and lock area
                    if (vehicleType === 'صغيرة' && lockArea === 'داخل المنطقة') {
                        price = centerPrice.price_small_inside + (timeDifference * 2);
                    } else if (vehicleType === 'صغيرة' && lockArea === 'خارج المنطقة') {
                        price = centerPrice.price_small_outside + (timeDifference * 2);
                    } else if (vehicleType === 'كبيرة' && lockArea === 'خارج المنطقة') {
                        price = centerPrice.price_big_outside + (timeDifference * 2);
                    } else if (vehicleType === 'كبيرة' && lockArea === 'داخل المنطقة') {
                        price = centerPrice.price_big_inside + (timeDifference * 2);
                    } else if (vehicleType === 'المعدات' && lockArea === 'داخل المنطقة') {
                        price = centerPrice.price_equipment_inside + (timeDifference * 2);
                    } else if (vehicleType === 'المعدات' && lockArea === 'خارج المنطقة') {
                        price = centerPrice.price_equipment_outside + (timeDifference * 2);
                    }
                }
            });

            
            // Get discount value
            let discountPercentage = parseFloat(discountInput.value) || 0;
            let discountAmount = (price * discountPercentage) / 100;
           // Calculate final price after discount
            let priceAfterDiscount = price - discountAmount;

            // Add VAT (15%)
            const priceAfterVAT = priceAfterDiscount + (priceAfterDiscount * 0.15);
            // Update the price input field with the calculated price
            priceInput.value = priceAfterVAT.toFixed(2);
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const vehicleNumberInput = document.getElementById('vehicle_number2');
        const chassisNumberInput = document.getElementById('chassis_number2');
        const vehicleForm = document.getElementById('vehicleForm2');

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
        // vehicleNumberInput.addEventListener('change', function() {
        //     const vehicleNumber = this.value;
        //     checkUniqueness('vehicle_number', vehicleNumber, function(isUnique) {
        //         const vehicleNumberError = document.getElementById('vehicle_number_error');
        //         if (!isUnique) {
        //             vehicleNumberError.textContent = "رقم المركبة موجود بالفعل";
        //         } else {
        //             vehicleNumberError.textContent = "";
        //         }
        //     });
        // });

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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reportNumberInput = document.getElementById('report_number');

        // Listen for input changes
        reportNumberInput.addEventListener('input', function() {
            const value = reportNumberInput.value;

            // Check if the input is not a number
            if (!/^\d*$/.test(value)) {
                reportNumberInput.value = value.replace(/\D/g, ''); // Remove non-numeric characters
                alert('يرجى إدخال أرقام فقط في حقل رقم البلاغ'); // Display error message
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-image').forEach(button => {
        button.addEventListener('click', function () {
            let imageId = this.getAttribute('data-id');
            let imageContainer = this.parentElement;

            if (confirm('هل أنت متأكد أنك تريد حذف هذه الصورة؟')) {
                fetch(`/vehicle/image/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        imageContainer.remove(); // Remove image from UI
                    } else {
                        alert('خطأ: تعذر حذف الصورة.');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });
});

</script>

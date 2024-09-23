@extends('layouts.app')

@section('content')
<div class="container">
    <h2>إخراج المركبة</h2>
    
    <form action="{{ route('vehicle.submitExit', $vehicle->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="exit_date">تاريخ وزمن خروج المركبة</label>
            <input type="datetime-local" name="exit_date" id="exit_date" class="form-control" value="{{ old('exit_date') }}" required>
            @error('exit_date')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="vehicle_price">سعر الاخراج</label>
            <input type="text" id="vehicle_price" class="form-control" value="سيتم حساب السعر تلقائياً" readonly>
        </div>
<br>
<a href="{{ route('home') }}" class="btn btn-primary" style="background-color: #FC3D39; border-color:#E33437">الرجوع</a>
        <button type="submit" class="btn btn-success" style="background-color: #46C263; border-color:#53D769">إخراج المركبة</button>
       
    </form>
    <table class="table table-bordered">
    @if($vehicle->vehicle_status == 'خرجت')
    <tr>
        <th>تاريخ وزمن دخول المركبة</th>
        <td>{{ $vehicle->enter_date }}</td>
    </tr>
        <tr>
            <th>تاريخ وزمن خروج المركبة</th>
            <td>{{ $vehicle->exit_date }}</td>
        </tr>
        <tr>
            <th> عدد الساعات</th>
            <td>
                @php
                    $enterDate = \Carbon\Carbon::parse($vehicle->enter_date);
                    $exitDate = \Carbon\Carbon::parse($vehicle->exit_date);
                    $hoursDifference = $exitDate->diffInMinutes($enterDate) / 60; // Calculate hours including minutes
                    $roundedHours = ceil($hoursDifference); // Round up to nearest hour
                @endphp
                {{ $roundedHours }} ساعة
            </td>
        </tr>
        <tr>
            <th> أجرة الساعات</th>
            <td>
                @php
                    $enterDate = \Carbon\Carbon::parse($vehicle->enter_date);
                    $exitDate = \Carbon\Carbon::parse($vehicle->exit_date);
                    $hoursDifference = $exitDate->diffInMinutes($enterDate) / 60; // Calculate hours including minutes
                    $roundedHours = ceil($hoursDifference); // Round up to nearest hour
                @endphp
                {{ $roundedHours* 2 }} ريال
            </td>
        </tr>
        <tr>
            <th>أجرة السحب  </th>
            @php
               $enterDate = \Carbon\Carbon::parse($vehicle->enter_date);
                    $exitDate = \Carbon\Carbon::parse($vehicle->exit_date);
                    $hoursDifference = $exitDate->diffInMinutes($enterDate) / 60; // Calculate hours including minutes
                    $roundedHours = ceil($hoursDifference); // Round up to nearest hour
            $normalPrice = round(($vehicle->vehicle_price)/(1+0.15));
            $fixedPrice = $normalPrice - (2*$roundedHours);
              @endphp
            <td>{{$fixedPrice}}</td>
        </tr>
        
    
        <tr>
            <th>السعر قبل إضافة الضريبة</th>
            <td>{{round(($vehicle->vehicle_price)/(1+0.15))}}</td>
        </tr>
        <tr>
            <th>السعر بعد إضافة الضريبة</th>
            <td>{{ $vehicle->vehicle_price }}</td>
        </tr>
        @endif
    </table>
</div>

<script>
    document.getElementById('exit_date').addEventListener('change', function() {
        const exitDate = new Date(this.value);
        const enterDate = new Date("{{ $vehicle->enter_date }}"); // تاريخ الدخول من قاعدة البيانات

        if (exitDate > enterDate) {
            // حساب الساعات بين تاريخ الدخول والخروج
            let hours = Math.abs(exitDate - enterDate) / 36e5;

            // إذا كان الفرق أقل من ساعة، نعتبره ساعة كاملة
            hours = Math.ceil(hours);

            let pricePerHour = 0;

            // تحديد السعر بناءً على نوع المركبة ومكان الحجز
            if ("{{ $vehicle->vehicle_type }}" === 'صغيرة') {
                if ("{{ $vehicle->lock_area }}" === 'داخل المنطقة') {
                    pricePerHour = 500;
                } else if ("{{ $vehicle->lock_area }}" === 'خارج المنطقة') {
                    pricePerHour = 800;
                }
            } else if ("{{ $vehicle->vehicle_type }}" === 'كبيرة') {
                if ("{{ $vehicle->lock_area }}" === 'داخل المنطقة') {
                    pricePerHour = 1000;
                } else if ("{{ $vehicle->lock_area }}" === 'خارج المنطقة') {
                    pricePerHour = 1500;
                }
            } else if ("{{ $vehicle->vehicle_type }}" === 'المعدات') {
                if ("{{ $vehicle->lock_area }}" === 'داخل المنطقة') {
                    pricePerHour = 2000;
                } else if ("{{ $vehicle->lock_area }}" === 'خارج المنطقة') {
                    pricePerHour = 2700;
                }
            }

            // حساب السعر الإجمالي
            const totalPrice = (pricePerHour) + (2 * hours);
            const totalPriceAfterVat = (totalPrice) +(0.15 * totalPrice)
            document.getElementById('vehicle_price').value = totalPriceAfterVat.toFixed(2) + " ريال";
        } else {
            alert('تاريخ الخروج يجب أن يكون بعد تاريخ الدخول');
        }
    });
</script>

@endsection

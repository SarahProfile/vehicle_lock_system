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
            <label for="vehicle_price">سعر المركبة</label>
            <input type="text" id="vehicle_price" class="form-control" value="سيتم حساب السعر تلقائياً" readonly>
        </div>
<br>
        <button type="submit" class="btn btn-success">إخراج المركبة</button>
        <a href="{{ route('home') }}" class="btn btn-primary" >الرجوع</a>
    </form>
</div>

<script>
    document.getElementById('exit_date').addEventListener('change', function() {
        const exitDate = new Date(this.value);
        const enterDate = new Date("{{ $vehicle->enter_date }}"); // تاريخ الدخول من قاعدة البيانات

        if (exitDate > enterDate) {
            const hours = Math.abs(exitDate - enterDate) / 36e5; // حساب الساعات بين تاريخ الدخول والخروج

            let pricePerHour = 0;

            // تحديد السعر بناءً على نوع المركبة ومكان الحجز
            if ("{{ $vehicle->vehicle_type }}" === 'صغيرة') {
                if ("{{ $vehicle->lock_area }}" === 'داخل المنطقة') {
                    pricePerHour = 500;
                } else if ("{{ $vehicle->lock_area }}" === 'خارج المنطقة') {
                    pricePerHour = 800;
                }
            } else if ("{{ $vehicle->vehicle_type }}" === 'كبيرة' && "{{ $vehicle->lock_area }}" === 'خارج المنطقة') {
                pricePerHour = 1500;
            }
         else if ("{{ $vehicle->vehicle_type }}" === 'كبيرة' && "{{ $vehicle->lock_area }}" === 'داخل المنطقة') {
                pricePerHour = 1000;
            }
            else if ("{{ $vehicle->vehicle_type }}" === 'المعدات' && "{{ $vehicle->lock_area }}" === 'داخل المنطقة') {
                pricePerHour = 2000;
            }
            else if ("{{ $vehicle->vehicle_type }}" === 'المعدات' && "{{ $vehicle->lock_area }}" === 'خارج المنطقة') {
                pricePerHour = 2700;
            }
            const totalPrice = (pricePerHour * hours) + (2 * hours);

            document.getElementById('vehicle_price').value = totalPrice.toFixed(2) + " ريال";
        } else {
            alert('تاريخ الخروج يجب أن يكون بعد تاريخ الدخول');
        }
    });
</script>
@endsection

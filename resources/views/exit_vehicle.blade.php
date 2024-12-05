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
        <table class="table table-bordered">
            <tr>
                <th>تاريخ وزمن دخول المركبة</th>
                <td>{{ $vehicle->enter_date }}</td>
            </tr>
            <tr>
                <th>تاريخ وزمن خروج المركبة</th>
                <td id="exitDateField">سيتم تحديده</td>
            </tr>
            <tr>
                <th>عدد الساعات</th>
                <td id="hoursField">سيتم حسابه</td>
            </tr>
            <tr>
                <th>أجرة الساعات</th>
                <td id="priceField">سيتم حسابه</td>
            </tr>
            <tr>
                <th>السعر قبل إضافة الضريبة</th>
                <td id="beforeVatField">سيتم حسابه</td>
            </tr>
            <tr>
                <th>السعر بعد إضافة الضريبة</th>
                <td id="afterVatField">سيتم حسابه</td>
            </tr>
        </table>
        <br>

        <a href="{{ route('home') }}" class="btn btn-primary" style="background-color: #FC3D39; border-color:#E33437">الرجوع</a>
        <button type="submit" class="btn btn-success" style="background-color: #46C263; border-color:#53D769">إخراج المركبة</button>
    </form>
    <br>

</div>

{{-- <script>
    document.getElementById('exit_date').addEventListener('change', function() {
        const exitDate = new Date(this.value);
        const enterDate = new Date("{{ $vehicle->enter_date }}"); // تاريخ الدخول من قاعدة البيانات

        if (exitDate > enterDate) {
            // حساب الساعات بين تاريخ الدخول والخروج
            let hours = Math.abs(exitDate - enterDate) / 36e5;

            // إذا كان الفرق أقل من ساعة، نعتبره ساعة كاملة
            hours = Math.ceil(hours);

            let pricePerHour = 0;

            // Loop through center prices to find the correct one based on vehicle's vehicle_center_id
            @foreach($centerPrices as $centerPrice)
                if ("{{ $vehicle->vehicle_center_id }}" === '{{ $centerPrice->center_id }}') {
                    // Based on vehicle type and lock area, assign price
                    if ("{{ $vehicle->vehicle_type }}" === 'صغيرة') {
                        pricePerHour = ("{{ $vehicle->lock_area }}" === 'داخل المنطقة') ? '{{ $centerPrice->price_small_inside }}' : '{{ $centerPrice->price_small_outside }}';
                    } else if ("{{ $vehicle->vehicle_type }}" === 'كبيرة') {
                        pricePerHour = ("{{ $vehicle->lock_area }}" === 'داخل المنطقة') ? '{{ $centerPrice->price_big_inside }}' : '{{ $centerPrice->price_small_outside }}';
                    } else if ("{{ $vehicle->vehicle_type }}" === 'المعدات') {
                        pricePerHour = ("{{ $vehicle->lock_area }}" === 'داخل المنطقة') ? '{{ $centerPrice->price_equipment_inside }}' : '{{ $centerPrice->price_equipment_outside }}';
                    }
                }
            @endforeach

            // حساب السعر الإجمالي
            const totalPrice = (pricePerHour) + (2 * hours);
            const totalPriceBeforeVat = totalPrice;
            const totalPriceAfterVat = totalPriceBeforeVat + (0.15 * totalPriceBeforeVat);

            // Update table fields dynamically
            document.getElementById('exitDateField').innerText = exitDate.toLocaleString();
            document.getElementById('hoursField').innerText = hours + " ساعة";
            document.getElementById('priceField').innerText = (2 * hours) + " ريال";
            document.getElementById('beforeVatField').innerText = totalPriceBeforeVat.toFixed(2) + " ريال";
            document.getElementById('afterVatField').innerText = totalPriceAfterVat.toFixed(2) + " ريال";

            // Update the vehicle price input field
            document.getElementById('vehicle_price').value = totalPriceAfterVat.toFixed(2) + " ريال";
        } else {
            alert('تاريخ الخروج يجب أن يكون بعد تاريخ الدخول');
        }
    });
</script> --}}
<script>
    // Pass the centerPrices data to JavaScript
    const centerPrices = @json($centerPrices);

    document.getElementById('exit_date').addEventListener('change', function() {
        const exitDate = new Date(this.value);
        const enterDate = new Date("{{ $vehicle->enter_date }}");

        if (exitDate > enterDate) {
            let hours = Math.abs(exitDate - enterDate) / 36e5;
            hours = Math.ceil(hours); // Round up to the nearest hour

            let pricePerHour = 0;

            // Loop through centerPrices to find the matching one
            centerPrices.forEach(centerPrice => {
                if ("{{ $vehicle->vehicle_center_id }}" === centerPrice.center_id.toString()) {
                    // Based on vehicle type and lock area, assign price
                    if ("{{ $vehicle->vehicle_type }}" === 'صغيرة') {
                        pricePerHour = ("{{ $vehicle->lock_area }}" === 'داخل المنطقة') ? centerPrice.price_small_inside : centerPrice.price_small_outside;
                    } else if ("{{ $vehicle->vehicle_type }}" === 'كبيرة') {
                        pricePerHour = ("{{ $vehicle->lock_area }}" === 'داخل المنطقة') ? centerPrice.price_big_inside : centerPrice.price_big_outside;
                    } else if ("{{ $vehicle->vehicle_type }}" === 'المعدات') {
                        pricePerHour = ("{{ $vehicle->lock_area }}" === 'داخل المنطقة') ? centerPrice.price_equipment_inside : centerPrice.price_equipment_outside;
                    }
                }
            });

            const totalPrice = (pricePerHour) + (2 * hours);
            const totalPriceBeforeVat = totalPrice;
            const totalPriceAfterVat = totalPriceBeforeVat + (0.15 * totalPriceBeforeVat);

            // Update the table dynamically
            document.getElementById('exitDateField').innerText = exitDate.toLocaleString();
            document.getElementById('hoursField').innerText = hours + " ساعة";
            document.getElementById('priceField').innerText = totalPrice + " ريال";
            document.getElementById('beforeVatField').innerText = totalPriceBeforeVat.toFixed(2) + " ريال";
            document.getElementById('afterVatField').innerText = totalPriceAfterVat.toFixed(2) + " ريال";

            // Update the vehicle price input field
            document.getElementById('vehicle_price').value = totalPriceAfterVat.toFixed(2) + " ريال";
        } else {
            alert('تاريخ الخروج يجب أن يكون بعد تاريخ الدخول');
        }
    });
</script>


@endsection

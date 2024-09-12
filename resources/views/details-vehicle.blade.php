@extends('layouts.app')

@section('content')
<div class="container">
    <h2>بيانات المركبة</h2>
    <table class="table table-bordered">
        <tr>
            <th>مركز الحجز</th>
            <td>{{ $vehicle->vehicle_center }}</td>
        </tr>
        <tr>
            <th>تاريخ وزمن دخول المركبة</th>
            <td>{{ $vehicle->enter_date }}</td>
        </tr>
        <tr>
            <th>موقع الرفع</th>
            <td>{{ $vehicle->lock_location }}</td>
        </tr>
        <tr>
            <th>مكان الحجز</th>
            <td>{{ $vehicle->lock_area }}</td>
        </tr>
        <tr>
            <th>رقم المركبة</th>
            <td>{{ $vehicle->vehicle_number }}</td>
        </tr>
        <tr>
            <th>موديل المركبة</th>
            <td>{{ $vehicle->vehicle_model }}</td>
        </tr>
        <tr>
            <th>رقم الهيكل</th>
            <td>{{ $vehicle->chassis_number }}</td>
        </tr>
        <tr>
            <th>صور المركبة</th>
            <td>
                @foreach($vehicle->images as $image)
                <img src="{{ asset('images/'.$image->image_path) }}" alt="Vehicle Image" style="width:100px; height:100px;">
            @endforeach
            
        </td>
        </tr>
    </table>
    <a href="{{ route('home') }}" class="btn btn-primary" >الرجوع</a>
    @if(auth::user()->type!='مشاهد' && auth::user()->type!='مشاهد الشرقية' && auth::user()->type!='مشاهدالمدينة')
    <td class="table-action">
        <a href="{{ route('vehicle.edit', $vehicle->id) }}" class="badge bg-success" style="font-size: 20px">تعديل</a>
    </td>
    <td class="table-action">
        <a href="{{ route('vehicle.exit', $vehicle->id) }}" class="badge bg-success" style="font-size: 20px">إخراج المركبة</a>
    </td>
    @endif
</div>
@endsection

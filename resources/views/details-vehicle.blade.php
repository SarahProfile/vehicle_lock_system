@extends('layouts.app')

@section('content')
<div class="container">
    <h2>بيانات المركبة</h2>
    <table class="table table-bordered">
        {{-- <tr>
            <th>مركز الحجز</th>
            <td>{{ $vehicle->vehicle_center }}</td>
        </tr> --}}
        <tr>
            <th>مركز الحجز</th>
            <td>{{ $vehicle->vehicleCenter->name }}</td>
        </tr>
   

        <tr>
            <th>تاريخ وزمن دخول المركبة</th>
            <td>{{ $vehicle->enter_date }}</td>
        </tr>
        @if($vehicle->vehicle_status == 'خرجت')
        <tr>
            <th>  حالة المركبة</th>
            <td>{{ $vehicle->vehicle_status }}</td>
        </tr>
        @endif
        <tr>
            <th>موقع الرفع</th>
            <td>{{ $vehicle->lock_location }}</td>
        </tr>
        <tr>
            <th>مكان السحب</th>
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
            <th>نوع المركبة</th>
            <td>{{ $vehicle->vehicle_type }}</td>
        </tr>
        <tr>
            <th>رقم الهيكل</th>
            <td>{{ $vehicle->chassis_number }}</td>
        </tr>
        @if($vehicle->vehicle_status == 'خرجت')
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
            <th>تاريخ وزمن خروج المركبة</th>
            <td>{{ $vehicle->exit_date }}</td>
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
        <tr>
            <th>صور المركبة</th>
            <td>
                @foreach($vehicle->images as $image)
                    <img src="{{ asset('images/'.$image->image_path) }}" alt="Vehicle Image" style="width:100px; height:100px;" onclick="openGallery('{{ asset('images/'.$image->image_path) }}')">
                @endforeach
            </td>
            
         
            
            <!-- Modal for the image gallery -->
            <div id="imageGalleryModal" class="modal" onclick="closeGallery()">
                <span class="close">&times;</span>
                <img class="modal-content" id="galleryImage">
            </div>
        </tr>
    </table>
    <a href="{{ route('home') }}" class="btn btn-primary" style="background-color: #FC3D39; border-color:#E33437" >الرجوع</a>
    @if(auth::user()->type!='مشاهد' && auth::user()->type!='مشاهد الشرقية' && auth::user()->type!='مشاهدالمدينة')
    <td class="table-action">
        <a href="{{ route('vehicle.edit', $vehicle->id) }}" class="btn btn-primary" >تعديل</a>
    </td>
    @if($vehicle->vehicle_status != 'خرجت')
    <td class="table-action">
        <a href="{{ route('vehicle.exit', $vehicle->id) }}" class="btn btn-primary"  style="background-color: #46C263; border-color:#53D769">إخراج المركبة</a>
    </td>
    @endif
    @endif
</div>
@endsection
<style>
    /* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9); /* Black background with opacity */
}

/* Modal Content (the image) */
.modal-content {
    margin: auto;
    display: block;
    max-width: 90%;
    max-height: 80%;
    animation-name: zoom;
    animation-duration: 0.6s;
}

/* Animation for zooming */
@keyframes zoom {
    from {transform: scale(0)}
    to {transform: scale(1)}
}

/* Close button */
.close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: white;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
}

.close:hover,
.close:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
}

    </style>
    <script>
        function openGallery(imageSrc) {
    const modal = document.getElementById('imageGalleryModal');
    const modalImg = document.getElementById('galleryImage');
    
    // Display the modal
    modal.style.display = "block";
    
    // Set the modal image to the clicked image
    modalImg.src = imageSrc;
}

function closeGallery() {
    const modal = document.getElementById('imageGalleryModal');
    
    // Hide the modal
    modal.style.display = "none";
}

        </script>
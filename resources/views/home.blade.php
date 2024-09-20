@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('عرض المركبات') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                   {{-- Table Code --}}
                   <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-2">
                                    @if(auth::user()->type!='مشاهدالمدينة' && auth::user()->type!='مشاهد الشرقية' && auth::user()->type!='مشاهد')
                                    <div class="col-sm-5">
                                        <a href="/admin/vehicles/create" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i>تسجيل المركبة</a>
                                    </div>
                                    @endif
                                </div>
                                {{-- search form --}}
                                <form method="GET"  class="mb-4">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" placeholder="ابحث بجزء من رقم اللوحة أو رقم الهيكل" value="{{ request('search') }}">
                                        <button type="submit" class="btn btn-primary" style="margin-right: 10px;">بحث</button>  <a href="{{ route('home') }}" class="btn btn-primary" style="margin-right: 10px;">الرجوع</a>
                                    </div>
                                </form>
                                
                                {{-- vehicles details for المدينة --}}
                                @if(auth::user()->type!='مشاهد الشرقية' && auth::user()->type!='مشرف الشرقية')
                            <h4> مركبات المدينة</h4>
                                <div class="table-responsive">
                                    <table class="table table-centered w-100 dt-responsive nowrap" id="car-datatable">
                                        <thead class="table-light">
                                            <tr>
                                                {{-- <th class="all">تاريخ دخول المركبة</th> --}}
                                                <tr>
                                                    <th class="all">
                                                        تاريخ دخول المركبة
                                                        <button type="button" class="btn btn-link p-0" style="margin-left: 5px;" onclick="toggleDateOrder()">
                                                            <i class="mdi mdi-filter" style="font-size: 18px;"></i>
                                                        </button>
                                                    </th>
                                
                                                <th class="all">رقم اللوحة</th>
                                                <th class="all">رقم الهيكل</th>
                                                {{-- <th class="all">موقع الرفع</th> --}}
                                                <th class="all">
                                                    موقع الرفع
                                                    <button type="button" class="btn btn-link p-0" style="margin-left: 5px;" onclick="toggleLockLocation()">
                                                        <i class="mdi mdi-filter" style="font-size: 18px;"></i>
                                                    </button>
                                                
                                                    <!-- Form for lock location sorting -->
                                                    <form id="lock-location-form" method="GET" style="display: inline;">
                                                        <input type="hidden" name="location_order" id="location-order-input" value="{{ $locationOrder }}">
                                                        {{-- Add other filters if necessary --}}
                                                    </form>
                                                </th>
                                                
                                                {{-- <th class="all">الحالة</th> --}}
                                                <th class="all">
                                                    الحالة
                                                    <button type="button" class="btn btn-link p-0" style="margin-left: 5px;" onclick="toggleStatusOrder()">
                                                        <i class="mdi mdi-filter" style="font-size: 18px;"></i>
                                                    </button>
                                                </th>
                                                <th style="width: 85px;">التفاصيل</th>
                                    
                                            </tr>
                                        </thead>
                                        @foreach($vehicles as $vehicle)
                                        @if($vehicle->vehicle_center =='المدينة')
                                        <tbody>
                                            {{-- @foreach($vehicles as $vehicle) --}}
                                                <tr>
                                                    {{-- <td>{{ $vehicle->enter_date }}</td> --}}
                                                    <td> {{ \Carbon\Carbon::parse($vehicle->enter_date)->format('Y-m-d') }}</td>
                                                    <td>{{ $vehicle->vehicle_number }}</td>
                                                    <td>{{ $vehicle->chassis_number }}</td>
                                                    <td>{{ $vehicle->lock_location }}</td>
                                                    <td>{{ $vehicle->vehicle_status}}</td>
                                                    <td class="table-action">
                                                        <a href="{{ route('vehicle.showFull', $vehicle->id) }}" class="badge bg-success" style="font-size: 20px">التفاصيل</a>
                                                    </td>
                                                    {{-- @if(auth::user()->type!='مشاهد')
                                                    <td class="table-action">
                                                        <a href="{{ route('vehicle.edit', $vehicle->id) }}" class="badge bg-success" style="font-size: 20px">تعديل</a>
                                                    </td>
                                                    @endif --}}
                                                </tr>
                                            {{-- @endforeach --}}
                                        </tbody>
                                        @endif
                                        @endforeach
                                    </table>
                                </div>
                             
                                @endif
                                {{----------------------------------}}
                                         {{-- vehicles details for الشرقية --}}
                                       
                                         @if(auth::user()->type!='مشاهدالمدينة' && auth::user()->type!='مشرف المدينة')
                                         <h4> مركبات المنطقة الشرقية</h4>
                                         <div class="table-responsive">
                                             <table class="table table-centered w-100 dt-responsive nowrap" id="car-datatable">
                                                 <thead class="table-light">
                                                     <tr>
                                                        
                                                        <th class="all">
                                                            تاريخ دخول المركبة
                                                            <button type="button" class="btn btn-link p-0" style="margin-left: 5px;" onclick="toggleDateOrder()">
                                                                <i class="mdi mdi-filter" style="font-size: 18px;"></i>
                                                            </button>
                                                        </th>
                                                         {{-- <th class="all">تاريخ دخول المركبة</th> --}}
                                                         <th class="all">رقم اللوحة</th>
                                                         <th class="all">رقم الهيكل</th>
                                                         {{-- <th class="all">موقع الرفع</th> --}}
                                                         <th class="all">
                                                            موقع الرفع
                                                            <button type="button" class="btn btn-link p-0" style="margin-left: 5px;" onclick="toggleLockLocation()">
                                                                <i class="mdi mdi-filter" style="font-size: 18px;"></i>
                                                            </button>
                                                        
                                                            <!-- Form for lock location sorting -->
                                                            <form id="lock-location-form" method="GET" style="display: inline;">
                                                                <input type="hidden" name="location_order" id="location-order-input" value="{{ $locationOrder }}">
                                                                {{-- Add other filters if necessary --}}
                                                            </form>
                                                        </th>
                                                        
                                                         {{-- <th class="all">الحالة</th> --}}
                                                         <th class="all">
                                                            الحالة
                                                            <button type="button" class="btn btn-link p-0" style="margin-left: 5px;" onclick="toggleStatusOrder()">
                                                                <i class="mdi mdi-filter" style="font-size: 18px;"></i>
                                                            </button>
                                                        </th>
                                                         <th style="width: 85px;">التفاصيل</th>
                                                     </tr>
                                                 </thead>
                                                 @foreach($vehicles as $vehicle)
                                                 @if($vehicle->vehicle_center == 'المنطقة الشرقية')
                                                 <tbody>
                                                     {{-- @foreach($vehicles as $vehicle) --}}
                                                         <tr>
                                                             {{-- <td>{{ $vehicle->enter_date }}</td> --}}
                                                             <td>{{ \Carbon\Carbon::parse($vehicle->enter_date)->format('Y-m-d') }}</td>
                                                             <td>{{ $vehicle->vehicle_number }}</td>
                                                             <td>{{ $vehicle->chassis_number }}</td>
                                                             <td>{{ $vehicle->lock_location }}</td>
                                                             <td>{{ $vehicle->vehicle_status}}</td>
                                                             <td class="table-action">
                                                                 <a href="{{ route('vehicle.showFull', $vehicle->id) }}" class="badge bg-success" style="font-size: 20px">التفاصيل</a>
                                                             </td>
                                                             {{-- @if(auth::user()->type!='مشاهد')
                                                             <td class="table-action">
                                                                <a href="{{ route('vehicle.edit', $vehicle->id) }}" class="badge bg-success" style="font-size: 20px">تعديل</a>
                                                            </td>
                                                              @endif --}}
                                                         </tr>
                                                     {{-- @endforeach --}}
                                                 </tbody>
                                                 @endif
                                                 @endforeach
                                             </table>
                                         </div>
                                        
                                         @endif
                                         {{----------------------------------}}
                                         
                            </div>
                        </div>
                    </div>
                </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
    function toggleDateFilter() {
        var dateFilter = document.getElementById('date-filter');
        if (dateFilter.style.display === 'none' || dateFilter.style.display === '') {
            dateFilter.style.display = 'inline-block';
            dateFilter.focus(); // Focus on the input field once it's displayed
        } else {
            dateFilter.style.display = 'none';
        }
    }
</script>
<script>
    function toggleFilter(filterId) {
        var filterInput = document.getElementById(filterId);
        if (filterInput.style.display === 'none' || filterInput.style.display === '') {
            filterInput.style.display = 'inline-block';
            filterInput.focus(); // Focus on the input field once it's displayed
        } else {
            filterInput.style.display = 'none';
        }
    }
</script>
<script>
     // JavaScript for vehicle_status filter toggle
     let vehicleStatusOrder = "{{ request('vehicle_status_order') }}";
    function toggleStatusOrder() {
        if (!vehicleStatusOrder || vehicleStatusOrder === 'out_first') {
            vehicleStatusOrder = 'in_first';
        } else if (vehicleStatusOrder === 'in_first') {
            vehicleStatusOrder = 'out_first';
        } else {
            vehicleStatusOrder = 'out_first';
        }
        window.location.href = `?vehicle_status_order=${vehicleStatusOrder}`;
    }
    // JavaScript for enter_date filter toggle
     // JavaScript for enter_date filter toggle
     let enterDateOrder = "{{ request('enter_date_order') }}";
    function toggleDateOrder() {
        if (!enterDateOrder || enterDateOrder === 'desc') {
            enterDateOrder = 'asc';
        } else if (enterDateOrder === 'desc') {
            enterDateOrder = '';
        } else {
            enterDateOrder = 'desc';
        }
        window.location.href = `?enter_date_order=${enterDateOrder}`;
    }


   
</script>

<script>
    const lockLocations = @json($lockLocations); // Fetch the lock locations from the database

    function toggleLockLocation() {
        const input = document.getElementById('location-order-input');
        const currentValue = input.value;

        // Toggle between asc and desc
        if (currentValue === 'asc') {
            input.value = 'desc';
        } else {
            input.value = 'asc';
        }

        // Submit the form to apply the sorting
        document.getElementById('lock-location-form').submit();
    }
</script>


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
                                    @if(auth()->user()->type != 'مشاهد')
                                    <div class="col-sm-5">
                                            <a href="/admin/vehicles/create" class="btn btn-danger mb-2" style="background-color: #E6E6E6; color: black; border-color: #F8F8F8;">
                                                <i class="mdi mdi-plus-circle me-2"></i>تسجيل المركبة
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                {{-- Search Form --}}
                                <form method="GET" class="mb-4">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" placeholder="ابحث بجزء من رقم اللوحة أو رقم الهيكل" value="{{ request('search') }}">
                                        <button type="submit" class="btn btn-primary" style="margin-right: 10px; border-radius:5px;">بحث</button>
                                        <a href="{{ route('home') }}" class="btn btn-primary" style="margin-right: 10px; border-radius:5px; background-color: #FC3D39; border-color:#E33437">الرجوع</a>
                                    </div>
                                </form>
                                
                                {{-- Vehicles Details for المدينة --}}
                                {{-- @if(auth::user()->type != 'مشاهد الشرقية' && auth::user()->type != 'مشرف الشرقية') --}}
                               @if(auth::user()->lock_center_id != 0)
                                <h4> مركبات {{$center->name}}</h4>
                                @endif
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
                                                <th class="all">رقم اللوحة</th>
                                                <th class="all">رقم الهيكل</th>
                                                <th class="all">
                                                    موقع الرفع
                                                    <form id="lock-location-form" method="GET" style="display: inline;">
                                                        <input type="hidden" name="location_order" id="location-order-input" value="{{ $locationOrder }}">
                                                    </form>
                                                </th>
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
                                            {{-- @if($vehicle->vehicleCenter && $vehicle->vehicleCenter->name == 'المدينة') --}}
                                            <tbody>
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($vehicle->enter_date)->format('Y-m-d') }}</td>
                                                    <td>{{ $vehicle->vehicle_number }}</td>
                                                    <td>{{ $vehicle->chassis_number }}</td>
                                                    <td>{{ $vehicle->lock_location }}</td>
                                                    <td>{{ $vehicle->vehicle_status }}</td>
                                                    <td class="table-action">
                                                        <a href="{{ route('vehicle.showFull', $vehicle->id) }}" class="btn btn-primary" style="font-size: 20px">التفاصيل</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            {{-- @endif --}}
                                        @endforeach
                                    </table>
                                </div>

                                {{-- Pagination for مدينة Vehicles --}}
                                <div class="d-flex justify-content-center">
                                    {{ $vehicles->links() }}
                                </div>
                                {{-- @endif --}}

                                {{-- Vehicles Details for الشرقية --}}
                                
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
<style>
    /* Container styling */
.container {
    padding: 20px;
}

/* Card and Header */
.card-header {
    font-size: 24px;
    background-color: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
}

.card-body {
    background-color: #fff;
    padding: 20px;
}

/* Table Styling */
.table {
    width: 100%;
    margin-bottom: 1rem;
    color: #333;
    border-collapse: collapse;
}

.table th,
.table td {
    padding: 12px;
    text-align: center;
    border: 1px solid #dee2e6;
    vertical-align: middle;
}

.table th {
    background-color: #f2f2f2;
    font-weight: bold;
}

.table td {
    font-size: 14px;
}

/* Responsive Table */
.table-responsive {
    overflow-x: auto;
}

/* Buttons */
.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
    padding: 8px 16px;
    font-size: 14px;
    border-radius: 4px;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #004085;
}

.btn-link {
    color: #007bff;
    text-decoration: none;
}

.btn-link:hover {
    text-decoration: underline;
}

/* Filter buttons and icons */
.btn-link i {
    color: #6c757d;
}

.btn-link i:hover {
    color: #007bff;
}

.input-group input {
    border-radius: 5px;
    padding: 8px;
    border: 1px solid #ced4da;
}

/* Pagination Styling */
.pagination {
    display: inline-flex;
    list-style-type: none;
    padding: 0;
    margin: 20px 0;
}

.pagination li {
    margin: 0 5px;
}

.pagination li a,
.pagination li span {
    display: block;
    padding: 8px 12px;
    color: #007bff;
    background-color: #fff;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    text-decoration: none;
}

.pagination li a:hover,
.pagination li span:hover {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    cursor: pointer;
}

.pagination li.active span {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

/* Center the pagination */
.d-flex {
    justify-content: center;
}

/* Hover effects for the details button */
.table-action .btn-primary:hover {
    background-color: #0056b3;
    border-color: #004085;
}

/* Filter inputs */
#date-filter, #status-filter {
    display: none;
    width: 180px;
    padding: 6px;
    border-radius: 4px;
    border: 1px solid #ced4da;
}
.hidden.sm\:flex-1.sm\:flex.sm\:items-center.sm\:justify-between {
    padding-top: 15px;
}
span.relative.z-0.inline-flex.rtl\:flex-row-reverse.shadow-sm.rounded-md {
    display: none;
}
    </style>

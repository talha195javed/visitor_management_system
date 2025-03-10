@extends('layouts.app')

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-8">
                <div class="row">

                    <!-- Sales Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">

                            <div class="card-body">
                                <h5 class="card-title">Checked In's <span>| Today</span></h5>

                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $totalCheckedInVisitors }}</h6>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Revenue Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card revenue-card">

                            <div class="card-body">
                                <h5 class="card-title">Checkout's <span>| Today</span></h5>

                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $totalCheckedOutVisitors }}</h6>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Customers Card -->
                    <div class="col-xxl-4 col-xl-12">

                        <div class="card info-card customers-card">

                            <div class="card-body">
                                <h5 class="card-title">Total visitors <span>| Today</span></h5>

                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-outlet"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6> {{ $totalVisitors }}</h6>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div><!-- End Customers Card -->

                    <div class="col-xxl-4 col-xl-12">

                        <div class="card info-card customers-card">

                            <div class="card-body">
                                <h5 class="card-title">Total visitors <span>| Last Week</span></h5>

                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-outlet"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6> {{ $totalCheckInsLastWeek }}</h6>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div><!-- End Customers Card -->

                    <div class="col-xxl-4 col-xl-12">

                        <div class="card info-card customers-card">

                            <div class="card-body">
                                <h5 class="card-title">Total visitors <span>| Last Month</span></h5>

                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-outlet"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6> {{ $totalCheckInsLastMonth }}</h6>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div><!-- End Customers Card -->

                    <!-- Recent Sales -->
                    <div class="col-12">
                        <div class="card recent-sales overflow-auto">

                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Filter</h6>
                                    </li>

                                    <li><a class="dropdown-item" href="#">Today</a></li>
                                    <li><a class="dropdown-item" href="#">This Month</a></li>
                                    <li><a class="dropdown-item" href="#">This Year</a></li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">Recent Checked' In</h5>

                                <table class="table table-borderless datatable">
                                    <thead>
                                    <tr>
                                        <th scope="col">ID #</th>
                                        <th scope="col">Visitor Name</th>
                                        <th scope="col">Purpose</th>
                                        <th scope="col"> Host Name</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($allVisitors->sortByDesc('check_in_time')->take(6) as $visitor)
                                    @php
                                    // Calculate time difference using Carbon
                                    $checkInTime = \Carbon\Carbon::parse($visitor->check_in_time);
                                    $timeDiff = $checkInTime->diffForHumans();
                                    @endphp
                                    <tr>
                                        <th scope="row">
                                            @if($visitor->id_type == 'emirates_id')
                                            Emirates ID
                                            @elseif($visitor->id_type == 'passport')
                                            Passport
                                            @else
                                            National CNIC
                                            @endif
                                            :
                                            {{ $visitor->identification_number }}
                                        </th>
                                        <td>{{ $visitor->full_name }}</td>
                                        <td><a href="#" class="text-primary">{{ $visitor->role }}</a></td>
                                        <td>{{ $visitor->employer_name }}</td>
                                        <td>
                                            <span class="badge bg-success">
                                                @if($visitor->check_out_time == null)
                                                    CheckedIn
                                                @else
                                                    CheckedOut
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div><!-- End Recent Sales -->

                </div>
            </div><!-- End Left side columns -->

            <!-- Right side columns -->
            <div class="col-lg-4">

                <!-- Recent Activity -->
                <div class="card">

                    <div class="card-body">
                        <h5 class="card-title">Recent Checked In's <span>| Latest</span></h5>

                        <div class="activity">
                            @foreach ($allVisitors->sortByDesc('check_in_time')->take(6) as $visitor)
                            @php
                            // Calculate time difference using Carbon
                            $checkInTime = \Carbon\Carbon::parse($visitor->check_in_time);
                            $timeDiff = $checkInTime->diffForHumans();
                            @endphp

                            <div class="activity-item d-flex">
                                <div class="activite-label">{{ $timeDiff }}</div>
                                <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                <div class="activity-content">
                                    {{ $visitor->full_name }} - Purpose: {{ $visitor->purpose }}
                                </div>
                            </div><!-- End activity item-->
                            @endforeach
                        </div>

                    </div>
                </div><!-- End Recent Activity -->

                <!-- Budget Report -->
                <div class="card">

                    <div class="card-body">
                        <h5 class="card-title">Recent Hosts <span>| Latest</span></h5>

                        <div class="activity">
                            @foreach ($allVisitors->sortByDesc('check_in_time')->take(6) as $visitor)
                            @php
                            // Calculate time difference using Carbon
                            $checkInTime = \Carbon\Carbon::parse($visitor->check_in_time);
                            $timeDiff = $checkInTime->diffForHumans();
                            @endphp

                            <div class="activity-item d-flex">
                                <div class="activite-label">{{ $timeDiff }}</div>
                                <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                <div class="activity-content">
                                    {{ $visitor->employer_name }} - Purpose: {{ $visitor->purpose }}
                                </div>
                            </div><!-- End activity item-->
                            @endforeach
                        </div>

                    </div>
                </div><!-- End Recent Activity -->

                <!-- News & Updates Traffic -->
                <div class="card">

                    <div class="card-body pb-0">
                        <h5 class="card-title">Working of System</h5>

                        <div class="news">
                            <div class="post-item clearfix">
                                <img src="assets/img/news-1.jpg" alt="">
                                <h4><a href="#">Visitor Tab</a></h4>
                                <p>1. View Complete Visitor List<br>
                                   2. You can Pre Register the Visitor.<br>
                                   3. You can View Checked In's List.<br>
                                   4. You can View Checked Out's List.<br>
                                   5. You can View Archived Visitors.
                                </p>
                            </div>

                            <div class="post-item clearfix">
                                <img src="assets/img/news-2.jpg" alt="">
                                <h4><a href="#">Employes Tab</a></h4>
                                <p>1. View Complete Employee List<br>
                                    2. You can Register New Employee.<br>
                                    3. You can Edit Registered Employee.<br>
                                    4. You can View Archived Employees.
                                </p>
                            </div>

                            <div class="post-item clearfix">
                                <img src="assets/img/news-3.jpg" alt="">
                                <h4><a href="#">Admin</a></h4>
                                <p>1. View Complete Admin User List<br>
                                    2. You can Add New Admin Users.<br>
                                    3. You can Edit Admin Users.<br>
                                    4. You can View Email Logs.
                                </p>
                            </div>

                        </div>

                    </div>
                </div><!-- End News & Updates -->

            </div><!-- End Right side columns -->

        </div>
    </section>

</main><!-- End #main -->

@endsection

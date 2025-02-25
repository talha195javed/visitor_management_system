@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/email.css') }}">
@endpush

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item">Emails List</li>
            </ol>
        </nav>
    </div>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="">
                <div class="card animated fadeInUp">
                    <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                        <h3 class="m-0">Emails List</h3>
                    </div>

                    <!-- DataTable for emails -->
                    <div class="table-container">
                        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Emails</th>
                                <th>Send At</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($emails as $email)
                            <tr class="animated fadeInUp">
                                <td>{{ $email->id }}</td>
                                <td>
                                    <div class="email-list">
                                        @foreach(explode(',', $email->to_email) as $index => $emailItem)
                                        @if ($index == 0)
                                        <div class="user"><strong>User:</strong> {{ trim($emailItem) }}</div>
                                        @elseif ($index == 1)
                                        <div class="employer"><strong>Employer:</strong> {{ trim($emailItem) }}</div>
                                        @elseif ($index == 2)
                                        <div class="hr"><strong>HR:</strong> {{ trim($emailItem) }}</div>
                                        @endif
                                        @endforeach
                                    </div>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($email->created_at)->format('h:i A, d M Y') }}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('#example').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true
        });
    });
</script>

@endsection

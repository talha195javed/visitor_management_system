@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/table_list.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
        --secondary-gradient: linear-gradient(135deg, #FF6B6B 0%, #FF0D6B 100%);
        --accent-gradient: linear-gradient(135deg, #4FACFE 0%, #00F2FE 100%);
        --success-gradient: linear-gradient(135deg, #3BB78F 0%, #0BAB64 100%);
        --dark-blue: #1A237E;
        --deep-purple: #311B92;
        --electric-blue: #2962FF;
    }

    .card {
        border: none;
        border-radius: 18px;
        box-shadow: 0 12px 35px rgba(25, 34, 126, 0.15);
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
        background: white;
    }

    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 45px rgba(25, 34, 126, 0.2);
    }

    .card-header {
        background: var(--primary-gradient);
        padding: 1.75rem;
        position: relative;
        overflow: hidden;
    }

    .card-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        transform: rotate(30deg);
    }

    .card-header h3 {
        position: relative;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .table-container {
        padding: 0 2rem 2rem;
    }

    .table {
        border-collapse: separate;
        border-spacing: 0 12px;
        margin-top: -10px;
    }

    .table thead th {
        border: none;
        background-color: #F5F7FF;
        color: var(--dark-blue);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.8px;
        padding: 1rem 1.25rem;
    }

    .table tbody tr {
        background-color: white;
        box-shadow: 0 4px 15px rgba(25, 34, 126, 0.08);
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
    }

    .table tbody tr:hover {
        transform: scale(1.02);
        box-shadow: 0 8px 25px rgba(25, 34, 126, 0.12);
    }

    .table tbody td {
        vertical-align: middle;
        border-top: none;
        padding: 1.25rem;
        position: relative;
    }

    .table tbody td:first-child::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: var(--electric-blue);
        border-radius: 0 4px 4px 0;
    }

    .action-btn {
        background: var(--accent-gradient);
        border: none;
        border-radius: 50px;
        padding: 0.6rem 1.5rem;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);
    }

    .action-btn::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(255,255,255,0.3) 0%, transparent 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(79, 172, 254, 0.4);
    }

    .action-btn:hover::after {
        opacity: 1;
    }

    .register-btn {
        background: var(--success-gradient);
        box-shadow: 0 4px 15px rgba(11, 171, 100, 0.3);
    }

    .register-btn:hover {
        box-shadow: 0 8px 25px rgba(11, 171, 100, 0.4);
    }

    .avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 12px;
        border: 3px solid #F5F7FF;
        box-shadow: 0 2px 8px rgba(25, 34, 126, 0.1);
    }

    .client-name {
        display: flex;
        align-items: center;
    }

    .badge-company {
        background-color: #F5F7FF;
        color: var(--electric-blue);
        padding: 0.4rem 0.8rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid rgba(41, 98, 255, 0.2);
    }

    .status-badge {
        padding: 0.5rem 0.9rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .pagination .page-item.active .page-link {
        background: var(--primary-gradient);
        border-color: transparent;
        box-shadow: 0 4px 10px rgba(107, 115, 255, 0.3);
    }

    .pagination .page-link {
        color: var(--electric-blue);
        border: 1px solid #E0E0E0;
        margin: 0 5px;
        border-radius: 12px !important;
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        font-weight: 600;
    }

    .pagination .page-link:hover {
        background-color: #F5F7FF;
        border-color: var(--electric-blue);
    }

    /* Modal Styling */
    .modal-header {
        background: var(--primary-gradient);
        padding: 1.5rem;
    }

    .modal-title {
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .modal-content {
        border-radius: 18px;
        overflow: hidden;
        border: none;
        box-shadow: 0 15px 40px rgba(25, 34, 126, 0.2);
    }

    /* Animation Enhancements */
    .animate__animated.animate__fadeInUp {
        animation-duration: 0.6s;
    }

    tr:hover .avatar {
        transform: scale(1.1);
        transition: transform 0.3s ease;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-header {
            text-align: center;
        }

        .register-btn {
            width: 100%;
            margin-top: 1rem;
        }
    }
</style>
@endpush

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Client Management</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Clients List</li>
            </ol>
        </nav>
    </div>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card animate__animated animate__fadeIn">
                    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center">
                        <h3 class="m-0 text-white">Client Portfolio</h3>
                        <a href="{{ route('admin.users.create') }}" class="btn register-btn action-btn mt-3 mt-md-0">
                            <i class="bi bi-plus-circle me-1"></i> Register New Client
                        </a>
                    </div>

                    <div class="table-container">
                        <table id="clientTable" class="table table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th>Client</th>
                                <th>Contact</th>
                                <th>Company</th>
                                <th>Status</th>
                                <th>Visitors</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($clients as $user)
                            <tr class="animate__animated animate__fadeInUp">
                                <td>
                                    <div class="client-name">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->user_name) }}&background=random&color=fff" alt="Avatar" class="avatar">
                                        <div>
                                            <strong>{{ $user->user_name }}</strong><br>
                                            <small class="text-muted">{{ $user->user_email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->client_phone }}</td>
                                <td>
                                    <span class="badge-company">{{ $user->client_company }}</span>
                                </td>
                                <td>
                                    <span class="status-badge bg-success">Active</span>
                                </td>
                                <td>
                                    <button class="btn action-btn view-visitors" data-user-id="{{ $user->user_id }}">
                                        <i class="bi bi-people me-1"></i> View Visitors
                                    </button>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill me-2">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visitor Modal -->
    <div class="modal fade" id="visitorModal" tabindex="-1" aria-labelledby="visitorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="visitorModalLabel">Visitor Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="text-primary"><i class="bi bi-person-badge me-2"></i>Visitor Information</h5>
                        </div>
                        <div class="col-md-6 text-end">
                            <button class="btn btn-sm action-btn">
                                <i class="bi bi-download me-1"></i> Export
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="visitorTable" class="table table-hover align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>Visitor</th>
                                <th>Visit Date</th>
                                <th>Purpose</th>
                                <th>Company</th>
                                <th>Status</th>
                                <th>Details</th>
                            </tr>
                            </thead>
                            <tbody id="visitorTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn action-btn rounded-pill">Save Report</button>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    const visitorShowRoute = "{{ route('visitors.show', ':id') }}";
    $(document).ready(function() {
        $('#clientTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "dom": '<"top"<"d-flex justify-content-between align-items-center"lf>>rt<"bottom"<"d-flex justify-content-between align-items-center"ip>><"clear">',
            "language": {
                "search": "<i class='bi bi-search me-2'></i>",
                "searchPlaceholder": "Search clients...",
                "lengthMenu": "Show _MENU_ entries",
                "paginate": {
                    "previous": "<i class='bi bi-chevron-left'></i>",
                    "next": "<i class='bi bi-chevron-right'></i>"
                }
            },
            "initComplete": function() {
                $('.dataTables_filter input').addClass('form-control border-2');
                $('.dataTables_length select').addClass('form-select border-2');
            }
        });

        // Handle view visitors button click
        $('.view-visitors').on('click', function() {
            const userId = $(this).data('user-id');
            loadVisitorData(userId);
            $('#visitorModal').modal('show');
        });

        // Function to load visitor data via AJAX
        function loadVisitorData(userId) {
            $.ajax({
                url: '/client/visitors/' + userId,
                type: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    $('#visitorTableBody').html('<tr><td colspan="6" class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');
                },
                success: function(response) {
                    if(response.success && response.visitors.length > 0) {
                        let html = '';
                        response.visitors.forEach(function(visitor) {
                            html += `
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(visitor.name)}&background=random" alt="Avatar" class="avatar me-3">
                                            <div>
                                                <strong>${visitor.full_name}</strong><br>
                                                <small class="text-muted">${visitor.email}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${new Date(visitor.check_in_time).toLocaleDateString()}</td>
                                    <td>${visitor.role}</td>
                                    <td>${visitor.comapny}</td>
                                    <td>
                                        <span class="status-badge ${visitor.check_out_time ? 'bg-success' : 'bg-warning'}">
                                            ${visitor.check_out_time ? 'Checked Out' : 'Checked In'}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary rounded-pill view-visitor-details" data-visitor-id="${visitor.id}">
                                            <i class="bi bi-eye"></i> Details
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                        $('#visitorTableBody').html(html);
                    } else {
                        $('#visitorTableBody').html('<tr><td colspan="6" class="text-center text-muted py-5"><i class="bi bi-people display-6 opacity-50"></i><p class="mt-3">No visitors found for this client</p></td></tr>');
                    }
                },
                error: function() {
                    $('#visitorTableBody').html('<tr><td colspan="6" class="text-center text-danger py-5"><i class="bi bi-exclamation-triangle display-6"></i><p class="mt-3">Error loading visitor data</p></td></tr>');
                }
            });
        }

        // Handle individual visitor details view
        $(document).on('click', '.view-visitor-details', function() {
            const visitorId = $(this).data('visitor-id');
            const redirectUrl = visitorShowRoute.replace(':id', visitorId);
            window.location.href = redirectUrl;
        });
    });
</script>
@endsection

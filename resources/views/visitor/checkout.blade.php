@extends('layouts.front_app')

@section('content')
<div class="mainScreen container-fluid d-flex flex-column flex-md-row vh-100">
    <div class="card shadow-lg p-5 rounded-4" style="max-width: 800px;">
    <div class="text-center">
        <h1 class="mb-4">Visitor Check-Out</h1>
        <p>Enter your Visitor ID or Visitor Name to Checkout.</p>

        <input type="text" id="visitorIdSearch" class="form-control mb-2" placeholder="Enter Visitor ID" autocomplete="off">
        <input type="text" id="visitorNameSearch" class="form-control mb-2" placeholder="Enter Visitor Name" autocomplete="off">

        <!-- Dropdown for Search Results -->
        <div id="searchResults" class="list-group position-absolute mx-auto"></div>

        <!-- Hidden Form -->
        <form id="checkoutForm">
            @csrf
            <input type="hidden" name="visitor_id" id="selectedVisitorId">
            <button type="submit" class="btn btn-danger btn-lg mt-3" id="checkoutButton" disabled>Check Out</button>
        </form>
    </div>
    </div>
</div>

<!-- Include SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- JavaScript -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const visitorIdSearch = document.getElementById("visitorIdSearch");
        const visitorNameSearch = document.getElementById("visitorNameSearch");
        const searchResults = document.getElementById("searchResults");
        const selectedVisitorId = document.getElementById("selectedVisitorId");
        const checkoutButton = document.getElementById("checkoutButton");

        function searchVisitors(query, searchBy) {
            if (query.length < 2) {
                searchResults.innerHTML = "";
                return;
            }

            fetch(`{{ route('visitor.search') }}?q=${query}&searchBy=${searchBy}`)
                .then(response => response.json())
                .then(data => {
                    searchResults.innerHTML = "";

                    if (data.length > 0) {
                        // Add table headings
                        let heading = document.createElement("div");
                        heading.classList.add("list-group-item", "active");
                        heading.innerHTML = `<strong>Visitor ID</strong> | <strong>Visitor Name</strong> | <strong>CheckIn Time</strong>`;
                        searchResults.appendChild(heading);

                        data.forEach(visitor => {
                            let item = document.createElement("a");
                            item.href = "#";
                            item.classList.add("list-group-item", "list-group-item-action");
                            item.innerHTML = `<strong>${visitor.id}</strong> | ${visitor.full_name} | ${visitor.check_in_time}`;
                            item.dataset.id = visitor.id;

                            item.addEventListener("click", function(e) {
                                e.preventDefault();
                                visitorIdSearch.value = visitor.id;
                                visitorNameSearch.value = visitor.full_name;
                                selectedVisitorId.value = visitor.id;
                                checkoutButton.removeAttribute("disabled");
                                searchResults.innerHTML = "";
                            });

                            searchResults.appendChild(item);
                        });
                    }
                })
                .catch(error => console.error("Fetch Error:", error));
        }

        // Disable one field when the other is being used
        visitorIdSearch.addEventListener("input", function() {
            if (visitorIdSearch.value.trim() !== "") {
                visitorNameSearch.setAttribute("disabled", "true");
            } else {
                visitorNameSearch.removeAttribute("disabled");
            }
            searchVisitors(visitorIdSearch.value.trim(), "id");
        });

        visitorNameSearch.addEventListener("input", function() {
            if (visitorNameSearch.value.trim() !== "") {
                visitorIdSearch.setAttribute("disabled", "true");
            } else {
                visitorIdSearch.removeAttribute("disabled");
            }
            searchVisitors(visitorNameSearch.value.trim(), "name");
        });

        // Hide dropdown when clicking outside
        document.addEventListener("click", function(e) {
            if (!searchResults.contains(e.target) && e.target !== visitorIdSearch && e.target !== visitorNameSearch) {
                searchResults.innerHTML = "";
            }
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // ...your existing code...

        const checkoutForm = document.getElementById("checkoutForm");

        checkoutForm.addEventListener("submit", function(e) {
            e.preventDefault();

            const formData = new FormData(checkoutForm);

            fetch("{{ route('visitor.checkout') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: "Checked Out Successfully!",
                            text: "Thank you for visiting. We hope to see you again soon!",
                            confirmButtonText: 'Go Home'
                        }).then(() => {
                            window.location.href = "{{ route('visitor.home') }}";
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.message || 'An error occurred.'
                        });
                    }
                })
                .catch(error => {
                    console.error('Checkout error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong. Please try again.'
                    });
                });
        });
    });
</script>


<!-- Styling -->
<style>
    #searchResults {
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
    }
    .mainScreen {
        background: url('{{ asset('assets/visitor_photos/remaining_screen_image.jpg') }}') no-repeat center center;
        background-size: cover;
    }
</style>

@endsection

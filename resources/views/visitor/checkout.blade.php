@extends('layouts.front_app')

@section('content')
<div class="mainScreen container-fluid d-flex flex-column flex-md-row vh-100">
    <div class="card shadow-lg p-5 rounded-4" style="max-width: 800px;">
    <div class="text-center">
        <h1 class="mb-4">Visitor Check-Out</h1>
        <p>Enter your name and select from the dropdown to check out.</p>

        <!-- Search Input -->
        <input type="text" id="visitorSearch" class="form-control mb-2" placeholder="Enter your name" autocomplete="off">

        <!-- Dropdown for Search Results -->
        <div id="searchResults" class="list-group position-absolute w-50 mx-auto"></div>

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
        const searchInput = document.getElementById("visitorSearch");
        const searchResults = document.getElementById("searchResults");
        const selectedVisitorId = document.getElementById("selectedVisitorId");
        const checkoutButton = document.getElementById("checkoutButton");
        const checkoutForm = document.getElementById("checkoutForm");

        searchInput.addEventListener("input", function() {
            let query = searchInput.value.trim();
            if (query.length < 2) {
                searchResults.innerHTML = "";
                return;
            }

            // Fetch matching visitors
            fetch("{{ route('visitor.search') }}?q=" + query)
                .then(response => response.json())
                .then(data => {
                    searchResults.innerHTML = "";
                    if (data.length > 0) {
                        data.forEach(visitor => {
                            let item = document.createElement("a");
                            item.href = "#";
                            item.classList.add("list-group-item", "list-group-item-action");
                            item.textContent = visitor.full_name;
                            item.dataset.id = visitor.id;

                            item.addEventListener("click", function(e) {
                                e.preventDefault();
                                searchInput.value = visitor.full_name;
                                selectedVisitorId.value = visitor.id;
                                checkoutButton.removeAttribute("disabled");
                                searchResults.innerHTML = "";
                            });

                            searchResults.appendChild(item);
                        });
                    }
                })
                .catch(error => console.error("Error:", error));
        });

        // Hide dropdown when clicking outside
        document.addEventListener("click", function(e) {
            if (!searchResults.contains(e.target) && e.target !== searchInput) {
                searchResults.innerHTML = "";
            }
        });

        // Handle form submission via AJAX
        checkoutForm.addEventListener("submit", function(e) {
            e.preventDefault();

            let visitorId = selectedVisitorId.value;
            if (!visitorId) {
                Swal.fire({
                    icon: "error",
                    title: "Oops!",
                    text: "Please select a visitor before checking out."
                });
                return;
            }

            fetch("{{ route('visitor.storeCheckOut') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ visitor_id: visitorId })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Success!",
                            text: "You have successfully checked out.",
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            window.location.href = "{{ route('visitor.home') }}";
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error!",
                            text: data.message || "Something went wrong."
                        });
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    Swal.fire({
                        icon: "error",
                        title: "Oops!",
                        text: "There was an issue processing your request."
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
        background: url('{{ asset('assets/img/checkin6.jpg') }}') no-repeat center center;
        background-size: cover;
    }
    .navbar-hidden {
        display: none !important; /* Hide the navbar */
    }
</style>

@endsection

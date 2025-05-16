document.addEventListener('DOMContentLoaded', function() {
    const isLoggedIn = localStorage.getItem('client_logged_in') === 'true';
    const clientId = localStorage.getItem('client_id');

    if (!isLoggedIn || !clientId) {
        // Store intended URL before redirecting
        if (!window.location.pathname.includes('/client/login')) {
            sessionStorage.setItem('intended_url', window.location.pathname);
        }
        window.location.href = '/client/login';
    }

    // Add client ID to all AJAX requests
    if (typeof $ !== 'undefined') {
        $.ajaxSetup({
            headers: {
                'X-Client-ID': clientId
            }
        });
    }

    // Logout functionality
    document.querySelectorAll('.client-logout').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            // Call backend logout to clear session & cookie
            $.post('/client/logout', {_token: $('meta[name="csrf-token"]').attr('content')}, function() {
                localStorage.removeItem('client_logged_in');
                localStorage.removeItem('client_id');
                window.location.href = '/client/login';
            });
        });
    });

});

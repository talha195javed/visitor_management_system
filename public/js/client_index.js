 $(document).ready(function () {
    $('#activeTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "dom": '<"top"<"d-flex justify-content-between align-items-center"lf>>rt<"bottom"ip><"clear">',
        "language": {
            "search": "_INPUT_",
            "searchPlaceholder": "Search active subscriptions...",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "paginate": {
                "previous": "<i class='fas fa-chevron-left'></i>",
                "next": "<i class='fas fa-chevron-right'></i>"
            }
        },
        "initComplete": function() {
            $('.dataTables_filter input').addClass('form-control');
            $('.dataTables_length select').addClass('form-select');
        },
        "columnDefs": [
            { "orderable": false, "targets": [9] }
        ]
    });

    $('#expiredTable').DataTable({
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
    "dom": '<"top"<"d-flex justify-content-between align-items-center"lf>>rt<"bottom"ip><"clear">',
    "language": {
    "search": "_INPUT_",
    "searchPlaceholder": "Search previous subscriptions...",
    "lengthMenu": "Show _MENU_ entries",
    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
    "paginate": {
    "previous": "<i class='fas fa-chevron-left'></i>",
    "next": "<i class='fas fa-chevron-right'></i>"
}
},
    "initComplete": function() {
    $('.dataTables_filter input').addClass('form-control');
    $('.dataTables_length select').addClass('form-select');
},
    "columnDefs": [
{ "orderable": false, "targets": [8] }
    ]
});

    $('.btn-outline-success').on('click', function(e) {
    if (!confirm('Are you sure you want to renew this subscription?')) {
    e.preventDefault();
}
});
});

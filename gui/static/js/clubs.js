/* any handlers depending on DOM elems go here */
$(document).ready(function() {
    /* init datatable */
    $('#clubsTable').DataTable({
        "lengthChange": true,
        "ordering": true,
        "paging": true,
        "searching": true,
        "columnDefs": [
            { "orderable": true, "targets": [1,4,5,6] },
            { "orderable": false, "targets": [0,2,3,7,8] }
        ],
        "order": [[ 1, 'asc' ]]
    });
});

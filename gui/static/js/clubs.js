/* any handlers depending on DOM elems go here */
$(document).ready(function() {
    /* init datatable */
    $('#clubsTable').DataTable({
        "columnDefs": [
            { "orderable": true, "targets": [0,3,4,5] },
            { "orderable": false, "targets": [1,2,6,7] }
        ],
        "order": [[ 0, 'asc' ]]
    });
});

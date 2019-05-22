// not sure?
window.RAPID = {};

/* any handlers depending on DOM elems go here */
$(document).ready(function() {
    /* add code syntax highlighting */
    $('pre code').each(function (i, block) {
        hljs.highlightBlock(block);
    });

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
        "order": [[ 1, 'asc' ]],
        "dom": '<"wrapper-horizontal edge-centered"l<"#sectionFilter"><"#dayFilter">fr><t><"wrapper-horizontal edge-centered"ip>',
        fnInitComplete: function() {
            $('#sectionFilter').html('<label>Section:\n' +
                '  <select>\n' +
                '    <option value="A">A</option>\n' +
                '    <option value="B">B</option>\n' +
                '  </select>\n' +
                '</label>');
            $('#dayFilter').html('<label>Day:\n' +
                '  <label class="radio-inline">\n' +
                '    <input type="checkbox" name="monday" checked>M\n' +
                '  </label>\n' +
                '  <label class="radio-inline">\n' +
                '    <input type="checkbox" name="tuesday" checked>T\n' +
                '  </label>\n' +
                '  <label class="radio-inline">\n' +
                '    <input type="checkbox" name="wednesday" checked>W\n' +
                '  </label>\n' +
                '  <label class="radio-inline">\n' +
                '    <input type="checkbox" name="thursday" checked>Th\n' +
                '  </label>\n' +
                '  <label class="radio-inline">\n' +
                '    <input type="checkbox" name="friday" checked>F\n' +
                '  </label>\n' +
                '</label>');
        }
    });

    /* datatable custom elements */


    /* handle clearing and updating modal data */
    $('#open-Add').click(function() {
        /* Clear out the modal */
        var modal_body = $('#add .modal-body');
        modal_body.find(".row_id").val('');
        modal_body.find(".club_name").val('');
        modal_body.find(".club_poc").val('');
        modal_body.find(".club_poc_email").val('');
        modal_body.find(".club_meeting_time").val('');
        modal_body.find(".club_meeting_loc").val('');
        var day_inputs = modal_body.find(".club_meeting_days input");
        for (var i = 0; i < 5; i++) {
            $(day_inputs[i]).removeAttr('checked');
        }
    });

    $('#open-Update').click(function() {
        var row_index = $(this).parent().parent().parent().index() - 1;
        var c = document.getElementById('clubsTable');
        var club_id = $(c).find('tbody tr:eq(' + row_index + ') td:eq(0)').text();
        var club_name = $(c).find('tbody tr:eq(' + row_index + ') td:eq(1)').text();
        var club_poc = $(c).find('tbody tr:eq(' + row_index + ') td:eq(2)').text();
        var club_poc_email = $(c).find('tbody tr:eq(' + row_index + ') td:eq(3)').text();
        var club_meeting_days = $(c).find('tbody tr:eq(' + row_index + ') td:eq(4)').text().split(',');
        var club_meeting_time = $(c).find('tbody tr:eq(' + row_index + ') td:eq(5)').text();
        var club_meeting_loc = $(c).find('tbody tr:eq(' + row_index + ') td:eq(6)').text();


        /* update modal fields */
        var modal_body = $('#edit .modal-body');
        modal_body.find(".row_id").val(club_id);
        modal_body.find(".club_name").val(club_name);
        modal_body.find(".club_poc").val(club_poc);
        modal_body.find(".club_poc_email").val(club_poc_email);
        modal_body.find(".club_meeting_time").val(club_meeting_time);
        modal_body.find(".club_meeting_loc").val(club_meeting_loc);

        /* normalize days into a fixed length dict */
        var days = {'mon':0,'tue':0,'wed':0,'thur':0,'fri':0};
        for (var i = 0; i <  club_meeting_days.length; i++) {
            if (club_meeting_days[i] in days) {
                days[club_meeting_days[i]] = 1;
            }
        }

        var day_inputs = modal_body.find(".club_meeting_days input");
        i = 0;
        for (var day in days) {
            if (days[day] === 1) {
                $(day_inputs[i]).attr('checked', true);
            }
            else {
                $(day_inputs[i]).removeAttr('checked');
            }

            i++;
        }
    });
});

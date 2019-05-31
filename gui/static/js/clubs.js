/* global vars passed in namespace */
var UPLOADS_URL_PATH = UPLOADS_URL_PATH || '/uploads/images/';
var fileReader = fileReader || new FileReader();

/* datatable custom elements */
var preDrawState = false;
var sectionFilterState = "A";
var sectionFilterHTML = '<label>Section:\n' +
    '  <select class="form-control input-sm">\n' +
    '    <option value="A">A</option>\n' +
    '    <option value="B">B</option>\n' +
    '  </select>\n' +
    '</label>';
var dayFilterState = [true,true,true,true,true];
var dayFilterHTML = '<label>Day:\n' +
    '  <label class="checkbox-inline">\n' +
    '    <input type="checkbox" value="mon" checked>M\n' +
    '  </label>\n' +
    '  <label class="checkbox-inline">\n' +
    '    <input type="checkbox" value="tue" checked>T\n' +
    '  </label>\n' +
    '  <label class="checkbox-inline">\n' +
    '    <input type="checkbox" value="wed" checked>W\n' +
    '  </label>\n' +
    '  <label class="checkbox-inline">\n' +
    '    <input type="checkbox" value="thur" checked>Th\n' +
    '  </label>\n' +
    '  <label class="checkbox-inline">\n' +
    '    <input type="checkbox"value="fri" checked>F\n' +
    '  </label>\n' +
    '</label>';

/**
 * Search dataTable using custom filters
 * @param settings
 * @param data
 * @param dataIndex
 * @returns {boolean}
 */
var searchTable = function(settings, data, dataIndex) {
    var current_section = $('#sectionFilter select').val();
    var current_days = $('#dayFilter input[type="checkbox"]').filter(function() {
        return this.checked === true;
    }).map(function() {
        return $(this).val();
    }).toArray();

    var club_section = data[3];
    var club_days = data[7].toLowerCase().split(',');

    return ((current_section === club_section) &&
        (current_days.some(function (val) {
            return club_days.indexOf(val) >= 0;
        }) === true));
};
/* add custom filtering function to datatables */
$.fn.dataTable.ext.search.push(
    searchTable
);

/* any handlers depending on DOM elems go here */
$(document).ready(function() {
    /* add code syntax highlighting */
    $('pre code').each(function (i, block) {
        hljs.highlightBlock(block);
    });

    /* init datatable */
    var table = $('#clubsTable').DataTable({
        "lengthChange": true,
        "ordering": true,
        "paging": true,
        "searching": true,
        "responsive": true,
        "columnDefs": [
            { "orderable": true, "targets": [1,4,5,6] },
            { "orderable": false, "targets": [0,2,3,7,8] }
        ],
        "order": [[ 1, 'asc' ]],
        "dom": '<"wrapper-horizontal edge-centered filter-header"l<"#sectionFilter"><"#dayFilter">fr><t><"wrapper-horizontal edge-centered filter-footer"ip>',
        /* make sure we redraw the filters correctly */
        fnPreDrawCallback: function() {
            preDrawState = true;
            $('#sectionFilter').html(sectionFilterHTML);
            $('#sectionFilter select').val(sectionFilterState);
            $('#dayFilter').html(dayFilterHTML);
            $('#dayFilter input[type="checkbox"]').each(function(i) {
                $(this).attr('checked', dayFilterState[i]);
            });
            preDrawState = false;
            return true;
        }
    });
    /* show inital table */
    table.draw();

    /* listen for changes to the custom filters */
    $('#sectionFilter, #dayFilter').change(function() {
        if (preDrawState === false) {
            sectionFilterState = $('#sectionFilter select').val();
            dayFilterState = $('#dayFilter input[type="checkbox"]').map(function() {
                return this.checked;
            }).toArray();
            table.draw();
        }
    });

    /* handle clearing and updating modal data */
    $('#open-Add').click(function() {
        /* Clear out the modal */
        var modal_body = $('#add .modal-body');
        modal_body.find(".row_id").val('');
        modal_body.find(".club_name").val('');
        modal_body.find(".club_president").val('');
        modal_body.find(".club_section").val('');
        modal_body.find(".club_description").val('');
        modal_body.find(".club_poc").val('');
        modal_body.find(".club_poc_email").val('');
        modal_body.find(".club_meeting_days").val('');
        modal_body.find(".club_meeting_time").val('');
        modal_body.find(".club_meeting_loc").val('');
        modal_body.find(".club_picture").val('');
        modal_body.find(".club_pic_preview").addClass('hidden');

        /* make sure meeting days are unchecked */
        var day_inputs = modal_body.find(".club_meetings input[type='checkbox']");
        for (var i = 0; i < 5; i++) {
            $(day_inputs[i]).removeAttr('checked');
        }
    });

    $('#open-Update').click(function() {
        var row_index = $(this).parent().parent().parent().index() - 1;
        var c = document.getElementById('clubsTable');
        var club_id = $(c).find('tbody tr:eq(' + row_index + ') td:eq(0)').text();
        var club_name = $(c).find('tbody tr:eq(' + row_index + ') td:eq(1)').text();
        var club_president = $(c).find('tbody tr:eq(' + row_index + ') td:eq(2)').text();
        var club_section = $(c).find('tbody tr:eq(' + row_index + ') td:eq(3)').text();
        var club_description = $(c).find('tbody tr:eq(' + row_index + ') td:eq(4)').text();
        var club_poc = $(c).find('tbody tr:eq(' + row_index + ') td:eq(5)').text();
        var club_poc_email = $(c).find('tbody tr:eq(' + row_index + ') td:eq(6)').text();
        var club_meeting_days = $(c).find('tbody tr:eq(' + row_index + ') td:eq(7)').text().toLowerCase().split(',');
        var club_meeting_time = $(c).find('tbody tr:eq(' + row_index + ') td:eq(8)').text();
        var club_meeting_loc = $(c).find('tbody tr:eq(' + row_index + ') td:eq(9)').text();
        var club_picture = $(c).find('tbody tr:eq(' + row_index + ') td:eq(10)').text();


        /* update modal fields */
        var modal_body = $('#edit .modal-body');
        modal_body.find(".row_id").val(club_id);
        modal_body.find(".club_name").val(club_name);
        modal_body.find(".club_president").val(club_president);
        modal_body.find(".club_section").val(club_section);
        modal_body.find(".club_description").val(club_description);
        modal_body.find(".club_poc").val(club_poc);
        modal_body.find(".club_poc_email").val(club_poc_email);
        modal_body.find(".club_meeting_days").val(club_meeting_days);
        modal_body.find(".club_meeting_time").val(club_meeting_time);
        modal_body.find(".club_meeting_loc").val(club_meeting_loc);

        if (club_picture) {
            var file_data = new ClipboardEvent('').clipboardData || // Firefox < 62 workaround exploiting https://bugzilla.mozilla.org/show_bug.cgi?id=1422655
                new DataTransfer(); // specs compliant (as of March 2018 only Chrome)
            file_data.items.add(new File([club_picture], UPLOADS_URL_PATH + club_picture));
            modal_body.find(".club_picture").get(0).files = file_data.files;
        }

        /* normalize days into a fixed length dict */
        var days = {'mon': 0, 'tue': 0, 'wed': 0, 'thur': 0, 'fri': 0};
        for (var i = 0; i < club_meeting_days.length; i++) {
            if (club_meeting_days[i] in days) {
                days[club_meeting_days[i]] = 1;
            }
        }

        /* check corresponding meeting days */
        var day_inputs = modal_body.find(".club_meetings input[type='checkbox']");
        i = 0;
        for (var day in days) {
            if (days[day] === 1) {
                $(day_inputs[i]).attr('checked', true);
            } else {
                $(day_inputs[i]).removeAttr('checked');
            }

            i++;
        }
    });

    /* update the club_meetings data on form submit */
    $('#add, #edit').find('form').submit(function(e) {
        var form = $(e.target);
        var day_elems = form.find(".club_meetings input[type='checkbox']").get();

        /* inverse normalization of data */
        var days = ['mon', 'tue', 'wed', 'thur', 'fri'];
        var club_days = [];
        for (var i = 0; i < 5; i++) {
            if (day_elems[i].checked === true) {
                club_days.push(days[i]);
            }
        }

        form.find(".club_meeting_days").val(club_days.join(','));
        return true;
    });

    /* TODO: limit image uploads & rescale preview dimensions
    /* handle preview images */
    fileReader.onload = function(e) {
        var img_preview = $('.club_pic_preview');
        img_preview.attr('src', e.target.result);
        img_preview.removeClass('hidden');
    };
    $(".club_picture").change(function(){
        readDataURL(this);
    });
});

/**
 * Created by Marko R on 20/06/2018.
 */

$(document).ready( function () {

    $('#article_table').DataTable({
        "order": [[ 0, 'asc' ]],
        "columnDefs": [
            { "orderable": false, "targets": -1 },
            { "searchable": false, "targets": -1 },
            {
                targets: 3,
                render: $.fn.dataTable.render.ellipsis( 85, true )
            }
        ],
        "lengthMenu": [ [5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"] ],
        "pageLength": 10,
        "pagingType": "full_numbers"
    });

    $('#category_table').DataTable({
        "order": [[ 0, 'asc' ]],
        "columnDefs": [
            { "orderable": false, "targets": -1 },
            { "searchable": false, "targets": -1 }
        ],
        "lengthMenu": [ [5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"] ],
        "pageLength": 10,
        "pagingType": "full_numbers"
    });

    $('#article_table tbody, #comment_table tbody').on('click', '.ellipsis', function () {
        var currentText = $(this).html();
        var parentTd = $(this).parent();

        $(this).html( parentTd.data('text') );
        parentTd.data('text', currentText);
    });

    $('#comment_table').DataTable({
        "order": [[ 0, 'asc' ]],
        "columnDefs": [
            { "orderable": false, "targets": -1 },
            { "searchable": false, "targets": -1 },
            {
                targets: 2,
                render: $.fn.dataTable.render.ellipsis( 85, true )
            }
        ],
        "lengthMenu": [ [5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"] ],
        "pageLength": 10,
        "pagingType": "full_numbers"
    });

} );
$(document).ready(function () {
    $('#list').dataTable({
        "aoColumnDefs": [ { "bSortable": false, "aTargets": [ 1,2,3,4 ] }]
    });
});
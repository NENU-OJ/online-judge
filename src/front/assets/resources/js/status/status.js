$(document).ready(function () {
    $('#list').dataTable({
        "aaSorting": [[6,"desc"]],
        "aoColumnDefs": [ { "bSortable": false, "aTargets": [ 0,1 ] }]
    });
});
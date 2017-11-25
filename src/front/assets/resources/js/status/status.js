$(document).ready(function () {
    $('#list').dataTable({
        "aaSorting": [[0, "desc"]],
        "aoColumnDefs": [{"bSortable": false, "aTargets": [ 0,1 ]}]
    });
});
var host = window.location.host;

$("#fil").click(function () {

    var search = $("#search").val();

    var url = "/problem/list/?id=1";

    if (search)
        url += '&search=' + search.replace(/ /g, '%20');
    console.log(url);
    window.location = url;
});

$(".pagi").click(function () {
    var search = $("#search").val();

    var url = "/problem/list/?id=" + $(this).attr('title');

    if (search)
        url += '&search=' + search.replace(/ /g, '%20');

    window.location = url;
});
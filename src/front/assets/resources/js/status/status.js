var host = window.location.host;

$("#fil").click(function () {

    var result = $("#result").val();
    var lang = $("#lang").val();
    var pid = $("#pid").val();
    var name = $("#name").val();

    var url = "/status/list/?id=1";

    if (result)
        url += '&result=' + result.replace(/ /g, '%20');
    if (lang)
        url += '&lang=' + lang;
    if (pid)
        url += '&pid=' + pid;
    if (name)
        url += '&name=' + name.replace(/ /g, '%20');

    window.location = url;
});

$(".pagi").click(function () {
    var result = $("#result").val();
    var lang = $("#lang").val();
    var pid = $("#pid").val();
    var name = $("#name").val();

    var url = "/status/list/?id=" + $(this).attr('title');

    if (result)
        url += '&result=' + result.replace(/ /g, '%20');
    if (lang)
        url += '&lang=' + lang;
    if (pid)
        url += '&pid=' + pid;
    if (name)
        url += '&name=' + name.replace(/ /g, '%20');
    window.location = url;
});
var host = window.location.host;

$("#fil").click(function () {

    var result = $("#result").val();
    var lang = $("#lang").val();
    var prob = $("#prob").val();
    var name = $("#name").val();

    var url = 'http://' + host + "/contest/" + _cid + '/status?page=1';

    if (result)
        url += '&result=' + result.replace(/ /g, '%20');
    if (lang)
        url += '&lang=' + lang;
    if (prob)
        url += '&prob=' + prob;
    if (name)
        url += '&name=' + name.replace(/ /g, '%20');

    window.location = url;
});

$(".pagi").click(function () {
    var result = $("#result").val();
    var lang = $("#lang").val();
    var prob = $("#prob").val();
    var name = $("#name").val();

    var url = 'http://' + host + "/contest/" + _cid + '/status?page=' + $(this).attr('title');

    if (result)
        url += '&result=' + result.replace(/ /g, '%20');
    if (lang)
        url += '&lang=' + lang;
    if (prob)
        url += '&prob=' + prob;
    if (name)
        url += '&name=' + name.replace(/ /g, '%20');
    window.location = url;
});

$("#reset").click(function () {
    var url = 'http://' + host + "/contest/" + _cid + '/status?page=1';
    window.location = url;
});
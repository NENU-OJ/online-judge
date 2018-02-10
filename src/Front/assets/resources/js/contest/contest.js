var host = window.location.host;

$("#fil").click(function() {

    var search = $("#search").val();

    var url = "/contest/?id=1";

    if (search)
        url += '&search=' + search.replace(/ /g, '%20');

    window.location = url;
});

$(".pagi").click(function() {
    var search = $("#search").val();

    var url = "/contest/?id=" + $(this).attr('title');

    if (search)
        url += '&search=' + search.replace(/ /g, '%20');

    window.location = url;
});

var host = window.location.host;
var contestId = null;

// 模态比赛密码输入窗口
$(".cid").click(function () {
    contestId = $(this).attr('data-id');
    var canView = false;
    $.ajax({
        type: "get",
        url: 'http://' + host + '/contest/can-view?id=' + contestId,
        dataType: 'json',
        async: false,
        success: function (resp) {
            if (resp.code == 0)
                canView = true;
        },
        error: function () {
            alert('获取JSON数据异常');
        },
    });
    if (canView) {
        window.location = 'http://' + host + '/contest/view/' + contestId;
        return;
    }
    $("#dialog_lc").css("display", "block");
    $("#overlay").css("display", "");
});
$(".contestclose").click(function () {
    $("#dialog_lc").css("display", "none");
    $("#overlay").css("display", "none");
});

$("#contest_submit").click(function () {
    $("#contest_error").text("");
    var password = $("#contest_password").val();

    $.ajax({
        type: "post",
        url: 'http://' + host + '/contest/login',
        dataType: "json",
        async: false,
        data: {
            contestId: contestId,
            password: password,
        },
        success: function (resp) {
            if (resp.code == 0) {
                window.location = 'http://' + host + '/contest/view/' + contestId;
            } else {
                $("#contest_error").text(resp.data);
            }
        },
        error: function () {
            $("#contest_error").text("获取JSON数据异常");
        }
    });
});

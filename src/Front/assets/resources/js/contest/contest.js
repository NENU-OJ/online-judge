var host = window.location.host;

$("#fil").click(function () {

    var search = $("#search").val();

    var url = "/contest/?id=1";

    if (search)
        url += '&search=' + search.replace(/ /g, '%20');

    window.location = url;
});

$(".pagi").click(function () {
    var search = $("#search").val();

    var url = "/contest/?id=" + $(this).attr('title');

    if (search)
        url += '&search=' + search.replace(/ /g, '%20');

    window.location = url;
});

var host = window.location.host;

// 模态比赛密码输入窗口
$(".cid").click(function () {
    $("#dialog_lc").css("display", "block");
    $("#overlay").css("display", "");
});
$(".contestclose").click(function () {
    $("#dialog_lc").css("display", "none");
    $("#overlay").css("display", "none");
});

$("#login_submit").click(function () {
    $("#login_error").text("");

    var username = $("#login_username").val();
    var password = $("#login_password").val();
    var remember = $("#remember").val();
    $.ajax({
        type: "post",
        url: 'http://' + host + '/user/login',
        dataType: "json",
        data: {
            username: username,
            password: password,
        },
        success: function (resp) {
            if (resp.code == 0) {
                location.reload(true);
            } else if (resp.code == 1) {
                $("#login_error").text(resp.data);
            }
        },
        error: function () {
            console.log("获取JSON数据异常");
        }
    });

});

var host = window.location.host;

// 模态登录窗口
$(".checklogin").click(function () {
    $("#dialog_lg").css("display", "block");
    $("#overlay").css("display", "");
});
$(".loginclose").click(function () {
    $("#dialog_lg").css("display", "none");
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

$("#logout").click(function () {
    $.ajax({
        type: "post",
        url: 'http://' + host + '/user/logout',
        dataType: "json",
        success: function (resp) {
            if (resp.code == 0) {
                location.reload();
            }
        },
        error: function () {
            console.log("获取JSON数据异常");
        }
    });
});

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


// 时间格式化
Date.prototype.format = function (fmt) { //author: meizz
    var o = {
        "M+": this.getMonth() + 1, //月份
        "d+": this.getDate(), //日
        "h+": this.getHours(), //小时
        "m+": this.getMinutes(), //分
        "s+": this.getSeconds(), //秒
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度
        "S": this.getMilliseconds() //毫秒
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
        if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
};
// 底部服务器时间
$(document).ready(function () {
    setInterval(function () {
        var timeNowSecond = parseInt($("#timer").attr('data-time')) + 1;
        $("#timer").attr('data-time', timeNowSecond);
        $("#timer").text(new Date(timeNowSecond * 1000).format('yyyy-MM-dd hh:mm:ss'));
    }, 1000);
});
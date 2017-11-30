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
    var username = $("#login_username").val();
    var password = $("#login_password").val();
    var remember = $("#remember").val();
    $.ajax({
        type: "ajax",
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
    })

});

$("#logout").click(function () {
    $.ajax({
        type: "ajax",
        url: 'http://' + host + '/user/logout',
        dataType: "json",
        success: function (resp) {
            if (resp.code == 0) {
                window.location.href = 'http://' + host;
            }
        },
        error: function () {
            console.log("获取JSON数据异常");
        }
    })
});

$("#register").on('click', "#registerSubmit", function () {
    var username = $.trim($("#registerUsername").val());
    var nickname = $.trim($("#registerNickname").val());
    var school = $.trim($("#registerSchool").val());
    var email = $.trim($("#registerEmail").val());
    var password = $.trim($("#registerPassword").val());
    var re_password = $.trim($("#re-password").val());
    if (username == null || username == "" || username == undefined || nickname == null || nickname == "" || nickname == undefined || password == null || password == "" || password == undefined || re_password == null || re_password == "" || re_password == undefined) {
        alert("请完善您的信息");
    }
    else if (password != re_password) {
        alert("两次输入密码不一致");
    }
    else {
        $.ajax({
            type: "get",
            url: 'http://' + host + '/user/user/register',
            dataType: "json",
            data: {
                username: username,
                nickname: nickname,
                school: school,
                email: email,
                password: password
            },
            success: function (data) {
                $.each(data, function (index, val) {
                    var code = val.code;
                    if (code == 0) {
                        alert("注册成功");
                        location.reload(true);
                    } else if (code == 1) {
                        alert("该username已被使用");
                    }
                })
            },
            error: function () {
                console.log("获取JSON数据异常");
            }
        })
    }
});

var host = window.location.host;

// 模态信息修改窗口
$("#set").click(function () {
    $("#dialog_st").css("display", "block");
    $("#overlay").css("display", "");
});
$(".setclose").click(function () {
    $("#dialog_st").css("display", "none");
    $("#overlay").css("display", "none");
});
$("#set_cancel").click(function () {
    $("#dialog_st").css("display", "none");
    $("#overlay").css("display", "none");
});

// 修改信息提交
$("#set_submit").click(function () {
    $("#set_error").text("");
    var nickname = $("#set_nick").val();
    var old_password = $("#oldpassword").val();
    var new_password = $("#newpassword").val();
    var up_password_repeat = $("#up_password_repeat").val();
    var school = $("#set_school").val();
    var email = $("#set_email").val();
    var signature = $("#up_signature").val();

    if (nickname === "" || nickname === null || nickname === undefined) {
        $("#set_error").text("缺少nickname");
        return;
    }
    if (old_password === "" || old_password === null || old_password === undefined) {
        $("#set_error").text("缺少old_password");
        return;
    }
    if (new_password !== up_password_repeat) {
        $("#set_error").text("两次密码不一致");
        return;
    }
    $.ajax({
        type: "post",
        url: 'http://' + host + '/user/update',
        dataType: "json",
        data: {
            nickname: nickname,
            old_password: old_password,
            new_password: new_password,
            school: school,
            email: email,
            signature: signature
        },
        success: function (resp) {
            if (resp.code == 0) {
                location.reload();
            } else {
                $("#set_error").text(resp.data);
            }
        },
        error: function () {
            console.log("获取JSON数据异常");
        }
    });
});
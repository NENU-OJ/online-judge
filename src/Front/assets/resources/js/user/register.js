var host = window.location.host;

$("#reg_submit").click(function () {
    $("#reg_error").text("");

    var username = $("#reg_username").val();
    var nickname = $.trim($("#reg_nick").val());
    var password = $("#reg_password").val();
    var re_password = $("#password_repeat").val();
    var school = $.trim($("#reg_school").val());
    var email = $.trim($("#reg_email").val());
    var signature = $.trim($("#signature").val());

    if (username == null || username == "" || username === undefined) {
        $("#reg_error").text("需要User Name");
    } else if (!new RegExp('^([a-z]|[A-Z]|[0-9]|_)+$').test(username)) {
        $("#reg_error").text("username只能由大小写字母、数字和下划线组成");
    } else if (nickname == null || nickname == "" || username === undefined) {
        $("#reg_error").text("需要Nick Name");
    } else if (password == null || password == "" || password === undefined) {
        $("#reg_error").text("需要Password");
    } else if (password !== re_password) {
        $("#reg_error").text("两次输入密码不一致");
    } else {
        $.ajax({
            type: "post",
            url: '//' + host + '/user/register',
            dataType: "json",
            data: {
                username: username,
                nickname: nickname,
                password: password,
                school: school,
                email: email,
                signature: signature
            },
            success: function (resp) {
                if (resp.code == 0) {
                    location.href = '//' + host + '/user/detail';
                } else {
                    $("#reg_error").text(resp.data);
                }
            },
            error: function () {
                console.log("获取JSON数据异常");
            }
        });
    }
});

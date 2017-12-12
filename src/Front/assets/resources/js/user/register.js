var host = window.location.host;

$("#reg_submit").click(function () {
    $("#reg_error").text("");

    var username = $.trim($("#reg_username").val());
    var nickname = $.trim($("#reg_nick").val());
    var password = $("#reg_password").val();
    var re_password = $("#password_repeat").val();
    var school = $.trim($("#reg_school").val());
    var email = $.trim($("#reg_email").val());
    var signature = $.trim($("#signature").val());

    if (username == null || username == "" || username === undefined) {
        $("#reg_error").text("需要User Name");
    } else if (nickname == null || nickname == "" || username === undefined) {
        $("#reg_error").text("需要Nick Name");
    } else if (password == null || password == "" || password === undefined) {
        $("#reg_error").text("需要Password");
    } else if (password !== re_password) {
        $("#reg_error").text("两次输入密码不一致");
    } else {
        $.ajax({
            type: "post",
            url: 'http://' + host + '/user/register',
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
                    location.href = 'http://' + host + '/user/detail';
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

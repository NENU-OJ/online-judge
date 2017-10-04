var host = window.location.host;

$("#update").keypress(function () {
    if (event.keyCode == 13) {
        event.cancelBubble = true;
        event.returnValue = false;
        $(this).find("#updateSubmit").click();
    }
});

$("#update").on('click', "#updateSubmit", function () {
    var username = $.trim($("#updateUsername").val());
    var nickname = $.trim($("#updateNickname").val());
    var school = $.trim($("#updateSchool").val());
    var email = $.trim($("#updateEmail").val());
    var oldPassword = $.trim($("#oldPassword").val());
    var password = $.trim($("#newPassword").val());
    var re_password = $.trim($("#re-newPassword").val());
    if (oldPassword == null || oldPassword == "" || oldPassword == undefined) {
        $.ajax({
            type: "get",
            url: 'http://' + host + '/user/user/update',
            dataType: "json",
            data: {
                username: username,
                nickname: nickname,
                school: school,
                email: email
            },
            success: function (data) {
                $.each(data, function (index, val) {
                    var code = val.code;
                    if (code == 0) {
                        alert("更新成功");
                        location.reload(true);
                    }
                })
            },
            error: function () {
                console.log("获取JSON数据异常");
            }
        })
    }
    else if (password != re_password) {
        alert("两次输入密码不一致");
    }
    else {
        $.ajax({
            type: "get",
            url: 'http://' + host + '/user/user/update',
            dataType: "json",
            data: {
                username: username,
                nickname: nickname,
                school: school,
                email: email,
                oldPassword: oldPassword,
                newPassword: password
            },
            success: function (data) {
                $.each(data, function (index, val) {
                    var code = val.code;
                    if (code == 0) {
                        alert("更新成功，请使用新密码重新登录");
                        $.ajax({
                            type: "get",
                            url: 'http://' + host + '/user/user/logout',
                            dataType: "json",
                            success: function (data) {
                                $.each(data, function (index, val) {
                                    var code = val.code;
                                    if (code == 0) {
                                        window.location.href = 'http://' + host;
                                    }
                                })
                            },
                            error: function () {
                                console.log("获取JSON数据异常");
                            }
                        })
                    } else if (code == 1) {
                        alert("原密码输入错误");
                    }
                })
            },
            error: function () {
                console.log("获取JSON数据异常");
            }
        })
    }
});
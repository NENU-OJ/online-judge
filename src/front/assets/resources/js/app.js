$(function () {
    $(".navbar-expand-toggle").click(function () {
        $(".app-container").toggleClass("expanded");
        return $(".navbar-expand-toggle").toggleClass("fa-rotate-90");
    });
    return $(".navbar-right-expand-toggle").click(function () {
        $(".navbar-right").toggleClass("expanded");
        return $(".navbar-right-expand-toggle").toggleClass("fa-rotate-90");
    });
});

$(function () {
    return $('select').select2();
});

$(function () {
    return $('.toggle-checkbox').bootstrapSwitch({
        size: "small"
    });
});

$(function () {
    return $('.match-height').matchHeight();
});

$(function () {
    return $('.datatable').DataTable({
        "dom": '<"top"fl<"clear">>rt<"bottom"ip<"clear">>'
    });
});

$(function () {
    return $(".side-menu .nav .dropdown").on('show.bs.collapse', function () {
        return $(".side-menu .nav .dropdown .collapse").collapse('hide');
    });
});

var host = window.location.host;

$("#login").keypress(function () {
    if (event.keyCode == 13) {
        event.cancelBubble = true;
        event.returnValue = false;
        $(this).find("#loginSubmit").click();
    }
});

$("#login").on('click', "#loginSubmit", function () {
    var username = $("#loginUsername").val();
    var password = $("#loginPassword").val();

    $.ajax({
        type: "get",
        url: 'http://' + host + '/user/user/login',
        dataType: "json",
        data: {
            username: username,
            password: password
        },
        success: function (data) {
            $.each(data, function (index, val) {
                var code = val.code;
                if (code == 0) {
                    location.reload(true);
                } else if (code == 1) {
                    alert("用户名或密码不正确");
                }
            })
        },
        error: function () {
            console.log("获取JSON数据异常");
        }
    })

});

$("#register").keypress(function () {
    if (event.keyCode == 13) {
        event.cancelBubble = true;
        event.returnValue = false;
        $(this).find("#registerSubmit").click();
    }
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

$("#logout").click(function () {
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
});
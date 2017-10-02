
var host = window.location.host;

$("#register").keypress(function () {
    if(event.keyCode == 13){
        event.cancelBubble = true;
        event.returnValue = false;
        $(this).find("#submit").click();
    }
});

$("#register").on('click',"#submit",function () {
    var username=$("#username").val();
    var nickname=$("#nickname").val();
    var school=$("#school").val();
    var email=$("#email").val();
    var password=$("#password").val();
    var re_password=$("#re-password").val();
    if(password!=re_password){
        alert("两次所输入密码不一致");
    }
    else {
        $.ajax({
            type:"get",
            url: 'http://'+host+'/user/user/register',
            dataType:"json",
            data:{
                username:username,
                nickname:nickname,
                school:school,
                email:email,
                password:password
            },
            success:function (data) {
                $.each(data,function(index,val) {
                    var code=val.code;
                    if (code == 0) {
                        alert("注册成功");
                        location.href='http://'+host;
                    }else if(code == 1){
                        alert("该username已被使用");
                    }
                })
            },
            error:function () {
                console.log("获取JSON数据异常");
            }
        })
    }
});
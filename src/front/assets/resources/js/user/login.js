
var host = window.location.host;

$("#login").keypress(function () {
    if(event.keyCode == 13){
        event.cancelBubble = true;
        event.returnValue = false;
        $(this).find("#submit").click();
    }
});

$("#login").on('click',"#submit",function () {
    var username=$("#username").val();
    var password=$("#password").val();

    $.ajax({
        type:"get",
        url:'http://'+host+'/user/user/login',
        dataType:"json",
        data:{
            username:username,
            password:password
        },
        success:function (data) {
            $.each(data,function(index,val) {
                var code=val.code;
                if (code == 0) {
                    alert("登录成功");
                    location.href='http://'+host;
                }else if(code == 1){
                    alert("用户名或密码不正确");
                }
            })
        },
        error:function () {
            console.log("获取JSON数据异常");
        }
    })

})
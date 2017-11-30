var host = window.location.host;

$("#problemSubmit").on('click', '#submit', "shit", function (event) {
    //$("*").css("background", "#aedaff");
    var _csrf_val = $("#_csrf").attr("value");
    // console.log("csrf = %s\n", _csrf_val);
    // console.log(event);
    // console.log(event.type);
    $(".navbar-brand").html("wating");
    $.ajax({
        type: "ajax",
        url: "http://" + host + "/test",
        dataType: "text",
        data: {
            fuck: "fuckyou",
            _csrf: _csrf_val,
        },
        success: function (data, st) {
            console.log(st);
            console.log(data);
            $(".navbar-brand").html("NENU OJ");
            // $.each(data, function (index, val) {
            //     var code = val.code;
            //     if (code == 0) {
            //         console.log(data);
            //     }
            // })
        },
        error: function () {
            console.log("获取JSON数据异常");
        }
    })
})
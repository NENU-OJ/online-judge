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

function countTime() {
    $("#contest_current").text(new Date(_timeNow * 1000).format('yyyy-MM-dd hh:mm:ss'));
    if (_timeNow > _endTime) {
        $("#conteststatus").removeClass().addClass('accept-text').text('Ended');
    } else if (_timeNow < _startTime) {
        $("#conteststatus").removeClass().addClass('info-text').text('Pending');
    } else {
        $("#conteststatus").removeClass().addClass('wrong-text').text('Running');
    }
}

$(document).ready(function () {
    countTime();
    setInterval(countTime, 1000);
});

$("#contest_submit").click(function () {
    $("#contest_error").text("");
    var password = $("#contest_password").val();

    $.ajax({
        type: "post",
        url: 'http://' + host + '/contest/login',
        dataType: "json",
        async: false,
        data: {
            contestId: contestId,
            password: password,
        },
        success: function (resp) {
            if (resp.code == 0) {
                window.location = 'http://' + host + '/contest/view/' + contestId;
            } else {
                $("#contest_error").text(resp.data);
            }
        },
        error: function () {
            $("#contest_error").text("获取JSON数据异常");
        }
    });
});

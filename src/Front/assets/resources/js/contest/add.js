// 时间格式化
Date.prototype.Format = function (fmt) { //author: meizz
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
}

// 初始化时间选择器材
$(document).ready(function() {
    $('#datepicker').Zebra_DatePicker();
});


$("#submit").click(function () {
    var title = $("#title").val();

    var rawDate = $('#datepicker').val();
    rawDate = rawDate.split('-');
    var hour = $("#hour").val();
    var min = $("#min").val();

    var lengthDay = $("#dd").val();
    var lengthHour = $("#hh").val();
    var lengthMin = $("#mm").val();

    var psw = $("#psw").val();
    var penalty = $("#penalty").val();
    var desc = $("#desc").val();
    var anc = $("#anc").val();

    var date = new Date();
    date.setFullYear(rawDate[0]);
    date.setMonth(rawDate[1] - 1);
    date.setDate(rawDate[2]);
    date.setHours(hour);
    date.setMinutes(min)
    date.setSeconds(0);

    console.log(date.Format("yyyy-MM-dd hh:mm:ss"));
    console.log(new Date().Format("yyyy-MM-dd hh:mm:ss"));

    var lengthSecond = 0;
    if (lengthDay)
        lengthSecond += parseInt(lengthDay) * 24 * 60 * 60;
    if (lengthHour)
        lengthSecond += parseInt(lengthHour) * 60 * 60;
    if (lengthMin)
        lengthSecond += parseInt(lengthMin) * 60;

    console.log("length = %d\n", lengthSecond);

    console.log(title);

    console.log(date);
    console.log(hour);
    console.log(min);

    console.log(lengthDay);
    console.log(lengthHour);
    console.log(lengthMin);

    console.log(psw);
    console.log(penalty);
    console.log(desc);
    console.log(anc);
});

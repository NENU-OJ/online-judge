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

$(document).ready(function () {
   setInterval(function () {
       _timeNow += 1;
   }, 1000);
});

function progressBar() {
    var $progress = $("#progress").children();
    var $contestInfo = $("#contest-info");
    if (_timeNow < _startTime) {
        $progress.removeClass('progress-bar-success').attr('style', 'width:100%');
        var minute = Math.floor((_startTime - _timeNow) / 60);
        var second = (_startTime - _timeNow) % 60;
        $contestInfo.text('距离开始 ' + minute + ':' + second);
    } else if (_timeNow > _endTime) {
        $progress.removeClass('progress-bar-danger').addClass('progress-bar-success').attr('style', 'width:100%');
        $contestInfo.text();
    } else {
        $progress
            .removeClass('progress-bar-success')
            .addClass('progress-bar-danger')
            .attr('style', 'width:' + ((_timeNow - _startTime) / (_endTime - _startTime) * 100) + '%');

        var minute = Math.floor((_endTime - _timeNow) / 60);
        var second = (_endTime - _timeNow) % 60;
        $contestInfo.text('距离结束 ' + minute + ':' + second);
    }
}

$(document).ready(function () {
    progressBar();
    setInterval(progressBar, 1000);
});
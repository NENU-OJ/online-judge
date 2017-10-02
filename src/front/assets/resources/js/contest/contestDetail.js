
var startTime = new Date("2017-09-13 21:00");
var nowTime = new Date();
var endTime = new Date("2017-09-13 22:10");


var timePercent = 100000/(endTime.getTime()-startTime.getTime());
var nowPercent = ((nowTime.getTime()-startTime.getTime())/1000)*timePercent;
var lineStatus = 1;

countSecond0();

function countSecond0() {
    // alert(timePercent);
    nowPercent = nowPercent + timePercent;
    meter0 = setTimeout("countSecond0()", 1000)
    $("#timeline").html(nowPercent + "%")
    $("#timeline").css("width", nowPercent + "%");
    if(lineStatus!=1&&(nowPercent>=0&&nowPercent<=50)){
        $("#timeline").addClass("progress-bar-success");
        lineStatus = 1;
    }
    if(lineStatus!=2&&(nowPercent>50&&nowPercent<=90)){
        $("#timeline").removeClass("progress-bar-success");
        $("#timeline").addClass("progress-bar-warning");
        lineStatus = 2;
    }
    if(lineStatus!=3&&(nowPercent>90&&nowPercent<=100)){
        $("#timeline").removeClass("progress-bar-warning");
        $("#timeline").addClass("progress-bar-danger");
        lineStatus = 3;
    }
    if (nowPercent >= 100.0) {
        clearTimeout(meter0);
    }
}
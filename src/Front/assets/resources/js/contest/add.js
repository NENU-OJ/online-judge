var host = window.location.host;

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


function addProblem(pid) {
    var id = problemList.length;
    var add =
        '<tr id="problem_' + id + '" data-id="' + id + '">\n' +
        '<td><a class="img_link delete" title="delete" href="javascript:;" data-id="' + id + '"></a></td>\n' +
        '<td><input class="probnum form-control input-sm" type="text" value="' + (pid !== null ? pid : '') + '" data-id="' + id + '"></td>\n' +
        '<td class="bold p_index">' + String.fromCharCode('A'.charCodeAt(0) + id) + '</td>\n' +
        '<td class="title error-text" id="text_' + id + '" style="text-align:left"></td>\n' +
        '</tr>';

    $("#problemList").append($(add));
    $("#problem_" + id + " .img_link.delete").click(onDelete);
    $("#problem_" + id + " .probnum").change(proNumChange);

    if (pid) {
        $.ajax({
            type: "get",
            url: 'http://' + host + '/problem/get-info/?id=' + pid,
            dataType: 'json',
            async: false,
            success: function (resp) {
                if (resp.code == 0) {
                    $("#text_" + id)
                        .removeClass('error-text')
                        .addClass('success-text')
                        .text(resp.data.title + (resp.data.is_hide ? " (隐藏题目)" : ""));
                } else {
                    pid = null;
                    $("#text_" + id).removeClass('success-text').addClass('error-text').text(resp.data);
                }
            },
            error: function () {
                pid = null;
                $("#text_" + id).removeClass('success-text').addClass('error-text').text('获取JSON数据异常');
            }
        });
    }
    problemList.push(pid);
}

var problemList = [];


// 初始化时间选择器
$(document).ready(function() {
    $('#datepicker').Zebra_DatePicker();

    // 初始化题目列表
    initProblemList.forEach(function (pid) {
       addProblem(pid);
    });
});


var proNumChange = function () { // 题号改变
    var pid = $(this).val();
    var id = $(this).attr('data-id');
    if (pid) {
        $.ajax({
            type: "get",
            url: 'http://' + host + '/problem/get-info/?id=' + pid,
            dataType: 'json',
            async: false,
            success: function(resp) {
                if (resp.code == 0) {
                    $("#text_" + id)
                        .removeClass('error-text')
                        .addClass('success-text')
                        .text(resp.data.title + (resp.data.is_hide ? " (隐藏题目)" : ""));
                    problemList[id] = parseInt(pid);
                } else {
                    $("#text_" + id).removeClass('success-text').addClass('error-text').text(resp.data);
                }
            },
            error: function () {
                $("#text_" + id).removeClass('success-text').addClass('error-text').text('获取JSON数据异常');
            }
        });
    } else {
        $("#text_" + id).text('');
    }
};
$(".probnum").change(proNumChange);

var onDelete = function () { // 删除题目
    if (problemList.length <= 1)
        return;

    var id = parseInt($(this).attr('data-id'));

    $("#problem_" + id).remove();

    for (var i = id + 1; i < problemList.length; ++i) {
        $item = $("#problem_" + i);
        $item.attr('data-id', i - 1);

        $item.children().children().attr('data-id', i - 1);
        $item.children('.p_index').text(String.fromCharCode('A'.charCodeAt(0) + i - 1));
        $item.children('.title').attr('id', 'text_' + (i - 1));

        $item.attr('id', 'problem_' + (i - 1));
    }

    problemList.splice(id, 1);

};
$(".img_link.delete").click(onDelete);

$("#add").click(function () { // 添加题目
    if (problemList.length == 26) return;
    var pid = null;
    if (problemList[problemList.length - 1] !== null)
        pid = problemList[problemList.length - 1] + 1;
    addProblem(pid);
});


$("#submit").click(function () { // 提交修改
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
    date.setMinutes(min);
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

    $.ajax({
        type: "post",
        url: 'http://' + host + '/contest/do-add/',
        dataType: 'json',
        data: {
            dd: 123,
            pl: problemList,
        }
    });
});

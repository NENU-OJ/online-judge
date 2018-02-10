var host = window.location.host;

// 代码编辑器
$(document).ready(function () {
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/monokai");
    editor.getSession().setMode("ace/mode/c_cpp");
    editor.setOptions({fontSize: "12pt"});

    $("#lang").change(function () {
        $langId = $("#lang").val();
        if ($langId == 1 || $langId == 2)
            editor.getSession().setMode("ace/mode/c_cpp");
        else if ($langId == 3)
            editor.getSession().setMode("ace/mode/java");
        else
            editor.getSession().setMode("ace/mode/python");
    })
});

// 默认选中C++11
$("#lang").val(2);

$("#submit").click(function () {
    $("#submitError").text("");
    $("#statusForm").css('display', 'none');

    $("#pending").css('display', 'none');
    $("#running").css('display', 'none');

    $("#statusInfo").text("");
    $("#statusDetail").text("");
    $("#statusAndImg").removeClass("alert-danger").removeClass("alert-success").addClass("alert-warning");

    var problemId = $("#problemId").val();
    var languageId = $("#lang").select().val();
    var sourceCode = ace.edit("editor").getValue();
    var isShared = document.getElementById("isShared").checked ? 1 : 0;
    var contestId = $("#contestId").val();


    if (sourceCode == null || sourceCode == "" || sourceCode === undefined) {
        $("#submitError").text("别交空代码啊");
    } else if (sourceCode.length > 65536) {
        $("#submitError").text("代码太长");
    } else {
        $("#submit").addClass("disabled");
        $.ajax({
            type: "post",
            url: 'http://' + host + '/problem/submit',
            dataType: "json",
            data: {
                problemId: problemId,
                languageId: languageId,
                sourceCode: sourceCode,
                isShared: isShared,
                contestId: contestId
            },
            success: function (resp) {
                if (resp.code == 0) { // 提交成功
                    $("#statusForm").css('display', 'block');
                    $("#statusInfo").text(resp.data.result);
                    $("#pending").css('display', '');
                    var times = 64;
                    var timer = setInterval(function () {
                        $.get("http://" + host + "/status/result/" + resp.data.id, function (result) {
                            result = JSON.parse(result);

                            if (result.data.result != 'Send to Judge' && result.data.result != 'Send to Rejudge') {
                                $("#running").css('display', '');
                                $("#pending").css('display', 'none');
                            }

                            $("#statusInfo").text(result.data.result);
                            if (result.data.finished) {
                                $("#pending").css('display', 'none');
                                $("#running").css('display', 'none');


                                var runDetail = 'Time used: ' + result.data.timeUsed + 'ms' +
                                    ' Memory used: ' + result.data.memoryUsed + 'kb';
                                $("#statusDetail").text(runDetail);
                                $("#statusInfo").text(result.data.result);
                                if (result.data.result == "Accepted")
                                    $("#statusAndImg").removeClass("alert-warning").addClass("alert-success");
                                else
                                    $("#statusAndImg").removeClass("alert-warning").addClass("alert-danger");
                                clearInterval(timer);
                                $("#submit").removeClass("disabled");
                            }
                        });

                        if (--times == 0) {
                            // 如果此次提交未评判完成则不开放提交按钮
                            // $("#submit").removeClass("disabled");
                        }
                    }, 700);

                } else if (resp.code == 2) { // 若没有登录就提交则提示登录
                    document.getElementById("checklogin").click();
                    $("#submit").removeClass("disabled");
                } else {
                    $("#submitError").text(resp.data);
                }
            },
            error: function () {
                console.log("获取JSON数据异常");
            }
        });
    }
});
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
    $("#statusInfo").text("");
    $("#statusInfo").removeClass("alert-danger").removeClass("alert-success").addClass("alert-warning");

    var problemId = $("#problemId").val();
    var languageId = $("#lang").select().val();
    var sourceCode = ace.edit("editor").getValue();
    var isShared = document.getElementById("isShared").checked ? 1 : 0;
    var contestId = $("#contestId").val();

    // console.log(document.getElementById("isShared").checked);
    console.log(isShared);

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
                    var times = 35;
                    var timer = setInterval(function () {
                        $.get("http://" + host + "/status/result/" + resp.data.id, function (result) {
                            result = JSON.parse(result);
                            $("#statusInfo").text(result.data.result);
                            if (result.data.finished) {
                                var runInfo = result.data.result +
                                    ' Time used: ' + result.data.timeUsed + 'ms' +
                                    ' Memory used: ' + result.data.memoryUsed + 'kb';
                                $("#statusInfo").text(runInfo);
                                if (result.data.result == "Accepted")
                                    $("#statusInfo").removeClass("alert-warning").addClass("alert-success");
                                else
                                    $("#statusInfo").removeClass("alert-warning").addClass("alert-danger");
                                clearInterval(timer);
                                $("#submit").removeClass("disabled");
                            }
                        });

                        if (--times == 0) {
                            clearInterval(timer);
                            $("#submit").removeClass("disabled");
                        }
                    }, 700);

                } else if (resp.code == 2) { // 若没有登录就提交则提示登录
                    document.getElementById("checklogin").click(); 
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
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

$("#submit").click(function () {
    $("#submitError").text("");

    var problemId = $("#problemId").val();
    var languageId = $("#lang").select().val();
    var sourceCode = ace.edit("editor").getValue();
    var isShared = document.getElementById("isShared").checked;
    var contestId = $("#contestId").val();

    if (sourceCode == null || sourceCode == "" || sourceCode === undefined) {
        $("#submitError").text("别交空代码啊");
    } else if (sourceCode.length > 65536) {
        $("#submitError").text("代码太长");
    } else {
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
                    $("#statusInfo").text("Send to Judge");
                    var times = 60;
                    var timer = setInterval(function () {
                        $("#statusInfo").text(Math.random());
                        if (--times == 0)
                            clearInterval(timer);
                    }, 1000);

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
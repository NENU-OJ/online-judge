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
    var problemId = $("#problemId").val();
    var languageId = $("#lang").select().val();
    var sourceCode = ace.edit("editor").getValue();
    var isShared = document.getElementById("isShared").checked;
    var contestId = $("#contestId").val();

    $("#statusForm").css('display', 'block');
    $("#statusInfo").text("fuck");
    var times = 0;
    var timer = setInterval(function () {
        $("#statusInfo").text(Math.random());
        if (times++ == 5)
            clearInterval(timer);
    }, 1000);

    console.log(problemId);
    console.log(languageId);
    console.log(sourceCode);
    console.log(isShared);
    console.log(contestId);
});
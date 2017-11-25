var host = window.location.host;

$("#problemSubmit").on('click', "#submit", function () {
    var problemId = $("#problemId").val();
    var languageId = $("#language").select().val();
    var sourceCode = $("#sourceCode").val();
    var isShared = document.getElementById("isShared").checked;
    var contestId = $("#contestId").val();

    if (contestId == null || contestId == "" || contestId == undefined) {
        $.ajax({
            type: "get",
            url: 'http://' + host + '/problem/problem-detail/submit',
            dataType: "json",
            data: {
                problemId: problemId,
                languageId: languageId,
                sourceCode: sourceCode,
                isShared: isShared
            },
            success: function (data) {
                $.each(data, function (index, val) {
                    var code = val.code;
                    if (code == 0) {
                        //alert("提交成功");
                        // window.location.href = 'http://' + host + '/problem/problem-detail/detail?p_id=' + problemId;
                        window.location.href = 'http://' + host + '/status/status';
                    }
                })
            },
            error: function () {
                console.log("获取JSON数据异常");
            }
        })
    }
    else {
        $.ajax({
            type: "get",
            url: 'http://' + host + '/problem/problem-detail/submit',
            dataType: "json",
            data: {
                problemId: problemId,
                contestId: contestId,
                languageId: languageId,
                sourceCode: sourceCode,
                isShared: isShared
            },
            success: function (data) {
                $.each(data, function (index, val) {
                    var code = val.code;
                    if (code == 0) {
                        alert("提交成功");
                        window.location.href = 'http://' + host + '/problem/problem-detail/detail?p_id=' + problemId + '&c_id=' + contestId;
                    }
                })
            },
            error: function () {
                console.log("获取JSON数据异常");
            }
        })
    }
});
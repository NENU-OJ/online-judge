var host = window.location.host;

$(document).ready(function () {

    // 初始化富文本编辑器
    var contentEditor = CKEDITOR.replace('content');

    var $err = $("#err");

    $("#submit").click(function () {
        $err.text('');
        var title = $("#topicTitle").val().trim();

        if (title == null || title == '' || title === undefined) {
            $err.text('标题不能为空');
            return;
        }

        var priority = 0;
        if ($("#topicPriority").length > 0)
            priority = $("#topicPriority").val().trim();
        var content = CKEDITOR.instances.content.getData();

        $("#submit").addClass('disabled');
        $.ajax({
            type: "post",
            url: 'http://' + host + '/discuss/create',
            dataType: "json",
            data: {
                id: id,
                title: title,
                priority: priority,
                content: content
            },
            success: function (resp) {
                if (resp.code == 0) {
                    location.href = 'http://' + host + '/discuss/' + resp.id;
                } else {
                    $err.text(resp.data);
                }
            },
            error: function () {
                $err.text("获取JSON数据异常");
            }
        });
        $("#submit").removeClass('disabled');
    });
});
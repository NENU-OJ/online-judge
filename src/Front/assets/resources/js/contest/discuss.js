$("#publish").click(function () {
    $err = $("#publish_err");
    $err.removeClass().addClass('error-text');
    $err.text('');
    var pid = $("#publish_pid").val();
    var title = $("#publish_title").val();
    var content = $("#publish_content").val();
    if (!title) {
        $err.text('标题不能为空');
        return;
    }

    if (pid)
        title = pid + '题: ' + title;
    else
        title = 'General: ' + title;

    $(this).addClass('disabled');
    $.ajax({
        type: "post",
        url: 'http://' + host + '/discuss/create',
        dataType: "json",
        data: {
            title: title,
            content: content,
            contestId: _cid,
        },
        async: false,
        success: function (resp) {
            if (resp.code == 0) {
                $err.removeClass().addClass('success-text').text(resp.data);
                $("#publish_title").val('');
                setTimeout(function () {
                    location.reload();
                }, 1200);
                $('html,body').animate({ scrollTop: 0}, 500);
            } else {
                $err.text(resp.data);
            }
        },
        error: function () {
            $err.text("获取JSON数据异常");
        }
    });
    $(this).removeClass('disabled');

});
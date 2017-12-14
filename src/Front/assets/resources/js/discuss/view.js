$addReply = $("#add_reply");

$(document).ready(function() {
    if ($("#content").length > 0)
        CKEDITOR.replace('content');
});

$addReply.click(function() {
    if ($("#to_reply").css('display') != 'none')
        $("#to_reply").css('display', 'none');
    else
        $("#to_reply").css('display', 'table-row');
});

$("#reply").click(function () {
    $("#err").text('');
    $(this).addClass('disabled');

    var content = CKEDITOR.instances['content'].getData();
    $.ajax({
        type: "post",
        url: 'http://' + host + '/discuss/reply',
        dataType: "json",
        data: {
            discuss_id: discussId,
            content: content
        },
        success: function (resp) {
            if (resp.code == 0) {
                location.reload();
            } else {
                $("#err").text(resp.data);
            }
        },
        error: function () {
            $("#err").text("获取JSON数据异常");
        }
    });
});

var replyId = null;

$(".reply-btn").click(function () {

    if (CKEDITOR.instances['reply_content_' + replyId]) {
        CKEDITOR.instances['reply_content_' + replyId].destroy();
    }

    if (replyId) {
        $("#reply_content_" + replyId).css('display', 'none');
        $("#reply_submit_" + replyId).css('display', 'none');
    }
    var nowId = $(this).attr('data-id');
    if (nowId == replyId) {
        replyId = null;
    } else {
        replyId = nowId;
        $("#reply_content_" + replyId).css('display', 'table-row');
        $("#reply_submit_" + replyId).css('display', '');
        CKEDITOR.replace("reply_content_" + replyId);
    }

});

$(".replySubmit").click(function () {
    $(this).addClass('disabled');

    var id = $(this).attr('data-id');
    $("#err_" + id).text('');

    var content = CKEDITOR.instances['reply_content_' + replyId].getData();
    var parentId = $(this).attr('data-fa');
    var replyAt = $(this).attr('data-username');

    $.ajax({
        type: "post",
        url: 'http://' + host + '/discuss/reply',
        dataType: "json",
        data: {
            discuss_id: discussId,
            parent_id: parentId,
            reply_at: replyAt,
            content: content
        },
        success: function (resp) {
            if (resp.code == 0) {
                location.reload();
            } else {
                $(".replySubmit").removeClass('disabled')
                $("#err_" + id).text(resp.data);
            }
        },
        error: function () {
        }
    });

});
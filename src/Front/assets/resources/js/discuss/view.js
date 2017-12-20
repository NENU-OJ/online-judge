$addReply = $("#add_reply");

$(document).ready(function() {
    if ($("#content").length > 0)
        CKEDITOR.replace('content');
});

// 对该话题添加回复
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

// 回复别人的评论

var replyId = null;
var updateReply = false;
var preContent = null;
var preHtml = null;

$(".reply-btn").click(function () {
    updateReply = false;
    if (replyId && preHtml) {
        $("#content_" + replyId).html(preHtml);
    }
    preHtml = null;
    preContent = null;

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

    url = '';
    data = {};

    if (updateReply) {
        url = 'http://' + host + '/discuss-reply/update';
        data = {
            id: updateReply,
            content: content
        }
    } else {
        url = 'http://' + host + '/discuss/reply';
        data = {
            discuss_id: discussId,
            parent_id: parentId,
            reply_at: replyAt,
            content: content
        }
    }

    $.ajax({
        type: "post",
        url: url,
        dataType: "json",
        data: data,
        success: function (resp) {
            if (resp.code == 0) {
                location.reload();
            } else {
                $(".replySubmit").removeClass('disabled')
                $("#err_" + id).text(resp.data);
            }
        },
        error: function () {
            alert("获取JSON数据失败");
        }
    });

});


// 修改自己的评论
$(".edit-btn").click(function () {

    updateReply = $(this).attr('data-id');

    if (replyId && preHtml) {
        $("#content_" + replyId).html(preHtml);
    }
    preHtml = null;
    preContent = null;

    preHtml = $("#content_" + updateReply).html();

    $.ajax({
        url: 'http://' + host + '/discuss-reply/get/?id=' + updateReply,
        async : false,
        dataType: 'json',
        success: function(resp) {
            if (resp.code == 0) {
                preContent = resp.data;
            } else {
                preContent = $("#content_" + updateReply).text();
            }
        }
    });

    $("#content_" + updateReply).text('');

    if (CKEDITOR.instances['reply_content_' + replyId]) {
        CKEDITOR.instances['reply_content_' + replyId].destroy();
    }

    if (replyId) {
        $("#reply_content_" + replyId).css('display', 'none');
        $("#reply_submit_" + replyId).css('display', 'none');
    }
    var nowId = $(this).attr('data-id');
    if (nowId == replyId) {
        if (replyId && preHtml) {
            $("#content_" + replyId).html(preHtml);
        }
        preContent = null;
        preHtml = null;
        replyId = null;
    } else {
        replyId = nowId;
        $("#reply_content_" + replyId).css('display', 'table-row');
        $("#reply_submit_" + replyId).css('display', '');
        CKEDITOR.replace("reply_content_" + replyId);
        CKEDITOR.instances['reply_content_' + replyId].setData(preContent);
    }
});
// 删除评论
$(".trash-btn").click(function () {
   var id = $(this).attr('data-id');
   if (window.confirm("确认要删除这条评论吗？")) {
       $.ajax({
           type: "post",
           url: 'http://' + host + '/discuss-reply/delete/',
           dataType: "json",
           data: {
               id: id
           },
           success: function (resp) {
               if (resp.code == 0) {
                   location.reload();
               } else {
                   alert("删除失败：" + resp.data);
               }
           },
           error: function () {
               alert("删除失败：获取JSON数据失败");
           }
       });
   }
});
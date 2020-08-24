var host = window.location.host;


$(".share").click(function () {
    $err = $("#shareError");
    $err.text('');

    var share = parseInt($(this).attr('data-val'));
    var statusId = parseInt($(this).attr('data-id'));
    $.ajax({
        type: "post",
        url: '//' + host + '/status/share',
        dataType: 'json',
        async: false,
        data: {
            share: share,
            statusId: statusId,
        },
        success: function(resp) {
            if (resp.code == 0) {
                $err.removeClass('error-text').addClass('success-text').text(resp.data);
            } else {
                $err.removeClass('success-text').addClass('error-text').text(resp.data);
            }
        },
        error: function() {
            $err.removeClass('success-text').addClass('error-text').text('获取JSON数据异常');
        }
    });

});

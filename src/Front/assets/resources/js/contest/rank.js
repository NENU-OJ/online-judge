$("#star").click(function () {

    var $err = $("#starerr");
    var starStr = $("#starstr").val();
    var contestId = _cid;
    var star = $("#type").val();
    var userList = [];

    starStr.split(/\s+/).forEach(function (userName) {
        if (new RegExp('^([a-z]|[A-Z]|[0-9]|_)+$').test(userName))
            userList.push(userName);
    });

    $.ajax({
        type: "post",
        url: 'http://' + host + '/contest/star/',
        dataType: 'json',
        async: false,
        data: {
            contestId: contestId,
            userList: userList,
            star: star
        },
        success: function(resp) {
            if (resp.code == 0) {
                $err.removeClass('error-text').addClass('success-text').text(resp.data);
            } else {
                $err.removeClass('success-text').addClass('error-text').text(resp.data);
            }
        },
        error: function() {
            $err.text('获取JSON数据异常')
        }
    });
});
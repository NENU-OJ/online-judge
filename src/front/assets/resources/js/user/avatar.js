/*! ACdreamOnlineJudge 2016-05-06 */
var $imgerr = $("#imgerr");
var $file = $("#file");
var $upload = $("#upload");
var $ui = $(".upload_info");

$(document).ready(function () {
    $upload.click(function () {
        $imgerr.text("fuck");
    })
})

$(document).ready(function () {
    $upload.click(function () {
        return $file.val() ? void 0 : (errAnimate($imgerr, "请选择文件！"), !1)
    });
    $file.upload({
        dataType: "json", add: function (a, b) {
            var c = b.files[0];
            $ui.text(c.name), $upload.unbind("click"), $upload.click(function () {
                var a = new RegExp("^.*.(jpg|jpeg|png)$", "i");
                return a.test(c.name) ?
                    c.size &&
                    c.size > 2097152 ?
                        (errAnimate($imgerr, "图片大小不得超过2m！"), !1) :
                        (showWaitting($imgerr), void b.submit()) :
                    (errAnimate($imgerr, "不支持的格式！"), !1)
            })
        }, progress: function (a, b) {
            var c = parseInt(b.loaded / b.total * 100, 10);
            $ui.text(c + "%")
        }, done: function (a, b) {
            var c = b.response().result;
            0 === c.ret ? window.location.reload(!0) : errAnimate($imgerr, c.msg)
        }
    })
});
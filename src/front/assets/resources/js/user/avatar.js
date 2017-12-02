var $imgerr = $("#imgerr");
var $file = $("#file");
var $upload = $("#upload");
var $ui = $(".upload_info");

$(document).ready(function () {
    $upload.click(function () {
        $imgerr.text("");
        if (!$file.val())
            $imgerr.text("请选择文件！");
    });
    $file.fileupload({
        dataType: "json",
        add: function (e, data) {
            var avatar = data.files[0];
            $ui.text(avatar.name);
            $upload.unbind("click");
            $upload.click(function () {
                var imgReg = new RegExp("^.*.(jpg|jpeg|png)$", "i");
                if (imgReg.test(avatar.name)) {
                    if (avatar.size > 2097152) {
                        $imgerr.text("图片大小不得超过2m！");
                    } else {
                        $imgerr.text("");
                        data.submit();
                    }
                } else {
                    $imgerr.text("不支持的格式！");
                }
            })
        },
        progress: function (e, data) {
            var per = parseInt(data.loaded / data.total * 100, 10);
            $ui.text(per + "%")
        },
        done: function (e, data) {
            var resp = data.response().result;
            if (resp.code == 0)
                window.location.reload(true);
            else
                $imgerr.text(resp.data);
        }
    })
});
$(document).ready(function () {

    // 初始化富文本编辑器
    var desc = CKEDITOR.replace('desc');
    var input = CKEDITOR.replace('input');
    var output = CKEDITOR.replace('output');
    var hint = CKEDITOR.replace('hint');
    CKFinder.setupCKEditor(desc);
    CKFinder.setupCKEditor(input);
    CKFinder.setupCKEditor(output);
    CKFinder.setupCKEditor(hint);

    var $loadInfo = $("#loadInfo");

    var $pid = $("#pid");
    var $title = $("#title");
    var $timeLimit = $("#timeLimit");
    var $memoryLimit = $("#memoryLimit");
    var $special = $("#special");
    var $hide = $("#hide");
    var $desc = $("#desc");
    var $input = $("#input");
    var $output = $("#output");
    var $sampleIn = $("#sampleIn");
    var $sampleOut = $("#sampleOut");
    var $hint = $("#hint");
    var $source = $("#source");
    var $author = $("#author");


    // load id
    $("#load").click(function () {

        $loadInfo.text('');

        var pid = $pid.val().trim();
        if (pid == null || pid == '' || pid === undefined) {
            $loadInfo.text('Problem ID不能为空');
            return;
        }

        $.ajax({
            type: "get",
            url: 'http://' + host + '/problem/detail',
            dataType: "json",
            data: {
                id: pid
            },
            success: function (resp) {
                if (resp.code == 0) {
                    $title.val(resp.data.title);
                    $timeLimit.val(resp.data.timeLimit);
                    $memoryLimit.val(resp.data.memoryLimit);
                    $special.val(resp.data.special);
                    $hide.val(resp.data.hide);
                    CKEDITOR.instances.desc.setData(resp.data.description);
                    CKEDITOR.instances.input.setData(resp.data.input);
                    CKEDITOR.instances.output.setData(resp.data.output);
                    CKEDITOR.instances.hint.setData(resp.data.hint);
                    $sampleIn.text(resp.data.sampleInput);
                    $sampleOut.text(resp.data.sampleOutput);
                    $source.text(resp.data.source);
                    $author.text(resp.data.author);
                } else {
                    $loadInfo.text(resp.data);
                }
            },
            error: function () {
                console.log("获取JSON数据异常");
            }
        });
    });

    // reset
    $("#reset").click(function () {
        $loadInfo.text('');

        $pid.val('');
        $title.val('');
        $timeLimit.val('');
        $memoryLimit.val('');
        $special.val(0);
        $hide.val(0);
        CKEDITOR.instances.desc.setData('');
        CKEDITOR.instances.input.setData('');
        CKEDITOR.instances.output.setData('');
        CKEDITOR.instances.hint.setData('');
        $sampleIn.text('');
        $sampleOut.text('');
        $source.val('');
        $author.val('');

    });

    $("#submit").click(function () {
        $submitInfo = $("#submitInfo");
        $submitError = $("#submitError");

        $submitInfo.text('');
        $submitError.text('');

        var pid = $pid.val().trim();
        var title = $title.val();
        var timeLimit = $timeLimit.val().trim();
        var memoryLimit = $memoryLimit.val().trim();
        var special = $special.val();
        var hide = $hide.val();
        var desc = CKEDITOR.instances.desc.getData();
        var input = CKEDITOR.instances.input.getData();
        var output = CKEDITOR.instances.output.getData();
        var hint = CKEDITOR.instances.hint.getData();
        var sampleIn = $sampleIn.val();
        var sampleOut = $sampleOut.val();
        var source = $source.val();
        var author = $author.val();




        if (title == null || title == '' || title === undefined) {
            $submitError.text("Title 不能为空");
            return;
        }

        if (timeLimit == null || timeLimit == '' || timeLimit === undefined) {
            $submitError.text("Time Limit 不能为空");
            return;
        }

        if (memoryLimit == null || memoryLimit == '' || memoryLimit === undefined) {
            $submitError.text("Memory Limit 不能为空");
            return;
        }

        $("#submit").addClass('disabled');
        $.ajax({
            type: "post",
            url: 'http://' + host + '/problem/update',
            dataType: "json",
            data: {
                pid: pid,
                title: title,
                timeLimit: timeLimit,
                memoryLimit: memoryLimit,
                special: special,
                hide: hide,
                desc: desc,
                input: input,
                output: output,
                hint: hint,
                sampleIn: sampleIn,
                sampleOut: sampleOut,
                source: source,
                author: author
            },
            success: function (resp) {
                if (resp.code == 0) {
                    $submitInfo.text(resp.data);
                    $("#pid").val(resp.id);
                } else {
                    $submitError.text(resp.data);
                }
                $("#submit").removeClass('disabled');
            },
            error: function () {
                console.log("获取JSON数据异常");
            }
        });
    });
});

<!DOCTYPE html>
<html lang="zh-CN">
<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */

/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;

$this->context->layout = false;
?>
<head>
    <title>NENU Online Judge</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300,400' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>
    <!-- CSS Libs -->
    <link rel="stylesheet" type="text/css" href="/assets/resources/lib/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/resources/lib/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/resources/lib/css/animate.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/resources/lib/css/bootstrap-switch.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/resources/lib/css/checkbox3.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/resources/lib/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/resources/lib/css/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/assets/resources/lib/css/select2.min.css">
    <!-- CSS App -->
    <link rel="stylesheet" type="text/css" href="/assets/resources/css/style.css">
    <link rel="stylesheet" type="text/css" href="/assets/resources/css/themes/flat-blue.css">
</head>
<body class="flat-blue">
<div class="container" id="page-content">
    <div class="row">
        <div class="col-xs-8 col-center-block">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <div class="title">Ooooooops!</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row no-margin">
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                <h3><?= Html::encode($this->title) ?></h3>
                            </div>
                            <div class="panel-body">
                                <?= nl2br(Html::encode($message)) ?>
                            </div>
                        </div>
                        <p>
                            The above error occurred while the Web server was processing your request.
                        </p>
                        <p>
                            Please <a href="https://github.com/NENU-OJ/OnlineJudge/issues">contact us</a> if you think
                            this is a server error. Thank you.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'views/smarty/templates/common/footer.html' ?>
<script type="text/javascript" src="/assets/resources/lib/js/jquery.min.js"></script>
<script type="text/javascript" src="/assets/resources/lib/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/resources/lib/js/Chart.min.js"></script>
<script type="text/javascript" src="/assets/resources/lib/js/bootstrap-switch.min.js"></script>

<script type="text/javascript" src="/assets/resources/lib/js/jquery.matchHeight-min.js"></script>
<script type="text/javascript" src="/assets/resources/lib/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/assets/resources/lib/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/resources/lib/js/select2.full.min.js"></script>
<script type="text/javascript" src="/assets/resources/lib/js/ace/ace.js"></script>
<script type="text/javascript" src="/assets/resources/lib/js/ace/mode-html.js"></script>
<script type="text/javascript" src="/assets/resources/lib/js/ace/theme-github.js"></script>
<!-- Javascript -->
<script type="text/javascript" src="/assets/resources/js/app.js"></script>
<!-- Javascript -->
</body>
</html>
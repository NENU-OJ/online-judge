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
    <title><?= \Yii::$app->params['longTitle'] ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/assets/resources/img/favicon.png"/>
    <link rel="bookmark" href="/assets/resources/img/favicon.png" type="image/x-icon"/>
    <!-- CSS Libs -->
    <link rel="stylesheet" type="text/css" href="/assets/resources/lib/css/bootstrap/bootstrap.min.css">
    <!-- CSS App -->
    <link rel="stylesheet" type="text/css" href="/assets/resources/css/base.css">
</head>
<body>
<div class="container" id="page-content">
    <div class="col-xs-12 col-center-block">
        <div class="panel panel-danger">
            <div class="panel-heading"><h3><?= Html::encode($this->title) ?></h3></div>
            <div class="panel-body"><h4><?= nl2br(Html::encode($message)) ?></h4></div>
        </div>
    </div>
</div>
<!-- footer部分start -->
<div id="footer">
    <div class="container"><h4><?= \Yii::$app->params['shortTitle'] ?></h4>
        <div class="row">
            <div class="col-sm-2"><a target="_blank" href="<?= \Yii::$app->params['version']['repo'] ?>">Commit <?= \Yii::$app->params['version']['id'] ?></a></div>
            <div class="col-sm-5"><span><?= \Yii::$app->params['copyright'] ?></span></div>
        </div>
        <div>
            <span>Designer and Developer :
            <?php foreach (\Yii::$app->params['developer'] as $developer => $site) { ?>
            <a target="_blank" href="<?= $site ?>"><?= $developer ?></a>
            <?php } ?>
            </span>
        </div>
    </div>
</div>
<!-- footer部分end -->
<script type="text/javascript" src="/assets/resources/lib/js/jquery/jquery.min.js"></script>
<script type="text/javascript" src="/assets/resources/lib/js/bootstrap/bootstrap.min.js"></script>
</body>
</html>

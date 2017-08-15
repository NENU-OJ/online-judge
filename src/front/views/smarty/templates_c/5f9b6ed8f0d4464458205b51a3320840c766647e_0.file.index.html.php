<?php
/* Smarty version 3.1.31, created on 2017-08-15 10:32:46
  from "/var/www/html/OnlineJudge/src/front/views/smarty/templates/index.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5992cdceeded23_92329968',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5f9b6ed8f0d4464458205b51a3320840c766647e' => 
    array (
      0 => '/var/www/html/OnlineJudge/src/front/views/smarty/templates/index.html',
      1 => 1502793164,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:common/header.html' => 1,
    'file:common/footer.html' => 1,
  ),
),false)) {
function content_5992cdceeded23_92329968 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>

  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>NENU Online Judge</title>

  <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->

  <!-- Bootstrap -->
  <link href="/assets/resources/css/bootstrap.min.css" rel="stylesheet">

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
      <?php echo '<script'; ?>
 src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"><?php echo '</script'; ?>
>
      <?php echo '<script'; ?>
 src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"><?php echo '</script'; ?>
>
      <![endif]-->
</head>

<body>
  <?php $_smarty_tpl->_subTemplateRender("file:common/header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


  <?php $_smarty_tpl->_subTemplateRender("file:common/footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <?php echo '<script'; ?>
 src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"><?php echo '</script'; ?>
>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <?php echo '<script'; ?>
 src="/assets/resources/js/bootstrap.min.js"><?php echo '</script'; ?>
>
</body>

</html><?php }
}

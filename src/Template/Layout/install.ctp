<?php
/**
 * @var \App\View\AppView $this
 */
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset(); ?>
    <title><?= h($this->fetch('title')); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= h($this->fetch('description')); ?>">

    <link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"
          rel="stylesheet">

    <?php
    echo $this->Html->meta('icon');

    echo $this->Html->css('/vendor/bootstrap/css/bootstrap.min.css');
    echo $this->Html->css('/vendor/dashboard/font-awesome/css/font-awesome.min.css');
    echo $this->Html->css('/vendor/dashboard/css/AdminLTE.min.css');
    echo $this->Html->css('/vendor/dashboard/css/skins/_all-skins.min.css');
    echo $this->Html->css('app.css?ver=' . APP_VERSION);

    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    ?>

    <?= $this->fetch('scriptTop') ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="installation login-page">

<div class="login-box">
    <div class="login-logo">
        <?= h($this->fetch('title')); ?>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <?= $this->Flash->render() ?>

        <?= $this->fetch('content') ?>

        <hr>

        <div class="text-center">
            <?= __('Copyright &copy;') ?> <?php echo date('Y'); ?> | <?= __('Developed by') ?> <a
                    href="http://www.mightyscripts.com" target="_blank">MightyScripts</a>
        </div>
    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<script data-cfasync="false" src="<?= $this->Assets->url('/js/ads.js?ver=' . APP_VERSION) ?>"></script>
<?= $this->Html->script('/vendor/jquery.min.js'); ?>
<?= $this->Html->script('/vendor/bootstrap/js/bootstrap.min.js'); ?>
<?= $this->Html->script('/vendor/clipboard.min.js'); ?>
<?= $this->element('js_vars'); ?>
<?= $this->Html->script('app.js?ver=' . APP_VERSION); ?>
<?= $this->Html->script('/vendor/dashboard/js/app.min.js'); ?>
<?= $this->fetch('scriptBottom') ?>
</body>
</html>

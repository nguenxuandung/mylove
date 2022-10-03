<?php
/**
 * @var \App\View\AppView $this
 */
?>
<!DOCTYPE html>
<html lang="<?= locale_get_primary_language(null) ?>">
<head>
    <meta name="robots" content="noindex, nofollow">
    <meta name="og:title" content="<?= h($this->fetch('og_title')); ?>">
    <meta name="og:description" content="<?= h($this->fetch('og_description')); ?>">
    <meta property="og:image" content="<?= h($this->fetch('og_image')); ?>"/>
    <?= $this->element('front_head'); ?>
</head>
<body class="interstitial-page">
<?= get_option('after_body_tag_code'); ?>

<nav id="mainNav" class="navbar navbar-default">
    <div class="container">

        <div class="row is-table-row">
            <div class="col-xs-6 col-sm-3">
                <div class="navbar-header pull-left">
                    <?php
                    $logo = get_logo();
                    $class = '';
                    if ($logo['type'] == 'image') {
                        $class = 'logo-image';
                    }

                    ?>
                    <a class="navbar-brand <?= $class ?>"
                       href="<?= build_main_domain_url('/'); ?>"><?= $logo['content'] ?></a>
                </div>
            </div>
            <div class="hidden-xs col-sm-6">
                <?php if (!empty($interstitial_banner_ad)) : ?>
                    <div class="banner banner-468x60">
                        <div class="banner-inner">
                            <?= $interstitial_banner_ad; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-xs-6 col-sm-3">
                <div class="pull-right">
                    <div class="skip-ad">
                        <div class="text-center">
                            <span><?= __('Please Wait') ?></span><br>
                            <span class="counter"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</nav>

<?= $this->Flash->render() ?>
<?= $this->fetch('content') ?>

<?= $this->element('js_vars'); ?>

<?php
echo $this->Assets->script('/js/ads.js');

if ((bool)get_option('combine_minify_css_js', false)) {
    echo $this->Assets->script('/build/js/script.min.js?ver=' . APP_VERSION);
} else {
    echo $this->Assets->script('/vendor/jquery.min.js?ver=' . APP_VERSION);
    echo $this->Assets->script('/vendor/bootstrap/js/bootstrap.min.js?ver=' . APP_VERSION);
    echo $this->Assets->script('/vendor/owl/owl.carousel.min.js?ver=' . APP_VERSION);
    echo $this->Assets->script('/vendor/wow.min.js?ver=' . APP_VERSION);
    echo $this->Assets->script('/vendor/clipboard.min.js?ver=' . APP_VERSION);
    echo $this->Assets->script('/js/front.js?ver=' . APP_VERSION);
    echo $this->Assets->script('/js/app.js?ver=' . APP_VERSION);
}
?>

<?= $this->fetch('scriptBottom') ?>
<?= get_option('footer_code'); ?>

</body>

</html>

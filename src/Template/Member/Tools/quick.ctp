<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $logged_user
 */
$this->assign('title', __('Quick Link'));
$this->assign('description', '');
$this->assign('content_title', __('Quick Link'));

?>

<div class="box box-primary">
    <div class="box-body">

        <div class="callout callout-success">
            <h4><?= __('Your API token:') ?></h4>
            <p>
            <pre><?= $logged_user->api_token ?></pre>
            </p>
        </div>

        <p><?= __('Everyone can use the shortest way to shorten links with {0}.', get_option('site_name')) ?></p>

        <p><?= __(
            'Just copy the link below to address bar into your web browser, change last part to ' .
                'destination link and press ENTER. {0} will redirect you to your shortened link. Copy it wherever ' .
                'you want and get paid.',
            get_option('site_name')
        ) ?></p>

        <pre><?= $this->Url->build('/', true); ?>st?api=<b><?= $logged_user->api_token ?></b>&url=<b>yourdestinationlink.com</b></pre>

    </div>
</div>

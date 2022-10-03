<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $logged_user
 */
$this->assign('title', __('Developers API'));
$this->assign('description', '');
$this->assign('content_title', __('Developers API'));
?>

<div class="box box-primary">
    <div class="box-body">

        <div class="callout callout-success">
            <h4><?= __('Your API token:') ?></h4>
            <p>
            <pre><?= $logged_user->api_token ?></pre>
            </p>
        </div>

        <p><?= __(
                'For developers {0} prepared <b>API</b> which returns responses in <b>JSON</b> or ' .
                '<b>TEXT</b> formats. ',
                get_option('site_name')
            ) ?></p>

        <p><?= __('Currently there is one method which can be used to shorten links on behalf of your account.') ?></p>

        <p><?= __('All you have to do is to send a <b>GET</b> request with your API token and URL Like the ' .
                'following:') ?></p>

        <div class="well">
            <?= $this->Url->build('/', true); ?>api?api=<b><?= $logged_user->api_token ?></b>&url=<b><?= urlencode('yourdestinationlink.com') ?></b>&alias=<b>CustomAlias</b>
        </div>

        <p><?= __('You will get a JSON response like the following') ?></p>

        <div class="well">
            {"status":"success","shortenedUrl":"<?= json_encode($this->Url->build('/', true) . 'xxxxx') ?>"}
        </div>

        <p><?= __('If you want a TEXT response just add <b>&format=text</b> at the end of your request as ' .
                'the below example. This will return just the short link. Note that if an error occurs, it will not ' .
                'output anything.') ?></p>

        <div class="well">
            <?= $this->Url->build('/', true); ?>api?api=<b><?= $logged_user->api_token ?></b>&url=<b><?= urlencode('yourdestinationlink.com') ?></b>&alias=<b>CustomAlias</b>&format=<b>text</b>
        </div>

        <?php
        $allowed_ads = get_allowed_ads();
        unset($allowed_ads[get_option('member_default_advert', 1)]);
        ?>

        <?php if (array_key_exists(1, $allowed_ads)) : ?>
            <p><?= __("If you want to use developers API with the interstitial advertising add the below code " .
                    "to the end of the URL") ?></p>
            <pre>&type=1</pre>
        <?php endif; ?>

        <?php if (array_key_exists(2, $allowed_ads)) : ?>
            <p><?= __("If you want to use developers API with the banner advertising add the below code to " .
                    "the end of the URL") ?></p>
            <pre>&type=2</pre>
        <?php endif; ?>

        <?php if (array_key_exists(0, $allowed_ads)) : ?>
            <p><?= __("If you want to use developers API without advertising add the below code to the end " .
                    "of the URL") ?></p>
            <pre>&type=0</pre>
        <?php endif; ?>

        <div class="alert alert-info">
            <h4><i class="icon fa fa-info"></i> <?= __("Note") ?></h4>
            <?= __("api & url are required fields and the other fields like alias, format & type are optional.") ?>
        </div>

        <p><?= __("That's it :)") ?></p>

        <h3><?= __("Using the API in PHP") ?></h3>

        <p><?= __("To use the API in your PHP application, you need to send a GET request via " .
                "file_get_contents or cURL. Please check the below sample examples using file_get_contents") ?></p>

        <p><?= __("Using JSON Response") ?></p>

        <div class="well">
            $long_url = urlencode('yourdestinationlink.com');<br>
            $api_token = '<?= $logged_user->api_token ?>';<br>
            $api_url = "<?= $this->Url->build('/', true); ?>api?api=<b>{$api_token}</b>&url=<b>{$long_url}</b>&alias=<b>CustomAlias</b>";<br>
            $result = @json_decode(file_get_contents($api_url),TRUE);<br>
            if($result["status"] === 'error') {<br>
            &emsp;echo $result["message"];<br>
            } else {<br>
            &emsp;echo $result["shortenedUrl"];<br>
            }
        </div>

        <p><?= __("Using Plain Text Response") ?></p>

        <div class="well">
            $long_url = urlencode('yourdestinationlink.com');<br>
            $api_token = '<?= $logged_user->api_token ?>';<br>
            $api_url = "<?= $this->Url->build('/', true); ?>api?api=<b>{$api_token}</b>&url=<b>{$long_url}</b>&alias=<b>CustomAlias</b>&format=<b>text</b>";<br>
            $result = @file_get_contents($api_url);<br>
            if( $result ){<br>
            &emsp;echo $result;<br>
            }
        </div>

    </div>
</div>

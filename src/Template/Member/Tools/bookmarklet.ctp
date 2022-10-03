<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $logged_user
 */
$this->assign('title', __('Bookmarklet'));
$this->assign('description', '');
$this->assign('content_title', __('Bookmarklet'));

?>

<div class="box box-primary">
    <div class="box-body">

        <p>
            <?= __('Short links more easily. Click and drag the following link to your links toolbar.') ?>
        </p>

        <p>
            <a class="btn btn-default"
               href='javascript:(function () {var a = window, b = document, c = encodeURIComponent, d = a.open("<?= $this->Url->build('/', true); ?>bookmarklet/?api=<?= $logged_user->api_token ?>&url=" + c(b.location), "bookmarklet_popup", "left=" + ((a.screenX || a.screenLeft) + 10) + ",top=" + ((a.screenY || a.screenTop) + 10) + ",height=510px,width=550px,resizable=1,alwaysRaised=1");a.setTimeout(function () {d.focus()}, 300)})()'>
                <?= __("Shorten!") ?>
            </a>
        </p>

        <p>
            <?= __("Once this is on your toolbar, you'll be able to make a short link at the click of a button.") ?>
        </p>

        <p>
            <?= __("This is compatible with most web browsers and platforms as long as your bookmarks or " .
                "favorites allow javascript. The links toolbar may not be visible in all setups and in most " .
                "browsers, you can enable it in the View->Toolbars menu of your web browser. You can also put it in " .
                "your bookmarks instead of the links toolbar.") ?>
        </p>

    </div>
</div>

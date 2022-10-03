<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var string $url
 */
$this->assign('title', __('Login as user'));
$this->assign('description', '');
$this->assign('content_title', __('Login as user'));
?>

<div class="box box-primary">
    <div class="box-body">

        <p>Copy the following URL and paste it on a different browser so you can login as this user.</p>
        <pre><?= h($url); ?></pre>

    </div>
</div>

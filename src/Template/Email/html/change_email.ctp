<?php
/**
 * @var \App\View\AppView $this
 * @var string $activation_key
 * @var string $username
 */
?>
<p><?= __('Hello') ?> <b><?php echo $username; ?></b>,</p>

<p><?= __('To change your email click on the following link or copy-paste it in your browser:') ?></p>

<p>
    <a href="<?php echo $this->Url->build('/',
        true); ?>member/users/change-email/<?php echo $username; ?>/<?php echo $activation_key; ?>"><?php echo $this->Url->build('/',
            true); ?>member/users/change-email/<?php echo $username; ?>/<?php echo $activation_key; ?></a>
</p>

<p>
    <?= __('Thanks,') ?><br>
    <?= h(get_option('site_name')) ?>
</p>
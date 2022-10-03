<?php
/**
 * @var \App\View\AppView $this
 * @var string $activation_key
 * @var string $username
 */
?>
<p><?= __('Hello') ?> <b><?php echo $username; ?></b>,</p>

<p><?= __('Someone requested that the password be reset for the following account:') ?></p>

<p><?php echo $this->Url->build('/', true); ?></p>

<p><?= __('If this was a mistake, just ignore this email and nothing will happen.') ?></p>

<p><?= __('To reset your password click on the following link or copy-paste it in your browser:') ?></p>

<p>
    <a href="<?php echo $this->Url->build('/',
        true); ?>auth/users/forgot-password/<?php echo $username; ?>/<?php echo $activation_key; ?>"><?php echo $this->Url->build('/',
            true); ?>auth/users/forgot-password/<?php echo $username; ?>/<?php echo $activation_key; ?></a>
</p>

<p>
    <?= __('Thanks,') ?><br>
    <?= h(get_option('site_name')) ?>
</p>
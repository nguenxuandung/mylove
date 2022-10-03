<?php
/**
 * @var \App\View\AppView $this
 * @var string $activation_key
 * @var string $username
 */
?>
<p><?= __('Hello') ?> <b><?php echo $username; ?></b>,</p>

<p><?= __('Thank you for registering at {0}. Your account is created and must be activated before you can use it.',
        h(get_option('site_name'))) ?></p>

<p><?= __('To activate the account click on the following link or copy-paste it in your browser:') ?></p>

<p>
    <a href="<?php echo $this->Url->build('/',
        true); ?>auth/users/activate-account/<?php echo $username; ?>/<?php echo $activation_key; ?>"><?php echo $this->Url->build('/',
            true); ?>auth/users/activate_account/<?php echo $username; ?>/<?php echo $activation_key; ?></a>
</p>

<p>
    <?= __('Thanks,') ?><br>
    <?= h(get_option('site_name')) ?>
</p>
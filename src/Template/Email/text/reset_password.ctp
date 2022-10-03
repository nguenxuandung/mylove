<?php
/**
 * @var \App\View\AppView $this
 * @var string $activation_key
 * @var string $username
 */
?>
<?= __('Hello') ?> <?php echo $username; ?>,


<?= __('Someone requested that the password be reset for the following account:') ?>


<?php echo $this->Url->build('/', true); ?>


<?= __('If this was a mistake, just ignore this email and nothing will happen.') ?>


<?= __('To reset your password click on the following link or copy-paste it in your browser:') ?>


<?php echo $this->Url->build('/', true); ?>auth/users/forgot-password/<?php echo $username; ?>/<?php echo $activation_key; ?>


<?= __('Thanks,') ?>

<?= h(get_option('site_name')) ?>

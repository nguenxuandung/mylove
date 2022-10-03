<?php
/**
 * @var \App\View\AppView $this
 * @var string $activation_key
 * @var string $username
 */
?>
<?= __('Hello') ?> <?php echo $username; ?>,

<?= __('Thank you for registering at {0}. Your account is created and must be activated before you can use it.',
        h(get_option('site_name'))) ?>


<p><?= __('To activate the account click on the following link or copy-paste it in your browser:') ?></p>


<?php echo $this->Url->build('/', true); ?>auth/users/activate_account/<?php echo $username; ?>/<?php echo $activation_key; ?>


<?= __('Thanks,') ?>

<?= h(get_option('site_name')) ?>

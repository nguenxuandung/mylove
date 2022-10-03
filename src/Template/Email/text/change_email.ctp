<?php
/**
 * @var \App\View\AppView $this
 * @var string $activation_key
 * @var string $username
 */
?>
<?= __('Hello') ?> <?php echo $username; ?>,

<?= __('To change your email click on the following link or copy-paste it in your browser:') ?>


<?php echo $this->Url->build('/', true); ?>member/users/change-email/<?php echo $username; ?>/<?php echo $activation_key; ?>


<?= __('Thanks,') ?>

<?= h(get_option('site_name')) ?>

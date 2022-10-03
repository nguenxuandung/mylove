<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<?= __('Hello') ?>,

<?= __('You have a new user registration') ?>

<?= __('User Id') ?>: <?= $user->id ?>

<?= __('Username') ?>: <?= $user->username ?>

<?= __('Email') ?>: <?= $user->email ?>


<?= __('Thanks,') ?>

<?= h(get_option('site_name')) ?>

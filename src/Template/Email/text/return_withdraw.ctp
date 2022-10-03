<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Withdraw $withdraw
 * @var \App\Model\Entity\User $user
 */
?>

<?= __('Hello') ?> <?= $user->username ?>,


<?= __('Your withdrawal request #{0} has been returned back to your account.', $withdraw->id) ?>


<?= __('Thanks,') ?>
<?= h(get_option('site_name')) ?>

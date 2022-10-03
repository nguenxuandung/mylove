<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Withdraw $withdraw
 * @var \App\Model\Entity\User $user
 */
?>

<?= __('Hello') ?> <?= $user->username ?>,


<?= __('Your withdrawal request #{0} has been canceled.', $withdraw->id) ?>

<?php if ($withdraw->user_note) : ?>
    <?= __('Reason') ?>
    <?= h($withdraw->user_note) ?>
<?php endif; ?>

<?= __('Thanks,') ?>
<?= h(get_option('site_name')) ?>

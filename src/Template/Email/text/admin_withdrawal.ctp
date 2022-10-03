<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Withdraw $withdraw
 * @var \App\Model\Entity\User $user
 * @var array $withdrawal_methods
 */
?>
<?= __('Hello') ?>,


<?= __('A new withdraw has been requested with the following details:') ?>


<?= __('Withdraw Id') ?>: <?= $withdraw->id ?>

<?= __('Username') ?>: <?= $user->username ?>

<?= __('Publisher Earnings') ?>: <?= display_price_currency($withdraw->publisher_earnings); ?>

<?= __('Referral Earnings') ?>: <?= display_price_currency($withdraw->referral_earnings); ?>

<?= __('Total Amount') ?>: <?= display_price_currency($withdraw->amount); ?>

<?= __('Method') ?>: <?= (isset($withdrawal_methods[$withdraw->method])) ?
    $withdrawal_methods[$withdraw->method] : $withdraw->method ?>

<?= __('Account') ?>: <?= nl2br(h($withdraw->account)) ?>


<?= __('Thanks,') ?>

<?= h(get_option('site_name')) ?>

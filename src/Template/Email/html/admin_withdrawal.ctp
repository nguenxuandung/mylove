<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Withdraw $withdraw
 * @var \App\Model\Entity\User $user
 */
$withdrawal_methods = array_column_polyfill(get_withdrawal_methods(), 'name', 'id');
?>

<p><?= __('Hello') ?>,</p>

<p><?= __('A new withdraw has been requested with the following details:') ?></p>

<p><?= __('Withdraw Id') ?>: <?= $withdraw->id ?></p>
<p><?= __('Username') ?>: <?= $user->username ?></p>
<p><?= __('Publisher Earnings') ?>: <?= display_price_currency($withdraw->publisher_earnings); ?></p>
<p><?= __('Referral Earnings') ?>: <?= display_price_currency($withdraw->referral_earnings); ?></p>
<p><?= __('Total Amount') ?>: <?= display_price_currency($withdraw->amount); ?></p>
<p><?= __('Method') ?>: <?= (isset($withdrawal_methods[$withdraw->method])) ?
        $withdrawal_methods[$withdraw->method] : $withdraw->method ?></p>
<p><?= __('Account') ?>: <?= nl2br(h($withdraw->account)) ?></p>

<p>
    <?= __('Thanks,') ?><br>
    <?= h(get_option('site_name')) ?>
</p>

<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Withdraw $withdraw
 * @var \App\Model\Entity\User $user
 */
$withdrawal_methods = array_column_polyfill(get_withdrawal_methods(), 'name', 'id');

$method = (isset($withdrawal_methods[$withdraw->method])) ?
    $withdrawal_methods[$withdraw->method] : $withdraw->method;

$amount = display_price_currency($withdraw->amount);
?>

<p><?= __('Hello') ?> <?= $user->username ?>,</p>

<p><?= __('We just processed your withdraw request #{0} for {1} via {2}', $withdraw->id, $amount, $method) ?></p>

<p><?= __('Happy Spending!') ?>

<p>
    <?= __('Thanks,') ?><br>
    <?= h(get_option('site_name')) ?>
</p>

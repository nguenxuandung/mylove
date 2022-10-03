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

<?= __('Hello') ?> <?= $user->username ?>,


<?= __('Your withdrawal request #{0} has been approved for amount {1} and will be sent via {2}',
    $withdraw->id, $amount, $method) ?>


<?= __('Your request will be processed as part of our normal schedule. ' .
    'You will receive an email when your withdrawal has been processed.') ?>


<?= __('Thanks,') ?>
<?= h(get_option('site_name')) ?>

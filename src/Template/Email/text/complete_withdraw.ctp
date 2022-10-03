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


<?= __('We just processed your withdraw request #{0} for {1} via {2}', $withdraw->id, $amount, $method) ?>


<?= __('Happy Spending!') ?>


<?= __('Thanks,') ?>
<?= h(get_option('site_name')) ?>


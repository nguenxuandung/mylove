<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Invoice $invoice
 */
$this->assign('title', __('Invoice #{0}', $invoice->id));
$this->assign('description', '');
$this->assign('content_title', __('Invoice #{0}', $invoice->id));
?>

<?php
$statuses = [
    1 => __('Paid'),
    2 => __('Unpaid'),
    3 => __('Canceled'),
    4 => __('Invalid Payment'),
    5 => __('Refunded'),
]
?>

<div class="box box-primary checkout-form">
    <div class="box-header with-border">
        <i class="fa fa-credit-card"></i>
        <h3 class="box-title"><?= __('Invoice #{0}', $invoice->id) ?></h3>
    </div>
    <div class="box-body">

        <table class="table table-hover table-striped">
            <tr>
                <td><?= __('Username'); ?></td>
                <td>
                    <?= $this->Html->link(
                        $invoice->user->username,
                        ['controller' => 'Users', 'action' => 'view', $invoice->user->id]
                    ); ?>
                </td>
            </tr>
            <tr>
                <td><?= __('Status') ?></td>
                <td><?= $statuses[$invoice->status] ?></td>
            </tr>
            <tr>
                <td><?= __('Description') ?></td>
                <td><?= h($invoice->description) ?></td>
            </tr>
            <tr>
                <td><?= __('Amount') ?></td>
                <td><?= display_price_currency($invoice->amount) ?></td>
            </tr>
            <tr>
                <td><?= __('Payment Method') ?></td>
                <td><?= (isset(get_payment_methods()[$invoice->payment_method])) ?
                        get_payment_methods()[$invoice->payment_method] : $invoice->payment_method ?></td>
            </tr>
            <tr>
                <td><?= __('Paid Date') ?></td>
                <td><?= h($invoice->paid_date) ?></td>
            </tr>
            <tr>
                <td><?= __('Created') ?></td>
                <td><?= display_date_timezone($invoice->created) ?></td>
            </tr>
        </table>

    </div><!-- /.box-body -->
</div>

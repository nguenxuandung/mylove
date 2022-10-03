<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Invoice[]|\Cake\Collection\CollectionInterface $invoices
 */
$this->assign('title', __('Manage Invoices'));
$this->assign('description', '');
$this->assign('content_title', __('Manage Invoices'));
?>

<div class="box box-primary">
    <div class="box-body no-padding">

        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <tr>
                    <th><?= $this->Paginator->sort('id', __('ID')); ?></th>
                    <th><?= $this->Paginator->sort('status', __('Status')); ?></th>
                    <th><?= __('Description'); ?></th>
                    <th><?= __('Username'); ?></th>
                    <th><?= $this->Paginator->sort('amount', __('Amount')); ?></th>
                    <th><?= $this->Paginator->sort('payment_method', __('Payment Method')); ?></th>
                    <th><?= $this->Paginator->sort('paid_date', __('Paid date')); ?></th>
                    <th><?= $this->Paginator->sort('created', __('Created')); ?></th>
                    <th><?php echo __('Actions') ?></th>
                </tr>

                <?php foreach ($invoices as $invoice) : ?>
                    <tr>
                        <td><?= $this->Html->link($invoice->id, ['action' => 'view', $invoice->id]); ?></td>
                        <td><?= h(invoice_statuses($invoice->status)); ?></td>
                        <td><?= h($invoice->description); ?></td>
                        <td>
                            <?= $this->Html->link(
                                $invoice->user->username,
                                ['controller' => 'Users', 'action' => 'view', $invoice->user->id]
                            ); ?>
                        </td>
                        <td><?= display_price_currency($invoice->amount); ?></td>
                        <td><?= (isset(get_payment_methods()[$invoice->payment_method])) ?
                                get_payment_methods()[$invoice->payment_method] : $invoice->payment_method ?></td>
                        <td><?= display_date_timezone($invoice->paid_date) ?></td>
                        <td><?= display_date_timezone($invoice->created) ?></td>
                        <td>
                            <?= $this->Html->link(
                                __('View'),
                                ['action' => 'view', $invoice->id],
                                ['class' => 'btn btn-primary btn-xs']
                            ); ?>

                            <?php if ($invoice->status != 1) : ?>
                                <?= $this->Form->postLink(
                                    __('Mark as paid'),
                                    ['action' => 'markPaid', $invoice->id],
                                    ['confirm' => __('Are you sure?'), 'class' => 'btn btn-success btn-xs']
                                );
                                ?>
                            <?php endif; ?>

                            <?= $this->Form->postLink(
                                __('Delete'),
                                ['action' => 'delete', $invoice->id],
                                ['confirm' => __('Are you sure?'), 'class' => 'btn btn-danger btn-xs']
                            );
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php unset($invoice); ?>
            </table>
        </div>

    </div><!-- /.box-body -->
</div>

<ul class="pagination">
    <?php
    $this->Paginator->setTemplates([
        'ellipsis' => '<li><a href="javascript: void(0)">...</a></li>',
    ]);

    if ($this->Paginator->hasPrev()) {
        echo $this->Paginator->prev('«');
    }

    echo $this->Paginator->numbers([
        'modulus' => 4,
        'first' => 2,
        'last' => 2,
    ]);

    if ($this->Paginator->hasNext()) {
        echo $this->Paginator->next('»');
    }
    ?>
</ul>

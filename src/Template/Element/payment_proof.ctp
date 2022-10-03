<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Withdraw[]|\Cake\Collection\CollectionInterface $withdraws
 */

$withdrawal_methods = array_column_polyfill(get_withdrawal_methods(), 'name', 'id');
?>

<div class="table-responsive">
    <table class="table table-hover table-striped">
        <thead>
        <tr>
            <th><?= __('Date') ?></th>
            <th><?= __('Username') ?></th>
            <th><?= __('Amount') ?></th>
            <th><?= __('Method') ?></th>
        </tr>
        </thead>
        <?php foreach ($withdraws as $withdraw) : ?>
            <tr>
                <td><?= display_date_timezone($withdraw->created); ?></td>
                <td><?= h(substr($withdraw->user->username, 0, 3)) . '******' ?></td>
                <td><?= display_price_currency($withdraw->amount); ?></td>
                <td><?= (isset($withdrawal_methods[$withdraw->method])) ?
                        $withdrawal_methods[$withdraw->method] : $withdraw->method ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
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

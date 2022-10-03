<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $referrals
 */
$this->assign('title', __('All Referrals'));
$this->assign('description', '');
$this->assign('content_title', __('All Referrals'));

?>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-exchange"></i> <?= __('All Referrals') ?></h3>
    </div><!-- /.box-header -->
    <div class="box-body no-padding">

        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <tr>
                    <th><?= __('Username'); ?></th>
                    <th><?= __('Referred By'); ?></th>
                    <th><?= __('Date'); ?></th>
                </tr>
                <!-- Here is where we loop through our $posts array, printing out post info -->
                <?php foreach ($referrals as $referral): ?>
                    <tr>
                        <td><?= $this->Html->link($referral->username,
                                ['controller' => 'Users', 'action' => 'view', $referral->id]); ?></td>
                        <td><?= $this->Html->link($referral->referred_by_username,
                                ['controller' => 'Users', 'action' => 'view', $referral->referred_by]); ?></td>
                        <td><?= display_date_timezone($referral->created) ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php unset($referral); ?>
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

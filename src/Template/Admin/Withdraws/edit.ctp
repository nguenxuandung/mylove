<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Withdraw $withdraw
 */
$this->assign('title', __('Withdraw #{0}', $withdraw->id));
$this->assign('description', '');
$this->assign('content_title', __('Withdraw #{0}', $withdraw->id));
?>

<div class="box box-primary">
    <div class="box-body">
        <?= $this->Form->create($withdraw); ?>
        <table class="table table-hover table-striped">
            <tr>
                <td><?= __('Id') ?></td>
                <td><?= $withdraw->id ?></td>
            </tr>
            <tr>
                <td><?= __('Status') ?></td>
                <td>


                    <?= $this->Form->hidden('id'); ?>

                    <?=
                    $this->Form->control('status', [
                        'label' => false,
                        'options' => [
                            2 => __('Pending'),
                            1 => __('Approved'),
                            3 => __('Complete'),
                            4 => __('Cancelled'),
                            5 => __('Returned')
                        ],
                        'empty' => __('Choose'),
                        'class' => 'form-control'
                    ]);
                    ?>

                </td>
            </tr>
            <tr>
                <td><?= __('Amount') ?></td>
                <td><?= $withdraw->amount ?></td>
            </tr>
            <tr>
                <td><?= __('User') ?></td>
                <td><?= $this->Html->link($withdraw->user->username,
                        array('controller' => 'Users', 'action' => 'view', $withdraw->user->id)); ?></td>
            </tr>
            <tr>
                <td><?= __('Date') ?></td>
                <td><?= display_date_timezone($withdraw->created); ?></td>
            </tr>
        </table>
        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']); ?>
        <?= $this->Form->end(); ?>

    </div><!-- /.box-body -->
</div>


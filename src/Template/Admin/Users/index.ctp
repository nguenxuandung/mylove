<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 * @var mixed $plans
 */
?>
<?php
$this->assign('title', __('Manage Users'));
$this->assign('description', '');
$this->assign('content_title', __('Manage Users'));
?>

<?php

$yes_no = [
    1 => __('Yes'),
    0 => __('No'),
];

$statuses = [
    1 => __('Active'),
    2 => __('Pending'),
    3 => __('Inactive'),
]

?>

<div class="box box-solid">
    <div class="box-body">
        <?php
        // The base url is the url where we'll pass the filter parameters
        $base_url = ['controller' => 'Users', 'action' => 'index'];

        echo $this->Form->create(null, [
            'url' => $base_url,
            'class' => 'form-inline',
        ]);
        ?>

        <?=
        $this->Form->control('Filter.id', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'placeholder' => __('Id'),
        ]);
        ?>

        <?=
        $this->Form->control('Filter.status', [
            'label' => false,
            'options' => $statuses,
            'empty' => __('Status'),
            'class' => 'form-control',
        ]);
        ?>

        <?=
        $this->Form->control('Filter.plan_id', [
            'label' => false,
            'options' => $plans,
            'empty' => __('Plan'),
            'class' => 'form-control',
        ]);
        ?>

        <?=
        $this->Form->control('Filter.disable_earnings', [
            'label' => false,
            'options' => $yes_no,
            'empty' => __('Disable Earnings'),
            'class' => 'form-control',
        ]);
        ?>

        <?=
        $this->Form->control('Filter.username', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'placeholder' => __('Username'),
        ]);
        ?>

        <?=
        $this->Form->control('Filter.email', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'placeholder' => __('Email'),
        ]);
        ?>

        <?=
        $this->Form->control('Filter.country', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'placeholder' => __('Country'),
        ]);
        ?>

        <?=
        $this->Form->control('Filter.login_ip', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'placeholder' => __('Login IP'),
        ]);
        ?>

        <?=
        $this->Form->control('Filter.register_ip', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'placeholder' => __('Register IP'),
        ]);
        ?>

        <?=
        $this->Form->control('Filter.other_fields', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'placeholder' => __('First name, last name, address'),
        ]);
        ?>

        <?= $this->Form->button(__('Filter'), ['class' => 'btn btn-default btn-sm']); ?>

        <?= $this->Html->link(__('Reset'), $base_url, ['class' => 'btn btn-link btn-sm']); ?>

        <?= $this->Form->end(); ?>

    </div>
</div>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-align-center"></i> <?= __('All Users') ?></h3>
    </div><!-- /.box-header -->
    <div class="box-body no-padding">

        <div class="table-responsive">
            <?= $this->Form->create(null, [
                'url' => ['controller' => 'Users', 'action' => 'mass'],
            ]);
            ?>
            <table class="table table-hover table-striped">
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th><?= $this->Paginator->sort('id', __('Id')); ?></th>
                    <th><?= $this->Paginator->sort('username', __('Username')); ?></th>
                    <th><?= $this->Paginator->sort('status', __('Status')); ?></th>
                    <th><?= __('Plan') ?></th>
                    <th><?= __('Expiration') ?></th>
                    <th><?= $this->Paginator->sort('disable_earnings', __('Disable Earnings')); ?></th>
                    <th><?= $this->Paginator->sort('email', __('Email')); ?></th>
                    <th><?= $this->Paginator->sort('login_ip', __('Login IP')); ?></th>
                    <th><?= $this->Paginator->sort('register_ip', __('Register IP')); ?></th>
                    <th><?= $this->Paginator->sort('modified', __('modified')); ?></th>
                    <th><?= $this->Paginator->sort('created', __('Created')); ?></th>
                    <th>
                        <div class="form-inline">
                            <?=
                            $this->Form->control('action', [
                                'label' => false,
                                'options' => [
                                    '' => __('Mass Action'),
                                    'activate' => __('Activate'),
                                    'pending' => __('Pending'),
                                    'deactivate' => __('Deactivate'),
                                    'resendActivation' => __('Resend Activation Email'),
                                    'delete' => __('Delete with all data'),
                                ],
                                'class' => 'form-control input-sm',
                                'required' => true,
                                'templates' => [
                                    'inputContainer' => '{{content}}',
                                ],
                            ]);
                            ?>

                            <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-default btn-sm']); ?>
                        </div>
                    </th>
                </tr>

                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <?= $this->Form->checkbox('ids[]', [
                                'hiddenField' => false,
                                'label' => false,
                                'value' => $user->id,
                                'class' => 'allcheckbox',
                            ]);
                            ?>
                        </td>
                        <td><?= $user->id ?></td>
                        <td>
                            <?= $this->Html->link($user->username,
                                ['controller' => 'users', 'action' => 'view', $user->id, 'prefix' => 'admin']);
                            ?>
                        </td>
                        <td><?= $statuses[$user->status]; ?></td>
                        <td><?= h($user->plan->title); ?></td>
                        <td><?= display_date_timezone($user->expiration); ?></td>
                        <td><?= $yes_no[$user->disable_earnings]; ?></td>
                        <td><?= $user->email; ?></td>
                        <td><?= $user->login_ip; ?></td>
                        <td><?= $user->register_ip; ?></td>
                        <td><?= display_date_timezone($user->modified); ?></td>
                        <td><?= display_date_timezone($user->created); ?></td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-block btn-default dropdown-toggle"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?= __("Select Action") ?> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <?= $this->Html->link(
                                            __('View'),
                                            ['action' => 'view', $user->id]
                                        );
                                        ?>
                                    </li>
                                    <li>
                                        <?= $this->Html->link(
                                            __('Edit'),
                                            ['action' => 'edit', $user->id]
                                        );
                                        ?>
                                    </li>
                                    <li>
                                        <?= $this->Html->link(
                                            __('Send a message'),
                                            ['action' => 'message', $user->id]
                                        );
                                        ?>
                                    </li>

                                    <?php if ($user->status === 2) : ?>
                                        <li>
                                            <?= $this->Html->link(
                                                __('Resend Activation Email'),
                                                [
                                                    'action' => 'resendActivation',
                                                    $user->id,
                                                    'token' => $this->request->getParam('_csrfToken'),
                                                ],
                                                ['confirm' => __('Are you sure?')]
                                            );
                                            ?>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ($user->status === 1) : ?>
                                        <li>
                                            <?= $this->Html->link(
                                                __('Deactivate'),
                                                [
                                                    'action' => 'deactivate',
                                                    $user->id,
                                                    'token' => $this->request->getParam('_csrfToken'),
                                                ],
                                                ['confirm' => __('Are you sure?')]
                                            );
                                            ?>
                                        </li>

                                        <li>
                                            <?= $this->Html->link(
                                                __('Login as user'),
                                                [
                                                    'action' => 'loginAsUser',
                                                    $user->id,
                                                ]
                                            );
                                            ?>
                                        </li>

                                        <li>
                                            <?= $this->Html->link(
                                                __('Withdrawal Request'),
                                                [
                                                    'controller' => 'Withdraws',
                                                    'action' => 'request',
                                                    $user->id,
                                                    'token' => $this->request->getParam('_csrfToken'),
                                                ],
                                                ['confirm' => __('Are you sure?')]
                                            );
                                            ?>
                                        </li>
                                    <?php endif; ?>

                                    <li>
                                        <?= $this->Html->link(
                                            __('Export'),
                                            [
                                                'action' => 'dataExport',
                                                $user->id,
                                                'token' => $this->request->getParam('_csrfToken'),
                                            ],
                                            ['confirm' => __('Are you sure?')]
                                        );
                                        ?>
                                    </li>

                                    <li role="separator" class="divider"></li>

                                    <li>
                                        <?= $this->Html->link(
                                            __('Delete'),
                                            [
                                                'action' => 'delete',
                                                $user->id,
                                                'token' => $this->request->getParam('_csrfToken'),
                                            ],
                                            ['confirm' => __('Are you sure?')]
                                        );
                                        ?>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?= $this->Form->end(); ?>
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

<?php $this->start('scriptBottom'); ?>
<script>
    $('#select-all').change(function() {
        $('.allcheckbox').prop('checked', $(this).prop('checked'));
    });
    $('.allcheckbox').change(function() {
        if ($(this).prop('checked') == false) {
            $('#select-all').prop('checked', false);
        }
        if ($('.allcheckbox:checked').length == $('.allcheckbox').length) {
            $('#select-all').prop('checked', true);
        }
    });
</script>
<?php $this->end(); ?>

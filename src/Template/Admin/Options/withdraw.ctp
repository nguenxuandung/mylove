<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $options
 * @var array $settings
 * @var object $withdraw_methods
 */
$this->assign('title', __('Withdraw Settings'));
$this->assign('description', '');
$this->assign('content_title', __('Withdraw Settings'));
?>

<div class="box box-primary">
    <div class="box-body">
        <?= $this->Form->create($options, [
            'id' => 'form-settings',
            'onSubmit' =>
                "save_settings.disabled=true; save_settings.innerHTML='" . __('Saving ...') . "'; return true;",
        ]); ?>

        <div class="row">
            <div class="col-sm-2"><?= __('Enable Withdraw') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['enable_withdraw']['id'] . '.value', [
                    'label' => false,
                    'options' => [
                        0 => __('No'),
                        1 => __('Yes'),
                    ],
                    'value' => $settings['enable_withdraw']['value'],
                    'class' => 'form-control',
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Withdraw Business Days') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['withdraw_days']['id'] . '.value', [
                    'label' => false,
                    'type' => 'number',
                    'min' => 1,
                    'step' => 1,
                    'value' => $settings['withdraw_days']['value'],
                    'class' => 'form-control',
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Minimum Withdrawal Amount') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['minimum_withdrawal_amount']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'text',
                    'value' => $settings['minimum_withdrawal_amount']['value'],
                ]);
                ?>
                <span class="help-block">
                    <?= __('This amount will be displayed only on home page.') ?>
                </span>
            </div>
        </div>

        <legend><?= __('Money Wallet Settings') ?></legend>

        <div class="row">
            <div class="col-sm-2"><?= __('Wallet Minimum Withdrawal Amount') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['wallet_withdrawal_amount']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'text',
                    'value' => $settings['wallet_withdrawal_amount']['value'],
                ]);
                ?>
            </div>
        </div>

        <?= $this->Form->button(__('Save'), ['name' => 'save_settings', 'class' => 'btn btn-primary']); ?>
        <?= $this->Form->end(); ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-4">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"><?= __('Add new withdraw method') ?></h3>
            </div>
            <div class="box-body">
                <?= $this->Form->create(null, [
                    'url' => ['controller' => 'Options', 'action' => 'withdrawItem'],
                ]); ?>

                <?=
                $this->Form->control('name', [
                    'label' => __('Name'),
                    'class' => 'form-control',
                    'required' => true,
                ])
                ?>

                <?=
                $this->Form->control('id', [
                    'label' => __('ID'),
                    'class' => 'form-control',
                    'required' => true,
                ])
                ?>

                <?=
                $this->Form->control('status', [
                    'label' => __('Status'),
                    'options' => [
                        1 => __('Enabled'),
                        0 => __('Disabled'),
                    ],
                    'class' => 'form-control',
                ]);
                ?>

                <?=
                $this->Form->control('amount', [
                    'label' => __('Minimum Withdrawal Amount'),
                    'class' => 'form-control',
                    'type' => 'number',
                    'min' => 0,
                    'step' => 'any',
                ])
                ?>

                <?=
                $this->Form->control('image', [
                    'label' => __('Image'),
                    'class' => 'form-control',
                ])
                ?>

                <?=
                $this->Form->control('description', [
                    'label' => __('Description'),
                    'class' => 'form-control',
                    'type' => 'textarea',
                ])
                ?>

                <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']); ?>
                <?= $this->Form->end(); ?>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"><?= __('Withdraw Methods') ?></h3>
            </div>
            <div class="box-body">
                <?= $this->Form->create(null, [
                    'id' => 'form-settings',
                    'url' => [
                        'controller' => 'Options',
                        'action' => 'withdrawMethods',
                    ],
                    'onSubmit' => "save_settings.disabled=true; save_settings.innerHTML='" . __('Saving ...') . "'; return true;",
                ]); ?>

                <ul id="sortable" class="list-group">
                    <?php foreach (json_decode($withdraw_methods->value) as $item) : ?>
                        <li class="ui-state-default list-group-item" data-id="<?= $item->id ?>">
                            <i class="fa fa-arrows"></i> <?= $item->name ?>
                            <a data-toggle="collapse" href="#menu-item-<?= $item->id ?>"
                               class="btn btn-link btn btn-sm no-padding pull-right">
                                <?= __('Edit') ?>
                            </a>
                            <div id="menu-item-<?= $item->id ?>" class="collapse" style="margin-top: 20px;">
                                <?=
                                $this->Form->control($item->id . '[id]', [
                                    'label' => __('ID'),
                                    'class' => 'form-control',
                                    'value' => $item->id,
                                    'required' => true,
                                ])
                                ?>

                                <?=
                                $this->Form->control($item->id . '[name]', [
                                    'label' => __('Name'),
                                    'class' => 'form-control',
                                    'value' => $item->name,
                                ])
                                ?>

                                <?=
                                $this->Form->control($item->id . '[status]', [
                                    'label' => __('Status'),
                                    'options' => [
                                        1 => __('Enabled'),
                                        0 => __('Disabled'),
                                    ],
                                    'value' => $item->status,
                                    'class' => 'form-control',
                                ]);
                                ?>

                                <?=
                                $this->Form->control($item->id . '[amount]', [
                                    'label' => __('Minimum Withdrawal Amount'),
                                    'class' => 'form-control',
                                    'value' => $item->amount,
                                    'type' => 'number',
                                    'min' => 0,
                                    'step' => 'any',
                                ])
                                ?>

                                <?=
                                $this->Form->control($item->id . '[image]', [
                                    'label' => __('Image'),
                                    'class' => 'form-control',
                                    'value' => $item->image,
                                ])
                                ?>

                                <?=
                                $this->Form->control($item->id . '[description]', [
                                    'label' => __('Description'),
                                    'class' => 'form-control',
                                    'type' => 'textarea',
                                    'value' => (isset($item->description)) ? $item->description : '',
                                ])
                                ?>

                                <div class="clearfix">
                                    <a href="#" class="item-delete btn btn-danger btn-xs pull-right">
                                        <?= __('Delete') ?></a>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <?= $this->Form->button(__('Submit'), ['name' => 'save_settings', 'class' => 'btn btn-primary']); ?>
                <?= $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>

<?php $this->start('scriptBottom'); ?>
<script src="https://cdn.jsdelivr.net/npm/jquery-ui-dist@1.12.1/jquery-ui.min.js"></script>
<style>
    #sortable .list-group-item {
        cursor: grabbing;
    }

    #sortable .list-group-item > i {
        margin-right: 15px;
    }
</style>
<script>
  $(function() {
    $('#sortable').sortable({
      //placeholder: "ui-state-highlight",
      items: '> li',
      cursor: 'move',
      opacity: 0.6,
    }).disableSelection();
    //$( "#sortable" ).disableSelection();
  });

  $('.item-delete').on('click', function(e) {
    e.preventDefault();
    if (confirm('Are you sure?')) {
      $(this).closest('li[data-id]').remove();
    }
    e.returnValue = false;
    return false;
  });
</script>
<?php $this->end(); ?>

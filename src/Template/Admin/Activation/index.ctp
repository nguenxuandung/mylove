<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', __('License Activation'));
$this->assign('description', '');
$this->assign('content_title', __('License Activation'));
?>

<div class="box box-primary">
    <div class="box-body">

        <?= $this->Form->create(null, [
            'url' => ['controller' => 'Activation', 'action' => 'index']
        ]); ?>

        <?=
        $this->Form->control('personal_token', [
            'label' => __('Personal Token'),
            'class' => 'form-control',
            'type' => 'text',
            'default' => get_option('personal_token', ''),
            'required' => 'required'
        ]);
        ?>

        <span class="help-block"><?= __('Click on this URL {0} to learn how to generate a personal token.',
                '<a href="https://mightyscripts.freshdesk.com/support/solutions/articles/5000708895-how-to-generate-a-personal-token-" target="_blank">https://mightyscripts.freshdesk.com/support/solutions/articles/5000708895-how-to-generate-a-personal-token-</a>') ?></span>

        <?=
        $this->Form->control('purchase_code', [
            'label' => __('Purchase Code'),
            'class' => 'form-control',
            'type' => 'text',
            'default' => get_option('purchase_code', ''),
            'required' => 'required'
        ]);
        ?>

        <span class="help-block"><?= __('Click on this URL {0} to learn how to get your purchase code.',
                '<a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank">https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-</a>') ?></span>

        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']); ?>
        <?= $this->Form->end(); ?>

    </div><!-- /.box-body -->
</div>

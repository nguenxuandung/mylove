<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', __('Step 2: Build database'));

?>


<div class="install">

    <p>
        <?= __('Create tables and load initial data'); ?>
    </p>
    <div class="form-actions">
        <?php
        echo $this->Html->link(__('Build database'), array(
            'action' => 'data',
            '?' => array('run' => 1)
        ), array(
            'label' => 'Submit',
            'class' => 'btn btn-success',
            'onclick' => '$(this).attr(\'disabled\', \'disabled\').find(\'i\').addClass(\'icon-spin icon-spinner\');'
        ));

        ?>
    </div>
</div>


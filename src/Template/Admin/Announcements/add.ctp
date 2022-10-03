<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Announcement $announcement
 */
$this->assign('title', __('Add Announcement'));
$this->assign('description', '');
$this->assign('content_title', __('Add Announcement'));
?>

<div class="box box-primary">
    <div class="box-body">

        <?= $this->Form->create($announcement); ?>

        <?=
        $this->Form->control('title', [
            'label' => __('Title'),
            'class' => 'form-control',
            'type' => 'text'
        ]);

        ?>

        <?=
        $this->Form->control('published', [
            'label' => __('Published'),
            'options' => [
                '1' => __('Yes'),
                '0' => __('No')
            ],
            'class' => 'form-control'
        ]);

        ?>

        <?=
        $this->Form->control('content', [
            'label' => __('Content'),
            'class' => 'form-control text-editor',
            'type' => 'textarea'
        ]);

        ?>

        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']); ?>

        <?= $this->Form->end(); ?>
    </div>
</div>

<?php $this->start('scriptBottom'); ?>

<script src="//cdn.ckeditor.com/4.10.1/full/ckeditor.js"></script>
<script>
  $(document).ready(function() {
    CKEDITOR.replaceClass = 'text-editor';
    CKEDITOR.config.allowedContent = true;
    CKEDITOR.dtd.$removeEmpty['span'] = false;
    CKEDITOR.dtd.$removeEmpty['i'] = false;
  });
</script>

<?php $this->end(); ?>

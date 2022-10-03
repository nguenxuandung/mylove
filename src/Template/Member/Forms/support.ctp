<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Form\ContactForm $contact
 */
?>
<?php
$this->assign('title', __('Support'));
$this->assign('description', '');
$this->assign('content_title', __('Support'));

?>

<div class="box box-primary">
    <div class="box-body">

        <?= $this->Form->create($contact); ?>

        <?=
        $this->Form->control('name', [
            'label' => __('Name'),
            'class' => 'form-control',
            'type' => 'text'
        ]);
        ?>

        <?=
        $this->Form->control('subject', [
            'label' => __('Subject'),
            'class' => 'form-control',
            'type' => 'text'
        ]);
        ?>

        <?=
        $this->Form->control('email', [
            'label' => __('Email'),
            'class' => 'form-control',
            'type' => 'email'
        ]);
        ?>

        <?=
        $this->Form->control('message', [
            'label' => __('Message'),
            'class' => 'form-control',
            'type' => 'textarea'
        ]);
        ?>

        <div class="form-group">
            <?= $this->Form->control('accept', [
                'type' => 'checkbox',
                'label' => "<b>" . __(
                        "I consent to collect my name and email. For more details check our {0}.",
                        "<a href='" . $this->Url->build('/') . 'pages/privacy' . "' target='_blank'>" .
                        __('Privacy Policy') . "</a>"
                    ) . "</b>",
                'escape' => false
            ]) ?>
        </div>

        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']); ?>

        <?= $this->Form->end(); ?>

    </div>
</div>

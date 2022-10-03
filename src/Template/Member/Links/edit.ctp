<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Link $link
 * @var \App\Model\Entity\Plan $logged_user_plan
 */
$this->assign('title', __('Edit Link: {0}', $link->alias));
$this->assign('description', '');
$this->assign('content_title', __('Edit Link: {0}', $link->alias));
?>

<div class="box box-primary">
    <div class="box-body">

        <?= $this->Form->create($link); ?>

        <?= $this->Form->hidden('id'); ?>

        <?=
        $this->Form->control('url', [
            'label' => __('Long URL'),
            'class' => 'form-control',
            'type' => 'url',
            'disabled' => ((bool)$logged_user_plan->edit_long_url) ? false : true,
        ]);
        ?>

        <?=
        $this->Form->control('title', [
            'label' => __('Title'),
            'class' => 'form-control',
            'type' => 'text',
        ]);
        ?>

        <?=
        $this->Form->control('description', [
            'label' => __('Description'),
            'class' => 'form-control',
            'type' => 'textarea',
        ]);
        ?>

        <?php if ((bool)$logged_user_plan->link_expiration) {
            echo $this->Form->control('expiration', [
                'label' => __('Expiration date'),
                'class' => 'form-control',
                'type' => 'datetime',
                'default' => null,
                'empty' => true,
                'value' => null,
                'minYear' => date('Y'),
                'maxYear' => date('Y') + 10,
                'orderYear' => 'asc',
            ]);
        }
        ?>

        <?php
        $ads_options = get_allowed_ads();

        if (count($ads_options) > 1) {
            echo $this->Form->control('ad_type', [
                'label' => __('Advertising Type'),
                'options' => $ads_options,
                'default' => get_option('member_default_advert', 1),
                //'empty'   => __( 'Choose' ),
                'class' => 'form-control input-sm',
            ]);
        } else {
            echo $this->Form->hidden('type', ['value' => get_option('member_default_redirect', 1)]);
        }
        ?>

        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']); ?>

        <?= $this->Form->end(); ?>
    </div>
</div>

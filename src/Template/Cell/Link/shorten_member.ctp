<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Plan $plan
 */
?>
<?=
$this->Form->create(null, [
    'url' => ['controller' => 'Links', 'action' => 'shorten', 'prefix' => false],
    'id' => 'shorten',
]);
?>

<?php
$this->Form->setTemplates([
    'inputContainer' => '{{content}}',
    'error' => '{{content}}',
    'inputContainerError' => '{{content}}',
]);
?>
<div class="form-group">
    <?=
    $this->Form->control('url', [
        'label' => false,
        'type' => 'text',
        'placeholder' => __('Your URL Here'),
        'required' => 'required',
        'class' => 'form-control',
    ]);
    ?>
</div>

<div class="row">

    <?php if ($plan->alias) : ?>
        <div class="col-sm-3">
            <div class="form-group">
                <?=
                $this->Form->control('alias', [
                    'label' => __('Alias'),
                    'type' => 'text',
                    'placeholder' => __('Alias'),
                    'class' => 'form-control input-sm',
                ]);
                ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($plan->multi_domains) : ?>
        <div class="col-sm-3">
            <?php if (count(get_multi_domains_list())) : ?>
                <div class="form-group">
                    <?=
                    $this->Form->control('domain', [
                        'label' => __('Domain'),
                        'options' => get_multi_domains_list(),
                        'default' => '',
                        'empty' => get_default_short_domain(),
                        'class' => 'form-control input-sm',
                    ]);
                    ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($plan->link_expiration) : ?>
        <div class="col-sm-3 link-expiration">
            <style>
                .link-expiration label {
                    display: block;
                }
            </style>
            <div class="form-group">
                <?=
                $this->Form->control('expiration', [
                    'label' => __('Expiration date'),
                    'class' => 'form-control input-sm',
                    'type' => 'datetime',
                    'default' => null,
                    'empty' => true,
                    'value' => null,
                    'minYear' => date('Y'),
                    'maxYear' => date('Y') + 10,
                    'orderYear' => 'asc',
                ]);
                ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="col-sm-3">
        <div class="form-group">
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
                echo $this->Form->hidden('ad_type', ['value' => get_option('member_default_advert', 1)]);
            }
            ?>
        </div>
    </div>

</div>

<?= $this->Form->button(__('Shorten'), ['class' => 'btn btn-submit btn-primary btn-xs']); ?>

<?= $this->Form->end(); ?>

<div class="shorten add-link-result"></div>

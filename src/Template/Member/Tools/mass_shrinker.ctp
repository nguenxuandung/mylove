<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Link $link
 * @var mixed $results
 */
$this->assign('title', __('Mass Shrinker'));
$this->assign('description', '');
$this->assign('content_title', __('Mass Shrinker'));

?>

<div class="box box-primary">
    <div class="box-body">

        <p><?= __('Enter up to {0} URLs (one per line) to be shrunk and added to your account',
                get_option('mass_shrinker_limit', 20)) ?></p>

        <p><?= __('Note: The \'Mass Shrinker\' can be disabled to your account if abused. Only create links that you will actually use.') ?></p>


        <?php if (isset($results) && is_array($results)) : ?>
            <div class="well">
                <?php foreach ($results as $result) : ?>
                    <p><?= h($result['url']) ?></p>
                    <?php if ($result['short'] != 'error'): ?>
                        <p class="text-success" style="font-weight: bold;"><?= get_short_url($result['short'],
                                $result['domain']); ?></p>
                    <?php else : ?>
                        <p class="text-danger" style="font-weight: bold;"><?= __('It is not a valid URL.') ?></p>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <p><?= __("All Short Links") ?></p>
            <div class="well">
                <p class="text-success" style="font-weight: bold;">
                    <?php foreach ($results as $result) : ?>
                        <?php if ($result['short'] != 'error'): ?>
                            <?= get_short_url($result['short'], $result['domain']); ?><br>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </p>
            </div>
            <a href="<?= $this->Url->build(['action' => 'mass-shrinker']); ?>" class="btn btn-info btn-s-xs"><i
                        class="fa fa-chevron-circle-left"></i> <?= __('Shorten More Links') ?></a>
        <?php else : ?>
            <?= $this->Form->create($link); ?>

            <?=
            $this->Form->control('urls', [
                'label' => false,
                'class' => 'form-control',
                'type' => 'textarea'
            ]);

            ?>

            <?php
            $ads_options = get_allowed_ads();

            if (count($ads_options) > 1) {
                echo $this->Form->control('ad_type', [
                    'label' => __('Advertising Type'),
                    'options' => $ads_options,
                    'default' => get_option('member_default_advert', 1),
                    //'empty'   => __( 'Choose' ),
                    'class' => 'form-control'
                ]);
            } else {
                echo $this->Form->hidden('ad_type', ['value' => get_option('member_default_advert', 1)]);
            }

            ?>

            <?= $this->Form->button(__('Shorten'), ['class' => 'btn btn-primary']); ?>

            <?= $this->Form->end(); ?>
        <?php endif; ?>


    </div>
</div>

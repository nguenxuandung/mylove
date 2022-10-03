<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Plan[]|\Cake\Collection\CollectionInterface $plans
 * @var \App\Model\Entity\User $user
 */
?>
<?php
$this->assign('title', __('Change Your Plan'));
$this->assign('description', '');
$this->assign('content_title', __('Change Your Plan'));
?>

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-refresh"></i> <?= __('Change Your Plan') ?></h3>
    </div><!-- /.box-header -->
    <div class="box-body">

        <style>

            .panel-pricing {
                -moz-transition: all .3s ease;
                -o-transition: all .3s ease;
                -webkit-transition: all .3s ease;
            }

            .panel-pricing:hover {
                box-shadow: 0px 0px 30px rgba(0, 0, 0, 0.2);
            }

            .panel-pricing .panel-heading {
                padding: 10px 10px 15px 10px;
            }

            .panel-pricing .list-group-item {
                color: #777777;
                border-bottom: 1px solid rgba(250, 250, 250, 0.5);
            }

            .panel-pricing .list-group-item:last-child {
                border-bottom-right-radius: 0px;
                border-bottom-left-radius: 0px;
            }

            .panel-pricing .list-group-item:first-child {
                border-top-right-radius: 0px;
                border-top-left-radius: 0px;
            }

            .panel-pricing .panel-body {
                font-size: 18px;
                color: #777777;
                padding: 20px;
                margin: 0px;
            }

        </style>

        <div class="row">
            <?php $i = 1; ?>
            <?php foreach ($plans as $plan) : ?>
                <?php
                if ($plan->id != 1 && $plan->monthly_price == 0 && $plan->yearly_price == 0) {
                    continue;
                }
                ?>
                <div class="col-md-4 text-center">
                    <div class="panel panel-primary panel-pricing">
                        <div class="panel-heading">
                            <h3><?= h($plan->title) ?></h3>
                        </div>
                        <div class="panel-body text-center">
                            <p>
                                <strong>
                                    <?php if ($plan->id === 1) : ?>
                                        <?= __("Free"); ?>
                                    <?php else : ?>
                                        <?php if ($plan->monthly_price > 0) : ?>
                                            <?= display_price_currency($plan->monthly_price) . ' ' . __('/ Month') ?>
                                        <?php endif; ?>
                                        <?php if ($plan->monthly_price > 0 && $plan->yearly_price > 0) : ?>
                                            <?= __("-") ?>
                                        <?php endif; ?>
                                        <?php if ($plan->yearly_price > 0) : ?>
                                            <?= display_price_currency($plan->yearly_price) . ' ' . __('/ Year') ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </strong>
                            </p>
                        </div>

                        <?= $plan->description ?>

                        <div class="panel-footer">
                            <div class="btn-group">

                                <?php if ($plan->id === 1) : ?>
                                    <button type="button" class="btn btn-lg btn-block btn-primary" disabled>
                                        <?= __("Free Plan") ?>
                                    </button>
                                <?php else : ?>
                                    <button type="button" class="btn btn-lg btn-block btn-primary dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                        <?php if ($plan->id === $user->plan_id) : ?>
                                            <?= __("Renew") ?>
                                        <?php else : ?>
                                            <?= __("Buy Now") ?>
                                        <?php endif; ?>
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <?php if ($plan->monthly_price > 0) : ?>
                                            <li><?= $this->Form->postLink(display_price_currency($plan->monthly_price) .
                                                    ' ' . __('Monthly'),
                                                    ['action' => 'PayPlan', $plan->id, 'm']); ?></li>
                                        <?php endif; ?>

                                        <?php if ($plan->monthly_price > 0 && $plan->yearly_price > 0) : ?>
                                            <li role="separator" class="divider"></li>
                                        <?php endif; ?>

                                        <?php if ($plan->yearly_price > 0) : ?>
                                            <li><?= $this->Form->postLink(display_price_currency($plan->yearly_price) .
                                                    ' ' . __('Yearly'),
                                                    ['action' => 'PayPlan', $plan->id, 'y']); ?></li>
                                        <?php endif; ?>
                                    </ul>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
                <?php
                if ($i % 3 == 0) {
                    echo '</div><div class="row">';
                }
                $i++;
                ?>
            <?php endforeach; ?>
        </div>

    </div><!-- /.box-body -->
</div>

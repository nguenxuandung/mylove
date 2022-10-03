<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $logged_user
 * @var \App\Model\Entity\Plan $logged_user_plan
 * @var \App\Model\Entity\Link $link
 * @var \App\Model\Entity\Announcement[]|\Cake\Collection\CollectionInterface $announcements
 * @var mixed $CurrentMonthDays
 * @var mixed $referral_earnings
 * @var mixed $total_earnings
 * @var mixed $total_views
 * @var mixed $year_month
 */
?>
<?php
$this->assign('title', __('Dashboard'));
$this->assign('description', '');
$this->assign('content_title', __('Dashboard'));
?>

    <div class="text-center">
        <div style="display: inline-block;">
            <?=
            $this->Form->create(null, [
                'type' => 'get',
                'url' => ['controller' => 'Users', 'action' => 'dashboard'],
            ]);
            ?>

            <?=
            $this->Form->control('month', [
                'label' => false,
                'options' => $year_month,
                'value' => ($this->request->getQuery('month')) ? h($this->request->getQuery('month')) : '',
                'class' => 'form-control input-lg',
                'onchange' => 'this.form.submit();',
                'style' => 'width: 300px;',
            ]);
            ?>

            <?= $this->Form->button(__('Submit'), ['class' => 'hidden']); ?>

            <?= $this->Form->end(); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3><?= $total_views ?></h3>

                    <p><?= __('Total Views') ?></p>
                </div>
                <div class="icon">
                    <i class="fa fa-bar-chart"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3><?= display_price_currency($total_earnings); ?></h3>

                    <p><?= __('Total Earnings') ?></p>
                </div>
                <div class="icon">
                    <i class="fa fa-shopping-bag"></i>
                </div>
            </div>
        </div>

        <?php if ((bool)get_option('enable_referrals', 1)) : ?>
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3><?= display_price_currency($referral_earnings); ?></h3>

                        <p><?= __('Referral Earnings') ?></p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-exchange"></i>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>
                        <?= (!empty($total_views)) ? display_price_currency($total_earnings / $total_views * 1000) : 0 ?>
                    </h3>

                    <p><?= __('Average CPM') ?></p>
                </div>
                <div class="icon">
                    <i class="fa fa-usd"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
    </div>

<?php if (count($announcements) > 0) : ?>
    <div class="box box-primary">
        <div class="box-header with-border">
            <i class="fa fa-bullhorn"></i>
            <h3 class="box-title"><?= __("Announcements") ?></h3>
        </div>
        <div class="box-body chat">
            <?php foreach ($announcements as $announcement) : ?>
                <div class="item">
                    <p class="announcement">
                            <span>
                                <small class="text-muted pull-right"><i class="fa fa-clock-o"></i>
                                    <?= h($announcement->created); ?></small>
                                <i class="fa fa-angle-double-right"></i> <b><?= h($announcement->title); ?></b>
                            </span>

                        <?= $announcement->content; ?>
                    </p>
                </div>
            <?php endforeach; ?>
            <?php unset($announcement) ?>
        </div>
    </div>
<?php endif; ?>

    <div class="box box-primary">
        <div class="box-header with-border">
            <i class="fa fa-bar-chart"></i>
            <h3 class="box-title"><?= __('Statistics') ?></h3>
        </div>
        <div class="box-body no-padding">
            <div id="chart_div" style="position: relative; height: 300px; width: 100%;"></div>
            <div class="small text-right" style="padding-right: 10px;">
                <?= __('Data is reported in {0} timezone', get_option('timezone', 'UTC')) ?>
            </div>
            <div style="height: 300px;overflow: auto;">
                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th><?= __('Date') ?></th>
                        <th><?= __('Views') ?></th>
                        <th><?= __('Link Earnings') ?></th>
                        <th><?= __('Daily CPM') ?></th>
                        <th><?= __('Referral Earnings') ?></th>
                    </tr>
                    </thead>
                    <?php foreach ($CurrentMonthDays as $key => $value) : ?>
                        <tr>
                            <td><?= $key ?></td>
                            <td><?= $value['view'] ?></td>
                            <td><?= display_price_currency($value['publisher_earnings']); ?></td>
                            <td>
                                <?= (!empty($value['view'])) ? display_price_currency(($value['publisher_earnings'] / $value['view']) * 1000) : 0 ?>
                            </td>
                            <td><?= display_price_currency($value['referral_earnings']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>

<?php
/*
    <div class="box box-success">
        <div class="box-header with-border">
            <i class="fa fa-fire"></i>
            <h3 class="box-title"><?= __("Top 10 Links") ?></h3>
        </div>
        <div class="box-body">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th><?= __('Alias') ?></th>
                    <th><?= __('Views') ?></th>
                    <th><?= __('Link Earnings') ?></th>
                </tr>
                </thead>
                <?php foreach ($popularLinks as $link) : ?>
                    <?php
                    if (!$link->views) {
                        continue;
                    }
                    ?>
                    <?php
                    $short_url = get_short_url($link->link->alias, $link->link->domain);

                    $title = $link->link->alias;
                    if (!empty($link->link->title)) {
                        $title = $link->link->title;
                    }
                    ?>
                    <tr>
                        <td><a href="<?= $short_url ?>" target="_blank" rel="nofollow noopener noreferrer">
                                <span class="glyphicon glyphicon-link"></span> <?= h($title) ?></a></td>
                        <td><?= $link->views ?></td>
                        <td><?= display_price_currency($link->publisher_earnings); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php unset($link) ?>
            </table>
        </div>
    </div>
*/
?>

<?php $this->start('scriptBottom'); ?>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/almasaeed2010/AdminLTE@v2.3.11/plugins/morris/morris.css">
    <script src="https://cdn.jsdelivr.net/gh/DmitryBaranovskiy/raphael@v2.1.0/raphael-min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/almasaeed2010/AdminLTE@v2.3.11/plugins/morris/morris.min.js"
            type="text/javascript"></script>

    <script>
      jQuery(document).ready(function() {
        new Morris.Line({
          element: 'chart_div',
          resize: true,
          data: [
              <?php
              foreach ($CurrentMonthDays as $key => $value) {
                  echo '{date: "' . $key . '", views: ' . $value['view'] . '},';
              }
              ?>
          ],
          xkey: 'date',
          xLabels: 'day',
          ykeys: ['views'],
          labels: ['<?= __('Views') ?>'],
          lineColors: ['#3c8dbc'],
          lineWidth: 2,
          hideHover: 'auto',
          smooth: false,
        });
      });
    </script>

<?php $this->end();

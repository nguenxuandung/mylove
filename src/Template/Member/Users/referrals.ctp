<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $logged_user
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $referrals
 */
?>
<?php
$this->assign('title', __('Referrals'));
$this->assign('description', '');
$this->assign('content_title', __('Referrals'));
?>

<div class="box box-default box-solid">
    <div class="box-body">
        <p>
            <?= __(
                'The {0} referral program is a great way to spread the word of this great service and to ' .
                'earn even more money with your short links! Refer friends and receive {1}% of their earnings ' .
                'for life!',
                [h(get_option('site_name', '')), h(get_option('referral_percentage', '20'))]
            ) ?>
        </p>

        <?php $ref = $this->Url->build('/', true) . 'ref/' . $logged_user->username; ?>

        <pre><?= $ref ?></pre>

        <?= str_replace('[referral_link]', $ref, get_option('referral_banners_code')); ?>

    </div>
</div>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-exchange"></i> <?= __('My Referrals') ?></h3>
    </div><!-- /.box-header -->
    <div class="box-body no-padding">

        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <tr>
                    <th><?= __('Username'); ?></th>
                    <th><?= __('Date'); ?></th>
                </tr>
                <!-- Here is where we loop through our $posts array, printing out post info -->
                <?php foreach ($referrals as $referral) : ?>
                    <tr>
                        <td><?= h($referral->username); ?></td>
                        <td><?= display_date_timezone($referral->created) ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php unset($referral); ?>
            </table>
        </div>

    </div><!-- /.box-body -->
</div>

<ul class="pagination">
    <?php
    $this->Paginator->setTemplates([
        'ellipsis' => '<li><a href="javascript: void(0)">...</a></li>',
    ]);

    if ($this->Paginator->hasPrev()) {
        echo $this->Paginator->prev('«');
    }

    echo $this->Paginator->numbers([
        'modulus' => 4,
        'first' => 2,
        'last' => 2,
    ]);

    if ($this->Paginator->hasNext()) {
        echo $this->Paginator->next('»');
    }
    ?>
</ul>

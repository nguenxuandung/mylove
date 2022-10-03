<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Announcement[]|\Cake\Collection\CollectionInterface $announcements
 */
$this->assign('title', __('Manage Announcements'));
$this->assign('description', '');
$this->assign('content_title', __('Manage Announcements'));
?>

<div class="box box-primary">
    <div class="box-body no-padding">

        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <tr>
                    <th><?= $this->Paginator->sort('id', __('Id')); ?></th>
                    <th><?= $this->Paginator->sort('title', __('Title')); ?></th>
                    <th><?= $this->Paginator->sort('published', __('Published')); ?></th>
                    <th><?= $this->Paginator->sort('modified', __('modified')); ?></th>
                    <th><?= $this->Paginator->sort('created', __('Created')); ?></th>
                    <th><?php echo __('Actions') ?></th>
                </tr>

                <!-- Here is where we loop through our $posts array, printing out post info -->

                <?php foreach ($announcements as $announcement): ?>
                    <tr>
                        <td><?= $announcement->id ?></td>
                        <td>
                            <?= $this->Html->link($announcement->title, [
                                'action' => 'edit',
                                $announcement->id,
                                '?' => ['lang' => get_option('language', 'en_US')]
                            ]); ?> <?= $this->Html->link(get_option('language', 'en_US'),
                                [
                                    'action' => 'edit',
                                    $announcement->id,
                                    '?' => ['lang' => get_option('language', 'en_US')]
                                ],
                                ['class' => 'label label-primary']); ?><br>
                            <p>
                                <?php foreach (get_site_languages() as $lang) : ?>
                                    <?= $this->Html->link($lang,
                                        ['action' => 'edit', $announcement->id, '?' => ['lang' => $lang]],
                                        ['class' => 'label label-default']); ?>
                                <?php endforeach; ?>
                            </p>
                        </td>
                        <td><?= ($announcement->published) ? __('Yes') : __('No') ?></td>
                        <td><?= display_date_timezone($announcement->modified) ?></td>
                        <td><?= display_date_timezone($announcement->created) ?></td>
                        <td>
                            <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $announcement->id],
                                ['confirm' => __('Are you sure?'), 'class' => 'btn btn-danger btn-xs']); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php unset($announcement); ?>
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

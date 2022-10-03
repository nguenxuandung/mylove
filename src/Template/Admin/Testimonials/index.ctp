<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Testimonial[]|\Cake\Collection\CollectionInterface $testimonials
 */
$this->assign('title', __('Manage Testimonials'));
$this->assign('description', '');
$this->assign('content_title', __('Manage Testimonials'));

?>

<div class="box box-primary">
    <div class="box-body no-padding">

        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <tr>
                    <th><?= $this->Paginator->sort('id', __('Id')); ?></th>
                    <th><?= $this->Paginator->sort('name', __('Title')); ?></th>
                    <th><?= $this->Paginator->sort('published', __('Published')); ?></th>
                    <th><?= $this->Paginator->sort('modified', __('modified')); ?></th>
                    <th><?= $this->Paginator->sort('created', __('Created')); ?></th>
                    <th><?php echo __('Actions') ?></th>
                </tr>

                <!-- Here is where we loop through our $posts array, printing out post info -->

                <?php foreach ($testimonials as $testimonial): ?>
                    <tr>
                        <td><?= $testimonial->id ?></td>
                        <td>
                            <?= $this->Html->link($testimonial->name, [
                                'action' => 'edit',
                                $testimonial->id,
                                '?' => ['lang' => get_option('language', 'en_US')],
                            ]); ?> <?= $this->Html->link(get_option('language', 'en_US'),
                                [
                                    'action' => 'edit',
                                    $testimonial->id,
                                    '?' => ['lang' => get_option('language', 'en_US')],
                                ],
                                ['class' => 'label label-primary']); ?><br>
                            <p>
                                <?php foreach (get_site_languages() as $lang) : ?>
                                    <?= $this->Html->link($lang,
                                        ['action' => 'edit', $testimonial->id, '?' => ['lang' => $lang]],
                                        ['class' => 'label label-default']); ?>
                                <?php endforeach; ?>
                            </p>
                        </td>
                        <td><?= ($testimonial->published) ? __('Yes') : __('No') ?></td>
                        <td><?= display_date_timezone($testimonial->modified) ?></td>
                        <td><?= display_date_timezone($testimonial->created) ?></td>
                        <td><?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $testimonial->id],
                                ['confirm' => __('Are you sure?'), 'class' => 'btn btn-danger btn-xs']); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php unset($testimonial); ?>
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

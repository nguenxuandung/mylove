<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Link[]|\Cake\Collection\CollectionInterface $links
 */
?>
<?php
$this->assign('title', __('Manage Links'));
$this->assign('description', '');
$this->assign('content_title', __('Manage Links'));
?>

<div class="box box-solid">
    <div class="box-body">
        <?php
        $base_url = ['controller' => 'Links', 'action' => 'index'];

        echo $this->Form->create(null, [
            'url' => $base_url,
            'class' => 'form-inline',
        ]);
        ?>

        <?=
        $this->Form->control('Filter.id', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'size' => 0,
            'placeholder' => __('Link Id'),
        ]);
        ?>

        <?=
        $this->Form->control('Filter.user_id', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'size' => 0,
            'placeholder' => __('User Id'),
        ]);
        ?>

        <?=
        $this->Form->control('Filter.alias', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'size' => 10,
            'placeholder' => __('Alias'),
        ]);
        ?>

        <?=
        $this->Form->control('Filter.ad_type', [
            'label' => false,
            'options' => [
                '1' => __('Interstitial'),
                '2' => __('Banner'),
                '0' => __('No Advert'),
            ],
            'empty' => __('Advertising Type'),
            'class' => 'form-control',
        ]);
        ?>

        <?=
        $this->Form->control('Filter.title_desc', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'placeholder' => __('Title, Desc. or URL'),
        ]);
        ?>

        <?= $this->Form->button(__('Filter'), ['class' => 'btn btn-default btn-sm']); ?>

        <?= $this->Html->link(__('Reset'), $base_url, ['class' => 'btn btn-link btn-sm']); ?>

        <?= $this->Form->end(); ?>

    </div>
</div>

<div class="box box-primary">
    <div class="box-body no-padding">
        <div class="table-responsive">
            <?= $this->Form->create(null, [
                'url' => ['controller' => 'Links', 'action' => 'mass'],
            ]);
            ?>
            <table class="table table-hover table-striped">
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th style="width:150px;"><?= __('Title') ?></th>
                    <th><?= __('Short Link') ?></th>
                    <th><?= __('Username'); ?></th>
                    <th><?= $this->Paginator->sort('created', __('Created')); ?></th>
                    <th>
                        <div class="form-inline">
                            <?=
                            $this->Form->control('action', [
                                'label' => false,
                                'options' => [
                                    '' => __('Mass Action'),
                                    'hide' => __('Hide'),
                                    'deactivate' => __('Inactivate'),
                                    'delete' => __('Delete'),
                                    'delete-stats' => __('Delete with stats'),
                                ],
                                'class' => 'form-control input-sm',
                                //'onchange' => 'this.form.submit();',
                                'required' => true,
                                'templates' => [
                                    'inputContainer' => '{{content}}',
                                ],
                            ]);
                            ?>

                            <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-default btn-sm']); ?>
                        </div>
                    </th>
                </tr>

                <?php foreach ($links as $link) : ?>
                    <tr>
                        <td>
                            <?= $this->Form->checkbox('ids[]', [
                                'hiddenField' => false,
                                'label' => false,
                                'value' => $link->id,
                                'class' => 'allcheckbox',
                            ]);
                            ?>
                        </td>
                        <td>
                            <?php
                            $title = $link->alias;
                            if (!empty($link->title)) {
                                $title = $link->title;
                            }
                            echo h($title);
                            ?>
                        </td>
                        <td>
                            <?php
                            $short_url = get_short_url($link->alias, $link->domain);
                            ?>
                            <div class="input-group">
                                <input type="text" class="form-control input-sm" value="<?= $short_url ?>" readonly=""
                                       onfocus="this.select()">
                                <div class="input-group-addon copy-it" data-clipboard-text="<?= $short_url ?>"
                                     data-toggle="tooltip" data-placement="bottom" title="<?= __('Copy') ?>">
                                    <i class="fa fa-clone"></i>
                                </div>
                            </div>
                            <div class="text-muted">
                                <small>
                                    <i class="fa fa-bar-chart"></i>
                                    <a href="<?= $short_url ?>/info" target="_blank" rel="nofollow noopener noreferrer">
                                        <?= __('Stats') ?></a> -

                                    <a target="_blank" rel="nofollow noopener noreferrer" href="<?= $link->url ?>">
                                        <?= strtoupper(parse_url($link->url, PHP_URL_HOST)); ?>
                                    </a>

                                    - <?= __('Created on') ?>: <?= h(get_link_methods($link->method)); ?>
                                </small>
                            </div>
                        </td>
                        <td>
                            <?=
                            $this->Html->link(
                                $link->user->username,
                                ['controller' => 'Users', 'action' => 'view', $link->user_id]
                            );
                            ?>
                        </td>
                        <td>
                            <?= display_date_timezone($link->created); ?>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-block btn-default dropdown-toggle"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?= __("Select Action") ?> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <?= $this->Html->link(
                                            __('Edit'),
                                            ['action' => 'edit', $link->id]
                                        ); ?>
                                    </li>
                                    <li>
                                        <?= $this->Html->link(
                                            __('Hide'),
                                            [
                                                'action' => 'hide',
                                                $link->id,
                                                'token' => $this->request->getParam('_csrfToken'),
                                            ],
                                            ['confirm' => __('Are you sure?')]
                                        ); ?>
                                    </li>

                                    <li>
                                        <?= $this->Html->link(
                                            __('Inactivate'),
                                            [
                                                'action' => 'deactivate',
                                                $link->id,
                                                'token' => $this->request->getParam('_csrfToken'),
                                            ],
                                            ['confirm' => __('Are you sure?')]
                                        ); ?>
                                    </li>

                                    <li role="separator" class="divider"></li>

                                    <li>
                                        <?= $this->Html->link(
                                            __('Delete'),
                                            [
                                                'action' => 'delete',
                                                $link->id,
                                                'token' => $this->request->getParam('_csrfToken'),
                                            ],
                                            ['confirm' => __('Are you sure?')]
                                        ); ?>
                                    </li>

                                    <li>
                                        <?= $this->Html->link(
                                            __('Delete with stats'),
                                            [
                                                'action' => 'delete',
                                                $link->id,
                                                true,
                                                'token' => $this->request->getParam('_csrfToken'),
                                            ],
                                            ['confirm' => __('Are you sure?')]
                                        ); ?>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?= $this->Form->end(); ?>
        </div>
    </div>
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

<?php $this->start('scriptBottom'); ?>
<script>
    $('#select-all').change(function() {
        $('.allcheckbox').prop('checked', $(this).prop('checked'));
    });
    $('.allcheckbox').change(function() {
        if ($(this).prop('checked') == false) {
            $('#select-all').prop('checked', false);
        }
        if ($('.allcheckbox:checked').length == $('.allcheckbox').length) {
            $('#select-all').prop('checked', true);
        }
    });
</script>
<?php $this->end(); ?>

<?php
/**
 * @var \App\View\AppView $this
 * @var object $menu
 */
?>
<?php
$this->assign('title', __('Menu Manager'));
$this->assign('description', '');
$this->assign('content_title', __('Menu Manager'));
?>

<?php if (!$menu) : ?>
    <div class="box box-primary">
        <div class="box-body">
            <?php
            $availableMenus = [
                'menu_main' => __('Main Menu'),
                'menu_short' => __('Short Link Page Menu'),
                'menu_footer' => __('Footer Menu'),
            ]
            ?>

            <div class="list-group">
                <?php foreach ($availableMenus as $key => $value) : ?>
                    <div class="list-group-item">
                        <h4 class="list-group-item-heading">
                            <?= $this->Html->link($value, [
                                'controller' => 'Options',
                                'action' => 'menu',
                                'menu' => $key,
                                'lang' => get_option('language', 'en_US'),
                            ]); ?>

                            <?= $this->Html->link(get_option('language', 'en_US'),
                                [
                                    'controller' => 'Options',
                                    'action' => 'menu',
                                    'menu' => $key,
                                    'lang' => get_option('language', 'en_US'),
                                ],
                                ['class' => 'label label-primary']); ?>
                        </h4>
                        <p class="list-group-item-text">
                            <?php foreach (get_site_languages() as $lang) : ?>
                                <?= $this->Html->link($lang,
                                    ['controller' => 'Options', 'action' => 'menu', 'menu' => $key, 'lang' => $lang],
                                    ['class' => 'label label-default']); ?>
                            <?php endforeach; ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
<?php endif; ?>


<?php if ($menu) : ?>

    <div class="row">
        <div class="col-sm-4">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><?= __('Add new menu item') ?></h3>
                </div>
                <div class="box-body">
                    <?= $this->Form->create(null, [
                        'url' => [
                            'controller' => 'Options',
                            'action' => 'menuItem',
                            'lang' => $this->getRequest()->getQuery('lang'),
                        ],
                    ]); ?>

                    <?= $this->Form->hidden('name', ['value' => $menu->name]) ?>

                    <?=
                    $this->Form->control('title', [
                        'label' => __('Title'),
                        'class' => 'form-control',
                    ])
                    ?>

                    <?=
                    $this->Form->control('link', [
                        'label' => __('Link'),
                        'class' => 'form-control',
                    ])
                    ?>

                    <?=
                    $this->Form->control('visibility', [
                        'label' => __('Visibility'),
                        'options' => [
                            'all' => __('All'),
                            'guest' => __('Only Guest'),
                            'logged' => __('Only Logged In'),
                        ],
                        'class' => 'form-control',
                    ]);
                    ?>

                    <?=
                    $this->Form->control('class', [
                        'label' => __('CSS Class'),
                        'class' => 'form-control',
                    ])
                    ?>

                    <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']); ?>
                    <?= $this->Form->end(); ?>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><?= __('Menu Edit') ?></h3>
                </div>
                <div class="box-body">
                    <?= $this->Form->create(null, [
                        'id' => 'form-settings',
                        'url' => [
                            'controller' => 'Options',
                            'action' => 'menu',
                            'menu' => $menu->name,
                            'lang' => $this->getRequest()->getQuery('lang'),
                        ],
                        'onSubmit' => "save_settings.disabled=true; save_settings.innerHTML='" . __('Saving ...') . "'; return true;",
                    ]); ?>

                    <ul id="sortable" class="list-group">
                        <?php foreach (json_decode($menu->value) as $item) : ?>
                            <li class="ui-state-default list-group-item" data-id="<?= $item->id ?>">
                                <i class="fa fa-arrows"></i> <?= $item->title ?>
                                <a data-toggle="collapse" href="#menu-item-<?= $item->id ?>"
                                   class="btn btn-link btn btn-sm no-padding pull-right">
                                    <?= __('Edit') ?>
                                </a>
                                <div id="menu-item-<?= $item->id ?>" class="collapse" style="margin-top: 20px;">
                                    <?=
                                    $this->Form->hidden($item->id . '[id]', [
                                        'value' => $item->id,
                                    ])
                                    ?>

                                    <?=
                                    $this->Form->control($item->id . '[title]', [
                                        'label' => __('Title'),
                                        'class' => 'form-control',
                                        'value' => $item->title,
                                    ])
                                    ?>

                                    <?=
                                    $this->Form->control($item->id . '[link]', [
                                        'label' => __('Link'),
                                        'class' => 'form-control',
                                        'value' => $item->link,
                                    ])
                                    ?>

                                    <?=
                                    $this->Form->control($item->id . '[visibility]', [
                                        'label' => __('Visibility'),
                                        'options' => [
                                            'all' => __('All'),
                                            'guest' => __('Only Guest'),
                                            'logged' => __('Only Logged In'),
                                        ],
                                        'value' => $item->visibility,
                                        'class' => 'form-control',
                                    ]);
                                    ?>

                                    <?=
                                    $this->Form->control($item->id . '[class]', [
                                        'label' => __('CSS Class'),
                                        'class' => 'form-control',
                                        'value' => $item->class,
                                    ])
                                    ?>

                                    <div class="clearfix">
                                        <a href="#" class="item-delete btn btn-danger btn-xs pull-right">
                                            <?= __('Delete') ?></a>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <?= $this->Form->button(__('Submit'), ['name' => 'save_settings', 'class' => 'btn btn-primary']); ?>
                    <?= $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>

<?php $this->start('scriptBottom'); ?>
<script src="https://cdn.jsdelivr.net/npm/jquery-ui-dist@1.12.1/jquery-ui.min.js"></script>
<style>
    #sortable .list-group-item {
        cursor: grabbing;
    }

    #sortable .list-group-item > i {
        margin-right: 15px;
    }
</style>
<script>
  $(function() {
    $('#sortable').sortable({
      //placeholder: "ui-state-highlight",
      items: '> li',
      cursor: 'move',
      opacity: 0.6,
    }).disableSelection();
    //$( "#sortable" ).disableSelection();
  });

  $('.item-delete').on('click', function(e) {
    e.preventDefault();
    if (confirm('Are you sure?')) {
      $(this).closest('li[data-id]').remove();
    }
    e.returnValue = false;
    return false;
  });
</script>
<?php $this->end(); ?>

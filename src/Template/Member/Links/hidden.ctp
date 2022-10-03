<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Link[]|\Cake\Collection\CollectionInterface $links
 */
$this->assign('title', __('Manage Links'));
$this->assign('description', '');
$this->assign('content_title', __('Manage Links'));

?>

<div class="box box-solid">
    <div class="box-body">
        <?php
        // The base url is the url where we'll pass the filter parameters
        $base_url = array('controller' => 'Links', 'action' => 'index');

        echo $this->Form->create(null, [
            'url' => $base_url,
            'class' => 'form-inline'
        ]);
        ?>

        <?=
        $this->Form->control('Filter.id', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'size' => 0,
            'placeholder' => __('Link Id')
        ]);
        ?>

        <?=
        $this->Form->control('Filter.alias', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'size' => 10,
            'placeholder' => __('Alias')
        ]);
        ?>

        <?=
        $this->Form->control('Filter.title_desc', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'placeholder' => __('Title, Desc. or URL')
        ]);
        ?>

        <?= $this->Form->button(__('Filter'), ['class' => 'btn btn-default btn-sm']); ?>

        <?= $this->Html->link(__('Reset'), $base_url, ['class' => 'btn btn-link btn-sm']); ?>

        <?= $this->Form->end(); ?>

    </div>
</div>

<?php foreach ($links as $link) : ?>

    <?php
    $short_url = $this->Url->build('/' . $link->alias, true);

    $title = $link->alias;
    if (!empty($link->title)) {
        $title = $link->title;
    }
    ?>

    <div class="box box-solid">
        <div class="box-body">
            <h4><a href="<?= $short_url ?>" target="_blank" rel="nofollow noopener noreferrer">
                    <span class="glyphicon glyphicon-link"></span> <?= h($title) ?></a></h4>
            <p class="text-muted">
                <small>
                    <i class="fa fa-calendar"></i> <?= display_date_timezone($link->created); ?> -
                    <a target="_blank" rel="nofollow noopener noreferrer" href="<?= $link->url ?>"><?=
                        strtoupper(parse_url(
                            $link->url,
                            PHP_URL_HOST
                        )); ?>
                    </a>
                    - <?= __('Created on') ?>: <?= h(get_link_methods($link->method)); ?>
                </small>
            </p>
            <div class="row">
                <div class="col-sm-6">
                    <div class="input-group"><input type="text" class="form-control input-sm" value="<?= $short_url ?>"
                                                    readonly="" onfocus="javascript:this.select()">
                        <div class="input-group-addon copy-it" data-clipboard-text="<?= $short_url ?>"
                             data-toggle="tooltip" data-placement="bottom" title="Copy"><i class="fa fa-clone"></i>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="text-right">
                        <?=
                        $this->Html->link(
                            __('Edit'),
                            ['action' => 'edit', $link->alias],
                            ['class' => 'btn btn-primary btn-sm']
                        );
                        ?>
                        <?= $this->Form->postLink(__('Unhide'), ['action' => 'unhide', $link->alias], [
                            'confirm' => __('Are you sure?'),
                            'class' => 'btn btn-danger btn-sm'
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endforeach; ?>
<?php unset($link); ?>

<ul class="pagination">
    <!-- Shows the previous link -->
    <?php
    if ($this->Paginator->hasPrev()) {
        echo $this->Paginator->prev(
            '«',
            array('tag' => 'li'),
            null,
            array('class' => 'disabled', 'tag' => 'li', 'disabledTag' => 'a')
        );
    }

    ?>
    <!-- Shows the page numbers -->
    <?php //echo $this->Paginator->numbers();    ?>
    <?php
    echo $this->Paginator->numbers(array(
        'modulus' => 4,
        'separator' => '',
        'ellipsis' => '<li><a>...</a></li>',
        'tag' => 'li',
        'currentTag' => 'a',
        'first' => 2,
        'last' => 2
    ));

    ?>
    <!-- Shows the next link -->
    <?php
    if ($this->Paginator->hasNext()) {
        echo $this->Paginator->next(
            '»',
            array('tag' => 'li'),
            null,
            array('class' => 'disabled', 'tag' => 'li', 'disabledTag' => 'a')
        );
    }

    ?>
</ul>

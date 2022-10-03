<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Link[]|\Cake\Collection\CollectionInterface $links
 * @var \App\Model\Entity\Plan $logged_user_plan
 */
$this->assign('title', __('Manage Links'));
$this->assign('description', '');
$this->assign('content_title', __('Manage Links'));

?>

<div class="box box-solid">
    <div class="box-body">
        <?php
        // The base url is the url where we'll pass the filter parameters
        $base_url = ['controller' => 'Links', 'action' => 'index'];

        echo $this->Form->create(null, [
            'url' => $base_url,
            'class' => 'form-inline',
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
            'options' => get_allowed_ads(),
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

<?php foreach ($links as $link) : ?>

    <?php
    $short_url = get_short_url($link->alias, $link->domain);

    $title = $link->alias;
    if (!empty($link->title)) {
        $title = $link->title;
    }

    $link_info_member = '';
    if (get_option('link_info_member', 'yes') == 'yes') {
        $link_info_member = "<i class='fa fa-bar-chart'></i> " .
            "<a href='{$short_url}/info' target='_blank'  rel='nofollow noopener noreferrer'>" .
            __('Stats') . "</a> - ";
    }
    ?>


    <div class="box box-solid">
        <div class="box-body">
            <h4><a href="<?= $short_url ?>" target="_blank" rel="nofollow noopener noreferrer">
                    <span class="glyphicon glyphicon-link"></span> <?= h($title) ?></a></h4>
            <p class="text-muted">
                <small>
                    <?= $link_info_member ?>
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
                             data-toggle="tooltip" data-placement="bottom" title="<?= __('Copy') ?>"><i
                                    class="fa fa-clone"></i></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="text-right">
                        <?php if ($logged_user_plan->edit_link) : ?>
                            <?=
                            $this->Html->link(
                                __('Edit'),
                                ['action' => 'edit', $link->alias],
                                ['class' => 'btn btn-primary btn-sm']
                            );
                            ?>
                        <?php endif; ?>

                        <?=
                        $this->Form->postLink(__('Hide'), ['action' => 'hide', $link->alias], [
                            'confirm' => __('Are you sure?'),
                            'class' => 'btn btn-danger btn-sm',
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endforeach; ?>

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

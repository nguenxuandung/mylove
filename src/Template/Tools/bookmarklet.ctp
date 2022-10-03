<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Link $link
 * @var mixed $custom_alias
 * @var mixed $short_link
 * @var mixed $valid_bookmarklet
 */
?>
<!DOCTYPE html>
<html lang="<?= locale_get_primary_language(null) ?>">
<head>
    <?= $this->Html->charset(); ?>
    <title><?= h(get_option('site_name')); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= $this->Html->meta('icon'); ?>
    <?= $this->Html->css('/vendor/bootstrap/css/bootstrap.min.css'); ?>

    <?php
    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    ?>

    <?= get_option('head_code'); ?>
    <?= $this->fetch('scriptTop') ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body style="padding: 20px 0;">
<?= get_option('after_body_tag_code'); ?>

<div class="container">

    <div class="page-header" style="margin-top: 0;">
        <h3 style="margin-top: 0;">
            <a href="JavaScript:void(0);" onclick="window.opener.location.href ='<?= $this->Url->build('/'); ?>';">
                <?= h(get_option('site_name')) ?>
            </a>
        </h3>
    </div>

    <?= $this->Flash->render() ?>

    <?php if (!$valid_bookmarklet) : ?>
        <script>
            window.setTimeout(function ()
            {
                self.close();
            }, 2000)
        </script>
    <?php endif; ?>

    <?php if ($valid_bookmarklet && isset($short_link)) : ?>
        <div class="form-group">
            <input type="text" class="form-control input-lg" value="<?= $short_link ?>"
                   readonly="" onfocus="javascript:this.select()">
        </div>

        <?= $this->Form->button(__('Close'), [
            'class' => 'btn btn-default',
            'onClick' => 'self.close();'
        ]); ?>

    <?php endif; ?>

    <?php if ($valid_bookmarklet && !isset($short_link)) : ?>
        <?= $this->Form->create($link); ?>

        <?= $this->Form->control('api', ['value' => $this->request->query('api'), 'type' => 'hidden']); ?>

        <?=
        $this->Form->control('url', [
            'label' => __('URL'),
            'value' => $this->request->query('url'),
            'class' => 'form-control input-sm',
            'type' => 'url'
        ]);
        ?>

        <?php if ($custom_alias) : ?>
            <?=
            $this->Form->control('alias', [
                'label' => __('Alias'),
                'type' => 'text',
                'required' => false,
                'placeholder' => __('Alias'),
                'class' => 'form-control input-sm'
            ]);
            ?>
        <?php endif; ?>

        <?php
        $ads_options = get_allowed_ads();

        if (count($ads_options) > 1) {
            echo $this->Form->control('ad_type', [
                'label' => __('Advertising Type'),
                'options' => $ads_options,
                'default' => get_option('member_default_advert', 1),
                //'empty'   => __( 'Choose' ),
                'class' => 'form-control input-sm'
            ]);
        } else {
            echo $this->Form->hidden('ad_type', ['value' => get_option('member_default_advert', 1)]);
        }
        ?>

        <?php if (count(get_multi_domains_list())
        ) : ?>
            <div class="form-group">
                <?=
                $this->Form->control('domain', [
                    'label' => __('Domain'),
                    'options' => get_multi_domains_list(),
                    'default' => '',
                    'empty' => get_default_short_domain(),
                    'class' => 'form-control input-sm'
                ]);
                ?>
            </div>
        <?php endif; ?>

        <?= $this->Form->button(__('Shorten'), ['class' => 'btn btn-primary']); ?>

        <?= $this->Form->button(__('Cancel'), [
            'class' => 'btn btn-default',
            'onClick' => 'self.close();'
        ]); ?>

        <?= $this->Form->end(); ?>
    <?php endif; ?>

</div> <!-- /container -->


<?= $this->Html->script('/vendor/jquery.min.js'); ?>
<?= $this->Html->script('/vendor/bootstrap/js/bootstrap.min.js'); ?>

</body>
</html>

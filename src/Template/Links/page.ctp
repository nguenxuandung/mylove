<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Link $link
 * @var \App\Model\Entity\Post $post
 * @var string $ad_captcha_above
 * @var string $ad_captcha_below
 * @var mixed $page
 */
$this->assign('title', get_option('site_name'));
$this->assign('description', get_option('description'));
$this->assign('content_title', get_option('site_name'));
$this->assign('og_title', $link->title);
$this->assign('og_description', $link->description);
$this->assign('og_image', $link->image);
?>

<?php $this->start('scriptTop'); ?>
<script type="text/javascript">
  if (window.self !== window.top) {
    window.top.location.href = window.location.href;
  }
</script>
<?php $this->end(); ?>

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="box box-success">
            <div class="box-body text-center">

                <?php if (!empty($ad_captcha_above)) : ?>
                    <div class="banner banner-captcha">
                        <div class="banner-inner">
                            <?= $ad_captcha_above; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($post): ?>
                    <div class="blog-item text-left">
                        <div class="page-header">
                            <h3>
                                <small><a href="<?= build_main_domain_url('/blog') ?>"><?= __('From Our Blog') ?>:</a>
                                </small> <?= h($post->title) ?></h3>
                        </div>
                        <div class="blog-content"><?= $post->description ?></div>
                    </div>
                <?php endif; ?>

                <?= $this->Flash->render() ?>

                <?= $this->Form->create(null, ['id' => '']); ?>
                <?= $this->Form->hidden('action', ['value' => 'continue']); ?>
                <?= $this->Form->hidden('page', ['value' => $page + 1]); ?>
                <?= $this->Form->button(__('Click here to continue'), ['class' => 'btn btn-primary',]); ?>
                <?= $this->Form->end() ?>

                <?php if (!empty($ad_captcha_below)) : ?>
                    <div class="banner banner-captcha">
                        <div class="banner-inner">
                            <?= $ad_captcha_below; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <hr>

                <div class="text-left">

                    <h3><?= __('What is {0}?', h(get_option('site_name'))) ?></h3>
                    <p><?= __(
                            '{0} is a completely free tool where you can create short links, which apart ' .
                            'from being free, you get paid! So, now you can make money from home, when managing and ' .
                            'protecting your links. Register now!',
                            h(get_option('site_name'))
                        ) ?></p>

                    <h3><?= __('Shorten URLs and earn money') ?></h3>
                    <p><?= __("Signup for an account in just 2 minutes. Once you've completed your registration'.
                    'just start creating short URLs and sharing the links with your family and friends.") ?></p>

                </div>

            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>

<?php $this->start('scriptBottom'); ?>
<?php $this->end(); ?>

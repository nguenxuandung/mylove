<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 */
$this->assign('title', ($post->meta_title) ?: $post->title);
$this->assign('description', $post->meta_description);
$this->assign('content_title', $post->title);
?>

<!-- Header -->
<header>
    <div class="section-inner">
        <div class="container">
            <div class="intro-text">
                <div class="intro-lead-in"><?= h($post->title) ?></div>
            </div>
        </div>
    </div>
</header>

<section id="services">
    <div class="container">
        <?= $post->description ?>

        <?php
        $post_url = $this->Url->build([
            'controller' => 'Posts',
            'action' => 'view',
            'id' => $post->id,
            'slug' => $post->slug,
            'prefix' => false
        ], true);

        ?>

        <div class="text-muted" style="overflow: hidden;">
            <div class="pull-left">
                <?= __("Published on") ?>: <?= $post->created ?>
            </div>
            <div class="pull-right">
                <a class="popup"
                   href="http://www.facebook.com/sharer.php?u=<?= urlencode($post_url) ?>&amp;t=<?= urlencode($post->title) ?>"
                   target="_blank" title="FaceBook"><span class="fa-stack"><i
                                style="color:#3B5998 !important; text-shadow:-1px 0 #3B5998, 0 1px #3B5998, 1px 0 #3B5998, 0 -1px #3B5998 !important;"
                                class="fa fa-stop fa-stack-2x"></i><i style="color:#ffffff !important;"
                                                                      class="fa fa-facebook fa-stack-1x"></i></span></a>

                <a class="popup"
                   href="https://twitter.com/share?text=<?= urlencode($post->title) ?>&amp;url=<?= urlencode($post_url) ?>"
                   target="_blank" title="Twitter"><span class="fa-stack"><i
                                style="color:#00aced !important; text-shadow:-1px 0  #00aced, 0 1px  #00aced, 1px 0  #00aced, 0 -1px  #00aced !important;"
                                class="fa fa-stop fa-stack-2x"></i><i style="color:#ffffff !important;"
                                                                      class="fa fa-twitter fa-stack-1x"></i></span></a>

                <a class="popup" href="https://plus.google.com/share?url=<?= urlencode($post_url) ?>" target="_blank"
                   title="Google+"><span class="fa-stack"><i
                                style="color:#C73B6F !important; text-shadow:-1px 0 #C73B6F, 0 1px #C73B6F, 1px 0 #C73B6F, 0 -1px #C73B6F !important;"
                                class="fa fa-stop fa-stack-2x"></i><i style="color:#ffffff !important;"
                                                                      class="fa fa-google-plus fa-stack-1x"></i></span></a>

                <a class="popup"
                   href="http://pinterest.com/pin/create/button/?url=<?= urlencode($post_url) ?>&amp;description=<?= urlencode($post->title) ?>"
                   title="Pinterest"><span class="fa-stack"><i
                                style="color:#cb2027 !important; text-shadow:-1px 0 #cb2027, 0 1px #cb2027, 1px 0 #cb2027, 0 -1px #cb2027 !important;"
                                class="fa fa-stop fa-stack-2x"></i><i style="color:#ffffff !important;"
                                                                      class="fa fa-pinterest fa-stack-1x"></i></span></a>
            </div>
        </div>

        <?php if ((bool)get_option('blog_comments_enable', false)) : ?>
            <div id="disqus_thread"></div>
            <script>
              var disqus_config = function () {
                this.language = "<?= locale_get_primary_language(null) ?>"
                this.page.url = '<?= $post_url; ?>'
                this.page.identifier = 'blog-<?= locale_get_default() ?>-<?= $post->id ?>'
                this.page.title = '<?= h($post->title); ?>'
              };
              (function () { // DON'T EDIT BELOW THIS LINE
                var d = document, s = d.createElement('script')
                s.src = '//<?= get_option('disqus_shortname') ?>.disqus.com/embed.js'
                s.setAttribute('data-timestamp', +new Date());
                (d.head || d.body).appendChild(s)
              })()
            </script>
        <?php endif; ?>
    </div>
</section>

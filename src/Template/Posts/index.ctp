<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post[]|\Cake\Collection\CollectionInterface $posts
 */
$this->assign('title', __('Blog'));
$this->assign('description', __('Discover all the latest news and tips about our service.'));
$this->assign('content_title', __('Blog'));

?>

<!-- Header -->
<header>
    <div class="section-inner">
        <div class="container">
            <div class="intro-text">
                <div class="intro-lead-in"><?= __('Blog') ?></div>
            </div>
        </div>
    </div>
</header>

<section class="blog">
    <div class="container">

        <?php foreach ($posts as $post) : ?>

            <?php
            $post_url = $this->Url->build([
                'controller' => 'Posts',
                'action' => 'view',
                'id' => $post->id,
                'slug' => $post->slug,
                'prefix' => false
            ], true);
            ?>

            <div class="blog-item">
                <div class="page-header">
                    <h3><a href="<?= $post_url ?>"><?= h($post->title) ?></a></h3>
                </div>
                <div class="blog-content"><?= $post->short_description ?></div>
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

                        <a class="popup" href="https://plus.google.com/share?url=<?= urlencode($post_url) ?>"
                           target="_blank" title="Google+"><span class="fa-stack"><i
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
            </div>

        <?php endforeach; ?>

        <ul class="pagination">
            <!-- Shows the previous link -->
            <?php
            if ($this->Paginator->hasPrev()) {
                echo $this->Paginator->prev('«', array('tag' => 'li'), null,
                    array('class' => 'disabled', 'tag' => 'li', 'disabledTag' => 'a'));
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
                echo $this->Paginator->next('»', array('tag' => 'li'), null,
                    array('class' => 'disabled', 'tag' => 'li', 'disabledTag' => 'a'));
            }

            ?>
        </ul>


    </div>
</section>

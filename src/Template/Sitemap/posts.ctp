<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post[]|\Cake\Collection\CollectionInterface $posts
 */
?>
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php foreach ($posts as $post) : ?>
        <url>
            <loc><?= $this->Url->build(
                    [
                        'controller' => 'Posts',
                        'action' => 'view',
                        'id' => $post->id,
                        'slug' => $post->slug,
                    ],
                    ['fullBase' => true]
                ); ?></loc>
        </url>
    <?php endforeach; ?>
</urlset>

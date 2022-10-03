<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Page[]|\Cake\Collection\CollectionInterface $pages
 */
?>
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php foreach ($pages as $page) : ?>
        <url>
            <loc><?= $this->Url->build(
                    [
                        'controller' => 'Pages',
                        'action' => 'view',
                        $page->slug,
                    ],
                    ['fullBase' => true]
                ); ?></loc>
        </url>
    <?php endforeach; ?>
</urlset>

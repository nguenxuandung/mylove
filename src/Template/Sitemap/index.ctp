<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $linksSitemaps
 */
?>
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc><?= build_main_domain_url('/sitemap/general') ?></loc>
    </sitemap>
    <sitemap>
        <loc><?= build_main_domain_url('/sitemap/pages') ?></loc>
    </sitemap>
    <?php if ((bool)get_option('blog_enable', false)) : ?>
        <sitemap>
            <loc><?= build_main_domain_url('/sitemap/posts') ?></loc>
        </sitemap>
    <?php endif; ?>
    <?php if ((bool)get_option('sitemap_shortlinks', false) && isset($linksSitemaps)) : ?>
        <?php for ($i = 1; $i <= $linksSitemaps; $i++) : ?>
            <sitemap>
                <loc><?= build_main_domain_url('/sitemap/links?page=' . $i) ?></loc>
            </sitemap>
        <?php endfor; ?>
    <?php endif; ?>
</sitemapindex>

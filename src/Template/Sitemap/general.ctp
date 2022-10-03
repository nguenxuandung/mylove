<?php
/**
 * @var \App\View\AppView $this
 */
?>
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc><?= build_main_domain_url('/') ?></loc>
    </url>
    <?php if ((bool)get_option('blog_enable', false)) : ?>
        <url>
            <loc><?= build_main_domain_url('/blog') ?></loc>
        </url>
    <?php endif; ?>
    <url>
        <loc><?= build_main_domain_url('/auth/signin') ?></loc>
    </url>
    <url>
        <loc><?= build_main_domain_url('/auth/signup') ?></loc>
    </url>
    <url>
        <loc><?= build_main_domain_url('/auth/forgot-password') ?></loc>
    </url>
</urlset>

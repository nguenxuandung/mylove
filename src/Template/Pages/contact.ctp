<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', __('Contact Us'));
$this->assign('description', '');
$this->assign('content_title', __('Contact Us'));

?>

<!-- Header -->
<header>
    <div class="section-inner">
        <div class="container">
            <div class="intro-text">
                <div class="intro-lead-in"><?= __('Contact Us') ?></div>
            </div>
        </div>
    </div>
</header>

<section id="contact">
    <div class="container">
        <?= $this->element('contact'); ?>
    </div>
</section>

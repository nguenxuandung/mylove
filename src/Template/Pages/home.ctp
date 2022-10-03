<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $totalClicks
 * @var mixed $totalLinks
 * @var mixed $totalUsers
 */
$this->assign('title', (get_option('site_meta_title')) ?: get_option('site_name'));
$this->assign('description', get_option('description'));
$this->assign('content_title', get_option('site_name'));
?>

<!-- Header -->
<header class="shorten">
    <div class="container">
        <div class="intro-text">
            <div class="intro-lead-in wow zoomIn" data-wow-delay="0.3s"><?= __('Shorten URLs and') ?></div>
            <div class="intro-heading wow pulse" data-wow-delay="2.0s"><?= __('earn money') ?></div>
            <div class="row wow rotateInUpLeft" data-wow-delay="0.3s">
                <div class="col-sm-8 col-sm-offset-2">
                    <?php if (get_option('home_shortening') == 'yes') : ?>
                        <?= $this->element('shorten'); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Services Section -->
<section>
    <div class="container">
        <div class="row wow bounceIn">
            <div class="col-lg-12 text-center">
                <h2 class="section-heading"><?= __('WHY JOIN US?') ?></h2>
                <h3 class="section-subheading text-muted"><?= __('We have defined earning extra money from the comfort of your home.') ?></h3>
            </div>
        </div>

        <div style="display: flex; flex-wrap: wrap; text-align: center">
            <div class="col-md-4 wow fadeInUp">
                <span class="fa-stack fa-4x">
                    <i class="fa fa-circle fa-stack-2x text-primary"></i>
                    <i class="fa fa-question-circle fa-stack-1x fa-inverse"></i>
                </span>
                <h4 class="service-heading"><?= __('What is {0}?', h(get_option('site_name'))) ?></h4>
                <p class="text-muted"><?= __('{0} is a completely free tool where you can create short links, which apart from being free, you get paid! So, now you can make money from home, when managing and protecting your links.',
                        h(get_option('site_name'))) ?></p>
            </div>

            <div class="col-md-4 wow fadeInUp">
                <span class="fa-stack fa-4x">
                    <i class="fa fa-circle fa-stack-2x text-primary"></i>
                    <i class="fa fa-money fa-stack-1x fa-inverse"></i>
                </span>
                <h4 class="service-heading"><?= __('How and how much do I earn?') ?></h4>
                <p class="text-muted"><?= __("How can you start making money in {0}? It's just 3 steps: create an account, create a link and post it - for every visit, you earn money. It's just that easy!",
                        h(get_option('site_name'))) ?></p>
            </div>

            <?php if ((bool)get_option('enable_referrals', 1)) : ?>
                <div class="col-md-4 wow fadeInUp">
                <span class="fa-stack fa-4x">
                    <i class="fa fa-circle fa-stack-2x text-primary"></i>
                    <i class="fa fa-users fa-stack-1x fa-inverse"></i>
                </span>
                    <h4 class="service-heading"><?= __('{0}% Referral Bonus',
                            h(get_option('referral_percentage'))) ?></h4>
                    <p class="text-muted"><?= __('The {0} referral program is a great way to spread the word of this great service and to earn even more money with your short links! Refer friends and receive {1}% of their earnings for life!',
                            [h(get_option('site_name')), h(get_option('referral_percentage'))]) ?></p>
                </div>
            <?php endif; ?>

            <div class="col-md-4 wow fadeInUp">
                <span class="fa-stack fa-4x">
                    <i class="fa fa-circle fa-stack-2x text-primary"></i>
                    <i class="fa fa-tachometer fa-stack-1x fa-inverse"></i>
                </span>
                <h4 class="service-heading"><?= __('Featured Administration Panel') ?></h4>
                <p class="text-muted"><?= __('Control all of the features from the administration panel with a click of a button.') ?></p>
            </div>

            <div class="col-md-4 wow fadeInUp">
                <span class="fa-stack fa-4x">
                    <i class="fa fa-circle fa-stack-2x text-primary"></i>
                    <i class="fa fa-bar-chart fa-stack-1x fa-inverse"></i>
                </span>
                <h4 class="service-heading"><?= __('Detailed Stats') ?></h4>
                <p class="text-muted"><?= __('Know your audience. Analyse in detail what brings you the most income and what strategies you should adapt.') ?></p>
            </div>

            <div class="col-md-4 wow fadeInUp">
                <span class="fa-stack fa-4x">
                    <i class="fa fa-circle fa-stack-2x text-primary"></i>
                    <i class="fa fa-money fa-stack-1x fa-inverse"></i>
                </span>
                <h4 class="service-heading"><?= __('Low Minimum Payout') ?></h4>
                <p class="text-muted"><?= __('You are required to earn only {0} before you will be paid. We can pay all users via their PayPal.',
                        display_price_currency(get_option('minimum_withdrawal_amount'))) ?></p>
            </div>

            <div class="col-md-4 wow fadeInUp">
                <span class="fa-stack fa-4x">
                    <i class="fa fa-circle fa-stack-2x text-primary"></i>
                    <i class="fa fa-line-chart fa-stack-1x fa-inverse"></i>
                </span>
                <h4 class="service-heading"><?= __('Highest Rates') ?></h4>
                <p class="text-muted"><?= __('Make the most out of your traffic with our always increasing rates.') ?></p>
            </div>

            <div class="col-md-4 wow fadeInUp">
                <span class="fa-stack fa-4x">
                    <i class="fa fa-circle fa-stack-2x text-primary"></i>
                    <i class="fa fa-code fa-stack-1x fa-inverse"></i>
                </span>
                <h4 class="service-heading"><?= __('API') ?></h4>
                <p class="text-muted"><?= __('Shorten links more quickly with easy to use API and bring your creative and advanced ideas to life.') ?></p>
            </div>

            <div class="col-md-4 wow fadeInUp">
                <span class="fa-stack fa-4x">
                    <i class="fa fa-circle fa-stack-2x text-primary"></i>
                    <i class="fa fa-life-ring fa-stack-1x fa-inverse"></i>
                </span>
                <h4 class="service-heading"><?= __('Support') ?></h4>
                <p class="text-muted"><?= __('A dedicated support team is ready to help with any questions you may have.') ?></p>
            </div>
        </div>
    </div>
</section>

<?php if ((bool)get_option('display_home_stats', 1)) : ?>
    <section class="bg-light-gray">
        <div class="container">
            <div class="row wow bounceIn">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading"><?= __("Fast Growing") ?></h2>
                    <h3 class="section-subheading text-muted"><?= __('Numbers speak for themselves') ?></h3>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4 text-center wow flipInY">
                    <span class="display-counter"><?= $totalClicks ?></span>
                    <span><?= __("Total Clicks") ?></span>
                </div>
                <div class="col-sm-4 text-center wow flipInY">
                    <span class="display-counter"><?= $totalLinks ?></span>
                    <span><?= __("Total URLs") ?></span>
                </div>
                <div class="col-sm-4 text-center wow flipInY">
                    <span class="display-counter"><?= $totalUsers ?></span>
                    <span><?= __("Registered users") ?></span>
                </div>
            </div>

        </div>
    </section>
<?php endif; ?>

<!-- Contact Section -->
<section id="contact">
    <div class="container">
        <div class="row wow bounceIn">
            <div class="col-lg-12 text-center">
                <h2 class="section-heading"><?= __('Contact Us') ?></h2>
                <h3 class="section-subheading text-muted"><?= __('Get in touch') ?></h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">

                <?= $this->element('contact'); ?>

            </div>
        </div>
    </div>
</section>

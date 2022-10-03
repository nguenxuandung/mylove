<?php if (count($testimonials) >= 2) : ?>
    <section id="testimonials">
        <div class="container">
            <div class="section-title text-center wow bounceIn">
                <h3 class="section-subheading"><?= __("Client's Testimonials") ?></h3>
                <h2 class="section-heading"><?= __('What People Say?') ?></h2>
            </div>
            <div class="testimonials owl-carousel">
                <?php foreach ($testimonials as $testimonial) : ?>
                    <div class="testimonial">
                        <div class="content">
                            <div class="row">
                                <div class="col-sm-1">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-quote-left fa-stack-1x fa-inverse"></i>
                                </span>
                                </div>
                                <div class="col-sm-11"><?= $testimonial->content ?></div>
                            </div>
                        </div>
                        <div class="div-table testimonial-info">
                            <div class="div-tr">
                                <div class="div-td testimonial-image">
                                    <img src="<?= $testimonial->image ?>" alt="<?= h($testimonial->name) ?>"/>
                                </div>
                                <div class="div-td testimonial-data">
                                    <h4><?= h($testimonial->name) ?></h4>
                                    <div><?= h($testimonial->position) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

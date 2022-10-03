<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<?php
$this->assign('title', __('Sign In'));
$this->assign('description', __('Sign in to start your session'));
?>

<p class="login-box-msg"><?= __('Sign in to start your session') ?></p>

<?= $this->Form->create($user, ['id' => 'signin-form']); ?>

<?=
$this->Form->control('username', [
    'label' => false,
    'placeholder' => __('Username or email address'),
    'class' => 'form-control',
])
?>

<?=
$this->Form->control('password', [
    'label' => false,
    'placeholder' => __('Password'),
    'class' => 'form-control',
])
?>

<?= $this->Form->control('remember_me', [
    'type' => 'checkbox',
    'label' => __('Remember me'),
]) ?>


<?php if ((get_option('enable_captcha_signin', 'no') == 'yes') && isset_captcha()) : ?>
    <div class="form-group captcha">
        <div id="captchaSignin" style="display: inline-block;"></div>
    </div>
<?php endif; ?>

<?= $this->Form->button(__('Sign In'), [
    'class' => 'btn btn-primary btn-block btn-flat btn-captcha',
    'id' => 'invisibleCaptchaSignin',
]); ?>

<?= $this->Form->end() ?>

<div class="social-auth-links text-center">
    <p>- <?= __("OR") ?> -</p>

    <?php if ((bool)get_option('social_login_facebook', false)) : ?>
        <?php
        echo $this->Form->postLink(
            '<i class="fa fa-facebook"></i> ' . __("Sign in with Facebook"),
            [
                'prefix' => false,
                'plugin' => 'ADmad/SocialAuth',
                'controller' => 'Auth',
                'action' => 'login',
                'provider' => 'facebook',
                '?' => ['redirect' => $this->request->getQuery('redirect')],
            ],
            [
                'class' => 'btn btn-block btn-social btn-facebook',
                'style' => 'margin-bottom: 5px;',
                'escape' => false,
            ]
        );
        ?>
    <?php endif; ?>

    <?php if ((bool)get_option('social_login_twitter', false)) : ?>
        <?php
        echo $this->Form->postLink(
            '<i class="fa fa-twitter"></i> ' . __("Sign in with Twitter"),
            [
                'prefix' => false,
                'plugin' => 'ADmad/SocialAuth',
                'controller' => 'Auth',
                'action' => 'login',
                'provider' => 'twitter',
                '?' => ['redirect' => $this->request->getQuery('redirect')],
            ],
            [
                'class' => 'btn btn-block btn-social btn-twitter',
                'style' => 'margin-bottom: 5px;',
                'escape' => false,
            ]
        );
        ?>
    <?php endif; ?>

    <?php if ((bool)get_option('social_login_google', false)) : ?>
        <?php
        echo $this->Form->postLink(
            '<i class="fa fa-google"></i> ' . __("Sign in with Google"),
            [
                'prefix' => false,
                'plugin' => 'ADmad/SocialAuth',
                'controller' => 'Auth',
                'action' => 'login',
                'provider' => 'google',
                '?' => ['redirect' => $this->request->getQuery('redirect')],
            ],
            [
                'class' => 'btn btn-block btn-social btn-google',
                'style' => 'margin-bottom: 5px;',
                'escape' => false,
            ]
        );
        ?>
    <?php endif; ?>
</div>

<a href="<?= $this->Url->build([
    'controller' => 'Users',
    'action' => 'forgotPassword',
    'prefix' => 'auth',
]); ?>"><?= __('I forgot my password') ?></a>
<br>
<?php if ((bool)get_option('close_registration', false) === false) : ?>
    <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'signup', 'prefix' => 'auth']); ?>"
       class="text-center"><?= __('Register a new membership') ?></a>
<?php endif; ?>

<?php $this->start('scriptBottom'); ?>

<script>
  var url_href = window.location.href;
  if (url_href.substr(-1) === '#') {
    history.pushState('', document.title, url_href.substr(0, url_href.length - 1));
  }
</script>

<?php $this->end(); ?>

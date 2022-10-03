<?php

namespace App\Controller;

use Cake\Event\Event;
use Securimage;

/**
 * @property \Cake\ORM\Table $Securimage
 */
class SecurimageController extends FrontController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['show', 'play', 'renderCaptcha']);
    }

    public function show()
    {
        $this->autoRender = false;

        return require ROOT . '/vendor/dapphp/securimage/securimage_show.php';
    }

    public function play()
    {
        $this->autoRender = false;

        return require ROOT . '/vendor/dapphp/securimage/securimage_play.php';
    }

    /**
     * @param string $namespace
     * @return \Cake\Http\Response|string
     * @link https://github.com/dapphp/securimage/blob/master/examples/multiple_captchas_single_page.php
     */
    public function renderCaptcha($namespace)
    {
        $this->autoRender = false;

        $view = new \Cake\View\View($this->getRequest(), $this->getResponse());

        $html = '<div style="width:260px;">';

        $html .= Securimage::getCaptchaHtml([
            'input_id' => $namespace . '_captcha',
            'input_name' => 'captcha_code',
            'image_id' => $namespace . '_captcha_img',
            'namespace' => $namespace,
            'show_image_url' => $view->Url->build('/securimage/show'),
            'audio_play_url' => $view->Url->build('/securimage/play'),
            'audio_icon_url' => $view->Url->build('/assets/securimage/images/audio_icon.png'),
            'refresh_icon_url' => $view->Url->build('/assets/securimage/images/refresh.png'),
            'loading_icon_url' => $view->Url->build('/assets/securimage/images/loading.png'),
            'js_file_path' => $view->Url->build('/assets/securimage/securimage.js'),
            'input_text' => '',
            'input_attributes' => [
                'class' => 'form-control',
                'placeholder' => __('Type the captcha text'),
                'style' => 'width: 100%;',
                'required' => 'required',
            ],
        ]);

        $html .= '<input type="hidden" name="captcha_namespace" value="' . $namespace . '" />';

        $html .= '</div';

        $this->setResponse($this->getResponse()->withType('html'));
        $this->setResponse($this->getResponse()->withStringBody($html));

        return $this->getResponse();
    }
}

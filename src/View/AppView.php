<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\View;

use Cake\View\View;

/**
 * Application View
 *
 * Your application's default view class
 *
 * @property \App\View\Helper\AssetsHelper $Assets
 *
 * @link https://book.cakephp.org/3/en/views.html#the-app-view
 */
class AppView extends View
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading helpers.
     *
     * e.g. `$this->loadHelper('Html');`
     *
     * @return void
     */
    public function initialize()
    {
        $this->loadHelper('Form', [
            'templates' => 'app_form',
        ]);
        $this->loadHelper('Assets');

        $this->Form->unlockField('g-recaptcha-response');

        $this->Form->unlockField('h-captcha-response');

        $this->Form->unlockField('adcopy_challenge');
        $this->Form->unlockField('adcopy_response');

        $this->Form->unlockField('captcha_namespace');
        $this->Form->unlockField('captcha_code');
    }
}

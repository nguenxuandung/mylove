<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Securimage;

class CaptchaComponent extends Component
{
    public function verify($post_data)
    {
        $captcha_type = get_option('captcha_type');

        if ($captcha_type == 'recaptcha') {
            return $this->recaptchaVerify($post_data);
        }

        if ($captcha_type == 'invisible-recaptcha') {
            return $this->invisibleRecaptchaVerify($post_data);
        }

        if ($captcha_type === 'hcaptcha_checkbox') {
            return $this->hcaptchaCheckboxVerify($post_data);
        }

        if ($captcha_type == 'solvemedia') {
            return $this->solvemediaVerify($post_data);
        }

        if ($captcha_type == 'securimage') {
            return $this->securimageVerify($post_data);
        }

        return false;
    }

    public function securimageVerify($post_data = [])
    {
        $securimage = new Securimage(['namespace' => $post_data['captcha_namespace']]);

        return (bool)$securimage->check($post_data['captcha_code']);
    }

    public function recaptchaVerify($post_data = [])
    {
        $recaptchaSecretKey = get_option('reCAPTCHA_secret_key');

        if (!isset($post_data['g-recaptcha-response'])) {
            $this->errorVerify($post_data);

            return false;
        }

        $data = [
            'secret' => $recaptchaSecretKey,
            'response' => $post_data['g-recaptcha-response'],
        ];

        $result = curlRequest('https://www.recaptcha.net/recaptcha/api/siteverify', 'POST', $data);
        $responseData = json_decode($result->body, true);

        if ($responseData['success']) {
            $this->successVerify($post_data);

            return true;
        }

        $this->errorVerify($post_data);

        return false;
    }

    public function invisibleRecaptchaVerify($post_data = [])
    {
        $recaptchaSecretKey = get_option('invisible_reCAPTCHA_secret_key');

        if (!isset($post_data['g-recaptcha-response'])) {
            $this->errorVerify($post_data);

            return false;
        }

        $data = [
            'secret' => $recaptchaSecretKey,
            'response' => $post_data['g-recaptcha-response'],
        ];

        $result = curlRequest('https://www.recaptcha.net/recaptcha/api/siteverify', 'POST', $data);
        $responseData = json_decode($result->body, true);

        if ($responseData['success']) {
            $this->successVerify($post_data);

            return true;
        }

        $this->errorVerify($post_data);

        return false;
    }

    public function hcaptchaCheckboxVerify($post_data = [])
    {
        $secretKey = \get_option('hcaptcha_checkbox_secret_key');

        if (!isset($post_data['h-captcha-response'])) {
            $this->errorVerify($post_data);

            return false;
        }

        $data = [
            'secret' => $secretKey,
            'response' => $post_data['h-captcha-response'],
        ];

        $result = curlRequest('https://hcaptcha.com/siteverify', 'POST', $data);
        $responseData = json_decode($result->body, true);

        if ($responseData['success']) {
            $this->successVerify($post_data);

            return true;
        }

        $this->errorVerify($post_data);

        return false;
    }

    public function solvemediaVerify($post_data = [])
    {
        $solvemedia_verification_key = get_option('solvemedia_verification_key');
        $solvemedia_authentication_key = get_option('solvemedia_authentication_key');

        if (!isset($post_data['adcopy_challenge']) || !isset($post_data['adcopy_response'])) {
            $this->errorVerify($post_data);

            return false;
        }

        $data = [
            'privatekey' => $solvemedia_verification_key,
            'challenge' => $post_data['adcopy_challenge'],
            'response' => $post_data['adcopy_response'],
            'remoteip' => get_ip(),
        ];

        $result = curlRequest('http://verify.solvemedia.com/papi/verify', 'POST', $data);
        $answers = explode("\n", $result->body);

        $hash = sha1($answers[0] . $post_data['adcopy_challenge'] . $solvemedia_authentication_key);

        if ($hash !== $answers[2]) {
            $this->errorVerify($post_data);

            return false;
        }

        if (trim($answers[0]) == 'true') {
            $this->successVerify($post_data);

            return true;
        }

        $this->errorVerify($post_data);

        return false;
    }

    public function successVerify($post_data)
    {
        $this->onetimeCaptcha($post_data);
    }

    public function errorVerify($post_data)
    {
    }

    public function onetimeCaptcha($post_data)
    {
        if (!isset($_SESSION['Auth']['User']['plan']['onetime_captcha'])) {
            return;
        }

        if (!$_SESSION['Auth']['User']['plan']['onetime_captcha']) {
            return;
        }

        if (empty($post_data['f_n'])) {
            return;
        }

        if ($post_data['f_n'] === 'slc') {
            $salt = \Cake\Utility\Security::salt();
            $_SESSION['onetime_captcha'] = sha1($salt . get_ip() . $_SERVER['HTTP_USER_AGENT']);
        }
    }
}

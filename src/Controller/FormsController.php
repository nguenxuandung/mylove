<?php

namespace App\Controller;

use App\Form\ContactForm;
use Cake\Event\Event;

/**
 * @property \App\Controller\Component\CaptchaComponent $Captcha
 * @property \Cake\ORM\Table $Forms
 */
class FormsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Captcha');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['contact']);
    }

    public function contact()
    {
        $this->autoRender = false;

        $this->setResponse($this->getResponse()->withType('json'));

        $contact = new ContactForm();

        if (!$this->getRequest()->is('ajax')) {
            $content = [
                'status' => 'error',
                'message' => __('Bad Request.'),
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->getResponse();
        }

        if ((get_option('enable_captcha_contact') == 'yes') &&
            isset_captcha() &&
            !$this->Captcha->verify($this->getRequest()->getData())
        ) {
            $content = [
                'status' => 'error',
                'message' => __('The CAPTCHA was incorrect. Try again'),
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->getResponse();
        }

        try {
            if ($contact->execute($this->getRequest()->getData())) {
                $content = [
                    'status' => 'success',
                    'message' => __('Your message has been sent!'),
                ];
                $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

                return $this->getResponse();
            }
        } catch (\Exception $exception) {
            \Cake\Log\Log::write('error', $exception->getMessage());
        }

        $content = [
            'status' => 'error',
            //'message' => serialize($contact->errors()),
            'message' => __('Can\'t send the message. Please try again later.'),
        ];
        $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

        return $this->getResponse();
    }
}

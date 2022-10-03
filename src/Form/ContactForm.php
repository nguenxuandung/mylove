<?php

namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Cake\Mailer\Email;

// http://book.cakephp.org/3.0/en/core-libraries/form.html

class ContactForm extends Form
{
    protected function _buildSchema(Schema $schema)
    {
        return $schema
            ->addField('name', 'string')
            ->addField('subject', 'string')
            ->addField('email', ['type' => 'string'])
            ->addField('message', ['type' => 'text'])
            ->addField('accept', ['type' => 'checkbox']);
    }

    protected function _buildValidator(Validator $validator)
    {
        return $validator
            ->notBlank('name', __('A name is required'))
            ->add('email', 'format', [
                'rule' => 'email',
                'message' => __('A valid email address is required')
            ])
            ->notBlank('subject', __('A subject is required'))
            ->notBlank('message', __('Message body is required'))
            ->equals('accept', 1, __('You must accept our Privacy Policy before contacting us.'));
    }

    protected function _execute(array $data)
    {
        $email = new Email();
        $email
            ->setProfile(get_option('email_method', 'default'))
            ->setReplyTo($data['email'], $data['name'])
            ->setTo(get_option('admin_email'))
            ->setSubject(h(get_option('site_name')) . ': ' . h($data['subject']))
            ->setViewVars([
                'name' => $data['name'],
                'message' => $data['message']
            ])
            ->setTemplate('contact')// By default template with same name as method name is used.
            ->setEmailFormat('html')
            ->send();

        return true;
    }
}

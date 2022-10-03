<?php

namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Cake\Mailer\Email;

// http://book.cakephp.org/3.0/en/core-libraries/form.html

class MessageUserForm extends Form
{
    protected function _buildSchema(Schema $schema)
    {
        return $schema
            ->addField('email', ['type' => 'string'])
            ->addField('subject', 'string')
            ->addField('message', ['type' => 'text']);
    }

    protected function _buildValidator(Validator $validator)
    {
        return $validator
            ->add('email', 'format', [
                'rule' => 'email',
                'message' => __('A valid email address is required')
            ])
            ->notEmpty('subject', __('A subject is required'))
            ->notEmpty('message', __('Message body is required'));
    }

    protected function _execute(array $data)
    {
        $email = new Email();
        $email
            ->profile(get_option('email_method', 'default'))
            ->replyTo(get_option('admin_email'), h(get_option('site_name')))
            ->to($data['email'])
            ->subject(h($data['subject']))
            ->viewVars([
                'message' => $data['message']
            ])
            ->template('message_user')// By default template with same name as method name is used.
            ->emailFormat('both')
            ->send();

        return true;
    }
}

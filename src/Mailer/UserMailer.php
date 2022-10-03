<?php

namespace App\Mailer;

use Cake\Mailer\Mailer;

class UserMailer extends Mailer
{
    public function activation($user)
    {
        $this
            ->setProfile(get_option('email_method', 'default'))
            ->setFrom([get_option('email_from', 'no_reply@localhost') => get_option('email_from_name')])
            ->setTo($user->email)
            ->setSubject(__("New Account"))
            ->setViewVars([
                'username' => $user->username,
                'activation_key' => $user->activation_key,
            ])
            ->setTemplate('register')// By default template with same name as method name is used.
            ->setLayout('app')
            ->setEmailFormat('both');
    }

    public function changeEmail($user)
    {
        $this
            ->setProfile(get_option('email_method', 'default'))
            ->setFrom([get_option('email_from', 'no_reply@localhost') => get_option('email_from_name')])
            ->setTo($user->temp_email)
            ->setSubject(__("Change Email"))
            ->setViewVars([
                'username' => $user->username,
                'activation_key' => $user->activation_key,
            ])
            ->setTemplate('change_email')// By default template with same name as method name is used.
            ->setLayout('app')
            ->setEmailFormat('both');
    }

    public function forgotPassword($user)
    {
        $this
            ->setProfile(get_option('email_method', 'default'))
            ->setFrom([get_option('email_from', 'no_reply@localhost') => get_option('email_from_name')])
            ->setTo($user->email)
            ->setSubject(__("Password Reset"))
            ->setViewVars([
                'username' => $user->username,
                'activation_key' => $user->activation_key,
            ])
            ->setTemplate('reset_password')// By default template with same name as method name is used.
            ->setLayout('app')
            ->setEmailFormat('both');
    }
}

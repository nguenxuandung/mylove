<?php

namespace App\Mailer;

use Cake\Mailer\Mailer;

class NotificationMailer extends Mailer
{
    public function newRegistration($user)
    {
        $this
            ->setProfile(get_option('email_method', 'default'))
            ->setFrom([get_option('email_from', 'no_reply@localhost') => get_option('email_from_name')])
            ->setTo(get_option('admin_email'))
            ->setSubject(__("New User Registration"))
            ->setViewVars([
                'user' => $user,
            ])
            ->setTemplate('admin_register')// By default template with same name as method name is used.
            ->setLayout('app')
            ->setEmailFormat('both');
    }

    public function newWithdrawal($withdraw, $user)
    {
        $this
            ->setProfile(get_option('email_method', 'default'))
            ->setFrom([get_option('email_from', 'no_reply@localhost') => get_option('email_from_name')])
            ->setTo(get_option('admin_email'))
            ->setSubject(__("New Withdrawal Request"))
            ->setViewVars([
                'withdraw' => $withdraw,
                'user' => $user,
            ])
            ->setTemplate('admin_withdrawal')// By default template with same name as method name is used.
            ->setLayout('app')
            ->setEmailFormat('both');
    }

    public function newInvoice($invoice, $user)
    {
        $this
            ->setProfile(get_option('email_method', 'default'))
            ->setFrom([get_option('email_from', 'no_reply@localhost') => get_option('email_from_name')])
            ->setTo(get_option('admin_email'))
            ->setSubject(__("New Invoice"))
            ->setViewVars([
                'invoice' => $invoice,
                'user' => $user,
            ])
            ->setTemplate('admin_invoice')// By default template with same name as method name is used.
            ->setLayout('app')
            ->setEmailFormat('both');
    }

    public function newPaidInvoice($invoice, $user)
    {
        $this
            ->setProfile(get_option('email_method', 'default'))
            ->setFrom([get_option('email_from', 'no_reply@localhost') => get_option('email_from_name')])
            ->setTo(get_option('admin_email'))
            ->setSubject(__("Paid Invoice"))
            ->setViewVars([
                'invoice' => $invoice,
                'user' => $user,
            ])
            ->setTemplate('admin_paid_invoice')// By default template with same name as method name is used.
            ->setLayout('app')
            ->setEmailFormat('both');
    }

    public function approveWithdraw($withdraw, $user)
    {
        $this
            ->setProfile(get_option('email_method', 'default'))
            ->setFrom([get_option('email_from', 'no_reply@localhost') => get_option('email_from_name')])
            ->setTo($user->email)
            ->setSubject(__("Your Request for Withdrawal has been Approved"))
            ->setViewVars([
                'withdraw' => $withdraw,
                'user' => $user,
            ])
            ->setTemplate('approve_withdraw')// By default template with same name as method name is used.
            ->setLayout('app')
            ->setEmailFormat('both');
    }

    public function completeWithdraw($withdraw, $user)
    {
        $this
            ->setProfile(get_option('email_method', 'default'))
            ->setFrom([get_option('email_from', 'no_reply@localhost') => get_option('email_from_name')])
            ->setTo($user->email)
            ->setSubject(__("Your Request for Withdrawal has been Processed"))
            ->setViewVars([
                'withdraw' => $withdraw,
                'user' => $user,
            ])
            ->setTemplate('complete_withdraw')// By default template with same name as method name is used.
            ->setLayout('app')
            ->setEmailFormat('both');
    }

    public function cancelWithdraw($withdraw, $user)
    {
        $this
            ->setProfile(get_option('email_method', 'default'))
            ->setFrom([get_option('email_from', 'no_reply@localhost') => get_option('email_from_name')])
            ->setTo($user->email)
            ->setSubject(__("Your Request for Withdrawal has been Canceled"))
            ->setViewVars([
                'withdraw' => $withdraw,
                'user' => $user,
            ])
            ->setTemplate('cancel_withdraw')// By default template with same name as method name is used.
            ->setLayout('app')
            ->setEmailFormat('both');
    }

    public function returnWithdraw($withdraw, $user)
    {
        $this
            ->setProfile(get_option('email_method', 'default'))
            ->setFrom([get_option('email_from', 'no_reply@localhost') => get_option('email_from_name')])
            ->setTo($user->email)
            ->setSubject(__("Your Request for Withdrawal has been Returned"))
            ->setViewVars([
                'withdraw' => $withdraw,
                'user' => $user,
            ])
            ->setTemplate('return_withdraw')// By default template with same name as method name is used.
            ->setLayout('app')
            ->setEmailFormat('both');
    }
}

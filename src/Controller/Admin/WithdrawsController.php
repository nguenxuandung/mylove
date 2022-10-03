<?php

namespace App\Controller\Admin;

use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Mailer\MailerAwareTrait;
use Cake\ORM\TableRegistry;

/**
 * @property \App\Model\Table\StatisticsTable $Statistics
 * @property \App\Model\Table\WithdrawsTable $Withdraws
 */
class WithdrawsController extends AppAdminController
{
    use MailerAwareTrait;

    public function index()
    {
        $conditions = [];

        $filter_fields = ['user_id', 'status', 'method'];

        //Transform POST into GET
        if ($this->getRequest()->is(['post', 'put']) && isset($this->getRequest()->data['Filter'])) {
            $filter_url = [];

            $filter_url['controller'] = $this->getRequest()->params['controller'];

            $filter_url['action'] = $this->getRequest()->params['action'];

            // We need to overwrite the page every time we change the parameters
            $filter_url['page'] = 1;

            // for each filter we will add a GET parameter for the generated url
            foreach ($this->getRequest()->data['Filter'] as $name => $value) {
                if (in_array($name, $filter_fields) && strlen($value)) {
                    // You might want to sanitize the $value here
                    // or even do a urlencode to be sure
                    $filter_url[$name] = urlencode($value);
                }
            }
            // now that we have generated an url with GET parameters,
            // we'll redirect to that page
            return $this->redirect($filter_url);
        } else {
            // Inspect all the named parameters to apply the filters
            foreach ($this->getRequest()->getQuery() as $param_name => $value) {
                $value = urldecode($value);
                if (in_array($param_name, $filter_fields)) {
                    $conditions['Withdraws.' . $param_name] = $value;
                    $this->getRequest()->data['Filter'][$param_name] = $value;
                }
            }
        }

        $query = $this->Withdraws->find()
            ->contain(['Users'])
            ->where($conditions);
        /*
        ->where([
            'Users.status' => 1
        ]);
        */
        $withdraws = $this->paginate($query);
        $this->set('withdraws', $withdraws);

        $publishers_earnings = $this->Withdraws->Users->find()
            ->select(['total' => 'SUM(publisher_earnings)'])
            ->first();
        $this->set('publishers_earnings', $publishers_earnings->total);

        $referral_earnings = $this->Withdraws->Users->find()
            ->select(['total' => 'SUM(referral_earnings)'])
            ->first();
        $this->set('referral_earnings', $referral_earnings->total);

        $pending_withdrawn = $this->Withdraws->find()
            ->select(['total' => 'SUM(amount)'])
            ->where(['status' => 2])
            ->first();

        $this->set('pending_withdrawn', $pending_withdrawn->total);

        $tolal_withdrawn = $this->Withdraws->find()
            ->select(['total' => 'SUM(amount)'])
            ->where(['status' => 3])
            ->first();

        $this->set('tolal_withdrawn', $tolal_withdrawn->total);
    }

    public function export()
    {
        if ($this->getRequest()->is('post')) {
            $fields = $this->getRequest()->data['fields'];
            $conditions = $this->getRequest()->data['conditions'];
            if (empty($fields)) {
                $this->Flash->error(__('Please, select fields to export.'));

                return null;
            }
            $this->processExport($fields, $conditions);
        }
    }

    protected function processExport($fields, $conditions)
    {
        $this->autoRender = false;

        foreach ($conditions as $key => $value) {
            if (empty($value)) {
                unset($conditions[$key]);
            }
        }

        $this->setResponse($this->getResponse()->withType('csv'));
        $this->setResponse($this->getResponse()->withDownload('export-' . date('Y-m-d') . '.csv'));

        $users = $this->Withdraws->find()
            ->select($fields)
            ->where($conditions)
            ->order(['id' => 'ASC'])->toArray();

        $header_fields = array_map(function ($value) {
            return '"' . $value . '"';
        }, $fields);

        $content = implode(",", $header_fields) . "\n";

        foreach ($users as $user) {
            $user_data = [];
            foreach ($fields as $field) {
                $user_data[] = '"' . $user->$field . '"';
            }
            $content .= implode(",", $user_data) . "\n";
        }

        $this->setResponse($this->getResponse()->withStringBody($content));

        return $this->getResponse();
    }

    public function view($id = null)
    {
        @ini_set('memory_limit', '768M');

        if (!$id) {
            throw new NotFoundException(__('Invalid Withdraw'));
        }

        /** @var \App\Model\Entity\Withdraw $withdraw */
        $withdraw = $this->Withdraws->find()->contain(['Users'])->where(['Withdraws.id' => $id])->first();
        if (!$withdraw) {
            throw new NotFoundException(__('Invalid Withdraw'));
        }

        $this->set('withdraw', $withdraw);

        /** @var \App\Model\Entity\Withdraw $pre_withdraw */
        $pre_withdraw = $this->Withdraws->find()
            ->where([
                'created <' => $withdraw->created,
                'user_id' => $withdraw->user_id,
                'status !=' => 5,
            ])
            ->order(['created' => 'DESC'])
            ->first();

        $this->set('pre_withdraw', $pre_withdraw);

        $this->loadModel('Statistics');

        if ($withdraw->json_data) {
            $data = json_decode($withdraw->json_data, true);
            $this->set('countries', $data['countries']);
            $this->set('reasons', $data['reasons']);
            $this->set('ips', $data['ips']);
            $this->set('referrers', $data['referrers']);
            $this->set('links', $data['links']);
        } else {
            $date1 = (!$pre_withdraw) ? '0000-00-00 00:00:00' : $pre_withdraw->created;
            $date2 = $withdraw->created;

            $countries = $this->Statistics->find()
                ->select([
                    'country',
                    'count' => 'COUNT(country)',
                    'publisher_earnings' => 'SUM(publisher_earn)',
                    //'referral_earnings' => 'SUM(referral_earn)'
                ])
                ->where([
                    "Statistics.created BETWEEN :date1 AND :date2",
                    'Statistics.publisher_earn >' => 0,
                    'Statistics.user_id' => $withdraw->user_id,
                ])
                ->bind(':date1', $date1, 'datetime')
                ->bind(':date2', $date2, 'datetime')
                ->order(['count' => 'DESC'])
                ->group(['country'])
                ->toArray();

            $this->set('countries', $countries);

            $reasons = $this->Statistics->find()
                ->select([
                    'reason',
                    'count' => 'COUNT(reason)',
                ])
                ->where([
                    "Statistics.created BETWEEN :date1 AND :date2",
                    'Statistics.user_id' => $withdraw->user_id,
                ])
                ->bind(':date1', $date1, 'datetime')
                ->bind(':date2', $date2, 'datetime')
                ->order(['count' => 'DESC'])
                ->group(['reason'])
                ->toArray();

            $this->set('reasons', $reasons);

            $ips = $this->Statistics->find()
                ->select([
                    'ip',
                    'count' => 'COUNT(ip)',
                    'publisher_earnings' => 'SUM(publisher_earn)',
                    //'referral_earnings' => 'SUM(referral_earn)'
                ])
                ->where([
                    "Statistics.created BETWEEN :date1 AND :date2",
                    'Statistics.publisher_earn >' => 0,
                    'Statistics.user_id' => $withdraw->user_id,
                ])
                ->bind(':date1', $date1, 'datetime')
                ->bind(':date2', $date2, 'datetime')
                ->order(['count' => 'DESC'])
                ->group(['ip'])
                ->toArray();

            $this->set('ips', $ips);

            $referrers = $this->Statistics->find()
                ->select([
                    'referer_domain',
                    'count' => 'COUNT(referer_domain)',
                    'publisher_earnings' => 'SUM(publisher_earn)',
                    //'referral_earnings' => 'SUM(referral_earn)'
                ])
                ->where([
                    "Statistics.created BETWEEN :date1 AND :date2",
                    'Statistics.publisher_earn >' => 0,
                    'Statistics.user_id' => $withdraw->user_id,
                ])
                ->bind(':date1', $date1, 'datetime')
                ->bind(':date2', $date2, 'datetime')
                ->order(['count' => 'DESC'])
                ->group(['referer_domain'])
                ->toArray();

            $this->set('referrers', $referrers);

            $links = $this->Statistics->find()
                ->contain(['Links'])
                ->select([
                    'Links.alias',
                    'Links.url',
                    'Links.title',
                    'Links.domain',
                    'count' => 'COUNT(Statistics.link_id)',
                    'publisher_earnings' => 'SUM(Statistics.publisher_earn)',
                ])
                ->where([
                    "Statistics.created BETWEEN :date1 AND :date2",
                    'Statistics.publisher_earn >' => 0,
                    'Statistics.user_id' => $withdraw->user_id,
                ])
                ->order(['count' => 'DESC'])
                ->bind(':date1', $date1, 'datetime')
                ->bind(':date2', $date2, 'datetime')
                ->group('Statistics.link_id')
                ->toArray();

            $this->set('links', $links);

            $withdraw->json_data = json_encode([
                'countries' => $countries,
                'reasons' => $reasons,
                'ips' => $ips,
                'referrers' => $referrers,
                'links' => $links,
            ]);
            $this->Withdraws->save($withdraw);
        }
    }

    /*
    public function edit($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Invalid Withdrawal Request'));
        }

        $withdraw = $this->Withdraws->find()->contain(['Users'])->where(['Withdraws.id' => $id])->first();
        if (!$withdraw) {
            throw new NotFoundException(__('Invalid Withdrawal Request'));
        }

        if ($this->getRequest()->is(['post', 'put'])) {
            $this->getRequest()->data['amount'] = $withdraw->amount;
            $withdraw = $this->Withdraws->patchEntity($withdraw, $this->getRequest()->data);
            if ($this->Withdraws->save($withdraw)) {
                $this->Flash->success(__('The withdrawal request has been updated.'));
                return $this->redirect(['action' => 'index']);
            } else {
                debug($withdraw->errors());
                $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
            }
        }
        $this->set('withdraw', $withdraw);
    }
    */

    public function request($userId)
    {
        if ($this->getRequest()->getParam('_csrfToken') !== $this->getRequest()->getQuery('token')) {
            throw new ForbiddenException();
        }

        $user = $this->Withdraws->Users->get($userId);

        $withdraw = $this->Withdraws->newEntity();
        $data = [];

        $withdraw->user_id = $userId;
        $withdraw->status = 2;
        $withdraw->publisher_earnings = price_database_format($user->publisher_earnings);
        $withdraw->referral_earnings = price_database_format($user->referral_earnings);

        $method = $user->withdrawal_method;
        $account = $user->withdrawal_account;

        if ($method !== 'wallet' && (empty($method) || empty($account))) {
            $this->Flash->error(__('User should fill his withdrawal info from his profile settings.'));

            return $this->redirect(['controller' => 'Users', 'action' => 'index']);
        }

        $data['amount'] = price_database_format($user->publisher_earnings + $user->referral_earnings);

        $withdrawal_methods = array_column_polyfill(get_withdrawal_methods(), 'amount', 'id');

        if (!in_array($user->withdrawal_method, array_keys($withdrawal_methods))) {
            $this->Flash->error(__('Invalid withdrawal method.'));

            return $this->redirect(['controller' => 'Users', 'action' => 'index']);
        }

        $minimum_withdrawal_amount = $withdrawal_methods[$user->withdrawal_method];

        if ($data['amount'] < $minimum_withdrawal_amount) {
            $this->Flash->error(__(
                'Withdraw amount should be equal or greater than {0}.',
                display_price_currency($minimum_withdrawal_amount)
            ));

            return $this->redirect(['controller' => 'Users', 'action' => 'index']);
        }

        $withdraw->method = $method;
        $withdraw->account = $account;

        $withdraw = $this->Withdraws->patchEntity($withdraw, $data);
        if ($this->Withdraws->save($withdraw)) {
            // Rest publisher balance
            $user->publisher_earnings = 0;
            $user->referral_earnings = 0;
            $this->Withdraws->Users->save($user);

            $queuedJobsTable = TableRegistry::getTableLocator()->get('Queue.QueuedJobs');
            $queuedJobsTable->createJob('Withdraw', ['id' => $withdraw->id]);

            if ((bool)get_option('alert_admin_new_withdrawal', 1)) {
                try {
                    $this->getMailer('Notification')->send('newWithdrawal', [$withdraw, $user]);
                } catch (\Exception $exception) {
                    \Cake\Log\Log::write('error', $exception->getMessage());
                }
            }

            $this->Flash->success(__('The withdrawal has been requested and under review.'));
        } else {
            $this->Flash->error(__('Unable to request the withdrawal.'));
        }

        return $this->redirect(['controller' => 'Users', 'action' => 'index']);
    }

    public function cancel($id)
    {
        $this->getRequest()->allowMethod(['post', 'put']);

        $withdraw = $this->Withdraws->get($id);

        $user = $this->Withdraws->Users->get($withdraw->user_id);

        $withdraw->status = 4;

        if($this->getRequest()->getData('user_note')) {
            $withdraw->user_note = $this->getRequest()->getData('user_note');
        }

        if ($this->Withdraws->save($withdraw)) {
            if (!empty($user->email)) {
                if ((bool)get_option('alert_member_canceled_withdraw', 1)) {
                    $this->getMailer('Notification')->send('cancelWithdraw', [$withdraw, $user]);
                }
            }

            $this->Flash->success(__('The withdrawal request with id: {0} has been canceled.', $id));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function returned($id)
    {
        $this->getRequest()->allowMethod(['post', 'put']);

        $withdraw = $this->Withdraws->get($id);

        $user = $this->Withdraws->Users->get($withdraw->user_id);

        $user->publisher_earnings = price_database_format($user->publisher_earnings + $withdraw->publisher_earnings);
        $user->referral_earnings = price_database_format($user->referral_earnings + $withdraw->referral_earnings);

        $this->Withdraws->Users->save($user);

        $withdraw->status = 5;

        if ($this->Withdraws->save($withdraw)) {
            if (!empty($user->email)) {
                if ((bool)get_option('alert_member_returned_withdraw', 1)) {
                    $this->getMailer('Notification')->send('returnWithdraw', [$withdraw, $user]);
                }
            }

            $this->Flash->success(__('The withdrawal request money returned to the user account.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function approve($id)
    {
        $this->getRequest()->allowMethod(['post', 'put']);

        $withdraw = $this->Withdraws->get($id);
        $user = $this->Withdraws->Users->get($withdraw->user_id);

        $withdraw->status = 1;

        if ($this->Withdraws->save($withdraw)) {
            if (!empty($user->email)) {
                if ((bool)get_option('alert_member_approved_withdraw', 1)) {
                    $this->getMailer('Notification')->send('approveWithdraw', [$withdraw, $user]);
                }
            }

            $this->Flash->success(__('The withdrawal request with id: {0} has been approved.', $id));

            return $this->redirect(['action' => 'index']);
        }
    }

    public function complete($id)
    {
        $this->getRequest()->allowMethod(['post', 'put']);

        $withdraw = $this->Withdraws->get($id);
        $user = $this->Withdraws->Users->get($withdraw->user_id);

        $withdraw->status = 3;

        if ($this->Withdraws->save($withdraw)) {
            if ($withdraw->method == 'wallet') {
                $user->wallet_money += $withdraw->amount;
                $this->Withdraws->Users->save($user);
            }

            if (!empty($user->email)) {
                if ((bool)get_option('alert_member_completed_withdraw', 1)) {
                    $this->getMailer('Notification')->send('completeWithdraw', [$withdraw, $user]);
                }
            }

            $this->Flash->success(__('The withdrawal request with id: {0} has been completed.', $id));

            return $this->redirect(['action' => 'index']);
        }
    }
}

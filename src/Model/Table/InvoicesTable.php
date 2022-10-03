<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Mailer\MailerAwareTrait;

/**
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Invoice get($primaryKey, $options = [])
 * @method \App\Model\Entity\Invoice newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Invoice[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Invoice|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Invoice saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Invoice patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Invoice[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Invoice findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @method \App\Model\Entity\Invoice[]|\Cake\Datasource\ResultSetInterface|false saveMany($entities, $options = [])
 * @method \App\Model\Entity\Invoice[]|\Cake\Datasource\ResultSetInterface saveManyOrFail($entities, $options = [])
 * @method \App\Model\Entity\Invoice[]|\Cake\Datasource\ResultSetInterface|false deleteMany($entities, $options = [])
 * @method \App\Model\Entity\Invoice[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail($entities, $options = [])
 */
class InvoicesTable extends Table
{
    use MailerAwareTrait;

    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
        $this->belongsTo('Users');
    }

    /**
     * @param \App\Model\Entity\Invoice|null $invoice
     * @return bool
     */
    public function successPayment($invoice = null)
    {
        if (!$invoice) {
            return false;
        }

        $user = $this->Users->find()->contain(['Plans'])->where(['Users.id' => $invoice->user_id])->first();

        // Plans
        if ($invoice->type === 1) {
            $this->Users = TableRegistry::getTableLocator()->get('Users');

            if ($invoice->status === 1) {
                $plan_expiration = new Time($user->expiration);
                if ($plan_expiration->isPast()) {
                    $plan_expiration = Time::now();
                }

                $payment_period = unserialize($invoice->data)['payment_period'];

                if ($payment_period === 'm') {
                    $expiration = $plan_expiration->addMonth();
                } else {
                    $expiration = $plan_expiration->addYear();
                }

                $user->expiration = $expiration;
                $user->plan_id = $invoice->rel_id;

                $this->Users->save($user);

                if (isset($_SESSION['Auth']['User']['id'])) {
                    if ($_SESSION['Auth']['User']['id'] === $user->id) {
                        $data = $this->Users->find()->contain(['Plans'])
                            ->where(['Users.id' => $user->id])
                            ->first()
                            ->toArray();
                        unset($data['password']);
                        //\Cake\Log\Log::write('debug', $data);
                        //$this->Auth->setUser($data);
                        $_SESSION['Auth']['User'] = $data;
                    }
                }

                if ((bool)get_option('alert_admin_paid_invoice', 1)) {
                    $this->getMailer('Notification')->send('newPaidInvoice', [$invoice, $user]);
                }
            }
        }

        // Campaigns
        if ($invoice->type === 2) {
            $this->Campaigns = TableRegistry::getTableLocator()->get('Campaigns');

            $campaign = $this->Campaigns->get($invoice->rel_id);

            if ($invoice->status === 1) {
                $campaign->status = 5;
                $this->Campaigns->save($campaign);

                if ((bool)get_option('alert_admin_paid_invoice', 1)) {
                    $this->getMailer('Notification')->send('newPaidInvoice', [$invoice, $user]);
                }
            } elseif ($invoice->status === 4) {
                $campaign->status = 7;
                $this->Campaigns->save($campaign);
            } elseif ($invoice->status === 5) {
                $campaign->status = 8;
                $this->Campaigns->save($campaign);
            }
        }

        // Wallet
        if ($invoice->type === 3) {
        }

        return true;
    }
}

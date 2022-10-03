<?php

namespace App\Controller\Member;

use Cake\Event\Event;
use Cake\Http\Exception\NotFoundException;
use Cake\Mailer\MailerAwareTrait;

/**
 * @property \App\Model\Table\InvoicesTable $Invoices
 * @property \App\Model\Table\CampaignsTable $Campaigns
 */
class CampaignsController extends AppMemberController
{
    use MailerAwareTrait;

    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['ipn']);
    }

    public function ipn()
    {
        return $this->redirect('/payment/ipn', 307);
    }

    protected function checkEnableAdvertising()
    {
        if (get_option('earning_mode', 'campaign') === 'simple' ||
            get_option('enable_advertising', 'yes') === 'no') {
            $this->Flash->error(__('Creating campaigns is currently disabled.'));

            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
    }

    public function view($id = null)
    {
        $this->checkEnableAdvertising();

        if (!$id) {
            throw new NotFoundException(__('Invalid campaign'));
        }

        $campaign = $this->Campaigns->findById($id)
            ->contain(['CampaignItems'])
            ->where(['user_id' => $this->Auth->user('id')])
            ->first();
        if (!$campaign) {
            throw new NotFoundException(__('Campaign Not Found'));
        }

        $this->set('campaign', $campaign);
    }

    public function index()
    {
        $this->checkEnableAdvertising();

        $conditions = [];

        $filter_fields = ['id', 'status', 'ad_type', 'name', 'other_fields'];

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
                    if (in_array($param_name, ['name'])) {
                        $conditions[] = [
                            ['Campaigns.' . $param_name . ' LIKE' => '%' . $value . '%'],
                        ];
                    } elseif (in_array($param_name, ['other_fields'])) {
                        $conditions['OR'] = [
                            ['Campaigns.website_title LIKE' => '%' . $value . '%'],
                            ['Campaigns.website_url LIKE' => '%' . $value . '%'],
                            ['Campaigns.banner_name LIKE' => '%' . $value . '%'],
                            ['Campaigns.banner_size LIKE' => '%' . $value . '%'],
                        ];
                    } elseif (in_array($param_name, ['id', 'status', 'ad_type'])) {
                        if ($param_name == 'status' && !in_array($value, [1, 2, 3, 4, 5, 6, 7, 8])) {
                            continue;
                        }
                        if ($param_name == 'ad_type' && !in_array($value, [1, 2, 3])) {
                            continue;
                        }
                        $conditions['Campaigns.' . $param_name] = $value;
                    }
                    $this->getRequest()->data['Filter'][$param_name] = $value;
                }
            }
        }

        $query = $this->Campaigns->find()
            ->contain(['CampaignItems'])
            ->where(['user_id' => $this->Auth->user('id')])
            ->where($conditions);
        $campaigns = $this->paginate($query);

        $this->set('campaigns', $campaigns);
    }

    public function pay($id = null)
    {
        $this->getRequest()->allowMethod(['post']);

        if (!$id) {
            throw new NotFoundException(__('Invalid request'));
        }

        $campaign = $this->Campaigns->findById($id)->contain('Invoices')
            ->where(['Campaigns.user_id' => $this->Auth->user('id')])
            ->first();

        if (!$campaign) {
            throw new NotFoundException(__('Not found campaign'));
        }

        if (isset($campaign->invoice->id)) {
            return $this->redirect(['controller' => 'Invoices', 'action' => 'view', $campaign->invoice->id]);
        }

        $this->loadModel('Invoices');

        $data = [
            'status' => 2, //Unpaid Invoice
            'user_id' => $this->Auth->user('id'),
            'description' => __("Advertising Campaign #") . $campaign->id,
            'type' => 2, //Campaign Invoice
            'rel_id' => $campaign->id, //Plan Id
            'payment_method' => '',
            'amount' => price_database_format($campaign->price),
            'data' => serialize([]),
        ];

        $invoice = $this->Invoices->newEntity($data);

        if ($this->Invoices->save($invoice)) {
            if ((bool)get_option('alert_admin_created_invoice', 0)) {
                $this->getMailer('Notification')->send('newInvoice', [$invoice, $this->logged_user]);
            }

            //$this->Flash->success(__('An invoice with id: {0} has been generated.', $invoice->id));
            return $this->redirect(['controller' => 'Invoices', 'action' => 'view', $invoice->id]);
        }
    }

    public function createInterstitial()
    {
        $this->checkEnableAdvertising();

        if (get_option('enable_interstitial', 'yes') == 'no') {
            $this->Flash->error(__('Creating interstitial campaigns is currently disabled.'));

            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }

        if ($this->getRequest()->is(['get']) && empty($this->getRequest()->getQuery('traffic_source'))) {
            return null;
        }

        $traffic_source = $this->getRequest()->getQuery('traffic_source');
        $interstitial_price = get_option('interstitial_price');

        $campaign = $this->Campaigns->newEntity(null, ['associated' => ['CampaignItems']]);
        $this->set('campaign', $campaign);

        if ($this->getRequest()->is('post')) {
            $campaign->user_id = $this->Auth->user('id');
            $campaign->ad_type = 1;
            $campaign->status = 6;

            $this->getRequest()->data['price'] = 0;

            foreach ($this->getRequest()->data['campaign_items'] as $key => $value) {
                if (empty($value['purchase'])) {
                    unset($this->getRequest()->data['campaign_items'][$key]);
                    continue;
                }

                $country = $this->getRequest()->data['campaign_items'][$key]['country'];

                $this->getRequest()->data['campaign_items'][$key]['advertiser_price'] =
                    price_database_format($interstitial_price[$country][$traffic_source]['advertiser']);

                $this->getRequest()->data['campaign_items'][$key]['publisher_price'] =
                    price_database_format($interstitial_price[$country][$traffic_source]['publisher']);

                $this->getRequest()->data['price'] +=
                    $value['purchase'] * $this->getRequest()->data['campaign_items'][$key]['advertiser_price'];

                $this->getRequest()->data['price'] = price_database_format($this->getRequest()->data['price']);
            }

            if (!empty(get_option('campaign_minimum_price'))) {
                if ($this->getRequest()->data['price'] < get_option('campaign_minimum_price')) {
                    $this->Flash->error(__('The campaign minimum price is {0}.', get_option('campaign_minimum_price')));

                    return null;
                }
            }

            if (count($this->getRequest()->data['campaign_items']) == 0) {
                $this->Flash->error(__('You must purchase at least from one country.'));

                return null;
            }

            $campaign = $this->Campaigns->patchEntity($campaign, $this->getRequest()->data);

            if ($this->Campaigns->save($campaign)) {
                $this->Flash->success(__('Your campaign has been added. After paying, we will review ' .
                    'it and it will appear on the website.'));

                return $this->redirect(['action' => 'view', $campaign->id]);
            } else {
                //debug($campaign->errors());
                $this->Flash->error(__('Unable to create your campaign.'));
            }
        }
        $this->set('campaign', $campaign);
    }

    public function createBanner()
    {
        $this->checkEnableAdvertising();

        if (get_option('enable_banner', 'yes') == 'no') {
            $this->Flash->error(__('Creating banner campaigns is currently disabled.'));

            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }

        if ($this->getRequest()->is(['get']) && empty($this->getRequest()->getQuery('traffic_source'))) {
            return null;
        }

        $traffic_source = $this->getRequest()->getQuery('traffic_source');
        $banner_price = get_option('banner_price');

        $campaign = $this->Campaigns->newEntity(null, ['associated' => ['CampaignItems']]);
        $this->set('campaign', $campaign);

        if ($this->getRequest()->is('post')) {
            $campaign->user_id = $this->Auth->user('id');
            $campaign->ad_type = 2;
            $campaign->status = 6;

            $this->getRequest()->data['price'] = 0;

            foreach ($this->getRequest()->data['campaign_items'] as $key => $value) {
                if (empty($value['purchase'])) {
                    unset($this->getRequest()->data['campaign_items'][$key]);
                    continue;
                }

                $country = $this->getRequest()->data['campaign_items'][$key]['country'];

                $this->getRequest()->data['campaign_items'][$key]['advertiser_price'] =
                    price_database_format($banner_price[$country][$traffic_source]['advertiser']);

                $this->getRequest()->data['campaign_items'][$key]['publisher_price'] =
                    price_database_format($banner_price[$country][$traffic_source]['publisher']);

                $this->getRequest()->data['price'] +=
                    $value['purchase'] * $this->getRequest()->data['campaign_items'][$key]['advertiser_price'];

                $this->getRequest()->data['price'] = price_database_format($this->getRequest()->data['price']);
            }

            if (!empty(get_option('campaign_minimum_price'))) {
                if ($this->getRequest()->data['price'] < get_option('campaign_minimum_price')) {
                    $this->Flash->error(__('The campaign minimum price is {0}.', get_option('campaign_minimum_price')));

                    return null;
                }
            }

            if (count($this->getRequest()->data['campaign_items']) == 0) {
                $this->Flash->error(__('You must purchase at least from one country.'));

                return null;
            }

            $campaign = $this->Campaigns->patchEntity($campaign, $this->getRequest()->data);

            if ($this->Campaigns->save($campaign)) {
                $this->Flash->success(__('Your campaign has been added. After paying, we will review ' .
                    'it and it will appear on the website.'));

                return $this->redirect(['action' => 'view', $campaign->id]);
            } else {
                $this->Flash->error(__('Unable to create your campaign.'));
            }
        }
        $this->set('campaign', $campaign);
    }

    public function createPopup()
    {
        $this->checkEnableAdvertising();

        if (get_option('enable_popup', 'yes') == 'no') {
            $this->Flash->error(__('Creating popup campaigns is currently disabled.'));

            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }

        if ($this->getRequest()->is(['get']) && empty($this->getRequest()->getQuery('traffic_source'))) {
            return null;
        }

        $traffic_source = $this->getRequest()->getQuery('traffic_source');
        $popup_price = get_option('popup_price');

        $campaign = $this->Campaigns->newEntity(null, ['associated' => ['CampaignItems']]);
        $this->set('campaign', $campaign);

        if ($this->getRequest()->is('post')) {
            $campaign->user_id = $this->Auth->user('id');
            $campaign->ad_type = 3;
            $campaign->status = 6;

            $this->getRequest()->data['price'] = 0;

            foreach ($this->getRequest()->data['campaign_items'] as $key => $value) {
                if (empty($value['purchase'])) {
                    unset($this->getRequest()->data['campaign_items'][$key]);
                    continue;
                }

                $country = $this->getRequest()->data['campaign_items'][$key]['country'];

                $this->getRequest()->data['campaign_items'][$key]['advertiser_price'] =
                    price_database_format($popup_price[$country][$traffic_source]['advertiser']);

                $this->getRequest()->data['campaign_items'][$key]['publisher_price'] =
                    price_database_format($popup_price[$country][$traffic_source]['publisher']);

                $this->getRequest()->data['price'] +=
                    $value['purchase'] * $this->getRequest()->data['campaign_items'][$key]['advertiser_price'];

                $this->getRequest()->data['price'] = price_database_format($this->getRequest()->data['price']);
            }

            if (!empty(get_option('campaign_minimum_price'))) {
                if ($this->getRequest()->data['price'] < get_option('campaign_minimum_price')) {
                    $this->Flash->error(__('The campaign minimum price is {0}.', get_option('campaign_minimum_price')));

                    return null;
                }
            }

            if (count($this->getRequest()->data['campaign_items']) == 0) {
                $this->Flash->error(__('You must purchase at least from one country.'));

                return null;
            }

            $campaign = $this->Campaigns->patchEntity($campaign, $this->getRequest()->data);

            if ($this->Campaigns->save($campaign)) {
                $this->Flash->success(__('Your campaign has been added. After paying, we will review ' .
                    'it and it will appear on the website.'));

                return $this->redirect(['action' => 'view', $campaign->id]);
            } else {
                //debug($campaign->errors());
                $this->Flash->error(__('Unable to create your campaign.'));
            }
        }
        $this->set('campaign', $campaign);
    }

    public function pause($id)
    {
        $this->checkEnableAdvertising();

        $this->getRequest()->allowMethod(['post', 'put']);

        $campaign = $this->Campaigns->findById($id)
            ->where(['user_id' => $this->Auth->user('id')])
            ->where(['status' => 1])
            ->first();

        if (!$campaign) {
            $this->Flash->success(__('Campaign not found'));

            return $this->redirect(['action' => 'index']);
        }

        $campaign->status = 2;
        $this->Campaigns->save($campaign);

        return $this->redirect(['action' => 'index']);
    }

    public function resume($id)
    {
        $this->checkEnableAdvertising();

        $this->getRequest()->allowMethod(['post', 'put']);

        $campaign = $this->Campaigns->findById($id)
            ->where(['user_id' => $this->Auth->user('id')])
            ->where(['status' => 2])
            ->first();

        if (!$campaign) {
            $this->Flash->success(__('Campaign not found'));

            return $this->redirect(['action' => 'index']);
        }

        $campaign->status = 1;
        $this->Campaigns->save($campaign);

        return $this->redirect(['action' => 'index']);
    }
}

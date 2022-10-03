<?php

namespace App\Controller\Admin;

use Cake\Http\Exception\NotFoundException;

/**
 * @property \App\Model\Table\CampaignsTable $Campaigns
 * @property \App\Model\Table\StatisticsTable $Statistics
 */
class CampaignsController extends AppAdminController
{
    public function index()
    {
        $conditions = [];

        $filter_fields = ['id', 'user_id', 'status', 'ad_type', 'name', 'other_fields'];

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
                            ['Campaigns.' . $param_name . ' LIKE' => '%' . $value . '%']
                        ];
                    } elseif (in_array($param_name, ['other_fields'])) {
                        $conditions['OR'] = [
                            ['Campaigns.website_title LIKE' => '%' . $value . '%'],
                            ['Campaigns.website_url LIKE' => '%' . $value . '%'],
                            ['Campaigns.banner_name LIKE' => '%' . $value . '%'],
                            ['Campaigns.banner_size LIKE' => '%' . $value . '%']
                        ];
                    } elseif (in_array($param_name, ['id', 'user_id', 'status', 'ad_type'])) {
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
            ->contain(['Users', 'CampaignItems'])
            ->where($conditions);
        $campaigns = $this->paginate($query);

        $this->set('campaigns', $campaigns);
    }

    public function view($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Invalid campaign'));
        }

        $campaign = $this->Campaigns->findById($id)
            ->contain(['CampaignItems'])
            ->first();

        if (!$campaign) {
            throw new NotFoundException(__('Campaign Not Found'));
        }

        $this->set('campaign', $campaign);

        $this->loadModel('Statistics');

        $campaign_earnings = $this->Statistics->find()
            ->select([
                'reason',
                'count' => 'COUNT(reason)',
                'earnings' => 'SUM(publisher_earn)',
            ])
            ->where([
                'campaign_id' => $campaign->id
            ])
            ->order(['count' => 'DESC'])
            ->group(['reason'])
            ->toArray();

        $this->set('campaign_earnings', $campaign_earnings);

        $campaign_countries = $this->Statistics->find()
            ->select([
                'country',
                'count' => 'COUNT(country)',
                'earnings' => 'SUM(publisher_earn)',
            ])
            ->where([
                'campaign_id' => $campaign->id
            ])
            ->order(['count' => 'DESC'])
            ->group(['country'])
            ->toArray();

        $this->set('campaign_countries', $campaign_countries);

        $campaign_referers = $this->Statistics->find()
            ->select([
                'referer_domain',
                'count' => 'COUNT(referer_domain)',
                'earnings' => 'SUM(publisher_earn)',
            ])
            ->where([
                'campaign_id' => $campaign->id
            ])
            ->order(['count' => 'DESC'])
            ->group(['referer_domain'])
            ->toArray();

        $this->set('campaign_referers', $campaign_referers);

        /*
        $campaign_statistics = $this->Statistics->find()
            ->select([
                'reason',
                'reason_count' => 'COUNT(reason)',
                'earnings' => 'SUM(publisher_earn)',
            ])
            ->where([
                'campaign_id' => $campaign->id
            ])
            ->group(['reason'])
            ->toArray();

        $this->set('campaign_statistics', $campaign_statistics);
        */
    }

    public function createInterstitial()
    {
        if ($this->getRequest()->is(['get']) && empty($this->getRequest()->getQuery('traffic_source'))) {
            return null;
        }

        $traffic_source = $this->getRequest()->getQuery('traffic_source');
        $interstitial_price = get_option('interstitial_price');

        $campaign = $this->Campaigns->newEntity(null, ['associated' => ['CampaignItems']]);
        $this->set('campaign', $campaign);

        if ($this->getRequest()->is('post')) {
            $campaign->ad_type = 1;

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

            if (count($this->getRequest()->data['campaign_items']) == 0) {
                $this->Flash->error(__('You must purchase at least from one country.'));

                return null;
            }

            $campaign = $this->Campaigns->patchEntity($campaign, $this->getRequest()->data);

            if ($this->Campaigns->save($campaign)) {
                $this->Flash->success(__('Your campaign has been created.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Unable to create your campaign.'));
            }
        }
        $this->set('campaign', $campaign);
    }

    public function createBanner()
    {
        if ($this->getRequest()->is(['get']) && empty($this->getRequest()->getQuery('traffic_source'))) {
            return null;
        }

        $traffic_source = $this->getRequest()->getQuery('traffic_source');
        $banner_price = get_option('banner_price');

        $campaign = $this->Campaigns->newEntity(null, ['associated' => ['CampaignItems']]);
        $this->set('campaign', $campaign);

        if ($this->getRequest()->is('post')) {
            $campaign->ad_type = 2;

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

            if (count($this->getRequest()->data['campaign_items']) == 0) {
                $this->Flash->error(__('You must purchase at least from one country.'));

                return null;
            }

            $campaign = $this->Campaigns->patchEntity($campaign, $this->getRequest()->data);

            if ($this->Campaigns->save($campaign)) {
                $this->Flash->success(__('Your campaign has been created.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Unable to create your campaign.'));
            }
        }
        $this->set('campaign', $campaign);
    }

    public function createPopup()
    {
        if ($this->getRequest()->is(['get']) && empty($this->getRequest()->getQuery('traffic_source'))) {
            return null;
        }

        $traffic_source = $this->getRequest()->getQuery('traffic_source');
        $popup_price = get_option('popup_price');

        $campaign = $this->Campaigns->newEntity(null, ['associated' => ['CampaignItems']]);
        $this->set('campaign', $campaign);

        if ($this->getRequest()->is('post')) {
            $campaign->ad_type = 3;

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

            if (count($this->getRequest()->data['campaign_items']) == 0) {
                $this->Flash->error(__('You must purchase at least from one country.'));

                return null;
            }

            $campaign = $this->Campaigns->patchEntity($campaign, $this->getRequest()->data);

            if ($this->Campaigns->save($campaign)) {
                $this->Flash->success(__('Your campaign has been created.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Unable to create your campaign.'));
            }
        }
        $this->set('campaign', $campaign);
    }

    public function edit($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Invalid campaign'));
        }

        $campaign = $this->Campaigns->find()
            ->where(['Campaigns.id' => $id])
            ->contain(['CampaignItems'])
            ->first();

        if (!$campaign) {
            throw new NotFoundException(__('Invalid campaign'));
        }

        if ($this->getRequest()->is(['post', 'put'])) {
            /*
            $this->getRequest()->data['price'] = 0;

            foreach ($this->getRequest()->data['campaign_items'] as $key => $value) {
                $this->getRequest()->data['price'] += $value['purchase'] * $value['advertiser_price'];
            }
            */

            $this->Campaigns->patchEntity($campaign, $this->getRequest()->getData());
            if ($this->Campaigns->save($campaign)) {
                $this->Flash->success(__('Campaign has been updated.'));

                return $this->redirect(['action' => 'edit', $id]);
            } else {
                $this->Flash->error(__('Unable to update campaign.'));
            }
        }

        $this->set('campaign', $campaign);
    }

    public function pause($id)
    {
        $this->getRequest()->allowMethod(['post', 'put']);

        $campaign = $this->Campaigns->findById($id)
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
        $this->getRequest()->allowMethod(['post', 'put']);

        $campaign = $this->Campaigns->findById($id)
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

    public function delete($id, $views = false)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);

        $campaign = $this->Campaigns->findById($id)->first();

        if ($this->Campaigns->delete($campaign)) {
            $this->Campaigns->CampaignItems->deleteAll(['campaign_id' => $id]);

            if ($views) {
                $this->Campaigns->Statistics->deleteAll(['campaign_id' => $id]);
            }

            $this->Flash->success(__('The campaign with id: {0} has been deleted.', $id));
        }

        return $this->redirect(['action' => 'index']);
    }
}

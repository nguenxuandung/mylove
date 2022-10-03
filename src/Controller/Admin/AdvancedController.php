<?php

namespace App\Controller\Admin;

/**
 * @property \App\Model\Table\StatisticsTable $Statistics
 * @property \Cake\ORM\Table $Advanced
 */
class AdvancedController extends AppAdminController
{
    public function statistics()
    {
        $this->loadModel('Statistics');

        $conditions = [];

        $filter_fields = ['user_id', 'reason', 'link_id', 'referral_id', 'ad_type', 'campaign_id', 'ip', 'country'];

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
                    if (in_array($param_name, [])) {
                        $conditions[] = [
                            ['Statistics.' . $param_name . ' LIKE' => '%' . $value . '%'],
                        ];
                    } elseif (in_array($param_name,
                        ['user_id', 'reason', 'link_id', 'referral_id', 'ad_type', 'campaign_id', 'ip', 'country'])) {
                        $conditions['Statistics.' . $param_name] = $value;
                    }
                    $this->getRequest()->data['Filter'][$param_name] = $value;
                }
            }
        }

        $query = $this->Statistics->find()
            ->contain([
                'Users' => [
                    'fields' => ['id', 'username'],
                ],
            ])
            ->where($conditions);
        $statistics = $this->paginate($query);

        $this->set('statistics', $statistics);
    }
}

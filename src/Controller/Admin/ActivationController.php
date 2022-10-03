<?php

namespace App\Controller\Admin;

use Cake\ORM\TableRegistry;
use Cake\Cache\Cache;

/**
 * @property \App\Model\Table\ActivationTable $Activation
 */
class ActivationController extends AppAdminController
{
    public function index()
    {
        if ($this->getRequest()->is('post')) {
            $response = $this->Activation->licenseCurlRequest($this->getRequest()->getData());

            $result = json_decode($response->body, true);

            if (isset($result['item_id']) && $result['item_id'] == 16887109) {
                Cache::write('license_response_result', data_encrypt($result), '1month');

                $Options = TableRegistry::getTableLocator()->get('Options');

                $personal_token = $Options->find()->where(['name' => 'personal_token'])->first();
                $personal_token->value = trim($this->getRequest()->getData('personal_token'));
                $Options->save($personal_token);

                $purchase_code = $Options->find()->where(['name' => 'purchase_code'])->first();
                $purchase_code->value = trim($this->getRequest()->getData('purchase_code'));
                $Options->save($purchase_code);

                $this->Flash->success(__('Your license has been verified.'));

                return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
            } else {
                if (isset($result['message']) && !empty($result['message'])) {
                    $this->Flash->error($result['message']);

                    return null;
                }
            }
        }
    }
}

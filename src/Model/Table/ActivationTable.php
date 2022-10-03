<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Cache\Cache;

class ActivationTable extends Table
{
    public function initialize(array $config)
    {
        $this->_table = false;
    }

    public function checkLicense()
    {
        $Options = TableRegistry::getTableLocator()->get('Options');

        $personal_token = $Options->find()->where(['name' => 'personal_token'])->first();
        $purchase_code = $Options->find()->where(['name' => 'purchase_code'])->first();

        if (empty($personal_token->value) || empty($purchase_code->value)) {
            return false;
        }

        if (!$this->validateLicense()) {
            return false;
        }

        return true;
    }

    public function validateLicense()
    {
        $result = Cache::read('license_response_result', '1month');

        if (!is_string($result)) {
            $result = false;
        }

        if ($result === false) {
            $personal_token = get_option('personal_token');
            $purchase_code = get_option('purchase_code');

            $response = $this->licenseCurlRequest([
                'personal_token' => $personal_token,
                'purchase_code' => $purchase_code,
            ]);

            $result = json_decode($response->body, true);

            $result = data_encrypt($result);

            Cache::write('license_response_result', $result, '1month');
        }

        if (($result = data_decrypt($result)) === false) {
            return false;
        }

        if (isset($result['item_id']) && $result['item_id'] == 16887109) {
            return true;
        }

        return false;
    }

    public function licenseCurlRequest($data = [])
    {
        return curlRequest('https://verify.mightyscripts.com/api/license', 'POST', [
            'purchase_code' => trim($data['purchase_code']),
            'envato_id' => 16887109,
            'domain' => get_option('main_domain'),
            'url' => build_main_domain_url('/'),
        ], ['Accept: application/json']);
    }
}

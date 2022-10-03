<?php

namespace App\Controller\Member;

use Cake\Routing\Router;
use Cake\Http\Exception\NotFoundException;

/**
 * @property \App\Model\Table\CampaignsTable $Campaigns
 * @property \App\Model\Table\InvoicesTable $Invoices
 */
class InvoicesController extends AppMemberController
{
    public function index()
    {
        $query = $this->Invoices->find()->where(['user_id' => $this->Auth->user('id')]);
        $invoices = $this->paginate($query);

        $this->set('invoices', $invoices);
    }

    public function view($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Invalid Invoice'));
        }

        $invoice = $this->Invoices->findById($id)->where(['user_id' => $this->Auth->user('id')])->first();
        if (!$invoice) {
            throw new NotFoundException(__('Invalid Invoice'));
        }
        $this->set('invoice', $invoice);
    }

    public function checkout()
    {
        $this->autoRender = false;

        $this->setResponse($this->getResponse()->withType('json'));

        if (!$this->getRequest()->is('ajax')) {
            $content = [
                'status' => 'error',
                'message' => __('Bad Request.'),
                'form' => '',
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->getResponse();
        }

        /**
         * @var \App\Model\Entity\User $user
         */
        $user = $this->Invoices->Users->find()->contain(['Plans'])
            ->where(['Users.id' => $this->Auth->user('id')])->first();

        /**
         * @var \App\Model\Entity\Invoice $invoice
         */
        $invoice = $this->Invoices->findById($this->getRequest()->getData('id'))->first();

        if ('wallet' == $this->getRequest()->getData('payment_method')) {
            if ($invoice->amount > $user->wallet_money) {
                $content = [
                    'status' => 'error',
                    'message' => __("You don't have enough money in your wallet."),
                ];
                $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

                return $this->getResponse();
            }

            $invoice->payment_method = 'wallet';
            $invoice->status = 1;
            $invoice->paid_date = date("Y-m-d H:i:s");
            $this->Invoices->save($invoice);

            $user->wallet_money -= $invoice->amount;
            if ($invoice->type === 1) {
                $payment_period = unserialize($invoice->data)['payment_period'];
                $expiration = (new \Cake\I18n\Time($user->expiration))->addYear();
                if ($payment_period === 'm') {
                    $expiration = (new \Cake\I18n\Time($user->expiration))->addMonth();
                }
                $user->expiration = $expiration;
                $user->plan_id = $invoice->rel_id;
            }
            $this->Invoices->Users->save($user);

            if ($this->Auth->user('id') === $user->id) {
                $data = $user->toArray();
                unset($data['password']);

                $this->Auth->setUser($data);
            }

            if ($invoice->type === 2) {
                $this->loadModel('Campaigns');
                /** @var \App\Model\Entity\Campaign $campaign */
                $campaign = $this->Campaigns->findById($invoice->rel_id)
                    ->where(['user_id' => $this->Auth->user('id')])
                    ->first();

                if (!$campaign) {
                    $content = [
                        'status' => 'error',
                        'message' => __('Not found campaign.'),
                        'form' => '',
                    ];
                    $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

                    return $this->getResponse();
                }

                $campaign->payment_method = 'wallet';
                $campaign->status = 5;
                $this->Campaigns->save($campaign);
            }

            $content = [
                'status' => 'success',
                'message' => '',
                'type' => 'offline',
                'url' => Router::url(['controller' => 'Invoices', 'action' => 'view', $invoice->id], true),
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->getResponse();
        }

        if ('paypal' === $this->getRequest()->getData('payment_method')) {
            $return_url = Router::url(['controller' => 'Invoices', 'action' => 'view', $invoice->id], true);
            $notify_url = Router::url('/payment/ipn?payment_method=paypal', true);

            $paymentData = [
                'business' => get_option('paypal_email'),
                'cmd' => '_xclick',
                'currency_code' => get_option('currency_code'),
                'amount' => $invoice->amount,
                'item_name' => __("Invoice"),
                'item_number' => '#' . $invoice->id,
                'page_style' => 'paypal',
                'return' => $return_url,
                'notify_url' => $notify_url,
                'rm' => '0',
                'cancel_return' => $return_url,
                'custom' => $invoice->id,
                'no_shipping' => 1,
            ];

            $url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';

            if (get_option('paypal_sandbox', 'no') == 'no') {
                $url = 'https://www.paypal.com/cgi-bin/webscr';
            }

            $form = $this->redirect_post($url, $paymentData);

            $invoice->payment_method = 'paypal';
            $this->Invoices->save($invoice);

            $content = [
                'status' => 'success',
                'message' => '',
                'type' => 'form',
                'form' => $form,
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->getResponse();
        }

        if ('skrill' === $this->getRequest()->getData('payment_method')) {
            $return_url = Router::url(['controller' => 'Invoices', 'action' => 'view', $invoice->id], true);
            $status_url = Router::url('/payment/ipn?payment_method=skrill', true);

            $paymentData = [
                'pay_to_email' => get_option('skrill_email'),
                'recipient_description' => get_option('site_name'),
                'status_url' => $status_url,
                'amount' => $invoice->amount,
                'currency' => get_option('currency_code'),
                'detail1_description' => __("Invoice"),
                'detail1_text' => '#' . $invoice->id,
                'transaction_id' => $invoice->id,
                'return_url' => $return_url,
                'cancel_url' => $return_url,
            ];

            $url = 'https://pay.skrill.com';

            $form = $this->redirect_post($url, $paymentData);

            $invoice->payment_method = 'skrill';
            $this->Invoices->save($invoice);

            $content = [
                'status' => 'success',
                'message' => '',
                'type' => 'form',
                'form' => $form,
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->getResponse();
        }

        if ('stripe' === $this->getRequest()->getData('payment_method')) {
            // https://stripe.com/docs/api#create_charge

            $data = [
                'card[number]' => $this->getRequest()->getData('stripe_cc'), //4242424242424242,
                'card[exp_month]' => $this->getRequest()->getData('stripe_exp_month'), //12,
                'card[exp_year]' => $this->getRequest()->getData('stripe_exp_year'), //2018,
                'card[cvc]' => $this->getRequest()->getData('stripe_cvc'), //123
            ];

            $headers = [
                "Content-Type: application/x-www-form-urlencoded",
            ];

            $options = [
                CURLOPT_USERPWD => get_option('stripe_secret_key') . ":",
            ];

            $token_obj = curlRequest(
                "https://api.stripe.com/v1/tokens",
                "POST",
                http_build_query($data),
                $headers,
                $options
            );

            if ($token_obj->error) {
                $content = [
                    'status' => 'error',
                    'message' => $token_obj->error,
                ];
                $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

                return $this->getResponse();
            }

            $result = json_decode($token_obj->body);

            \Cake\Log\Log::debug($result, 'payments');

            if (isset($result->error)) {
                $content = [
                    'status' => 'error',
                    'message' => $result->error->message,
                ];
                $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

                return $this->getResponse();
            }

            $data = [
                'amount' => intval($invoice->amount * 100),
                'currency' => 'usd',
                'source' => $result->id,
                'description' => $invoice->description,
            ];

            $headers = [
                "Content-Type: application/x-www-form-urlencoded",
            ];

            $options = [
                CURLOPT_USERPWD => get_option('stripe_secret_key') . ":",
            ];

            $charge_obj = curlRequest(
                "https://api.stripe.com/v1/charges",
                "POST",
                http_build_query($data),
                $headers,
                $options
            );

            if ($charge_obj->error) {
                $content = [
                    'status' => 'error',
                    'message' => $charge_obj->error,
                ];
                $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

                return $this->getResponse();
            }

            $charge = json_decode($charge_obj->body);

            \Cake\Log\Log::debug($charge, 'payments');

            if (isset($charge->error)) {
                $content = [
                    'status' => 'error',
                    'message' => $charge->error->message,
                ];
                $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

                return $this->getResponse();
            }

            $invoice->payment_method = 'stripe';
            $invoice->status = 1;
            $invoice->paid_date = date("Y-m-d H:i:s");
            $this->Invoices->save($invoice);

            $this->Invoices->successPayment($invoice);

            $content = [
                'status' => 'success',
                'message' => '',
                'type' => 'url',
                'url' => Router::url(['controller' => 'Invoices', 'action' => 'view', $invoice->id], true),
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->getResponse();
        }

        if ('coinpayments' === $this->getRequest()->getData('payment_method')) {
            $ipn_url = Router::url('/payment/ipn?payment_method=coinpayments', true);

            $req = [
                'version' => 1,
                'cmd' => 'create_transaction',
                'key' => get_option('coinpayments_public_key'),
                'format' => 'json',
                'amount' => $invoice->amount,
                'currency1' => get_option('currency_code'),
                'currency2' => 'BTC',
                'buyer_email' => $user->email,
                'item_name' => __("Invoice") . ' #' . $invoice->id,
                'ipn_url' => $ipn_url,
                'custom' => $invoice->id,
            ];

            $url = "https://www.coinpayments.net/api.php";

            $post_data = http_build_query($req, '', '&');

            $hmac = hash_hmac('sha512', $post_data, get_option('coinpayments_private_key'));

            $headers = [
                "Content-Type: application/x-www-form-urlencoded",
                "HMAC: " . $hmac,
            ];

            $result = curlRequest($url, "POST", $post_data, $headers);
            $response = json_decode($result->body);

            \Cake\Log\Log::debug($response, 'payments');

            if (isset($response->error) && $response->error !== 'ok') {
                $content = [
                    'status' => 'error',
                    'message' => $response->error,
                    'type' => 'url',
                    'url' => '',
                ];
                $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

                return $this->getResponse();
            }

            $redirect_url = $response->result->status_url;

            $invoice->payment_method = 'coinpayments';
            $this->Invoices->save($invoice);

            $content = [
                'status' => 'success',
                'message' => '',
                'type' => 'url',
                'url' => $redirect_url,
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->getResponse();
        }

        if ('coinbase' === $this->getRequest()->getData('payment_method')) {
            $return_url = Router::url(['controller' => 'Invoices', 'action' => 'view', $invoice->id], true);
            $alert_url = Router::url('/payment/ipn?payment_method=coinbase', true);

            // https://commerce.coinbase.com/docs/api/#create-a-checkout
            $paymentData = [
                'name' => __("Invoice") . ' #' . $invoice->id,
                'description' => mb_substr($invoice->description, 0, 200),
                'pricing_type' => 'fixed_price',
                'local_price' => [
                    'amount' => $invoice->amount,
                    'currency' => get_option('currency_code'),
                ],
                'metadata' => [
                    'invoice_id' => $invoice->id,
                ],
                'success_url' => $return_url,
                'cancel_url' => $return_url,
            ];

            $url = "https://api.commerce.coinbase.com/charges";

            $body = json_encode($paymentData);

            $headers = [
                "X-CC-Api-Key: " . get_option('coinbase_api_key'),
                "X-CC-Version: 2018-03-22",
                "Content-Type: application/json",
            ];
            $result = curlRequest($url, "POST", $body, $headers);
            $response = json_decode($result->body);

            \Cake\Log\Log::debug($response, 'payments');

            if (isset($response->error)) {
                $content = [
                    'status' => 'error',
                    'message' => $response->error->message,
                    'type' => 'url',
                    'url' => '',
                ];
                $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

                return $this->getResponse();
            }

            $invoice->payment_method = 'coinbase';
            $this->Invoices->save($invoice);

            $content = [
                'status' => 'success',
                'message' => '',
                'type' => 'url',
                'url' => $response->data->hosted_url,
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->getResponse();
        }

        if ('webmoney' === $this->getRequest()->getData('payment_method')) {
            $return_url = Router::url(['controller' => 'Invoices', 'action' => 'view', $invoice->id], true);
            $result_url = Router::url('/payment/ipn?payment_method=webmoney', true);

            // https://wiki.wmtransfer.com/projects/webmoney/wiki/Web_Merchant_Interface
            $paymentData = [
                'LMI_PAYMENT_AMOUNT' => $invoice->amount,
                'LMI_PAYMENT_DESC_BASE64' => base64_encode(__("Invoice") . ' #' . $invoice->id),
                'LMI_PAYMENT_NO' => $invoice->id,
                'LMI_PAYEE_PURSE' => get_option('webmoney_merchant_purse'),
                'LMI_RESULT_URL' => $result_url,
                'LMI_SUCCESS_URL' => $return_url,
                'LMI_FAIL_URL' => $return_url,
            ];

            $url = 'https://merchant.wmtransfer.com/lmi/payment.asp';

            $form = $this->redirect_post($url, $paymentData);

            $invoice->payment_method = 'webmoney';
            $this->Invoices->save($invoice);

            $content = [
                'status' => 'success',
                'message' => '',
                'type' => 'form',
                'form' => $form,
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->getResponse();
        }

        if ('perfectmoney' === $this->getRequest()->getData('payment_method')) {
            $return_url = Router::url(['controller' => 'Invoices', 'action' => 'view', $invoice->id], true);
            $status_url = Router::url('/payment/ipn?payment_method=perfectmoney', true);

            // https://perfectmoney.is/sample-api.html
            $paymentData = [
                'PAYEE_ACCOUNT' => get_option('perfectmoney_account'),
                'PAYEE_NAME' => get_option('site_name'),
                'PAYMENT_AMOUNT' => $invoice->amount,
                'PAYMENT_UNITS' => get_option('currency_code'),
                'PAYMENT_ID' => $invoice->id,
                'STATUS_URL' => $status_url,
                'PAYMENT_URL' => $return_url,
                'PAYMENT_URL_METHOD' => 'GET',
                'NOPAYMENT_URL' => $return_url,
                'NOPAYMENT_URL_METHOD' => 'GET',
                'BAGGAGE_FIELDS' => '',
                'SUGGESTED_MEMO' => __("Invoice") . ' #' . $invoice->id,
                'SUGGESTED_MEMO_NOCHANGE' => 1,
            ];

            $url = 'https://perfectmoney.is/api/step1.asp';

            $form = $this->redirect_post($url, $paymentData);

            $invoice->payment_method = 'perfectmoney';
            $this->Invoices->save($invoice);

            $content = [
                'status' => 'success',
                'message' => '',
                'type' => 'form',
                'form' => $form,
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->getResponse();
        }

        if ('payeer' === $this->getRequest()->getData('payment_method')) {
            $m_shop = get_option('payeer_merchant_id');
            $m_orderid = $invoice->id;
            $m_amount = number_format($invoice->amount, 2, '.', '');
            $m_curr = get_option('currency_code');
            $m_desc = base64_encode(__("Invoice") . ' #' . $invoice->id);
            $m_key = get_option('payeer_secret_key');
            $return_url = Router::url(['controller' => 'Invoices', 'action' => 'view', $invoice->id], true);
            $status_url = Router::url('/payment/ipn?payment_method=payeer', true);

            // Forming an array for signature generation
            $arHash = [
                $m_shop,
                $m_orderid,
                $m_amount,
                $m_curr,
                $m_desc,
            ];

            // Forming an array for additional parameters
            $arParams = [
                'success_url' => $return_url,
                'fail_url' => $return_url,
                'status_url' => $status_url,
            ];

            // Forming a key for encryption
            $key = md5(get_option('payeer_encryption_key') . $m_orderid);

            // Encrypting additional parameters
            //$m_params = urlencode(base64_encode(mcrypt_encrypt(MCR YPT_RIJNDAEL_256,
            //$key, json_encode($arParams), MCRYPT_MODE_ECB)));
            // Encrypting additional parameters using AES-256-CBC (for >= PHP 7)
            $m_params = urlencode(base64_encode(openssl_encrypt(
                json_encode($arParams),
                'AES-256-CBC',
                $key,
                OPENSSL_RAW_DATA
            )));

            // Adding parameters to the signature-formation array
            $arHash[] = $m_params;

            // Adding the secret key to the signature-formation array
            $arHash[] = $m_key;

            // Forming a signature
            $sign = strtoupper(hash('sha256', implode(':', $arHash)));

            $paymentData = [
                'm_shop' => $m_shop,
                'm_orderid' => $m_orderid,
                'm_amount' => $m_amount,
                'm_curr' => $m_curr,
                'm_desc' => $m_desc,
                'm_sign' => $sign,
                'm_params' => $m_params,
                'm_cipher_method' => 'AES-256-CBC',
                'success_url' => $return_url,
                'fail_url' => $return_url,
                'status_url' => $status_url,
            ];

            $url = 'https://payeer.com/merchant/';

            $form = $this->redirect_post($url, $paymentData);

            $invoice->payment_method = 'payeer';
            $this->Invoices->save($invoice);

            $content = [
                'status' => 'success',
                'message' => '',
                'type' => 'form',
                'form' => $form,
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->getResponse();
        }

        if ('paystack' === $this->request->getData('payment_method')) {
            $return_url = Router::url(['controller' => 'Invoices', 'action' => 'view', $invoice->id], true);
            $webhook_url = Router::url('/payment/ipn?payment_method=paystack', true);

            $post_data = [
                'email' => $user->email,
                'currency' => get_option('currency_code'),
                'amount' => $invoice->amount * 100,
                "reference" => $invoice->id,
                'callback_url' => $return_url,
                'webhook_url' => $webhook_url,
                'metadata' => [
                    'cancel_action' => $return_url,
                ],
            ];

            $headers = [
                'Authorization: Bearer ' . get_option('paystack_secret_key'),
                'Content-Type: application/json',

            ];

            $url = 'https://api.paystack.co/transaction/initialize';

            $result = curlRequest($url, "POST", json_encode($post_data), $headers);
            $response = json_decode($result->body);

            \Cake\Log\Log::debug($response, 'payments');

            if (isset($response->status) && (int)$response->status !== 1) {
                $content = [
                    'status' => 'error',
                    'message' => $response->message,
                    'type' => 'url',
                    'url' => '',
                ];
                $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

                return $this->getResponse();
            }

            $redirect_url = $response->data->authorization_url;

            $invoice->payment_method = 'paystack';
            $this->Invoices->save($invoice);

            $content = [
                'status' => 'success',
                'message' => '',
                'type' => 'url',
                'url' => $redirect_url,
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->getResponse();
        }

        if ('paytm' == $this->getRequest()->getData('payment_method')) {
            $return_url = Router::url(['controller' => 'Invoices', 'action' => 'view', $invoice->id], true);
            $notify_url = Router::url('/payment/ipn?payment_method=paytm', true);

            require_once(APP . 'Library/gateways/paytm/encdec_paytm.php');

            $paymentData = [
                'MID' => get_option('paytm_merchant_mid'),
                'ORDER_ID' => $invoice->id,
                'CUST_ID' => $user->email,
                'TXN_AMOUNT' => $invoice->amount,
                'CHANNEL_ID' => 'WEB',
                'INDUSTRY_TYPE_ID' => get_option('paytm_industry_type'),
                'CALLBACK_URL' => $notify_url,
                'WEBSITE' => get_option('paytm_merchant_website'),
            ];

            $paytmChecksum = getChecksumFromArray($paymentData, get_option('paytm_merchant_key'));

            $paymentData['CHECKSUMHASH'] = $paytmChecksum;

            $url = 'https://securegw.paytm.in/theia/processTransaction';

            $form = $this->redirect_post($url, $paymentData);

            $invoice->payment_method = 'paytm';
            $this->Invoices->save($invoice);

            $content = [
                'status' => 'success',
                'message' => '',
                'type' => 'form',
                'form' => $form,
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->getResponse();
        }

        if ('banktransfer' === $this->getRequest()->getData('payment_method')) {
            $invoice->payment_method = 'banktransfer';
            $this->Invoices->save($invoice);

            $content = [
                'status' => 'success',
                'message' => '',
                'type' => 'offline',
                'url' => Router::url(['controller' => 'Invoices', 'action' => 'view', $invoice->id], true),
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->getResponse();
        }

        $content = [
            'status' => 'error',
            'message' => __("Invalid payment method."),
        ];
        $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

        return $this->getResponse();
    }

    protected function redirect_post($url, array $data)
    {
        ob_start(); ?>
        <form id="checkout-redirect-form" method="post" action="<?= $url; ?>">
            <?php
            if (!is_null($data)) {
                foreach ($data as $k => $v) {
                    echo '<input type="hidden" name="' . $k . '" value="' . $v . '"> ';
                }
            } ?>
        </form>
        <?php
        $form = ob_get_contents();
        ob_end_clean();

        \Cake\Log\Log::debug($form, 'payments');

        return $form;
    }

    public function edit($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Invalid Invoice'));
        }

        $invoice = $this->Invoices->findById($id)->where(['user_id' => $this->Auth->user('id')])->first();
        if (!$invoice) {
            throw new NotFoundException(__('Invalid Invoice'));
        }

        if ($this->getRequest()->is(['post', 'put'])) {
            $invoice = $this->Invoices->patchEntity($invoice, $this->getRequest()->getData());

            if ($this->Invoices->save($invoice)) {
                $this->Flash->success(__('Invoice has been updated.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
        }
        $this->set('invoice', $invoice);
    }

    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);

        $invoice = $this->Invoices->findById($id)->where(['user_id' => $this->Auth->user('id')])->first();

        if ($this->Invoices->delete($invoice)) {
            $this->Flash->success(__('The invoice with id: {0} has been deleted.', $invoice->id));

            return $this->redirect(['action' => 'index']);
        }
    }
}

<?php

namespace App\Controller;

use Cake\Event\Event;

/**
 * @property \App\Model\Table\InvoicesTable $Invoices
 */
class InvoicesController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['ipn']);

        if (in_array($this->getRequest()->getParam('action'), ['ipn'])) {
            //$this->eventManager()->off($this->Csrf);
            $this->eventManager()->off($this->Security);
        }
    }

    public function ipn()
    {
        $this->autoRender = false;

        $payment_method = $this->getRequest()->getQuery('payment_method');

        \Cake\Log\Log::debug($payment_method, 'payments');

        if ($payment_method && !empty($this->getRequest()->getData())) {
            \Cake\Log\Log::debug($this->getRequest()->getData(), 'payments');

            if ($payment_method == 'paypal') {
                $this->ipnPaypal($this->getRequest()->getData());

                return null;
            }

            if ($payment_method === 'skrill') {
                $this->ipnSkrill($this->getRequest()->getData());

                return null;
            }

            if ($payment_method === 'webmoney') {
                $this->ipnWebmoney($this->getRequest()->getData());

                return null;
            }

            if ($payment_method === 'coinpayments') {
                $this->ipnCoinPayments($this->getRequest()->getData());

                return null;
            }

            if ($payment_method === 'perfectmoney') {
                $this->ipnPerfectMoney($this->getRequest()->getData());

                return null;
            }

            if ($payment_method === 'payeer') {
                $this->ipnPayeer($this->getRequest()->getData());

                return null;
            }

            if ($payment_method === 'paytm') {
                $this->ipnPaytm($this->getRequest()->getData());

                return null;
            }
        }

        if ($payment_method === 'paystack') {
            $this->ipnPaystack();

            return null;
        }

        if ($payment_method === 'coinbase') {
            $this->ipnCoinbase();

            return null;
        }
    }

    // @see https://github.com/Paytm-Payments/Paytm_WHMCS_Kit/blob/master/Paytm_WHMCS_v6.x-7.x_Kit-master/Paytm/gateways/callback/paytm.php
    protected function ipnPaytm($data)
    {
        if (isset($data['ORDERID']) && isset($data['STATUS']) && isset($data['RESPCODE']) && $data['RESPCODE'] != 325) {
            require_once(APP . 'Library/gateways/paytm/encdec_paytm.php');

            $invoice_id = (int)$data['ORDERID'];
            $invoice = $this->Invoices->get($invoice_id);

            $checksum_recv = '';
            if (isset($data['CHECKSUMHASH'])) {
                $checksum_recv = (isset($data['CHECKSUMHASH'])) ? $data['CHECKSUMHASH'] : '';
            }

            $checksum_status = verifychecksum_e(
                $data,
                html_entity_decode(get_option('paytm_merchant_key')),
                $checksum_recv
            );

            // Create an array having all required parameters for status query.
            $requestParamList = ["MID" => get_option('paytm_merchant_mid'), "ORDERID" => $data['ORDERID']];
            $StatusCheckSum = getChecksumFromArray(
                $requestParamList,
                html_entity_decode(get_option('paytm_merchant_key'))
            );
            $requestParamList['CHECKSUMHASH'] = $StatusCheckSum;

            // Call the PG's getTxnStatus() function for verifying the transaction status.
            $check_status_url = 'https://securegw.paytm.in/order/status';

            if ($data['STATUS'] == 'TXN_SUCCESS' && $checksum_status == "TRUE") {
                $responseParamList = callNewAPI($check_status_url, $requestParamList);
                if ($responseParamList['STATUS'] == 'TXN_SUCCESS' && $responseParamList['TXNAMOUNT'] == $response['TXNAMOUNT']) {
                    $invoice->status = 1;
                    $invoice->paid_date = date("Y-m-d H:i:s");
                    $this->Invoices->save($invoice);
                    $message = 'VERIFIED';
                } else {
                    $invoice->status = 4;
                    $this->Invoices->save($invoice);
                    $message = 'INVALID';
                }
            } elseif ($data['STATUS'] == "TXN_SUCCESS" && $checksum_status != "TRUE") {
                $invoice->status = 4;
                $this->Invoices->save($invoice);
                $message = 'INVALID';
            } else {
                $invoice->status = 4;
                $this->Invoices->save($invoice);
                $message = 'INVALID';
            }
        }

        $return_url = \Cake\Routing\Router::url(['controller' => 'Invoices', 'action' => 'view', $invoice->id], true);
    }

    /**
     * @see https://developers.paystack.co/docs/paystack-standard#section-3-handle-chargesuccess-event
     *
     * @return void
     */
    protected function ipnPaystack()
    {
        $body = file_get_contents("php://input");

        \Cake\Log\Log::debug($_SERVER, 'payments');
        \Cake\Log\Log::debug(json_decode($body), 'payments');

        $signature = (isset($_SERVER['HTTP_X_PAYSTACK_SIGNATURE']) ? $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] : '');
        if (!$signature) {
            exit();
        }

        if ($signature !== hash_hmac('sha512', $body, get_option('paystack_secret_key'))) {
            exit();
        }

        // parse event (which is json string) as object
        // Give value to your customer but don't give any output
        // Remember that this is a call from Paystack's servers and
        // Your customer is not seeing the response here at all
        $result = json_decode($body);

        switch ($result->event) {
            case 'charge.success':
                $invoice_id = (int)explode('_', $result->data->reference)[0];
                $invoice = $this->Invoices->get($invoice_id);

                $amount = (float)$result->data->amount / 100;

                if ($invoice->amount === $amount) {
                    $invoice->status = 1;
                    $invoice->paid_date = date("Y-m-d H:i:s");
                    $this->Invoices->save($invoice);
                    $message = 'VERIFIED';
                } else {
                    // If not, returning an error
                    $invoice->status = 4;
                    $this->Invoices->save($invoice);
                    $message = 'INVALID';
                }

                $this->Invoices->successPayment($invoice);

                break;
        }
        exit();
    }

    protected function ipnPayeer($data)
    {
        // Rejecting queries from IP addresses not belonging to Payeer
        if (!in_array(get_ip(), ['185.71.65.92', '185.71.65.189', '149.202.17.210'])) {
            return null;
        }

        if (isset($data['m_operation_id']) && isset($data['m_sign'])) {
            $m_key = get_option('payeer_secret_key');
            // Forming an array for signature generation
            $arHash = [
                $data['m_operation_id'],
                $data['m_operation_ps'],
                $data['m_operation_date'],
                $data['m_operation_pay_date'],
                $data['m_shop'],
                $data['m_orderid'],
                $data['m_amount'],
                $data['m_curr'],
                $data['m_desc'],
                $data['m_status'],
            ];

            // Adding additional parameters to the array if such parameters have been transferred
            if (isset($data['m_params'])) {
                $arHash[] = $data['m_params'];
            }

            // Adding the secret key to the array
            $arHash[] = $m_key;

            // Forming a signature
            $sign_hash = strtoupper(hash('sha256', implode(':', $arHash)));

            $invoice_id = (int)$data['m_orderid'];
            $invoice = $this->Invoices->get($invoice_id);

            // If the signatures match and payment status is “Complete”
            if ($data['m_sign'] == $sign_hash && $data['m_status'] == 'success') {
                // Here you can mark the invoice as paid or transfer funds to your customer
                // Returning that the payment was processed successfully
                $invoice->status = 1;
                $invoice->paid_date = date("Y-m-d H:i:s");
                $this->Invoices->save($invoice);
                $message = 'VERIFIED';
            } else {
                // If not, returning an error
                $invoice->status = 4;
                $this->Invoices->save($invoice);
                $message = 'INVALID';
            }
        }

        $this->Invoices->successPayment($invoice);
    }

    protected function ipnPerfectMoney($data)
    {
        $perfectmoney_account = get_option('perfectmoney_account');
        $perfectmoney_passphrase = get_option('perfectmoney_passphrase');

        $concatFields = $data['PAYMENT_ID'] . ':' . $data['PAYEE_ACCOUNT'] . ':' .
            $data['PAYMENT_AMOUNT'] . ':' . $data['PAYMENT_UNITS'] . ':' .
            $data['PAYMENT_BATCH_NUM'] . ':' .
            $data['PAYER_ACCOUNT'] . ':' . $perfectmoney_passphrase . ':' .
            $data['TIMESTAMPGMT'];

        $hash = strtoupper(md5($concatFields));

        if ($hash == $data['V2_HASH']) {
            $invoice_id = (int)$data['PAYMENT_ID'];
            $invoice = $this->Invoices->get($invoice_id);

            if ($data['PAYMENT_AMOUNT'] == $invoice->amount &&
                $data['PAYEE_ACCOUNT'] == $perfectmoney_account &&
                $data['PAYMENT_UNITS'] == get_option('currency_code')
            ) {
                $invoice->status = 1;
                $invoice->paid_date = date("Y-m-d H:i:s");
                $this->Invoices->save($invoice);
                $message = 'VERIFIED';
            } else {
                $invoice->status = 4;
                $this->Invoices->save($invoice);
                $message = 'INVALID';
            }
        }

        $this->Invoices->successPayment($invoice);
    }

    protected function ipnWebmoney($data)
    {
        if (isset($data['LMI_PAYMENT_NO'])) {
            $invoice_id = (int)$data['LMI_PAYMENT_NO'];
            $invoice = $this->Invoices->get($invoice_id);

            if ($invoice->amount == $data['LMI_PAYMENT_AMOUNT']) {
                $invoice->status = 1;
                $invoice->paid_date = date("Y-m-d H:i:s");
                $this->Invoices->save($invoice);
                $message = 'VERIFIED';
            } else {
                $invoice->status = 4;
                $this->Invoices->save($invoice);
                $message = 'INVALID';
            }

            $this->Invoices->successPayment($invoice);
        }
    }

    protected function ipnCoinPayments($data)
    {
        $merchant_id = get_option('coinpayments_merchant_id');
        $secret = get_option('coinpayments_ipn_secret');

        if (!isset($_SERVER['HTTP_HMAC']) || empty($_SERVER['HTTP_HMAC'])) {
            \Cake\Log\Log::write('error', 'No HMAC signature sent', 'payments');
            exit();
        }

        $request = file_get_contents('php://input');
        if ($request === false || empty($request)) {
            \Cake\Log\Log::write('error', 'Error reading POST data', 'payments');
            exit();
        }

        $merchant = isset($_POST['merchant']) ? $_POST['merchant'] : '';
        if (empty($merchant)) {
            \Cake\Log\Log::write('error', 'No Merchant ID passed', 'payments');
            exit();
        }
        if ($merchant != $merchant_id) {
            \Cake\Log\Log::write('error', 'Invalid Merchant ID', 'payments');
            exit();
        }

        $hmac = hash_hmac("sha512", $request, $secret);
        if ($hmac != $_SERVER['HTTP_HMAC']) {
            \Cake\Log\Log::write('error', 'HMAC signature does not match', 'payments');
            exit();
        }

        $invoice_id = intval($_POST['custom']);
        $amount1 = floatval($_POST['amount1']);
        $status = intval($_POST['status']);

        $invoice = $this->Invoices->get($invoice_id);

        // Check amount against order total
        if ($amount1 < $invoice->amount) {
            \Cake\Log\Log::write('error', 'Amount is less than order total!', 'payments');
            exit();
        }

        if ($status >= 100 || $status == 2) {
            $invoice->status = 1;
            $invoice->paid_date = date("Y-m-d H:i:s");
            $this->Invoices->save($invoice);
            $message = 'VERIFIED';
        } elseif ($status < 0) {
            $invoice->status = 4;
            $this->Invoices->save($invoice);
            $message = 'INVALID';
        }

        $this->Invoices->successPayment($invoice);
    }

    protected function ipnCoinbase()
    {
        // https://github.com/coinbase/coinbase-commerce-php/blob/master/examples/Webhook/Webhook.php

        $secret = get_option('coinbase_api_secret');
        $signature = (isset($_SERVER['HTTP_X_CC_WEBHOOK_SIGNATURE'])) ? $_SERVER['HTTP_X_CC_WEBHOOK_SIGNATURE'] : '';
        $payload = trim(file_get_contents('php://input'));

        $data = json_decode($payload);

        \Cake\Log\Log::debug($_SERVER, 'payments');
        \Cake\Log\Log::debug($data, 'payments');

        if (json_last_error()) {
            \Cake\Log\Log::error('Invalid payload provided. No JSON object could be decoded.', 'payments');
            exit();
        }

        if (!isset($data->event)) {
            \Cake\Log\Log::error('Invalid payload provided.', 'payments');
            exit();
        }

        $computedSignature = hash_hmac('sha256', $payload, $secret);
        if (hash_equals($signature, $computedSignature)) {
            \Cake\Log\Log::error('HMAC signature does not match', 'payments');
            exit();
        }

        $invoice_id = (int)$data->event->data->metadata->invoice_id;
        $invoice = $this->Invoices->get($invoice_id);

        if ($data->event->type == 'charge:confirmed') {
            $invoice->status = 1;
            $invoice->paid_date = date("Y-m-d H:i:s");
            $this->Invoices->save($invoice);
            $message = 'VERIFIED';
        } else {
            $invoice->status = 4;
            $this->Invoices->save($invoice);
            $message = 'INVALID';
        }

        $this->Invoices->successPayment($invoice);
    }

    protected function ipnSkrill($data)
    {
        $concatFields = $data['merchant_id'] .
            $data['transaction_id'] .
            strtoupper(md5(get_option('skrill_secret_word'))) .
            $data['mb_amount'] .
            $data['mb_currency'] .
            $data['status'];

        $MBEmail = get_option('skrill_email');

        $invoice_id = (int)$data['transaction_id'];
        $invoice = $this->Invoices->get($invoice_id);

        if ($invoice->amount == $data['amount']) {
            if (strtoupper(md5($concatFields)) == $data['md5sig'] &&
                $data['status'] == 2 &&
                $data['pay_to_email'] == $MBEmail
            ) {
                $invoice->status = 1;
                $invoice->paid_date = date("Y-m-d H:i:s");
                $this->Invoices->save($invoice);
                $message = 'VERIFIED';
            }
        } else {
            $invoice->status = 4;
            $this->Invoices->save($invoice);
            $message = 'INVALID';
        }

        $this->Invoices->successPayment($invoice);
    }

    protected function ipnPaypal($data)
    {
        $data['cmd'] = '_notify-validate';

        // https://developer.paypal.com/docs/classic/ipn/integration-guide/IPNTesting/?mark=IPN%20troubleshoot#invalid

        $paypalURL = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';

        if (get_option('paypal_sandbox', 'no') == 'no') {
            $paypalURL = 'https://ipnpb.paypal.com/cgi-bin/webscr';
        }

        $res = curlRequest($paypalURL, 'POST', $data)->body;

        \Cake\Log\Log::debug($res, 'payments');

        $invoice_id = (int)$data['custom'];
        $invoice = $this->Invoices->get($invoice_id);

        if (strcmp($res, "VERIFIED") == 0) {
            switch ($data['payment_status']) {
                case 'Refunded':
                    $invoice->status = 5;
                    break;
                case 'Completed':
                    $invoice->status = 1;
                    $invoice->paid_date = date("Y-m-d H:i:s");
                    break;
            }

            $this->Invoices->save($invoice);
            $message = 'VERIFIED';
        } elseif (strcmp($res, "INVALID") == 0) {
            $invoice->status = 4;
            $this->Invoices->save($invoice);
            $message = 'INVALID';
        }

        $this->Invoices->successPayment($invoice);
    }
}

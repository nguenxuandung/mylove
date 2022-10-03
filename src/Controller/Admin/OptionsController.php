<?php

namespace App\Controller\Admin;

use Cake\Event\Event;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

/**
 * @property \App\Model\Table\OptionsTable $Options
 */
class OptionsController extends AppAdminController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        if (in_array($this->getRequest()->getParam('action'),
            ['menu', 'menuItem', 'withdrawMethods', 'withdrawItem'])) {
            $this->getEventManager()->off($this->Security);
        }
    }

    public function index()
    {
        $plans = TableRegistry::getTableLocator()->get('Plans')
            ->find('list', [
                'keyField' => 'id',
                'valueField' => 'title',
            ])
            ->where(['enable' => 1])
            ->toArray();

        $this->set('plans', $plans);

        if ($this->saveOptions()) {
            emptyCache();

            $this->Flash->success(__('Settings have been saved.'));

            return $this->redirect(['action' => 'index']);
        }
    }

    public function ads()
    {
        if ($this->saveOptions()) {
            $this->Flash->success(__('Ads have been saved.'));

            return $this->redirect(['action' => 'ads']);
        }
    }

    public function email()
    {
        if ($this->saveOptions()) {
            createEmailFile();

            $this->Flash->success(__('Email settings have been saved.'));

            return $this->redirect(['action' => 'email']);
        }
    }

    public function socialLogin()
    {
        if ($this->saveOptions()) {
            $this->Flash->success(__('Social login settings have been saved.'));

            return $this->redirect(['action' => 'socialLogin']);
        }
    }

    public function payment()
    {
        if ($this->saveOptions()) {
            $this->Flash->success(__('Payment settings have been saved.'));

            return $this->redirect(['action' => 'payment']);
        }
    }

    public function withdraw()
    {
        $withdraw_methods = $this->Options->findByName('withdraw_methods')->first();
        $this->set('withdraw_methods', $withdraw_methods);

        if ($this->saveOptions()) {
            $this->Flash->success(__('Withdraw settings have been saved.'));

            return $this->redirect(['action' => 'withdraw']);
        }
    }

    public function withdrawMethods()
    {
        $withdraw_methods = $this->Options->findByName('withdraw_methods')->first();

        if ($this->getRequest()->is(['post', 'put'])) {
            $items = [];
            foreach ($this->getRequest()->getData() as $item) {
                if (isset($item['id'])) {
                    $item['id'] = \Cake\Utility\Text::slug($item['id']);
                    $items[] = $item;
                }
            }

            $withdraw_methods->value = json_encode($items);

            $this->Options->save($withdraw_methods);

            $this->Flash->success(__('Withdraw settings have been saved.'));

            return $this->redirect($this->referer());
        }
    }

    public function withdrawItem()
    {
        $this->autoRender = false;

        if (!$this->getRequest()->is(['post', 'put'])) {
            return $this->redirect($this->referer());
        }

        $data = $this->getRequest()->getData();

        /**
         * @var \App\Model\Entity\Option $menu
         */
        $withdraw_methods = $this->Options->findByName('withdraw_methods')->first();

        $items = json_decode($withdraw_methods->value);

        $items[] = [
            'id' => \Cake\Utility\Text::slug($data['id']),
            'name' => $data['name'],
            'status' => $data['status'],
            'amount' => floatval($data['amount']),
            'image' => $data['image'],
            'description' => $data['description'],
        ];

        $withdraw_methods->value = json_encode($items);

        $this->Options->save($withdraw_methods);

        return $this->redirect($this->referer());
    }

    public function menu()
    {
        $this->Options->addBehavior('Translate', ['fields' => ['value']]);

        if ($this->getRequest()->getQuery('lang') && isset(get_site_languages()[$this->getRequest()->getQuery('lang')])) {
            $this->Options->setLocale($this->getRequest()->getQuery('lang'));
        }

        $menu = $this->Options->findByName($this->getRequest()->getQuery('menu'))->first();

        if ($this->getRequest()->is(['post', 'put'])) {
            $items = [];
            foreach ($this->getRequest()->getData() as $item) {
                $items[] = $item;
            }

            $menu->value = json_encode($items);

            $this->Options->save($menu);

            $this->Flash->success(__('Menu has been saved.'));

            return $this->redirect($this->referer());
        }

        $this->set('menu', $menu);
    }

    public function menuItem()
    {
        $this->Options->addBehavior('Translate', ['fields' => ['value']]);

        $this->autoRender = false;

        if ($this->getRequest()->getQuery('lang') && isset(get_site_languages()[$this->getRequest()->getQuery('lang')])) {
            $this->Options->setLocale($this->getRequest()->getQuery('lang'));
        }

        if (!$this->getRequest()->is(['post', 'put'])) {
            return $this->redirect($this->referer());
        }

        $data = $this->getRequest()->getData();

        /**
         * @var \App\Model\Entity\Option $menu
         */
        $menu = $this->Options->findByName($data['name'])->first();

        $items = json_decode($menu->value);

        $items[] = [
            'id' => 'm_' . uniqid(),
            'title' => $data['title'],
            'link' => $data['link'],
            'visibility' => $data['visibility'],
            'class' => '',
        ];

        $menu->value = json_encode($items);

        $this->Options->save($menu);

        return $this->redirect($this->referer());
    }

    protected function saveOptions()
    {
        $options = $this->Options->find()->all();

        $settings = [];
        foreach ($options as $option) {
            $settings[$option->name] = [
                'id' => $option->id,
                'value' => $option->value,
            ];
        }

        if ($this->getRequest()->is(['post', 'put'])) {
            foreach ($this->getRequest()->getData('Options') as $key => $optionData) {
                if (is_array($optionData['value'])) {
                    $optionData['value'] = serialize($optionData['value']);
                }
                $option = $this->Options->newEntity();
                $option->id = $key;
                $option = $this->Options->patchEntity($option, $optionData);
                $this->Options->save($option);
            }

            return true;
        }

        $this->set('options', $options);
        $this->set('settings', $settings);
    }

    public function interstitial()
    {
        if ($this->getRequest()->is(['get']) && empty($this->getRequest()->getQuery('source'))) {
            return;
        }

        $source = $this->getRequest()->getQuery('source');

        $option = $this->Options->findByName('interstitial_price')->first();
        if (!$option) {
            throw new NotFoundException(__('Invalid option'));
        }

        $option->value = unserialize($option->value);

        if ($this->getRequest()->is(['post', 'put'])) {
            foreach ($this->getRequest()->data['value'] as $key => $value) {
                if (!empty($value[$source]['advertiser']) && !empty($value[$source]['publisher'])) {
                    $option->value[$key][$source] = [
                        'advertiser' => abs($value[$source]['advertiser']),
                        'publisher' => abs($value[$source]['publisher']),
                    ];
                } else {
                    $option->value[$key][$source] = [
                        'advertiser' => '',
                        'publisher' => '',
                    ];
                }
            }
            unset($key, $value);

            $option->value = serialize($option->value);

            if ($this->Options->save($option)) {
                //debug($option);
                $this->Flash->success('Prices have been updated.');

                return $this->redirect(['action' => 'interstitial', '?' => ['source' => $source]]);
            } else {
                $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
            }
        }

        $this->set('option', $option);
    }

    public function banner()
    {
        if ($this->getRequest()->is(['get']) && empty($this->getRequest()->getQuery('source'))) {
            return;
        }

        $source = $this->getRequest()->getQuery('source');

        $option = $this->Options->findByName('banner_price')->first();
        if (!$option) {
            throw new NotFoundException(__('Invalid option'));
        }

        $option->value = unserialize($option->value);

        if ($this->getRequest()->is(['post', 'put'])) {
            foreach ($this->getRequest()->data['value'] as $key => $value) {
                if (!empty($value[$source]['advertiser']) && !empty($value[$source]['publisher'])) {
                    $option->value[$key][$source] = [
                        'advertiser' => abs($value[$source]['advertiser']),
                        'publisher' => abs($value[$source]['publisher']),
                    ];
                } else {
                    $option->value[$key][$source] = [
                        'advertiser' => '',
                        'publisher' => '',
                    ];
                }
            }
            unset($key, $value);

            $option->value = serialize($option->value);

            if ($this->Options->save($option)) {
                //debug($option);
                $this->Flash->success('Prices have been updated.');

                return $this->redirect(['action' => 'banner', '?' => ['source' => $source]]);
            } else {
                $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
            }
        }

        $this->set('option', $option);
    }

    public function popup()
    {
        if ($this->getRequest()->is(['get']) && empty($this->getRequest()->getQuery('source'))) {
            return;
        }

        $source = $this->getRequest()->getQuery('source');

        $option = $this->Options->findByName('popup_price')->first();
        if (!$option) {
            throw new NotFoundException(__('Invalid option'));
        }

        $option->value = unserialize($option->value);

        if ($this->getRequest()->is(['post', 'put'])) {
            foreach ($this->getRequest()->data['value'] as $key => $value) {
                if (!empty($value[$source]['advertiser']) && !empty($value[$source]['publisher'])) {
                    $option->value[$key][$source] = [
                        'advertiser' => abs($value[$source]['advertiser']),
                        'publisher' => abs($value[$source]['publisher']),
                    ];
                } else {
                    $option->value[$key][$source] = [
                        'advertiser' => '',
                        'publisher' => '',
                    ];
                }
            }
            unset($key, $value);

            $option->value = serialize($option->value);

            if ($this->Options->save($option)) {
                //debug($option);
                $this->Flash->success('Prices have been updated.');

                return $this->redirect(['action' => 'popup', '?' => ['source' => $source]]);
            } else {
                $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
            }
        }

        $this->set('option', $option);
    }

    public function payoutInterstitial()
    {
        $option = $this->Options->findByName('payout_rates_interstitial')->first();
        if (!$option) {
            throw new NotFoundException(__('Invalid option'));
        }

        $option->value = unserialize($option->value);

        if ($this->getRequest()->is(['post', 'put'])) {
            foreach ($this->getRequest()->getData('value') as $key => $value) {
                if (!empty($value[2]) && !empty($value[3])) {
                    $option->value[$key] = [
                        2 => abs($value[2]),
                        3 => abs($value[3]),
                    ];
                } else {
                    $option->value[$key] = [
                        2 => '',
                        3 => '',
                    ];
                }
            }
            unset($key, $value);

            $option->value = serialize($option->value);

            if ($this->Options->save($option)) {
                //debug($option);
                $this->Flash->success('Prices have been updated.');

                return $this->redirect(['action' => 'payoutInterstitial']);
            } else {
                $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
            }
        }

        $this->set('option', $option);
    }

    public function payoutBanner()
    {
        $option = $this->Options->findByName('payout_rates_banner')->first();
        if (!$option) {
            throw new NotFoundException(__('Invalid option'));
        }

        $option->value = unserialize($option->value);

        if ($this->getRequest()->is(['post', 'put'])) {
            foreach ($this->getRequest()->getData('value') as $key => $value) {
                if (!empty($value[2]) && !empty($value[3])) {
                    $option->value[$key] = [
                        2 => abs($value[2]),
                        3 => abs($value[3]),
                    ];
                } else {
                    $option->value[$key] = [
                        2 => '',
                        3 => '',
                    ];
                }
            }
            unset($key, $value);

            $option->value = serialize($option->value);

            if ($this->Options->save($option)) {
                //debug($option);
                $this->Flash->success('Prices have been updated.');

                return $this->redirect(['action' => 'payoutBanner']);
            } else {
                $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
            }
        }

        $this->set('option', $option);
    }

    public function payoutPopup()
    {
        $option = $this->Options->findByName('payout_rates_popup')->first();
        if (!$option) {
            throw new NotFoundException(__('Invalid option'));
        }

        $option->value = unserialize($option->value);

        if ($this->getRequest()->is(['post', 'put'])) {
            foreach ($this->getRequest()->getData('value') as $key => $value) {
                if (!empty($value[2]) && !empty($value[3])) {
                    $option->value[$key] = [
                        2 => abs($value[2]),
                        3 => abs($value[3]),
                    ];
                } else {
                    $option->value[$key] = [
                        2 => '',
                        3 => '',
                    ];
                }
            }
            unset($key, $value);

            $option->value = serialize($option->value);

            if ($this->Options->save($option)) {
                //debug($option);
                $this->Flash->success('Prices have been updated.');

                return $this->redirect(['action' => 'payoutPopup']);
            } else {
                $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
            }
        }

        $this->set('option', $option);
    }

    public function system()
    {
        $this->viewBuilder()->setLayout('blank');
    }
}

<?php

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * @property \Cake\ORM\Table $AppAdmin
 */
class AppAdminController extends AppController
{
    public $paginate = [
        'limit' => 10,
        'order' => ['id' => 'DESC'],
    ];

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->viewBuilder()->setLayout('admin');

        if ($this->redirect_for_database_upgrade()) {
            return $this->redirect(['controller' => 'Upgrade', 'action' => 'index'], 307);
        }

        if ($this->Auth->user()) {
            $this->checkDefaultCampaigns();
        }
    }

    public function isAuthorized($user = null)
    {
        // Admin can access every action
        if ($user['role'] === 'admin') {
            return true;
        }
        // Default deny
        return false;
    }

    protected function redirect_for_database_upgrade()
    {
        if (require_database_upgrade() && $this->getRequest()->getParam('controller') !== 'Upgrade') {
            return true;
        }

        return false;
    }

    protected function redirect_for_license_activate()
    {
        if (require_database_upgrade()) {
            return false;
        }

        $Activation = TableRegistry::getTableLocator()->get('Activation');
        if ($Activation->checkLicense() === false && $this->getRequest()->getParam('controller') !== 'Activation') {
            return true;
        }

        return false;
    }

    protected function checkDefaultCampaigns()
    {
        if (require_database_upgrade()) {
            return true;
        }

        $Campaigns = TableRegistry::getTableLocator()->get('Campaigns');
        $interstitial_campaigns = $Campaigns->find()
            ->where([
                'default_campaign' => 1,
                'ad_type' => 1,
                'status' => 1,
            ])
            ->count();

        if ($interstitial_campaigns == 0) {
            $this->Flash->error(__('You must have at least one interstitial campaign marked as default.'));
        }

        $banner_campaigns = $Campaigns->find()
            ->where([
                'default_campaign' => 1,
                'ad_type' => 2,
                'status' => 1,
            ])
            ->count();

        if ($banner_campaigns == 0) {
            $this->Flash->error(__('You must have at least one banner campaign marked as default.'));
        }
    }
}

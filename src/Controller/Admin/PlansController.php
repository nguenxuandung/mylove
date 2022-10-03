<?php

namespace App\Controller\Admin;

use Cake\Http\Exception\NotFoundException;

/**
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\PlansTable $Plans
 */
class PlansController extends AppAdminController
{
    public function index()
    {
        $query = $this->Plans->find();
        $plans = $this->paginate($query);

        $this->set('plans', $plans);
    }

    public function add()
    {
        $plan = $this->Plans->newEntity();

        if ($this->getRequest()->is('post')) {
            $this->getRequest()->data['monthly_price'] = price_database_format($this->getRequest()->data['monthly_price']);
            $this->getRequest()->data['yearly_price'] = price_database_format($this->getRequest()->data['yearly_price']);

            $plan = $this->Plans->patchEntity($plan, $this->getRequest()->data);

            if ($this->Plans->save($plan)) {
                $this->Flash->success(__('Plan has been added.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
        }
        $this->set('plan', $plan);
    }

    public function edit($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Invalid Plan'));
        }

        if ($this->getRequest()->getQuery('lang') && isset(get_site_languages()[$this->getRequest()->getQuery('lang')])) {
            //$post->_locale = $this->getRequest()->getQuery('lang');
            $this->Plans->locale($this->getRequest()->getQuery('lang'));
        }

        $plan = $this->Plans->get($id);
        if (!$plan) {
            throw new NotFoundException(__('Invalid Plan'));
        }

        if ($this->getRequest()->is(['post', 'put'])) {
            if ($id == 1) {
                $this->getRequest()->data['enable'] = 1;
                $this->getRequest()->data['monthly_price'] = 0;
                $this->getRequest()->data['yearly_price'] = 0;
            }

            $this->getRequest()->data['monthly_price'] = price_database_format($this->getRequest()->data['monthly_price']);
            $this->getRequest()->data['yearly_price'] = price_database_format($this->getRequest()->data['yearly_price']);

            $plan = $this->Plans->patchEntity($plan, $this->getRequest()->data);

            if ($this->Plans->save($plan)) {
                $this->Flash->success(__('Plan has been updated.'));

                return $this->redirect(['action' => 'edit', $plan->id]);
            }
            $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
        }
        $this->set('plan', $plan);
    }

    public function delete($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Invalid Plan'));
        }

        if ($id == 1) {
            $this->Flash->error(__("Default plan can't be deleted."));

            return $this->redirect(['action' => 'index']);
        }

        $plan = $this->Plans->findById($id)->first();
        if (!$plan) {
            throw new NotFoundException(__('Invalid Plan'));
        }

        $plans = $this->Plans->find('list', [
            'keyField' => 'id',
            'valueField' => 'title',
        ])->where(['enable' => 1, 'id  <>' => $id])->toArray();

        if ($this->getRequest()->is(['post', 'put'])) {
            $this->loadModel('Users');

            $query = $this->Users->query()
                ->update()
                ->set(['plan_id' => $this->getRequest()->data['plan_replace']])
                ->where(['plan_id' => $id])
                ->execute();

            if ($this->Plans->delete($plan)) {
                $this->Flash->success(__('The plan with id: {0} has been deleted.', $plan->id));

                return $this->redirect(['action' => 'index']);
            }
        }
        $this->set('plan', $plan);
        $this->set('plans', $plans);
    }
}

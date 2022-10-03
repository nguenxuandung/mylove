<?php

namespace App\Controller\Admin;

use Cake\Event\Event;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\Time;

/**
 * @property \App\Model\Table\LinksTable $Links
 * @property \App\Controller\Component\CaptchaComponent $Captcha
 */
class LinksController extends AppAdminController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        if (in_array($this->getRequest()->getParam('action'), ['mass'])) {
            $this->getEventManager()->off($this->Security);
        }
    }

    public function index()
    {
        $conditions = [];

        $filter_fields = ['id', 'user_id', 'alias', 'ad_type', 'title_desc'];

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
                    if (in_array($param_name, ['alias'])) {
                        $conditions[] = [
                            ['Links.' . $param_name . ' LIKE' => '%' . $value . '%'],
                        ];
                    } elseif (in_array($param_name, ['title_desc'])) {
                        $conditions['OR'] = [
                            ['Links.title LIKE' => '%' . $value . '%'],
                            ['Links.description LIKE' => '%' . $value . '%'],
                            ['Links.url LIKE' => '%' . $value . '%'],
                        ];
                    } elseif (in_array($param_name, ['id', 'user_id', 'ad_type'])) {
                        $conditions['Links.' . $param_name] = $value;
                    }
                    $this->getRequest()->data['Filter'][$param_name] = $value;
                }
            }
        }

        $query = $this->Links->find()
            ->contain(['Users'])
            ->where($conditions)
            ->where(['Links.status' => 1]);
        $links = $this->paginate($query);

        $this->set('links', $links);
    }

    public function hidden()
    {
        $conditions = [];

        $filter_fields = ['id', 'user_id', 'alias', 'title_desc'];

        //Transform POST into GET
        if ($this->getRequest()->is(['post', 'put']) && isset($this->getRequest()->data['Filter'])) {
            $filter_url = [];

            $filter_url['controller'] = $this->getRequest()->params['controller'];

            $filter_url['action'] = $this->getRequest()->params['action'];

            // We need to overwrite the page every time we change the parameters
            $filter_url['page'] = 1;

            // for each filter we will add a GET parameter for the generated url
            foreach ($this->getRequest()->data['Filter'] as $name => $value) {
                if (in_array($name, $filter_fields) && $value) {
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
                    if (in_array($param_name, ['alias'])) {
                        $conditions[] = [
                            ['Links.' . $param_name . ' LIKE' => '%' . $value . '%'],
                        ];
                    } elseif (in_array($param_name, ['title_desc'])) {
                        $conditions['OR'] = [
                            ['Links.title LIKE' => '%' . $value . '%'],
                            ['Links.description LIKE' => '%' . $value . '%'],
                            ['Links.url LIKE' => '%' . $value . '%'],
                        ];
                    } elseif (in_array($param_name, ['id', 'user_id'])) {
                        $conditions['Links.' . $param_name] = $value;
                    }
                    $this->getRequest()->data['Filter'][$param_name] = $value;
                }
            }
        }

        $query = $this->Links->find()
            ->contain(['Users'])
            ->where($conditions)
            ->where(['Links.status' => 2]);
        $links = $this->paginate($query);

        $this->set('links', $links);
    }

    public function inactive()
    {
        $conditions = [];

        $filter_fields = ['id', 'user_id', 'alias', 'title_desc'];

        //Transform POST into GET
        if ($this->getRequest()->is(['post', 'put']) && isset($this->getRequest()->data['Filter'])) {
            $filter_url = [];

            $filter_url['controller'] = $this->getRequest()->params['controller'];

            $filter_url['action'] = $this->getRequest()->params['action'];

            // We need to overwrite the page every time we change the parameters
            $filter_url['page'] = 1;

            // for each filter we will add a GET parameter for the generated url
            foreach ($this->getRequest()->data['Filter'] as $name => $value) {
                if (in_array($name, $filter_fields) && $value) {
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
                    if (in_array($param_name, ['alias'])) {
                        $conditions[] = [
                            ['Links.' . $param_name . ' LIKE' => '%' . $value . '%'],
                        ];
                    } elseif (in_array($param_name, ['title_desc'])) {
                        $conditions['OR'] = [
                            ['Links.title LIKE' => '%' . $value . '%'],
                            ['Links.description LIKE' => '%' . $value . '%'],
                            ['Links.url LIKE' => '%' . $value . '%'],
                        ];
                    } elseif (in_array($param_name, ['id', 'user_id'])) {
                        $conditions['Links.' . $param_name] = $value;
                    }
                    $this->getRequest()->data['Filter'][$param_name] = $value;
                }
            }
        }

        $query = $this->Links->find()
            ->contain(['Users'])
            ->where($conditions)
            ->where(['Links.status' => 3]);
        $links = $this->paginate($query);

        $this->set('links', $links);
    }

    public function edit($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Invalid link'));
        }

        /** @var \App\Model\Entity\Link $link */
        $link = $this->Links->findById($id)->first();
        if (!$link) {
            throw new NotFoundException(__('Invalid link'));
        }

        if ($this->getRequest()->is(['post', 'put'])) {
            $this->setRequest($this->getRequest()->withData('user_id', $link->user_id));

            $link = $this->Links->patchEntity($link, $this->getRequest()->getData());

            $link->url_hash = sha1($link->url);
            $link->last_activity = Time::now();

            if ($this->Links->save($link)) {
                $this->Flash->success(__('The Link has been updated.'));
            } else {
                //debug($link->getErrors());
                $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
            }

            return $this->redirect($this->referer());
        }
        $this->set('link', $link);
    }

    public function hide($id)
    {
        if ($this->getRequest()->getParam('_csrfToken') !== $this->getRequest()->getQuery('token')) {
            throw new ForbiddenException();
        }

        if ($this->hideLink($id)) {
            $this->Flash->success(__('The Link has been hidden.'));
        }

        return $this->redirect($this->referer());
    }

    protected function hideLink($id)
    {
        /** @var \App\Model\Entity\Link $link */
        $link = $this->Links->findById($id)->first();

        $link->status = 2;
        $link->last_activity = Time::now();

        if (!$this->Links->save($link)) {
            return false;
        }

        return true;
    }

    public function unhide($id)
    {
        if ($this->getRequest()->getParam('_csrfToken') !== $this->getRequest()->getQuery('token')) {
            throw new ForbiddenException();
        }

        if ($this->unhideLink($id)) {
            $this->Flash->success(__('The Link has been unhidden.'));
        }

        return $this->redirect($this->referer());
    }

    protected function unhideLink($id)
    {
        /** @var \App\Model\Entity\Link $link */
        $link = $this->Links->findById($id)->first();

        $link->status = 1;
        $link->last_activity = Time::now();

        if (!$this->Links->save($link)) {
            return false;
        }

        return true;
    }

    public function deactivate($id)
    {
        if ($this->getRequest()->getParam('_csrfToken') !== $this->getRequest()->getQuery('token')) {
            throw new ForbiddenException();
        }

        if ($this->deactivateLink($id)) {
            $this->Flash->success(__('The Link has been deactivated.'));
        }

        return $this->redirect($this->referer());
    }

    protected function deactivateLink($id)
    {
        /** @var \App\Model\Entity\Link $link */
        $link = $this->Links->findById($id)->first();

        $link->status = 3;
        $link->last_activity = Time::now();

        if (!$this->Links->save($link)) {
            return false;
        }

        return true;
    }

    public function delete($id, $views = false)
    {
        if ($this->getRequest()->getParam('_csrfToken') !== $this->getRequest()->getQuery('token')) {
            throw new ForbiddenException();
        }

        if ($this->deleteLink($id, $views)) {
            $this->Flash->success(__('The link has been deleted.'));
        }

        return $this->redirect($this->referer());
    }

    protected function deleteLink($id, $views = false)
    {
        /** @var \App\Model\Entity\Link $link */
        $link = $this->Links->findById($id)->first();

        if (!$this->Links->delete($link)) {
            return false;
        }

        if ($views) {
            $this->Links->Statistics->deleteAll(['link_id' => $id]);
        }

        return true;
    }

    public function mass()
    {
        $this->getRequest()->allowMethod(['post']);

        $action = $this->getRequest()->getData('action');
        $ids = $this->getRequest()->getData('ids');

        if (!$ids || !$action) {
            return $this->redirect($this->referer());
        }

        if ($action === 'hide') {
            foreach ($ids as $id) {
                $this->hideLink($id);
            }
        }

        if ($action === 'hide') {
            foreach ($ids as $id) {
                $this->hideLink($id);
            }
        }

        if ($action === 'unhide') {
            foreach ($ids as $id) {
                $this->unhideLink($id);
            }
        }

        if ($action === 'deactivate') {
            foreach ($ids as $id) {
                $this->deactivateLink($id);
            }
        }

        if ($action === 'delete') {
            foreach ($ids as $id) {
                $this->deleteLink($id);
            }
        }

        if ($action === 'delete-stats') {
            foreach ($ids as $id) {
                $this->deleteLink($id, true);
            }
        }

        return $this->redirect($this->referer());
    }
}

<?php

namespace App\Controller\Admin;

use Cake\Http\Exception\NotFoundException;

/**
 * @property \App\Model\Table\PagesTable $Pages
 */
class PagesController extends AppAdminController
{
    public function index()
    {
        $query = $this->Pages->find();
        $pages = $this->paginate($query);

        $this->set('pages', $pages);
    }

    public function add()
    {
        $page = $this->Pages->newEntity();

        if ($this->getRequest()->is('post')) {
            if (!empty($this->getRequest()->getData('slug'))) {
                $this->getRequest()->data['slug'] = $this->Pages->createSlug($this->getRequest()->getData('slug'));
            } else {
                $this->getRequest()->data['slug'] = $this->Pages->createSlug($this->getRequest()->getData('title'));
            }

            $page = $this->Pages->patchEntity($page, $this->getRequest()->getData());

            if ($this->Pages->save($page)) {
                $this->Flash->success(__('Page has been added.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
        }
        $this->set('page', $page);
    }

    public function edit($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Invalid Page'));
        }

        if ($this->getRequest()->getQuery('lang') && isset(get_site_languages()[$this->getRequest()->getQuery('lang')])) {
            //$page->_locale = $this->getRequest()->getQuery('lang');
            $this->Pages->locale($this->getRequest()->getQuery('lang'));
        }

        $page = $this->Pages->get($id);
        if (!$page) {
            throw new NotFoundException(__('Invalid Page'));
        }

        if ($this->getRequest()->is(['post', 'put'])) {
            if (!empty($this->getRequest()->getData('slug'))) {
                $this->getRequest()->data['slug'] = $this->Pages->createSlug($this->getRequest()->getData('slug'), $id);
            } else {
                $this->getRequest()->data['slug'] = $this->Pages->createSlug($this->getRequest()->getData('title'), $id);
            }

            $page = $this->Pages->patchEntity($page, $this->getRequest()->getData());

            if ($this->Pages->save($page)) {
                $this->Flash->success(__('Page has been updated.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
        }
        $this->set('page', $page);
    }

    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);

        /*
        if(in_array($id, [1, 2, 3, 4, 5]) ) {
            $this->Flash->error(__('You can not delete this page.'));
            return $this->redirect(['action' => 'index']);
        }
        */

        $page = $this->Pages->findById($id)->first();

        if ($this->Pages->delete($page)) {
            $this->Flash->success(__('The page with id: {0} has been deleted.', $page->id));

            return $this->redirect(['action' => 'index']);
        }
    }
}

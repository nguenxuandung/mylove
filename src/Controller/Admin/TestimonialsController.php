<?php

namespace App\Controller\Admin;

use Cake\Http\Exception\NotFoundException;

/**
 * @property \App\Model\Table\TestimonialsTable $Testimonials
 */
class TestimonialsController extends AppAdminController
{
    public function index()
    {
        $query = $this->Testimonials->find();
        $testimonials = $this->paginate($query);

        $this->set('testimonials', $testimonials);
    }

    public function add()
    {
        $testimonial = $this->Testimonials->newEntity();

        if ($this->getRequest()->is('post')) {
            $testimonial = $this->Testimonials->patchEntity($testimonial, $this->getRequest()->getData());

            if ($this->Testimonials->save($testimonial)) {
                $this->Flash->success(__('Testimonial has been added.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
        }
        $this->set('testimonial', $testimonial);
    }

    public function edit($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Invalid Testimonial'));
        }

        if ($this->getRequest()->getQuery('lang') && isset(get_site_languages()[$this->getRequest()->getQuery('lang')])) {
            //$testimonial->_locale = $this->getRequest()->getQuery('lang');
            $this->Testimonials->locale($this->getRequest()->getQuery('lang'));
        }

        $testimonial = $this->Testimonials->get($id);
        if (!$testimonial) {
            throw new NotFoundException(__('Invalid Testimonial'));
        }

        if ($this->getRequest()->is(['post', 'put'])) {
            $testimonial = $this->Testimonials->patchEntity($testimonial, $this->getRequest()->getData());

            if ($this->Testimonials->save($testimonial)) {
                $this->Flash->success(__('Testimonial has been updated.'));

                \Cake\Cache\Cache::delete('home_testimonials_' . locale_get_default(), '1day');

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
        }
        $this->set('testimonial', $testimonial);
    }

    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);

        $testimonial = $this->Testimonials->findById($id)->first();

        if ($this->Testimonials->delete($testimonial)) {
            $this->Flash->success(__('The testimonial with id: {0} has been deleted.', $testimonial->id));

            return $this->redirect(['action' => 'index']);
        }
    }
}

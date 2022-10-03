<?php

namespace App\View\Cell;

use Cake\View\Cell;

class TestimonialCell extends Cell
{
    public function display()
    {
        $this->loadModel('Testimonials');
        $testimonials = $this->Testimonials->find()
            ->where(['published' => 1])
            ->order(['RAND()'])
            ->limit(5)
            ->toArray();
        $this->set('testimonials', $testimonials);
    }
}

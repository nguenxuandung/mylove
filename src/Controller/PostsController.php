<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\Http\Exception\NotFoundException;

/**
 * @property \App\Model\Table\PostsTable $Posts
 */
class PostsController extends FrontController
{
    public $paginate = [
        'limit' => 10
    ];

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['index', 'view']);
    }

    public function index()
    {
        if (!(bool)get_option('blog_enable', 0)) {
            throw new NotFoundException(__('Not Found.'));
        }

        $query = $this->Posts->find()
            ->where(['Posts.published' => 1])
            ->order(['Posts.id' => 'DESC']);
        $posts = $this->paginate($query);

        $this->set('posts', $posts);
    }

    public function view($id = null, $slug = null)
    {
        if (!(bool)get_option('blog_enable', 0)) {
            throw new NotFoundException(__('Not Found.'));
        }

        if (!$id) {
            throw new NotFoundException(__('Invalid Post.'));
        }

        $post = $this->Posts->find()->where(['Posts.id' => $id, 'Posts.published' => 1])->first();

        if (!$post) {
            throw new NotFoundException(__('Invalid Post.'));
        }

        $this->set('post', $post);
    }
}

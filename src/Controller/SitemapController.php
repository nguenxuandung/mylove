<?php

namespace App\Controller;

use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\Http\Exception\NotFoundException;

/**
 * @property \App\Model\Table\LinksTable $Links
 * @property \App\Model\Table\PagesTable $Pages
 * @property \App\Model\Table\PostsTable $Posts
 * @property \Cake\ORM\Table $Sitemap
 */
class SitemapController extends FrontController
{
    public $paginate = [
        'limit' => 50000,
        'maxLimit' => 50000,
    ];

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->viewBuilder()->setLayout('blank');
        $this->Auth->allow(['index', 'general', 'links', 'pages', 'posts']);
    }

    public function index()
    {
        $this->response = $this->response->withType('xml');

        $this->loadModel('Links');

        if ((bool)get_option('sitemap_shortlinks', false)) {
            if (($linksSitemaps = Cache::read('linksSitemaps', '1day')) === false) {
                $linksSitemaps = $this->Links->find()
                    ->contain(['Users'])
                    ->select(['Users.status', 'Links.status', 'Links.alias', 'Links.domain'])
                    ->where([
                        'Users.status' => 1,
                        'Links.status IN' => [1, 2],
                    ])
                    ->count();

                Cache::write('linksSitemaps', $linksSitemaps, '1day');
            }
            $this->set('linksSitemaps', ceil($linksSitemaps / 50000));
        }
    }

    public function general()
    {
        $this->response = $this->response->withType('xml');
    }

    public function pages()
    {
        $this->response = $this->response->withType('xml');

        $this->loadModel('Pages');

        $pages = $this->Pages->find()
            ->select(['published', 'slug'])
            ->where([
                'published' => 1,
            ]);

        $this->set('pages', $pages);
    }

    public function posts()
    {
        $this->response = $this->response->withType('xml');

        if (!(bool)get_option('blog_enable', false)) {
            throw new NotFoundException(__('Not Found.'));
        }

        $this->loadModel('Posts');

        $posts = $this->Posts->find()
            ->select(['published', 'id', 'slug'])
            ->where([
                'published' => 1,
            ]);

        $this->set('posts', $posts);
    }

    public function links()
    {
        @ini_set('memory_limit', '-1');
        @set_time_limit(0);

        $this->response = $this->response->withType('xml');

        if (!(bool)get_option('sitemap_shortlinks', false)) {
            throw new NotFoundException(__('Not Found.'));
        }

        $this->loadModel('Links');

        $query = $this->Links->find()
            ->contain(['Users'])
            ->select(['Users.status', 'Links.status', 'Links.alias', 'Links.domain'])
            ->where([
                'Users.status' => 1,
                //'Links.status <>' => 3,
                'Links.status IN' => [1, 2],
            ]);

        $cacheKey = $this->name . '_' . md5(json_encode($this->request->query));
        $query->cache($cacheKey, '1day');

        $links = $this->paginate($query);

        $this->set('links', $links);
    }
}

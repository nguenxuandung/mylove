<?php

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Filesystem\File;
use Migrations\Migrations;
use Cake\ORM\TableRegistry;

/**
 * @property \App\Model\Table\UsersTable $Users
 * @property \Cake\ORM\Table $Install
 */
class InstallController extends AppController
{
    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow();

        //$this->Security->config('unlockedActions', ['index', 'database', 'data', 'adminuser', 'finish']);
        //$this->getEventManager()->off($this->Csrf);
        $this->getEventManager()->off($this->Security);

        $this->viewBuilder()->setLayout('install');
    }

    protected function check()
    {
        if (is_app_installed()) {
            return $this->redirect('/');
        }
    }

    public function index()
    {
        $this->check();
    }

    public function database()
    {
        $this->check();

        if (!empty($this->getRequest()->getData())) {
            try {
                $host = $this->getRequest()->getData('host');
                $port = $this->getRequest()->getData('port');
                $username = $this->getRequest()->getData('username');
                $password = $this->getRequest()->getData('password');
                $database = $this->getRequest()->getData('database');

                $conn = new \PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
                // set the PDO error mode to exception
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $conn = null;
            } catch (\PDOException $e) {
                $this->Flash->error(__("Connection failed: ") . $e->getMessage());

                return null;
            }

            $result = $this->createConfigureFile($this->getRequest()->getData());

            if ($result !== true) {
                $this->Flash->error($result);
            } else {
                return $this->redirect(['action' => 'data']);
            }
        }
    }

    public function data()
    {
        $this->check();

        if ($this->getRequest()->getQuery('run')) {
            set_time_limit(10 * MINUTE);

            try {
                $migrations = new Migrations();
                $result = $migrations->migrate();
            } catch (\Exception $ex) {
                $result = __('Can not load initial data. ') . $ex->getMessage();
            }

            if ($result !== true) {
                $this->Flash->error($result);

                return null;
            }

            return $this->redirect(['action' => 'adminuser']);
        }
    }

    public function adminuser()
    {
        $this->check();

        $this->loadModel('Users');

        $user = $this->Users->newEntity();

        if ($this->getRequest()->is('post')) {
            $user = $this->Users->patchEntity($user, $this->getRequest()->getData());

            $user->role = 'admin';
            $user->status = 1;
            $user->plan_id = 1;

            $user->api_token = \Cake\Utility\Security::hash(\Cake\Utility\Text::uuid(), 'sha1', true);
            $user->activation_key = '';

            if ($this->Users->save($user)) {
                return $this->redirect(['action' => 'finish']);
            }
            $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
        }
        $this->set('user', $user);
    }

    public function finish()
    {
        $this->check();

        $Options = TableRegistry::getTableLocator()->get('Options');

        $Users = TableRegistry::getTableLocator()->get('Users');
        $admin_user = $Users->get(2);

        $Options->updateAll(['value' => 1], ['name' => 'installed']);

        $Options->updateAll(['value' => $admin_user->email], ['name' => 'admin_email']);

        createEmailFile();

        Configure::write('Adlinkfly.installed', 1);
        Configure::dump('app_vars', 'default', ['Adlinkfly']);
    }

    protected function createConfigureFile($data)
    {
        $config = [
            'host' => 'localhost',
            'port' => '3306',
            'username' => 'root',
            'password' => '',
            'database' => '',
        ];

        foreach ($data as $key => $value) {
            if (isset($config[$key])) {
                $config[$key] = $value;
            }
        }

        $config = array_map(function ($value) {
            return addcslashes($value, '\'');
        }, $config);

        $result = copy(CONFIG . 'configure.install.php', CONFIG . 'configure.php');
        if (!$result) {
            return __('Could not copy configure.php file.');
        }

        $file = new File(CONFIG . 'configure.php');
        $content = $file->read();

        foreach ($config as $configKey => $configValue) {
            $content = str_replace('{default_' . $configKey . '}', $configValue, $content);
        }

        $content = str_replace('__SALT__', generate_random_string(50), $content);

        if (!$file->write($content)) {
            return __('Could not write configure.php file.');
        }

        return true;
    }
}

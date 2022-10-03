<?php

namespace Queue\Shell\Task;

use Cake\Console\ConsoleIo;
use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\Mailer\Email;
use Exception;
use Queue\Model\QueueException;
use Throwable;

/**
 * A convenience task ready to use for asynchronously sending basic emails.
 *
 * Especially useful is the fact that sending is auto-retried as per your config.
 * Do do not lose the email, you can decide to even retry manually again afterwards.
 *
 * @author Mark Scherer
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class QueueEmailTask extends QueueTask implements AddInterface {

	/**
	 * @var int
	 */
	public $timeout = 120;

	/**
	 * @var \Cake\Mailer\Email
	 */
	public $Email;

	/**
	 * List of default variables for Email class.
	 *
	 * @var array
	 */
	protected $defaults = [];

	/**
	 * @param \Cake\Console\ConsoleIo|null $io IO
	 */
	public function __construct(ConsoleIo $io = null) {
		parent::__construct($io);

		$adminEmail = Configure::read('Config.adminEmail');
		if ($adminEmail) {
			$this->defaults['from'] = $adminEmail;
		}
	}

	/**
	 * "Add" the task, not possible for QueueEmailTask
	 *
	 * @return void
	 */
	public function add() {
		$adminEmail = Configure::read('Config.adminEmail');
		if ($adminEmail) {
			$data = [
				'settings' => [
					'to' => $adminEmail,
					'subject' => 'Test Subject',
					'from' => $adminEmail,
				],
				'content' => 'Hello world',
			];
			$this->QueuedJobs->createJob('Email', $data);
			$this->success('OK, job created for email `' . $adminEmail . '`, now run the worker');

			return;
		}

		$this->err('Queue Email Task cannot be added via Console without `Config.adminEmail` being set.');
		$this->out('Please set this config value in your app.php Configure config. It will use this for to+from then.');
		$this->out('Or use createJob() on the QueuedTasks Table to create a proper QueueEmail job.');
		$this->out('The payload $data array should look something like this:');
		$this->out(var_export([
			'settings' => [
				'to' => 'email@example.com',
				'subject' => 'Email Subject',
				'from' => 'system@example.com',
				'template' => 'sometemplate',
			],
			'content' => 'hello world',
		], true));
		$this->out('Alternatively, you can pass the whole Email class to directly use it.');
	}

	/**
	 * @param array $data The array passed to QueuedJobsTable::createJob()
	 * @param int $jobId The id of the QueuedJob entity
	 * @return void
	 * @throws \Exception
	 * @throws \Throwable
	 */
	public function run(array $data, $jobId) {
		if (!isset($data['settings'])) {
			throw new QueueException('Queue Email task called without settings data.');
		}

		/** @var \Cake\Mailer\Email|null $email */
		$email = $data['settings'];
		if (is_object($email) && $email instanceof Email) {
			$this->Email = $email;

			$result = null;
			try {
				if (!empty($data['transport'])) {
					$email->setTransport($data['transport']);
				}
				$content = isset($data['content']) ? $data['content'] : null;
				$result = $email->send($content);

			} catch (Throwable $e) {
				$error = $e->getMessage();
				$error .= ' (line ' . $e->getLine() . ' in ' . $e->getFile() . ')' . PHP_EOL . $e->getTraceAsString();
				Log::write('error', $error);

				throw $e;

			} catch (Exception $e) {
				$error = $e->getMessage();
				$error .= ' (line ' . $e->getLine() . ' in ' . $e->getFile() . ')' . PHP_EOL . $e->getTraceAsString();
				Log::write('error', $error);

				throw $e;
			}

			if (!$result) {
				throw new QueueException('Could not send email.');
			}

			return;
		}

		$this->Email = $this->_getMailer();

		$settings = $data['settings'] + $this->defaults;
		foreach ($settings as $method => $setting) {
			$setter = 'set' . ucfirst($method);
			if (in_array($method, ['theme', 'template', 'layout'], true)) {
				call_user_func_array([$this->Email->viewBuilder(), $setter], (array)$setting);

				continue;
			}

			call_user_func_array([$this->Email, $setter], (array)$setting);
		}

		$message = null;
		if (isset($data['content'])) {
			$message = $data['content'];
		}
		if (!empty($data['vars'])) {
			$this->Email->setViewVars($data['vars']);
		}
		if (!empty($data['headers'])) {
			if (!is_array($data['headers'])) {
				throw new QueueException('Please provide headers as array.');
			}
			$this->Email->setHeaders($data['headers']);
		}

		if (!$this->Email->send($message)) {
			throw new QueueException('Could not send email.');
		}
	}

	/**
	 * Check if Mail class exists and create instance
	 *
	 * @return \Cake\Mailer\Email
	 * @throws \Exception
	 */
	protected function _getMailer() {
		$class = Configure::read('Queue.mailerClass');
		if (!$class) {
			$class = 'Tools\Mailer\Email';
			if (!class_exists($class)) {
				$class = 'Cake\Mailer\Email';
			}
		}
		if (!class_exists($class)) {
			throw new QueueException(sprintf('Configured mailer class `%s` in `%s` not found.', $class, get_class($this)));
		}

		return new $class();
	}

	/**
	 * Log message
	 *
	 * @param array $contents log-data
	 * @param mixed $log int for loglevel, array for merge with log-data
	 * @return void
	 */
	protected function _log($contents, $log) {
		$config = [
			'level' => LOG_DEBUG,
			'scope' => 'email',
		];
		if ($log !== true) {
			if (!is_array($log)) {
				$log = ['level' => $log];
			}
			$config = array_merge($config, $log);
		}

		Log::write(
			$config['level'],
			PHP_EOL . $contents['headers'] . PHP_EOL . $contents['message'],
			$config['scope']
		);
	}

}

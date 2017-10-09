<?php
declare(strict_types=1);

namespace Headsnet\DeployNotifyBundle\DependencyInjection;

use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Finder\Finder;

/**
 * Class DeployNotifySender
 */
class DeployNotifySender
{
	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @var \Swift_Mailer
	 */
	private $mailer;

	/**
	 * @var \Twig_Environment
	 */
	private $twig;

	/**
	 * @var ConvertMarkDown
	 */
	private $convertMarkDown;

	/**
	 * @var string
	 */
	private $projectDir;

	/**
	 * @var array
	 */
	private $config;

	/**
	 * @param LoggerInterface   $logger
	 * @param \Swift_Mailer     $mailer
	 * @param \Twig_Environment $twig
	 * @param ConvertMarkDown   $convertMarkDown
	 * @param string            $projectDir
	 * @param array             $config
	 */
	public function __construct(
		LoggerInterface   $logger,
		\Swift_Mailer     $mailer,
		\Twig_Environment $twig,
		ConvertMarkDown   $convertMarkDown,
		string            $projectDir,
		array             $config
	)
	{
		$this->logger = $logger;
		$this->mailer = $mailer;
		$this->twig = $twig;
		$this->convertMarkDown = $convertMarkDown;
		$this->projectDir = $projectDir;
		$this->config = $config;
	}

	/**
	 * Send deployment notifications to defined recipients
	 */
	public function send()
	{
		$changeLogContent = $this->convertChangeLogMarkDown($this->loadChangeLog());

		foreach ($this->config['recipients'] as $recipient)
		{
			$this->logger->info("Sending deployment notification to: " . $recipient['name']);

			$this->sendEmail($recipient, $changeLogContent);
		}
	}

	/**
	 * @return string
	 * @throws FileNotFoundException
	 */
	private function loadChangeLog()
	{
		if (empty($this->config['changelog']['filename']))
		{
			return '';
		}

		$finder = new Finder();

		$finder
			->files()
			->in($this->projectDir . $this->config['changelog']['path'])
			->depth(0)
			->name($this->config['changelog']['filename']);

		foreach ($finder as $file)
		{
			// This returns only the most recent block from the changelog
			//return preg_split('@(?=\<h3)@', preg_replace("/\r|\n/", '', $file->getContents()))[1];

			// This returns the entire changelog
			return $file->getContents();
		}

		throw new FileNotFoundException('Cannot find changelog file!');
	}

	/**
	 *
	 */
	private function convertChangeLogMarkDown($changeLogContent)
	{
		if (substr($this->config['changelog']['filename'], -3) == '.md')
		{
			$changeLogContent = $this->convertMarkDown->convertToHtml($changeLogContent);
		}

		return $changeLogContent;
	}

	/**
	 * @param array  $recipient
	 * @param string $changeLogContent
	 */
	private function sendEmail(array $recipient, string $changeLogContent)
	{
		$data = [
			'recipient' => $recipient,
			'config'    => $this->config,
			'changelog' => $changeLogContent
		];

		$subject = $this->config['email']['subject'] . ' - ' . $this->config['app_name'];

		$message = (new \Swift_Message($subject))
			->setFrom($this->config['email']['sender_email'], $this->config['email']['sender_name'])
			->setTo($recipient['email'], $recipient['name'])
			->setBody(
				$this->twig->render(
					'HeadsnetDeployNotifyBundle:Mail:notification.html.twig',
					$data
				),
				'text/html'
			)
			->addPart(
				$this->twig->render(
					'HeadsnetDeployNotifyBundle:Mail:notification.text.twig',
					$data
				),
				'text/plain'
			);

		$this->mailer->send($message);
	}

}

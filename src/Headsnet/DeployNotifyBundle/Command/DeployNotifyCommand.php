<?php
declare(strict_types=1);

namespace Headsnet\DeployNotifyBundle\Command;

use Headsnet\DeployNotifyBundle\DependencyInjection\DeployNotifySender;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command
 */
class DeployNotifyCommand extends Command
{
	/**
	 * @var DeployNotifySender
	 */
	private $sender;

	/**
	 * @param DeployNotifySender $sender
	 */
	public function __construct(DeployNotifySender $sender)
	{
		parent::__construct();

		$this->sender = $sender;
	}

	/**
	 * Configure the Symfony command
	 */
	protected function configure()
	{
		$this->setName('headsnet:deploy:notify')->setDescription('Send deployment notification emails');
	}

	/**
	 * Execute the Symfony command
	 *
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 *
	 * @return void
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->sender->send();
	}

}

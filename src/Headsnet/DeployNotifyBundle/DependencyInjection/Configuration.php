<?php
declare(strict_types=1);

namespace Headsnet\DeployNotifyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 */
class Configuration implements ConfigurationInterface
{

	/**
	 * @return TreeBuilder
	 */
	public function getConfigTreeBuilder()
	{
		$treeBuilder = new TreeBuilder();
		$rootNode = $treeBuilder->root('headsnet_deploy_notify');

		$rootNode
			->fixXmlConfig('recipient')
			->children()

				->scalarNode('app_name')
					->isRequired()
					->cannotBeEmpty()
					->info('The name of the application, used to identify which application has been deployed')
				->end()

				->scalarNode('mailer')
					->defaultValue('swiftmailer.mailer')
				->end()

				->arrayNode('email')
					->children()
						->scalarNode('sender_email')
							->isRequired()
							->cannotBeEmpty()
							->info('The sender email address for the email notification')
						->end()
						->scalarNode('sender_name')
							->defaultValue('')
							->info('The sender name for the email notification')
						->end()
						->scalarNode('subject')
							->defaultValue('Deployment Notification')
							->info('The subject line of the email notification')
						->end()
					->end()
				->end()

				->arrayNode('changelog')
					->children()
						->scalarNode('filename')
							->info('The file name of the change log file')
						->end()
						->scalarNode('path')
							->defaultValue('/')
							->info('The path to the change log file')
						->end()
						->scalarNode('public_url')
							->defaultValue('')
							->info('The public URL of the changelog. If defined used as a link in the email.')
						->end()
					->end()
				->end()

				->arrayNode('recipients')
					->performNoDeepMerging()
					->prototype('array')
						->children()
							->scalarNode('name')->end()
							->scalarNode('email')->end()
						->end()
					->end()
				->end()

			->end();


		return $treeBuilder;
	}

}

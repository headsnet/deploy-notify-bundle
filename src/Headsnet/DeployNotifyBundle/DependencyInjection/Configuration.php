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
				->end()

				->arrayNode('email')
					->children()
						->scalarNode('sender_email')
							->isRequired()
							->cannotBeEmpty()
						->end()
						->scalarNode('sender_name')
							->defaultValue('')
						->end()
						->scalarNode('subject')
							->defaultValue('Deployment Notification')
						->end()
					->end()
				->end()

				->arrayNode('changelog')
					->children()
						->scalarNode('filename')->end()
						->scalarNode('path')
							->defaultValue('/')
						->end()
					->end()
				->end()

				->arrayNode('recipients')
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

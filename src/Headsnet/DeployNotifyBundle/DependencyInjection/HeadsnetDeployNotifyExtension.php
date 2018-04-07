<?php

namespace Headsnet\DeployNotifyBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class HeadsnetDeployNotifyExtension
 */
class HeadsnetDeployNotifyExtension extends Extension
{
	/**
	 * {@inheritDoc}
	 */
	public function load(array $configs, ContainerBuilder $container)
	{
		$loader = new YamlFileLoader(
			$container,
			new FileLocator(__DIR__.'/../Resources/config')
		);
		$loader->load('services.yml');

		$configuration = new Configuration();
		$config = $this->processConfiguration($configuration, $configs);

		$def = $container->getDefinition('headsnet.deploy.notify.sender');
		$def->replaceArgument(5, $config);
	}

}

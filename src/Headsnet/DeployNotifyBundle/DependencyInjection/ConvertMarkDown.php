<?php
declare(strict_types=1);

namespace Headsnet\DeployNotifyBundle\DependencyInjection;

use Michelf\Markdown;

/**
 * Class ConvertMarkDown
 */
class ConvertMarkDown
{

	/**
	 * @param string $textContent
	 *
	 * @return string
	 */
	public function convertToHtml($textContent)
	{
		return Markdown::defaultTransform($textContent);
	}

}

<?php
/**
 * User: Goldenzero
 * Date: 14/02/15
 * Time: 6:06 PM
 */

namespace Goldenzero\Generator\Generators\Common;


use Config;
use Goldenzero\Generator\CommandData;
use Goldenzero\Generator\Generators\GeneratorProvider;

class RoutesGenerator implements GeneratorProvider
{
	/** @var  CommandData */
	private $commandData;

	private $path;

	function __construct($commandData)
	{
		$this->commandData = $commandData;
		$this->path = Config::get('path_routes', app_path('HTTP/routes.php'));
	}

	public function generate()
	{
		$routeContents = $this->commandData->fileHelper->getFileContents($this->path);

		$routeContents .= "\n\nRoute::resource('" . $this->commandData->modelNamePluralCamel . "', '" . $this->commandData->modelName . "Controller');";

		$this->commandData->fileHelper->writeFile($this->path, $routeContents);
		$this->commandData->commandObj->comment("\nroutes.php modified:");
		$this->commandData->commandObj->info("\"" . $this->commandData->modelNamePluralCamel . "\" route added.");
	}
}
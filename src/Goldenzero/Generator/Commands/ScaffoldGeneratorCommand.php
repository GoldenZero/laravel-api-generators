<?php

namespace Goldenzero\Generator\Commands;

use Illuminate\Console\Command;
use Goldenzero\Generator\CommandData;
use Goldenzero\Generator\Generators\Common\MigrationGenerator;
use Goldenzero\Generator\Generators\Common\ModelGenerator;
use Goldenzero\Generator\Generators\Common\RepositoryGenerator;
use Goldenzero\Generator\Generators\Common\RequestGenerator;
use Goldenzero\Generator\Generators\Common\RoutesGenerator;
use Goldenzero\Generator\Generators\Scaffold\RepoViewControllerGenerator;
use Goldenzero\Generator\Generators\Scaffold\ViewControllerGenerator;
use Goldenzero\Generator\Generators\Scaffold\ViewGenerator;
use Symfony\Component\Console\Input\InputArgument;

class ScaffoldGeneratorCommand extends Command
{

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'goldenzero.generator:scaffold';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a full CRUD for given model with initial views';


	public $commandData;

	/**
	 * Create a new command instance.
	 *
	 */
	public function __construct()
	{
		parent::__construct();

		$this->commandData = new CommandData($this);
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$this->commandData->modelName = $this->argument('model');
		$this->commandData->initVariables();
		$this->commandData->inputFields = $this->getInputFields();

		$followRepoPattern = $this->confirm("\nDo you want to generate repository ? (y|N)", false);

		$migrationGenerator = new MigrationGenerator($this->commandData);
		$migrationGenerator->generate();

		$modelGenerator = new ModelGenerator($this->commandData);
		$modelGenerator->generate();

		$requestGenerator = new RequestGenerator($this->commandData);
		$requestGenerator->generate();

		if($followRepoPattern)
		{
			$repositoryGenerator = new RepositoryGenerator($this->commandData);
			$repositoryGenerator->generate();

			$repoControllerGenerator = new RepoViewControllerGenerator($this->commandData);
			$repoControllerGenerator->generate();
		}
		else
		{
			$controllerGenerator = new ViewControllerGenerator($this->commandData);
			$controllerGenerator->generate();
		}

		$viewsGenerator = new ViewGenerator($this->commandData);
		$viewsGenerator->generate();

		$routeGenerator = new RoutesGenerator($this->commandData);
		$routeGenerator->generate();

		if($this->confirm("\nDo you want to migrate database? [y|N]", false))
			$this->call('migrate');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['model', InputArgument::REQUIRED, 'Singular Model name']
		];
	}

	private function getInputFields()
	{
		$fields = [];

		$this->info("Specify fields for the model (skip id & timestamp fields, will be added automatically)");
		$this->info("Left blank to finish");

		while(true)
		{
			$fieldInputStr = $this->ask("Field:");

			if(empty($fieldInputStr))
				break;

			$fieldInputs = explode(":", $fieldInputStr);

			if(sizeof($fieldInputs) < 2)
			{
				$this->error("Invalid Input. Try again");
				continue;
			}

			$fieldName = $fieldInputs[0];

			$fieldTypeOptions = explode(",", $fieldInputs[1]);
			$fieldType = $fieldTypeOptions[0];
			$fieldTypeParams = [];
			if(sizeof($fieldTypeOptions) > 1)
			{
				for($i = 1; $i < sizeof($fieldTypeOptions); $i++)
					$fieldTypeParams[] = $fieldTypeOptions[$i];
			}

			$fieldOptions = [];
			if(sizeof($fieldInputs) > 2)
				$fieldOptions[] = $fieldInputs[2];

			$validations = $this->ask("Enter validations: ");

			$field = [
				'fieldName'       => $fieldName,
				'fieldType'       => $fieldType,
				'fieldTypeParams' => $fieldTypeParams,
				'fieldOptions'    => $fieldOptions,
				'validations'     => $validations
			];

			$fields[] = $field;
		}

		return $fields;
	}
}

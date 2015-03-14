<?php

namespace Goldenzero\Generator;

use Illuminate\Support\ServiceProvider;
use Goldenzero\Generator\Commands\APIGeneratorCommand;
use Goldenzero\Generator\Commands\ScaffoldGeneratorCommand;

class GeneratorServiceProvider extends ServiceProvider
{

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$configPath = __DIR__ . '/../../../config/generator.php';
		$this->publishes([$configPath => config_path('generator.php')], 'config');
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('goldenzero.generator.api', function ($app)
		{
			return new APIGeneratorCommand();
		});

		$this->app->singleton('goldenzero.generator.scaffold', function ($app)
		{
			return new ScaffoldGeneratorCommand();
		});

		$this->app->singleton(
			'Illuminate\Contracts\Debug\ExceptionHandler',
			'Goldenzero\Generator\Exceptions\APIExceptionsHandler'
		);

		$this->commands(['goldenzero.generator.api', 'goldenzero.generator.scaffold']);
	}

}

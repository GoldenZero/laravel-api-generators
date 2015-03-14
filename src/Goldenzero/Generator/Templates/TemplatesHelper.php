<?php
/**
 * User: Goldenzero
 * Date: 14/02/15
 * Time: 4:54 PM
 */

namespace Goldenzero\Generator\Templates;


class TemplatesHelper
{
	public function getTemplate($template, $type = "Common")
	{
		$path = base_path('vendor/goldenzerogolakiya/laravel-api-generator/src/Goldenzero/Generator/Templates/' . $type . '/' . $template . '.txt');

		$fileData = file_get_contents($path);

		return $fileData;
	}
}
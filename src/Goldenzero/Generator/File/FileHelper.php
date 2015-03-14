<?php
/**
 * User: Goldenzero
 * Date: 14/02/15
 * Time: 4:56 PM
 */

namespace Goldenzero\Generator\File;


class FileHelper
{
	public function writeFile($file, $contents)
	{
		file_put_contents($file, $contents);
	}

	public function getFileContents($file)
	{
		return file_get_contents($file);
	}
}
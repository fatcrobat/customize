<?php

class CustomizeRunonce extends Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function run()
	{
		$this->import('Files');
		$this->loadAlljQueryVersion();
		$this->loadAlljQueryUIVersion();
	}
	
	/**
	 * download all jQuery Versions as File
	 */
	public function loadAlljQueryVersion()
	{
		$files = $this->Files;
		
		if(!is_dir(CustomizeHelper::$jqueryFolder))
		{
			$files->mkdir(CustomizeHelper::$jqueryFolder);
		}
		
		foreach ($GLOBALS['JQUERY_VERSIONS'] as $k=>$v)
		{
			foreach (array_values($v) as $version)
			{
				$target = CustomizeHelper::$jqueryFolder . 'jquery-' .  $version . '.min.js';
				$files->fopen($target, 'w');
				$source = file_get_contents('http://ajax.googleapis.com/ajax/libs/jquery/'.$version.'/jquery.min.js'); 
				file_put_contents($target, $source);
				$files->fclose($target);
			}
		}
	}
	
	/**
	 * download all jQuery UI Versions as File
	 */
	public function loadAlljQueryUIVersion()
	{
		$files = $this->Files;
		
		if(!is_dir(CustomizeHelper::$jqueryUIFolder))
		{
			$files->mkdir(CustomizeHelper::$jqueryUIFolder);
		}
		
		foreach ($GLOBALS['JQUERYUI_VERSIONS'] as $k=>$v)
		{
			foreach (array_values($v) as $version)
			{
				$target = CustomizeHelper::$jqueryUIFolder . 'jquery-ui-' .  $version . '.min.js';
				$files->fopen($target, 'w');
				$source = file_get_contents('http://ajax.googleapis.com/ajax/libs/jqueryui/'.$version.'/jquery-ui.min.js');
				file_put_contents($target, $source);
				$files->fclose($target);
			}
		}
	}
}

/**
 * Instantiate controller
 */
$objCustomizeRunonce = new CustomizeRunonce();
$objCustomizeRunonce->run();

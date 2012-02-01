<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

class Customize extends PageRegular
{
	
	protected $footerJs;
	
	protected $objLayout;
	
	protected $head;
	
	public function extendPage(Database_Result $objPage, Database_Result $objLayout, PageRegular $objPageRegular)
	{
		$this->objLayout = $objLayout;
		
		$mootools = deserialize($this->objLayout->mootools);
		
		$head = deserialize($this->objLayout->head);
		
		$this->footerJs = is_array($mootools) ? $mootools : array();
		
		$this->addjQueryToLayout();
		
		$this->addjQueryUIToLayout();
		
		$this->addNoConflictMode();
		
		$this->addCustomJSToLayout();
		
		$objLayout->mootools = serialize($this->footerJs);
		
		return $objPage;
	}
	
	protected function addjQueryToLayout()
	{
		if($this->objLayout->addjQuery)
		{
			array_push($this->footerJs, sprintf('jquery_%s', $this->objLayout->jQuerySource));
			return true;
		}
		return false;
	}
	
	protected function addCustomJSToLayout()
	{
		$jqueryScripts = deserialize($this->objLayout->jQueryScripts);
		
		if(is_array($jqueryScripts))
		{
			array_push($this->footerJs, 'custom_scripts');
			return true;
		}
		return false;
	}
	
	protected function addjQueryUIToLayout()
	{
		if($this->objLayout->addjQueryUI)
		{
			$this->addjQueryUITheme();
			array_push($this->footerJs, sprintf('jquery.ui_%s', $this->objLayout->jQueryUISource));
			// add jquery ui defaults like datepicker language from local...
			array_push($this->footerJs, 'jquery.ui-defaults');
			return true;
		}
		return false;
	}
	
	protected function addjQueryUITheme()
	{
		$objTemplate = new FrontendTemplate(sprintf('jquery.ui.theme_%s', $this->objLayout->jQueryUISource));
		$objTemplate->version = $this->objLayout->jQueryUIVersion;
		$objTemplate->theme = $this->objLayout->jQueryUITheme;
		$GLOBALS['TL_HEAD'][] = $objTemplate->parse();
	}
	
	protected function addNoConflictMode()
	{
		array_push($this->footerJs, 'jquery_noconflict');
	}
}
<?php namespace Clumsy\AgeCheck;

use Illuminate\Support\Facades\Config;

use \Carbon\Carbon;

class AgeCheck {

	protected $theme;
	protected $possibleThemes;

	protected $themesPath;


	public function __construct()
	{
		$this->theme = Config::get('age-check::theme');
		$this->themesPath = realpath(dirname(__FILE__)).'/Ages/';
		$this->possibleThemes = $this->getPossibleThemes();
	}

	public function checkByDate($date,$country)
	{
		if (!($date instanceof Carbon)) {
			if (!is_string($date)) {
				return null;
			}

			$date = Carbon::parse($date);
		}

		$now = Carbon::now();
		$age = $now->diffInYears($date);

		return $this->checkByAge($age,$country);
	}

	public function checkByAge($age,$country)
	{
		$maxAge = $this->getAgeByCountry($country);

		return $maxAge != null ? ($age >= $maxAge) : null;
	}

	public function getAgeByCountry($country)
	{
		$ages = array();
		$fileDir = $this->themesPath;
		$buffer = explode('.',$this->theme);
		$level = '';
		foreach ($buffer as $item) { 
			$level = $level == '' ? $item : $level.'.'.$item;
			if (array_get($this->possibleThemes, $level) != null) {
				$fileDir = $fileDir.$item.'/';
			}
			$filePath = $fileDir.$item.'.php';	

			$ages = array_merge($ages,include($filePath));
		}

		return isset($ages[$country]) ? $ages[$country] : null;
	}

	public function setTheme($theme)
	{
		$value = array_get($this->possibleThemes, $theme);
		if ($value == null) {
			$buffer = explode('.',$theme);
			$index = count($buffer) - 1;
			$fileName = substr($theme,strpos($theme,$buffer[$index]));
			$newTheme = substr($theme,0,strpos($theme,$buffer[$index])-1);

			$newValue = array_get($this->possibleThemes, $newTheme);
			if ($newValue == null) {
				return false;
			}
			if (!in_array($fileName,$newValue)) {
				return false;
			}
		}

		$this->theme = $theme;

		return true;
	}

	private function getPossibleThemes()
	{
		$fileData = $this->fillArrayWithFileNodes( new \DirectoryIterator($this->themesPath) );

		return $fileData;
	}

	private function fillArrayWithFileNodes(\DirectoryIterator $dir, $dirname = null)
	{
		$data = array();
		foreach ($dir as $node){
		    if ($node->isDir() && !$node->isDot()){
				$data[$node->getFilename()] = 
					$this->fillArrayWithFileNodes(new \DirectoryIterator($node->getPathname()),$node->getFilename());
		    }
			else if ($node->isFile() &&  !$this->startsWith($node->getFilename(),'.') 
				&& substr($node->getFilename(),0,-4) != $dirname){
				$data[] = substr($node->getFilename(),0,-4);
			}
		}
		return $data;
	}

	private function startsWith($haystack, $needle) {
		// search backwards starting from haystack length characters from the end
		return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
	}
}
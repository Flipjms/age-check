<?php namespace Clumsy\AgeCheck;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

use \Carbon\Carbon;

class AgeCheck {

	protected $theme;
	protected $possibleThemes;
	protected $session;

	protected $themesPath;


	public function __construct()
	{
		$this->theme = Config::get('age-check::theme');
		$this->session = Config::get('age-check::save_session');
		$this->themesPath = realpath(dirname(__FILE__)).'/Ages/';
		$this->possibleThemes = $this->getPossibleThemes();
	}

	public function check()
	{
		return Session::get('clumsy.age_check',false);
	}

	/**
	 * 
	 * Validate an age given the birthdate, country
	 * 
	 * @param  str/Carbon 	$date    	Birthdate to validate
	 * @param  str 			$country 	The country's name
	 * @return bool          			returns null on error
	 */
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

		$result = $this->checkByAge($age,$country);
		if ($this->session) {
			Session::put('clumsy.age_check',$result);
		}

		return $this->checkByAge($age,$country);
	}

	/**
	 * 
	 * Validate an age given a Country
	 * 
	 * @param  int 		$age     	The age, in years, to validate
	 * @param  str 		$country 	The country's name
	 * @return bool          		returns null on error
	 */
	public function checkByAge($age,$country)
	{
		$maxAge = $this->getAgeByCountry($country);

		if ($maxAge == -1) {
			return false;
		}

		return $maxAge != null ? ($age >= $maxAge) : null;
	}

	/**
	 * 
	 * Gets an age given a Country
	 * 
	 * @param  str 		$country 	The country's name
	 * @return int          		The age or null if the country is not valid
	 */
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

	/**
	 * 
	 * Gets all the countries for the current theme
	 * 
	 * @return Array countries's name
	 */
	public function getCountriesByTheme()
	{
		$buffer = explode('.', $this->theme);

		$info = include($this->themesPath.$buffer[0].'/'.$buffer[0].'.php');

		$countries = array();
		foreach ($info as $country => $age) {
			$countries[] = $country;
		}
		
		return $countries;
	}

	/**
	 * 
	 * set's the theme on which the other functions will run against
	 * 
	 * @param str 	$theme 		laravel type path for the theme (eg. 'majority.alcohol.spirits')
	 */
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

	/**
	 * 
	 * Check the Ages folder for all the possible themes
	 * 
	 * @return array 	returns all the valid possibilites found
	 */
	private function getPossibleThemes()
	{
		$fileData = $this->fillArrayWithFileNodes( new \DirectoryIterator($this->themesPath) );

		return $fileData;
	}

	/*
	|--------------------------------------------------------------------------
	| Auxiliary Functions
	|--------------------------------------------------------------------------
	*/

	/**
	 * 
	 * Inspects the given folder recursively for valid files/directories
	 * 
	 * @param  \DirectoryIterator 	$dir     	Directory object to analyze
	 * @param  str             		$dirname 	Directory name for the current node (used for recursive calls)
	 * @return array                     		Array with the directory structure
	 */
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

	/**
	 * 
	 * Checks if a string starts with...
	 * 
	 * @param  str 		$haystack 	the string to search for
	 * @param  str 		$needle   	the entry to search
	 * @return bool           	
	 */
	private function startsWith($haystack, $needle) {
		// search backwards starting from haystack length characters from the end
		return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
	}
}
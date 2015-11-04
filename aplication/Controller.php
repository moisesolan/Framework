<?php

abstract class Appcontroller
{
	protected $_view;
	protected $db;

	abstract public function index();

	public function __construct(){
		$this->_view = new View(new Request);
		$this->db = new classPDO();
	}


	protected function set($name = null, $value=array()){
		$GLOBALS[$name] = $value;
	}




	protected function redirect($url = array()){
		$path = "";

		if ($url['controller']) {
			$path .= $url['controller'];
		}
		if ($url['action']) {
			$path .= "/".$url['action'];
		}
		header("location: ".APP_URL.$path);

	}

}

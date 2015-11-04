<?php

/**
 * La clase Request
 * 
 * La siguiente clase para validar la  url, definir controlador y los metodos a ejecutar
 * 
 * @author Moises Olan Gonzalez <itic722014@gmail.com>
 * @version 1.0 Mi primera versión
 * @package Mi Framework
 * 
 */

class Request{
	private $_controlador;
	private $_metodo;
	private $_argumentos;

	public function __construct(){
		if (isset($_GET['url'])) {
			$url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
			$url = explode('/', $url);
			$url = array_filter($url);

			$this->_controlador = strtolower(array_shift($url));
			$this->_metodo = strtolower(array_shift($url));
			$this->_argumentos = $url;
		}

		if (!$this->_controlador) {
			$this->_controlador = DEFAULT_CONTROLLER;
		}

		if (!$this->_metodo) {
			$this->_metodo = 'index';
		}

		if (!$this->_argumentos) {
			$this->_argumentos = array();
		}

	}

/**
	 * getControlador se define controlador
	 * @return objeto _controlador tiene el controlador a solicitar
	 * 
*/

	public function getControlador(){
		return $this->_controlador;
	}

	/**
	 * 
	 * getMetodo se define el metodo
	 * @return objeto _metodo contiene el metodo a solicitar
	 * 
	 */

	public function getMetodo(){
		return $this->_metodo;
	}

	/**
	 * getArgs se define los argumentos
	 * @return  _argumentos contiene los argumentos a solicitar
	 */

	public function getArgs(){
		return $this->_argumentos;
	}


}

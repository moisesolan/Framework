<?php
	function __autoload($name){
		require_once(ROOT."libs".DS.$name.".php");
	}


?>
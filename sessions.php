<?php
	// Start session
	session_start();

	// Include config.php
    require_once("config.php");

	function startSession($pass)
	{
		$pass = htmlentities($pass);
		if((isset($GLOBALS['settings']['security']) && $GLOBALS['settings']['security'] == "yes"))
		{
			if(password_verify($pass, $GLOBALS['settings']['password'])) $_SESSION['logged'] = 1;
			else $_SESSION['logged'] = 0;
		} else {
			$_SESSION['logged'] = 1;
		}
	}

	function endSession()
	{
		if(!session_id()) session_start();
		session_destroy();
	}

	function secured(){
		return (isset($GLOBALS['settings']['security']) && $GLOBALS['settings']['security'] === "yes");
	}

	function loggedIn(){
		return (isset($_SESSION['logged']) && $_SESSION['logged'] == 1);
	}

	function authorized(){
		return (!secured() || loggedIn());
	}
?>
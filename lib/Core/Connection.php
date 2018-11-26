<?php

require_once 'Define.php';
require_once __DIR__ . "/../NotORM.php";
	
	function DBConnection(){			
		$str_conn = "firebird:dbname=".LocDB.";host=" .hostDB;
		$link = NEW PDO($str_conn,  userDB, passwordDB);
		
		
		$structure = new NotORM_Structure_Convention(
			$primary = "ID", // id_$table
			$foreign = "%s_ID", // id_$table
			$table = "%s", // {$table}s
			$prefix = "" // wp_$table
		);
		
		$db = new NotORM($link, $structure);	
		return $db;
	};

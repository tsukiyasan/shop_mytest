<?php



defined( '_VALID_WAY' ) or die( 'Do not Access the Location Directly!' );






class database {
	

	var $_sql='';
	

	var $_errorNum=0;
	

	var $_errorMsg='';
	

	var $_table_prefix='';
	

	var $_resource='';
	

	var $_cursor=null;
	

	var $_debug=0;
	

	var $_ticker=0;
	

	var $_log=null;
	
	var $pdo_query=null;

	

	function database( $host='localhost', $user, $pass, $db, $table_prefix ,$newlink=null) {
		
		global $globalConf_dbtype,$Conf_pdo;			
		if($Conf_pdo == 'pdo')
		{
			
			if($globalConf_dbtype == 'mysql')
			{
				try 
				{				
					$options = array(
						PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
					); 
					
					$this->_resource = new PDO("mysql:host=$host;dbname=$db", $user, $pass,$options); 
				}
				catch (PDOException $e) 
				{
					$iceSystemError = 2;
					$basePath = dirname( __FILE__ );
					include $basePath . '/../configuration.php';
					include $basePath . '/../offline.php';
					exit();
				}
			}
			else if($globalConf_dbtype == 'oracle')
			{				
				$tns ="(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=1521))(CONNECT_DATA=(SERVICE_NAME=$db)))";
				try{
					$this->_resource = new PDO('oci:dbname=' . $tns . ';charset=UTF8', $user,$pass);
				}catch(PDOException $e){
					echo ($e->getMessage());
				}
			}
		}
		else
		{
			
			if (!function_exists( 'mysql_connect' )) {
				
				$iceSystemError = 1;
				$basePath = dirname( __FILE__ );
				include $basePath . '/../configuration.php';
				include $basePath . '/../offline.php';
				exit();
			}
			
			if($newlink==null)
			{
				if (!($this->_resource = @mysql_connect( $host, $user, $pass ))) {
					
					$iceSystemError = 2;
					$basePath = dirname( __FILE__ );
					include $basePath . '/../configuration.php';
					include $basePath . '/../offline.php';
					exit();
				}
			}
			else
			{
				if (!($this->_resource = @mysql_connect( $host, $user, $pass ,$newlink))) {
					
					$iceSystemError = 2;
					$basePath = dirname( __FILE__ );
					include $basePath . '/../configuration.php';
					include $basePath . '/../offline.php';
					exit();
				}
			}
			mysql_query("SET NAMES 'UTF8'");

			if (!mysql_select_db($db) && $newlink != 'pdo') {
				
				
				die();
				
				
				$iceSystemError = 3;
				$basePath = dirname( __FILE__ );
				include $basePath . '/../configuration.php';
				include $basePath . '/../offline.php';
				exit();
			}
		
		}
		
		$this->_table_prefix = $table_prefix;
		$this->_ticker = 0;
		$this->_log = array();

	}
	
	

	function debug( $level ) {
	    $this->_debug = intval( $level );
	}
	

	function getErrorNum() {
		return $this->_errorNum;
	}
	

	function getErrorMsg() {
		return str_replace( array( "\n", "'" ), array( '\n', "\'" ), $this->_errorMsg );
	}
	

	function getEscaped( $text ) {
		
		global $Conf_pdo;
		
		if($Conf_pdo == 'pdo')
		{
			return $text;
		}
		else
		{
			return mysql_escape_string( $text );
		}
	}
	

	function setQuery( $sql, $prefix='#__' ) {
	    $sql = trim( $sql );

		$inQuote = false;
		$escaped = false;
		$quoteChar = '';

		$n = strlen( $sql );
		$np = strlen( $prefix );
		$literal = '';

		for ($j=0; $j < $n; $j++ ) {
			$c = $sql{$j};
			$test = substr( $sql, $j, $np );

			
			if (!$inQuote) {
				if ($c == '"' || $c == "'") {
					$inQuote = true;
					$escaped = false;
					$quoteChar = $c;
				}
			} else {
				
				if ($c == $quoteChar && !$escaped) {
					$inQuote = false;
				} else if ($c == "\\" && !$escaped) {
					$escaped = true;
				} else {
					$escaped = false;
				}
			}
			if ($test == $prefix && !$inQuote) {
			    $literal .= $this->_table_prefix;
			    $j += $np-1;
			} else {
				$literal .= $c;
			}
		}
		$this->_sql = $literal;
	}
	

	function getQuery() {
		return "<pre>" . htmlspecialchars( $this->_sql ) . "</pre>";
	}
	

	function query( $parameters_arr = array() ) {
		
		global $Conf_pdo;
		
		if($Conf_pdo == 'pdo')
		{
			$this->_errorNum = 0;
			$this->_errorMsg = '';
			
			
			if(!empty($parameters_arr) && count($parameters_arr))
			{
				$this->_cursor = $this->_resource->prepare($this->_sql); 
		
				foreach( $parameters_arr as $key => $value )
				{
					$this->_cursor->bindParam($key, $parameters_arr[$key]); 
				}
					
				$this->_cursor->execute(); 
			}
			else
			{
				$this->_cursor = $this->_resource->query($this->_sql);
			}
			
			if ($this->_resource->errorCode() != 00000){
				$this->_cursor = false;
				$this->_errorNum = $this->_resource->errorCode();
				$this->_errorMsg = $this->_resource->errorInfo();
				
				return false;
			}
			
			return $this->_cursor;
		}
		else
		{
		
			
			
			 
			global $Config_debug;
			if ($this->_debug) {
				$this->_ticker++;
				$this->_log[] = $this->_sql;
			}
			$this->_errorNum = 0;
			$this->_errorMsg = '';
		   
			$this->_cursor = mysql_query( $this->_sql, $this->_resource );
			if (!$this->_cursor) {
				$this->_errorNum = mysql_errno( $this->_resource );
				$this->_errorMsg = mysql_error( $this->_resource )." SQL=$this->_sql";
				if (trigger_error( mysql_error( $this->_resource ), E_USER_NOTICE ) && $Config_debug) {
					echo "<pre>" . $this->_sql . "</pre>\n";
				}
				if ($this->_debug && function_exists( 'debug_backtrace' ) ) {
					foreach( debug_backtrace() as $back) {
						if (@$back['file']) {
							echo '<br />'.$back['file'].':'.$back['line'];
						}
					}
				}
				return false;
			}
			return $this->_cursor;
		}
	}

	function query_batch( $abort_on_error=true, $p_transaction_safe = false) {
		
		global $Conf_pdo;
		
		if($Conf_pdo == 'pdo')
		{
			$this->_errorNum = 0;
			$this->_errorMsg = '';
			
			$query_split = preg_split ("/[;]+/", $this->_sql);
			$error = 0;
			
			if($p_transaction_safe)
				$this->_resource->commit();
			
			foreach ($query_split as $command_line) {
				$command_line = trim( $command_line );
				if ($command_line != '') {
										
					$this->_cursor = $this->_resource->query($command_line);
					
					if ($this->_resource->errorCode() != 00000){
						$this->_cursor = false;
					}
					
					if (!$this->_cursor) {
						$this->_errorNum .= $this->_resource->errorCode() . ' ';
						$this->_errorMsg .= $this->_resource->errorInfo()." SQL=$command_line <br />";
						
						if($p_transaction_safe)
							$this->_resource->rollback();
						
						if ($abort_on_error) {
							return $this->_cursor;;
						}
					}
				}
			}
			
			if($p_transaction_safe)
				$this->_resource->commit();
			
			return $error ? false : true;
		}
		else
		{
		
		
			$this->_errorNum = 0;
			$this->_errorMsg = '';
			if ($p_transaction_safe) {
				$si = mysql_get_server_info();
				preg_match_all( "/(\d+)\.(\d+)\.(\d+)/i", $si, $m );
				if ($m[1] >= 4) {
					$this->_sql = 'START TRANSACTION;' . $this->_sql . '; COMMIT;';
				} else if ($m[2] >= 23 && $m[3] >= 19) {
					$this->_sql = 'BEGIN WORK;' . $this->_sql . '; COMMIT;';
				} else if ($m[2] >= 23 && $m[3] >= 17) {
					$this->_sql = 'BEGIN;' . $this->_sql . '; COMMIT;';
				}
			}
			$query_split = preg_split ("/[;]+/", $this->_sql);
			$error = 0;
			foreach ($query_split as $command_line) {
				$command_line = trim( $command_line );
				if ($command_line != '') {
					$this->_cursor = mysql_query( $command_line, $this->_resource );
					if (!$this->_cursor) {
						$this->_errorNum .= mysql_errno( $this->_resource ) . ' ';
						$this->_errorMsg .= mysql_error( $this->_resource )." SQL=$command_line <br />";
						if ($abort_on_error) {
							return $this->_cursor;;
						}
					}
				}
			}
			return $error ? false : true;
		
		}
	}

	

	function explain($parameters_arr = array()) {
		
		global $Conf_pdo;
		
		if($Conf_pdo == 'pdo')
		{
			$temp = $this->_sql;
			$this->_sql = "EXPLAIN $this->_sql";
			
			if(!($cur = $this->query($parameters_arr))){
				return null;
			}
			$first = true;
			
			$buf = "<table cellspacing=\"1\" cellpadding=\"2\" border=\"0\" bgcolor=\"#000000\" align=\"center\">";
			$buf .= $this->getQuery();
			
			$cur->setFetchMode(PDO::FETCH_ASSOC);  
			
			while ($row = $cur->fetch()) {
				if ($first) {
					$buf .= "<tr>";
					foreach ($row as $k=>$v) {
						$buf .= "<th bgcolor=\"#ffffff\">$k</th>";
					}
					$buf .= "</tr>";
					$first = false;
				}
				$buf .= "<tr>";
				foreach ($row as $k=>$v) {
					$buf .= "<td bgcolor=\"#ffffff\">$v</td>";
				}
				$buf .= "</tr>";
			}
			$buf .= "</table><br />&nbsp;";
			
			$this->_sql = $temp;
			
			return "<div style=\"background-color:#FFFFCC\" align=\"left\">$buf</div>";
		}
		else
		{
		
			$temp = $this->_sql;
			$this->_sql = "EXPLAIN $this->_sql";
			$this->query();

			if (!($cur = $this->query())) {
				return null;
			}
			$first = true;

			$buf = "<table cellspacing=\"1\" cellpadding=\"2\" border=\"0\" bgcolor=\"#000000\" align=\"center\">";
			$buf .= $this->getQuery();
			while ($row = mysql_fetch_assoc( $cur )) {
				if ($first) {
					$buf .= "<tr>";
					foreach ($row as $k=>$v) {
						$buf .= "<th bgcolor=\"#ffffff\">$k</th>";
					}
					$buf .= "</tr>";
					$first = false;
				}
				$buf .= "<tr>";
				foreach ($row as $k=>$v) {
					$buf .= "<td bgcolor=\"#ffffff\">$v</td>";
				}
				$buf .= "</tr>";
			}
			$buf .= "</table><br />&nbsp;";
			mysql_free_result( $cur );

			$this->_sql = $temp;

			return "<div style=\"background-color:#FFFFCC\" align=\"left\">$buf</div>";
		}
	}
	

	function getNumRows( $cur=null ) {
		
		global $Conf_pdo;
		
		if($Conf_pdo == 'pdo')
		{
			return $this->_cursor->rowCount();
		}
		else
		{
			return mysql_num_rows( $cur ? $cur : $this->_cursor );
		}
	}

	

	function loadResult($parameters_arr = array()) {
		
		global $Conf_pdo;
		
		if($Conf_pdo == 'pdo')
		{
			if (!($cur = $this->query($parameters_arr))) {
				return null;
			}
			
			$ret = null;
			
			$cur->setFetchMode(PDO::FETCH_BOTH);  
			if($row = $cur->fetch())
			{
				$ret = $row[0];
			}
			
			return $ret;
		
		}
		else
		{
			
			
			
			if (!($cur = $this->query())) {
				return null;
			}
			$ret = null;
			if ($row = mysql_fetch_row( $cur )) {
				$ret = $row[0];
			}
			mysql_free_result( $cur );
			return $ret;
		}
	}
	

	function loadResultArray($numinarray = 0,$parameters_arr = array()) {
		
		global $Conf_pdo;
		
		if($Conf_pdo == 'pdo')
		{
			if (!($cur = $this->query($parameters_arr))) {
				return null;
			}
			
			$array = array();
			
			$cur->setFetchMode(PDO::FETCH_BOTH);  
			while($row = $cur->fetch())
			{
				$array[] = $row[$numinarray];
			}
			
			return $array;
		}
		else
		{
		
			if (!($cur = $this->query())) {
				return null;
			}
			$array = array();
			while ($row = mysql_fetch_row( $cur )) {
				$array[] = $row[$numinarray];
			}
			mysql_free_result( $cur );
			return $array;
		}
	}
	

	function loadObject( &$object , $parameters_arr = array() ) {
		
		global $Conf_pdo;
		
		if($Conf_pdo == 'pdo')
		{
			if ($object != null) {
				
				if(!($cur = $this->query($parameters_arr))){
					return false;
				}
				
				$cur->setFetchMode(PDO::FETCH_ASSOC);  
				if($array = $cur->fetch())
				{
					rsBindArrayToObject( $array, $object );
					return true;
				}
				else
				{
					return false;
				}
			}
			else{
				
				if(!($cur = $this->query($parameters_arr))){
					return false;
				}
				
				$cur->setFetchMode(PDO::FETCH_OBJ);  
				
				if($object = $cur->fetch())
				{
					return true;
				}
				else
				{
					$object = null;
					return false;
				}
			}
			
		}
		else
		{
		
			if ($object != null) {
				if (!($cur = $this->query())) {
					return false;
				}
				if ($array = mysql_fetch_assoc( $cur )) {
					mysql_free_result( $cur );
					rsBindArrayToObject( $array, $object );
					return true;
				} else {
					return false;
				}
			} else {
				if ($cur = $this->query()) {
					if ($object = mysql_fetch_object( $cur )) {
						mysql_free_result( $cur );
						return true;
					} else {
						$object = null;
						return false;
					}
				} else {
					return false;
				}
			}
		}
	}
	

	function loadObjectList( $key='',$parameters_arr = array() ) {
		
		global $Conf_pdo;
		
		if($Conf_pdo == 'pdo')
		{
			if(!($cur = $this->query($parameters_arr))){
				return null;
			}
			$array = array();
			$cur->setFetchMode(PDO::FETCH_OBJ);  
			
			while($row = $cur->fetch()){
				if ($key) {
					$array[$row->$key] = $row;
				} else {
					$array[] = $row;
				}
			}
			return $array;
			
		}
		else
		{
			if (!($cur = $this->query())) {
				return null;
			}
			$array = array();
			while ($row = mysql_fetch_object( $cur )) {
				if ($key) {
					$array[$row->$key] = $row;
				} else {
					$array[] = $row;
				}
			}
			mysql_free_result( $cur );
			return $array;
		}
	}
	

	function loadRow($parameters_arr = array()) {
		
		
		global $Conf_pdo;
		
		if($Conf_pdo == 'pdo')
		{
			if (!($cur = $this->query($parameters_arr))) {
				return null;
			}
			
			$ret = null;
			
			$cur->setFetchMode(PDO::FETCH_BOTH);  
			if($row = $cur->fetch())
			{
				$ret = $row;
			}
			
			return $ret;
		
		}
		else
		{
			if (!($cur = $this->query())) {
				return null;
			}
			$ret = null;
			if ($row = mysql_fetch_assoc( $cur )) {
				$ret = $row;
			}
						
			mysql_free_result( $cur );
			return $ret;
		}
	}
	

	function loadRowList( $key='' , $parameters_arr = array() ) {
		
		global $Conf_pdo;
		
		if($Conf_pdo == 'pdo')
		{
			if(!($cur = $this->query($parameters_arr))){
				return null;
			}
			
			$array = array();
			$cur->setFetchMode(PDO::FETCH_ASSOC);  
			
			while (@$row = $cur->fetch()) {
				if ($key) {
					$array[$row[$key]] = $row;
				} else {
					$array[] = $row;
				}
			}
			return $array;
		}
		else
		{
			
			
			
			if (!($cur = $this->query())) {
				return null;
			}
			$array = array();
			while ($row = mysql_fetch_assoc( $cur )) {
				if ($key) {
					$array[$row[$key]] = $row;
				} else {
					$array[] = $row;
				}
			}
			mysql_free_result( $cur );
			return $array;
		
		}
	}
	
	

	function stderr( $showSQL = false ) {
		return "DB function failed with error number $this->_errorNum"
		."<br /><font color=\"red\">$this->_errorMsg</font>"
		.($showSQL ? "<br />SQL = <pre>$this->_sql</pre>" : '');
	}

	// function insertid()
	// {
	// 	global $Conf_pdo;
		
	// 	if($Conf_pdo == 'pdo')
	// 	{
	// 		return $this->_resource->lastInsertId();
	// 	}
	// 	else
	// 	{
	// 		return mysql_insert_id();
	// 	}
	// }

	
	function insertid()
	{
		global $Conf_pdo;
		
		if($Conf_pdo == 'pdo')
		{
			return $this->_resource->lastInsertId();
		}
		else
		{
			$id = getFieldValue("SELECT LAST_INSERT_ID()","LAST_INSERT_ID()");
			return $id;
			// return mysql_insert_id();
		}
	}

	function getVersion()
	{
		global $Conf_pdo;
		
		if($Conf_pdo == 'pdo')
		{
			return $this->_resource->getAttribute(constant("PDO::SERVER_VERSION"));
		}
		else
		{
			return mysql_get_server_info();
		}
	}

	

	function GenID( $foo1=null, $foo2=null ) {
		return '0';
	}
	

	function getTableList() {
		$this->setQuery( 'SHOW tables' );
		$this->query();
		return $this->loadResultArray();
	}
	

	function getTableCreate( $tables ) {
		$result = array();

		foreach ($tables as $tblval) {
			$this->setQuery( 'SHOW CREATE table ' . $tblval );
			$this->query();
			$result[$tblval] = $this->loadResultArray( 1 );
		}

		return $result;
	}
	

	function getTableFields( $tables ) {
		$result = array();

		foreach ($tables as $tblval) {
			$this->setQuery( 'SHOW FIELDS FROM ' . $tblval );
			$this->query();
			$fields = $this->loadObjectList();
			foreach ($fields as $field) {
				$result[$tblval][$field->Field] = preg_replace("/[(0-9)]/",'', $field->Type );
			}
		}

		return $result;
	}
}

?>

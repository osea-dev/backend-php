<?php
require_once('constant.php');
require_once('encrypt.php');
class database
{
	var $connection;
	function __construct() { $this->dbConnect(); }
	function dbConnect() 
	{
		//$this->connection = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
		$this->connection = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
		if (mysqli_connect_errno()) die("Database connection failed: ".mysqli_connect_error()); 
	}
	function query($sql)
	{
		$rs = $this->connection->query($sql) or $this->connection->error;
		return $rs;
	}
	
	function selectdb($db) {
		return $this->connection->select_db($db);
	}
	function multiQuery($sql) 
	{
		$rs = $this->connection->multi_query($sql) or $this->connection->error;
		return $rs;
	}
	
	function beginTrans()
	{
		$this->connection->begin_transaction();
	}
	
	function commitTrans()
	{
		$this->connection->commit();
	}
	
	function rollbackTrans()
	{
		$this->connection->rollback();
	}
	
	function ferror() { return $this->connection->error;}
	function fescape($val) { return $this->connection->real_escape_string($val);}
	function fobject($rs) { return $rs->fetch_object(); }
	function farray($rs) { return $rs->fetch_array(); }
	function fassoc($rs) { return $rs->fetch_assoc(); }
	function nrows($rs) { return $rs->num_rows; }
	function flush($rs) { $rs->close(); }
	function close() { $this->connection->close(); }
	
	function nextresult() { $this->connection->next_result(); }
	function prepare($query) { return $this->connection->prepare($query);}
}
$db = new database();

?>
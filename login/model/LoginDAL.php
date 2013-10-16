<?php

namespace model;

class LoginDAL
{
 	private static $tableName = "login";
	
	private $mysqli;
	
	public function __construct(\mysqli $mysqli) {

		$this->mysqli = $mysqli; 
	}
	
	public function createTable()
	{
		$sql ="CREATE TABLE IF NOT EXISTS ".self::$tableName."
		(
			pk INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
			user VARCHAR(25),
			password VARCHAR(20)		
		)
		ENGINE = MyISAM;";
		
		if ($this->mysqli->query($sql) === FALSE) {
          throw new \Exception("'$sql' failed " . $this->mysqli->error);
      }
	}
	
	public function addUser($user, $pass)
	{
		$sql = "INSERT INTO ". self::$tableName."
		(
			user,
			password
		)
		VALUES(?,?)";
		
		$stmt = $this->mysqli->prepare($sql);
		
		$stmt->bind_param("ss",$user,$pass);
		$stmt->execute();
    }
	
	public function getUsers()
	{
		
		$con = mysqli_connect("localhost", "root", "", "usersLabb3");
		// Check connection
		if (mysqli_connect_errno())
		  {
		  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }
		
		$result = mysqli_query($con,"SELECT user FROM login;");
		
		$array = array();
		
		while($row = mysqli_fetch_array($result))
		  {
			array_push($array,$row['user']);
		  }
		  
	  	return $array;
		mysqli_close($con);
	}
}

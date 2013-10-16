<?php

namespace login\model;


require_once("UserCredentials.php");
require_once("common/model/PHPFileStorage.php");

class UserList {
	private $adminFile;

	private $users;
	
	private $usersFile;

	public function  __construct( ) {
		$this->users = array();
		$this->loadAdmin();
		$this->loadMoreUsers();
	}

	public function loadMoreUsers()
	{
		$con = mysqli_connect("localhost", "root", "", "usersLabb3");
		// Check connection
		if (mysqli_connect_errno())
		  {
		  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }
		
		$result = mysqli_query($con,"SELECT * FROM login;");
		while($row = mysqli_fetch_array($result))
		  {
		  	$userName = new UserName($row['user']);
			$password = Password::fromCleartext($row['Password']);
			$user = UserCredentials::create( $userName, $password);
			$this->update($user);			
			
		  	$this->users[$user->getUserName()->__toString()] = $user;
		  }
		  
	}
	public function findUser(UserCredentials $fromClient) {
		foreach($this->users as $user) {
			if ($user->isSame($fromClient) ) {
				\Debug::log("found User");
				return  $user;
			}
		}
		throw new \Exception("could not login, no matching user");
	}

	public function saveUser(UserCredentials $user)
	{
		array_push($this->users, $user);
	}
	
	public function update(UserCredentials $changedUser) {
		$this->adminFile->writeItem($changedUser->getUserName(), $changedUser->toString());

		\Debug::log("wrote changed user to file", true, $changedUser);
		$this->users[$changedUser->getUserName()->__toString()] = $changedUser;
	}

	private function loadAdmin() {
		
		$this->adminFile = new \common\model\PHPFileStorage("data/admin.php");
		try {
			$adminUserString = $this->adminFile->readItem("Admin");
			$admin = UserCredentials::fromString($adminUserString);

		} catch (\Exception $e) {
			\Debug::log("Could not read file, creating new one", true, $e);

			$userName = new UserName("Admin");
			$password = Password::fromCleartext("Password");
			$admin = UserCredentials::create( $userName, $password);
			$this->update($admin);
		}

		$this->users[$admin->getUserName()->__toString()] = $admin;
	}
}
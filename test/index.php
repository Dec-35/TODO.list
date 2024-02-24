<?php // require("register.class.php") ?>
<?php // require("login.class.php") ?>
<?php
session_start();
function register($username, $password){

	$username = trim($username);
	$username = filter_var($username, FILTER_SANITIZE_STRING);

	$raw_password = filter_var(trim($password), FILTER_SANITIZE_STRING);
	//$encrypted_password = password_hash($raw_password, PASSWORD_DEFAULT);

	$stored_users = json_decode(file_get_contents("data.json"), true);

	$new_user = [
		"username" => $username,
		"password" => $raw_password,
		"lists" => array(),
	];

	
	insertUser($stored_users, $username, $new_user);
	
}


function usernameExists($stored_users, $username){
	foreach($stored_users as $user){
		if($username == $user['username']){
			$_SESSION['error'] = "Username already taken, please choose a different one.";
			return true;
		}
	}
	return false;
}


function insertUser($stored_users, $username, $new_user){
	if(usernameExists($stored_users, $username) == FALSE){
		array_push($stored_users, $new_user);
		if(file_put_contents("data.json", json_encode($stored_users, JSON_PRETTY_PRINT))){
			$_SESSION['user'] = $username;
			return $_SESSION['success'] = "Your registration was successful";
		}else{
			return $_SESSION['error'] = "Something went wrong, please try again";
		}
	}
}

function login($stored_users, $username, $password){
		foreach ($stored_users as $user) {
			if($user['username'] == $username){
				if($password == $user['password']){
					
					$_SESSION['user'] = $username;
					$tempUserName = $_SESSION['user'];
					echo "<script>console.log('Debug Objects username: " . $tempUserName . "' );</script>";
					$_SESSION['userLists'] = implode(" ",$user['lists']);
					echo "<script>console.log('Debug Objects file lists: " . $_SESSION["userLists"] . "' );</script>";
					$_SESSION['error'] = "";
					$_SESSION['success'] = "Successfully logged in !";
					return;
				}
			}
		}
		return $_SESSION['error'] = "Wrong username or password";
	}


function saveLists($stored_users, $username, $lists){
	for ($i = 0; $i < sizeof($stored_users); $i++) {
		if($stored_users[$i]['username'] == $username){
			$Oldlists = $stored_users[$i]['lists'];
			$stored_users[$i]['lists'] = explode(",",$lists);
			$_SESSION['userLists'] = implode(" ",$stored_users[$i]['lists']);
		}
	}
	echo "<script>console.log('Debug Objects old lists: " . $Oldlists . "' );</script>";
	
	
	echo "<script>console.log('Debug Objects session name: " . $_SESSION["user"] . "' );</script>";
	
	echo "<script>console.log('Debug Objects session name: " . $_SESSION["userLists"] . "' );</script>";

	
	if(file_put_contents("data.json", json_encode($stored_users, JSON_PRETTY_PRINT))){
		return $_SESSION['success'] = "Your lists have been successfully saved";
	}else{
		return $_SESSION['error'] = "Something went wrong, please try again";
	}
				
}


if(isset($_POST['register'])){
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	register($username, $password);
}
if(isset($_POST['login'])){
	$username = $_POST['username'];
	$password = $_POST['password'];
	$stored_users = json_decode(file_get_contents("data.json"), true);
	login($stored_users, $username, $password);
	
}
if(isset($_POST['listes'])){
	$username = $_SESSION['user'];
	$lists = $_POST['lists'];
	echo "<script>console.log('Debug Objects lists: " . $lists . "' );</script>";
	echo "<script>console.log('Debug Objects lists: " . explode(",",$lists) . "' );</script>";
	echo "<script>console.log('Debug Objects username: " . $username . "' );</script>";
	
	$stored_users = json_decode(file_get_contents("data.json"), true);
	saveLists($stored_users, $username, $lists);
	
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <link rel="stylesheet" href="styles.css">
	<title>Register form</title>
</head>
<body>

	<form action="" method="post" enctype="multipart/form-data" autocomplete="off">
		<h2>Register form</h2>
		<h4>Both fields are <span>required</span></h4>

		<label>Username</label>
		<input type="text" name="username" required>

		<label>Password</label>
		<input type="text" name="password" required>

		


		
		<button type="submit" name="register">Register</button>
		<button type="submit" name="login">Login</button>
		

		<p class="error"><?php echo $_SESSION['error'] ?></p>
		<p class="success"><?php echo $_SESSION['success'] ?></p>
		<p>Welcome <?php echo $_SESSION['user'] ?></p>
		<p>Your lists are <?php echo $_SESSION['userLists'] ?></p>

	</form>
	<form action="" method="post">
		<label>Lists</label>
		<input type="text" name="lists" required>
		<button type="submit" name="listes">Save lists</button>
	</form>

</body>
</html>
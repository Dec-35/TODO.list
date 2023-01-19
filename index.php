<?php
session_start();
function register($username, $password){

	$username = trim($username);
	$username = filter_var($username, FILTER_SANITIZE_STRING);

	$raw_password = filter_var(trim($password), FILTER_SANITIZE_STRING);
	//$encrypted_password = password_hash($raw_password, PASSWORD_DEFAULT);

	$stored_users = json_decode(file_get_contents("json/login.json"), true);

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
      $_SESSION['success'] = "";
			$_SESSION['error'] = "Username already taken, please choose a different one.";
			return true;
		}
	}
	return false;
}


function insertUser($stored_users, $username, $new_user){
	if(usernameExists($stored_users, $username) == FALSE){
		array_push($stored_users, $new_user);
		if(file_put_contents("json/login.json", json_encode($stored_users, JSON_PRETTY_PRINT))){
			$_SESSION['user'] = $username;
      $_SESSION['userLists'] = "{}";
			$_SESSION['error'] = "";
			return $_SESSION['success'] = "Your registration was successful";
      
		}else{
      $_SESSION['success'] = "";
			return $_SESSION['error'] = "Something went wrong, please try again";
		}
	}
}

function login($stored_users, $username, $password){
		foreach ($stored_users as $user) {
			if($user['username'] == $username){
				if($password == $user['password']){
					
					$_SESSION['user'] = $username;
					
					$_SESSION['userLists'] = $user['lists'];
					$_SESSION['error'] = "";
					$_SESSION['success'] = "Successfully logged in !";

					return;
				}
			}
		}
    $_SESSION['success'] = "";
		return $_SESSION['error'] = "Wrong username or password";
	}


function saveLists($stored_users, $username, $lists){
	for ($i = 0; $i < sizeof($stored_users); $i++) {
		if($stored_users[$i]['username'] == $username){
			$Oldlists = $stored_users[$i]['lists'];
			$stored_users[$i]['lists'] = $lists;
			$_SESSION['userLists'] = $lists;      
		}
	}
	
	
	if(file_put_contents("json/login.json", json_encode($stored_users, JSON_PRETTY_PRINT))){
    $_SESSION['error'] = "";
		return $_SESSION['success'] = "Your lists have been successfully saved";
	}else{
    $_SESSION['success'] = "";
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
	$stored_users = json_decode(file_get_contents("json/login.json"), true);
	login($stored_users, $username, $password);
	
}
if(isset($_POST['save'])){
	$username = $_SESSION['user'];
	$lists = $_POST['lists'];
	
	$stored_users = json_decode(file_get_contents("json/login.json"), true);
	saveLists($stored_users, $username, $lists);
	
}
?>



<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=0"
    />
    <title>TODO.list</title>
    <link rel="icon" type="image/png" href="media/LogoRound.png" />
    <link rel="stylesheet" href="style.css" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
  </head>

  <body>
    <!-- <a href="morpion.html">Go to the Tick Tack Toe</a> -->
    <input id="loginButtonInput" type="checkbox" checked="true" />
    <label for="loginButtonInput" id="loginButton" style="z-index: 80"></label>

    <form
      id="loginOptionsContainer"
      method="post"
      action=""
      style="z-index: 70"
    >
      <div id="formGrid">
        <h6>
          Logged in as <em><?php echo $_SESSION['user'] ?></em>
        </h6>
        <input
          class="loginField"
          type="text"
          name="username"
          id="loginId"
          placeholder="username"
          required
        />
        <input
          class="loginField"
          name="password"
          id="loginPass"
          placeholder="password"
          required
          type="password"
        />

        <button class="loginOptions" id="login" type="submit" name="login">
          Login
        </button>
        <div id="signup-hint">
          <p>Don't have an account yet?</p>
          <button
            class="loginOptions"
            id="signUp"
            type="submit"
            name="register"
          >
            Register
          </button>
        </div>
      </div>

      <p class="error result"><?php echo $_SESSION['error'] ?></p>
      <p class="success result"><?php echo $_SESSION['success'] ?></p>
    </form>

    <div id="mainContainerContent" style="z-index: 60">
      <h1 id="mainTitle">TODO.list</h1>

      <div id="main">
        <div id="gridMain">
          <input
            type="text"
            id="valInput"
            placeholder="type something"
            autocomplete="off"
          />
          <button
            class="submit"
            id="ajouter"
            onclick="entrerVal(document.getElementById('valInput').value)"
          >
            ADD
          </button>
          <button onclick="suppr()" class="submit" id="tSuppr">CLEAR</button>
          <form action="" method="post" id="save">
            <button
              class="submit"
              type="submit"
              name="save"
              onclick="saveLists()"
            >
              SAVE
            </button>
            <input
              type="text"
              name="lists"
              id="hiddenInput"
              style="display: none"
            />
          </form>
        </div>
        <h3>click on items to delete them</h3>
      </div>
    </div>
    <div id="titreEtListes" style="z-index: 65">
      <div id="listes"></div>
    </div>
    <h4
      onclick="window.open('https://m-legal.fr', '_blank')"
    >
      © 2022 - 2023 Meriadec Legal
    </h4>
  </body>
  <script src="https://code.jquery.com/jquery-2.2.1.min.js"></script>
  <script src="logic.js"></script>
  <script type="text/javascript">
    listArray = '<?php echo $_SESSION["userLists"];?>';
    console.log("lecture du json" + " " + listArray);
    parsedArray = JSON.parse(listArray);
    console.log(parsedArray);

    for (var key in parsedArray) {
      entrerVal(parsedArray[key]);
    }
  </script>
</html>

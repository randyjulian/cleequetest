<?php
	session_start();
	if(isset($_SESSION['username'])){header('Location: loginPage.php');};
?>

<!DOCTYPE html>
<html>
<head>
	<title>Cleeque | Homepage</title>
	<link rel="icon" type="image/png" href="/favicon.ico">
	<meta name="theme-color" content="#ffffff">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
	<script type="text/javascript" src="main.js"></script>
	<link type="text/css" rel="stylesheet" href="style.css"></link>
	<!---Fonts-->
	<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Roboto:300' rel='stylesheet' type='text/css'>
</head>
<body>
<!--<form action="upload.php" method="post" enctype="multipart/form-data">
    Select files to upload:

    <input type="file" multiple = '' name="fileToUpload[]" id="fileToUpload"><br />
    <input type="submit" value="Upload File" name="submit">
<a href="signup.php">Sign Up</a>
<a href="login.php">Log in</a>
</form> -->
<?php
	
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

	$location = null;
	$error = false;
try {
    $database = new PDO("mysql:host=$server;dbname=$db", $username, $password);
    // set the PDO error mode to exception
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
   		if ($e -> getcode() == 23000) {
   		echo "The username has already exist.";	
   		}
   		else {
   			print($e->getMessage());
   		}
    
    }
    //NUSNET connection API
    require_once 'LightOpenID-master/openid.php';
	$openid= new LightOpenID("https://cleeque.herokuapp.com/index.php");

	$openid->identity = 'https://openid.nus.edu.sg/';
	$openid->required = array(
		'contact/email',
		'namePerson/friendly',
		'namePerson');
	$openid->returnUrl = 'https://cleeque.herokuapp.com/nusnetlogin.php';

    if(isset($_POST['submit'])){
    	$errMessage='';
    	$username=trim($_POST['username']);
    	$password=trim($_POST['password']);
    	if($username==''){
    		$errMessage.='Name is not filled! ';
    	}
    	if($password==''){
    		$errMessage.='Password is not filled! ';
    	}
    	if($errMessage==''){
    		$records= $database->prepare('SELECT id, username, password, email FROM userid WHERE username=:username');
    		$records->bindParam(':username', $username);
    		$records->execute();
    		$results=$records->fetch(PDO::FETCH_ASSOC);
    		if(count($results)>0 && password_verify($password, $results['password'])){
    			$_SESSION['username']=$results['username'];
    			echo "<script>window.location.href='dashboard.php';</script>";
    			echo $_SESSION['username'];
    		} else {
    			$errMessage.="Username and Password are not found!<br>";
    		}
    		}
    	}
    	

?>


	<div class="modal">
		<div class="modal-content">
			<div class="modalHeader">
				<span class="close"> X </span>
				<p id="modalCleeque">CLEEQUE</p>				
			</div>
			<div class="modalBody">
				<a href='<?php echo $openid->authUrl()?>' style="text-decoration:none;"><div class="nusnetLogin">
					<p>Login with NUSNET</p>
				</div></a>
				<form  method="post">
					<input type="text" id="username" placeholder="Username"><br><br>
					<input type="password" id="password" placeholder="Password"><br>
					<p id="errorMessage" style="color:red"></p>
					<input type="submit" name="submit" value="Sign In" id="loginButton">
				</form>
				
				<br id="account"> Don't have an account? <a href="signup.php" id="modalSignup">Sign up!</a>
			</div>
		</div>
	</div>
	<div class="navbar">
		<img id="logo" src="http://i.imgur.com/NXXGa4e.png" height="35" width="35" style="float: left; margin-top: 6.4px;"><p id= "cleeque" style="margin-top:0px;" >  CLEEQUE</p> 
		<div class="menu" style="float:right;">
			<div class="mainMenu">
				<p><a href="index.php">Home</a></p>
				<p><a href="about.php">About</a></p>
			</div>
			<p id="login">Sign In</p>
			<p id="responsiveNavButton"> &#9776; Menu</p>
		</div>
		

	</div>
	<div class="main">
		<div class="slider">
			<div class="sliderController">
				<p id="slideBack" style="font-size: 100px; color: white; float: left; 	left: 2%;cursor:pointer"><</p>
				<p id="slideNext" style="font-size: 100px; color: white; float: right; right: 2%;cursor:pointer">></p>
			</div>
			<div class="navdot">
				<ul>
					<li id="dot1" class="active"></li>
					<li id="dot2"></li>
					<li id="dot3"></li>
				</ul>
			</div>
			<div class="slide" id="first">	
				<p><span style="font-size: 150px" >CLEEQUE</span></p>
				<p id="tagline">Arranging a meeting has never been this easy</p>
			</div>
			<div class="slide" id="second">
				<p style="font-color: white" id='min'><span style="font-size: 150px">UPDATE</span><br>New stuff coming up soon!</p>
			</div> 
			<div class="slide" id="third">
				<p style="font-color: white" id='min'>Find your group's common timeslot<br>in less than 3 minutes!</p>
			</div> 
			<div class="slide" id="first">	
				<p><span style="font-size: 150px" >CLEEQUE</span></p>
				<p span id="tagline">Arranging a meeting has never been this easy</p>
			</div>
		</div>
	</div>
	<div class="footer">
		<p style="text-align: left;"> &copy Cleeque 2016</p>
	</div>

</body>
</html>
</body>
</html>
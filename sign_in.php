<?php
	session_start();
	
	$cookie_name = "cookien";
	
	if(count($_COOKIE) > 0){
		
		if(!isset($_COOKIE[$cookie_name])){
			echo '<script>alert("This site use cookie and javascript")</script>';
			setcookie($cookie_name, "temp", time() + (3600*24*30*12));
		}
	} else{
		header("Location: error.php");
	}
	
?>

<!doctype html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="style.css">

		<!-- Bootstrap CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		
		<title>Sign in</title>
		
		<?php
		
			if($_SERVER['HTTPS'] != "on"){
				header("Location: https://" .$_SERVER['HTTP_HOST'] .$_SERVER['REQUEST_URI']);
			}
			
		?>
		
	</head>
	
	<body>
		
		<div id="mySidenav" class="sidenav">
			<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
			<a href="index.php">Home</a>
		</div>

		<div id="main">
			<span style="font-size:50px;cursor:pointer" onclick="openNav()">&#9776;</span>
		</div>
		
		<h1 id="title" class="text-center font-weight-bold">Sign in</h1> 
		
		<div id="data" class="rounded">
			<form action="interactions/Sign_inscript.php" method="POST">
				<div class="form-group col-10">
					<label for="exampleDropdownFormEmail1" style="color: white">Email address</label>
					<input id="emailbttn" type="email" name="username" class="form-control shadow" placeholder="email@example.com" onkeyup="checkregex()">
				</div>
				<div class="form-group col-10">
					<label for="exampleDropdownFormPassword1" style="color: white">Password</label>
					<input id="pwdbttn" type="password" name="password" class="form-control shadow" placeholder="Password" onkeyup="checkregex()">
					<small style="color: white">
						must contain at least a lowercase alphabetic character, and at least another character which must be either anuppercase alphabetic character or a digit
					</small>
				</div>
				<button name="signin" id="signb" type="submit" class="btn btn-primary" method="POST" disabled>Sign in</button>
			</form>
		</div>
		
		<script>
			function openNav() {
				document.getElementById("mySidenav").style.width = "15%";
				document.getElementById("main").style.marginLeft = "10%";
				document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
			}

			function closeNav() {
				document.getElementById("mySidenav").style.width = "0";
				document.getElementById("main").style.marginLeft= "0";
				document.body.style.backgroundColor = "white";
			}
			
			function checkregex(){
				var text = document.getElementById("pwdbttn").value;
				var pattern = /([a-z]+).*([A-Z]+|[0-9]+)|(([A-Z]+|[0-9]+).*([a-z]+))/;
				var res = pattern.test(text);
				var empty = document.getElementById("emailbttn").value;
				
				if(!res | empty == ""){
					document.getElementById("signb").disabled = true;
				} else{
					document.getElementById("signb").disabled = false;
				}
				
			}
			
		</script>
		
		<!-- Optional JavaScript -->
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>
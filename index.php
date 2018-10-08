<?php
	session_start();
	include_once 'interactions/timeout.php';
	include_once 'interactions/database.php';
	
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
		
		<title>Home</title>
		
		<?php
		
			if($_SERVER['HTTPS'] != "on"){
				header("Location: https://" .$_SERVER['HTTP_HOST'] .$_SERVER['REQUEST_URI']);
			}
			
		?>
		
	</head>
	
	<body>
		
		<div id="mySidenav" class="sidenav">
		
			<?php
				
				if(isset($_SESSION['Name'])){
					echo '<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
							<a href="userpage.php">Personal Page</a>
							<form id="logoutb" action="interactions/logout.php" method="POST">
								<a>
									<input name="logout" type="submit" value="Logout">
								</a>
							</form>';
				} else{
					echo '<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
						<a href="login.php">Login</a>
						<a href="sign_in.php">Sign in</a>';
				}
			?> 
			
		</div>

		<div id="main">
			<span style="font-size:50px;cursor:pointer" onclick="openNav()">&#9776;</span>
		</div>
		
		<h1 id="title" class="text-center font-weight-bold">Shuttle's Travel</h1> 
		
		<?php
			include_once './indextable.php';
		?>
		
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
		</script>
		
		<!-- Optional JavaScript -->
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>
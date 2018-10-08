<?php
	session_start();
	include_once 'interactions/timeout.php';
	include_once 'interactions/database.php';
	
	if(!isset($_SESSION['Name'])){
		header("Location: index.php");
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
		
		<title>Personal Page</title>
	</head>
	
	<body>
		
		<div id="mySidenav" class="sidenav">
		
			<?php
			
				if($_SERVER['HTTPS'] != "on"){
					header("Location: https://" .$_SERVER['HTTP_HOST'] .$_SERVER['REQUEST_URI']);
				}
				
				if(isset($_SESSION['Name'])){
					echo '<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
							<a href="index.php">Home</a>
							<form id="logoutb" action="interactions/logout.php" method="POST">
								<a>
									<input name="logout" type="submit" value="Logout">
								</a>
							</form>';
				}
			?> 
			
		</div>

		<div id="main">
			<span style="font-size:50px;cursor:pointer" onclick="openNav()">&#9776;</span>
		</div>
		
		<h1 id="title" class="text-center font-weight-bold"><span>Personal Page</span></h1> 
		
		<?php
			include_once './userpagetable.php';
		?>
		
		<br><br><br>
		
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
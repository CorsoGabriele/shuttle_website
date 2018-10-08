<?php
	session_start();
	
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
		
		<title>Error</title>
		
		<?php
		
			if($_SERVER['HTTPS'] != "on"){
				header("Location: https://" .$_SERVER['HTTP_HOST'] .$_SERVER['REQUEST_URI']);
			}
			
			if(count($_COOKIE) > 0){
				header("Location: index.php");
			}
			
		?>
		
	</head>
	
	<body>
		
		<h1 class="text-center font-weight-bold">Activate the cookies</h1>
		
		<!-- Optional JavaScript -->
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>
<?php
	
	session_start();
	
	if(isset($_POST['login'])){
		
		include_once 'database.php';
		
		// store the user input
		$username = mysqli_real_escape_string($conn, $_POST['username']);
		$password = mysqli_real_escape_string($conn, $_POST['password']);
		
		// check the input
		if(empty($username) || empty($password)){
			mysqli_close($conn);
			header("Location: ../login.php?loginemptyfields");
			exit();
		} else{
			
			// search if there is yet someone with that username
			$sql = "SELECT * FROM members WHERE Name='$username'";
			
			try{
				mysqli_autocommit($conn, false);
				$res = mysqli_query($conn, $sql);
				
				if(!$res){
					throw new Exception();
				}
				
				mysqli_commit($conn);
			} catch(Exception $e){
				mysqli_rollback($conn);
				mysqli_close($conn);
				mysqli_free_result($res);
				header("Location: ../sign_in.php?signinqueryfailed");
				exit();
			}
			
			
			$nrow = mysqli_num_rows($res);
			
			if($nrow < 1){
				mysqli_close($conn);
				mysqli_free_result($res);
				header("Location: ../login.php?logininvaliduser");
				exit();
			} else{
				
				if($row = mysqli_fetch_assoc($res)){
					
					// verify the password
					$pwdcheck = password_verify($password, $row['Password']);
					
					if($pwdcheck == true){
						
						// if the password is correct then active the session
						$_SESSION['Name'] = $row['Name'];
						$_SESSION['Password'] = $row['Password'];
						$_SESSION['time'] = time();
						
						// redirect to the logged page!
						mysqli_free_result($res);
						header("Location: ../userpage.php?loggedin");
						exit();
						
					} else{
						mysqli_close($conn);
						mysqli_free_result($res);
						header("Location: ../login.php?wrongpassword");
						exit();
					}
					
				}
				
			}
	
		}
		
	} else {
		header("Location: ../index.php");
		exit();
	}
	
?>
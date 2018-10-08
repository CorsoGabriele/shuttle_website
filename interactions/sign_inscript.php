<?php
	
	if(isset($_POST['signin'])){
		
		include_once 'database.php';
		
		// store the user input
		$username = mysqli_real_escape_string($conn, $_POST['username']);
		$password = mysqli_real_escape_string($conn, $_POST['password']);
		
		// check the values
		if(empty($username) || empty($password)){
			mysqli_close($conn);
			header("Location: ../sign_in.php?signinemptyfields");
			exit();
		} else{
			
			// check if the password respects the specifics
			if((preg_match("/([a-z]+).*([A-Z]+|[0-9]+)|(([A-Z]+|[0-9]+).*([a-z]+))/", $password)) != 1){
				mysqli_close($conn);
				header("Location: ../sign_in.php?signininvalidpassword");
				exit();
			}
			
			// check the email
			if(!filter_var($username, FILTER_VALIDATE_EMAIL)){
				mysqli_close($conn);
				header("Location: ../sign_in.php?signininvalidemail");
				exit();
			} else{
				
				// control if the user exists yet
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
				
				if($nrow > 0){
					mysqli_close($conn);
					mysqli_free_result($res);
					header("Location: ../sign_in.php?signinusertaker");
					exit();
				} else{
					
					// encode the password
					$hashpwd = password_hash($password, PASSWORD_DEFAULT);
					
					// insert the new user
					$sql = "INSERT INTO members (Name, Password) VALUES ('$username', '$hashpwd')";
					
					try{
						mysqli_autocommit($conn, false);
						
						if(!mysqli_query($conn, $sql)){
							throw new Exception();
						}
						
						mysqli_commit($conn);
						mysqli_free_result($res);
					} catch(Exception $e){
						mysqli_rollback($conn);
						mysqli_close($conn);
						mysqli_free_result($res);
						header("Location: ../sign_in.php?signinqueryfailed");
						exit();
					}
					
					header("Location: ../index.php?signinsuccess");
					exit();
				}
				
			}
			
		}
		
	} else {
		header("Location: ../sign_in.php?signedinfailed");
		exit();
	}
	
?>
<?php
	
	session_start();
	$maxseats = 5;
	
	if(isset($_POST['prenote'])){
		
		include_once 'database.php';
		
		$dep = mysqli_real_escape_string($conn, $_POST['sdep']);
		$dep = strtoupper($dep);
		$dep = stripcslashes($dep);
		$dep = strip_tags($dep);
		$dep = trim($dep);
		$dep = preg_replace("/[^A-Z]/", "", $dep);
		
		$arr = mysqli_real_escape_string($conn, $_POST['sarr']);
		$arr = strtoupper($arr);
		$arr = stripcslashes($arr);
		$arr = strip_tags($arr);
		$arr = trim($arr);
		$arr = preg_replace("/[^A-Z]/", "", $arr);
		
		$nseats = $_POST['seats'];
		$nseats = stripcslashes($nseats);
		$nseats = strip_tags($nseats);
		$nseats = trim($nseats);
		$nseats = preg_replace("/[^0-9]/", "", $nseats);
		
		
		// check if the input is wrong
		if(empty($dep) || empty($arr) || empty($nseats) || ($nseats < 1) || ($nseats > $maxseats) || (strcasecmp($arr, $dep) < 0) || (strcasecmp($arr, $dep) == 0)){
			$dd = strcasecmp($arr, $dep);
			mysqli_close($conn);
			echo '<script> alert("Booking not valid, wrong parameters in the fields");';
			echo'window.location.href="../userpage.php";</script>';
			exit();
		} else{
			
			// check if the user alredy has a booked travel
			$sql = "SELECT * FROM members WHERE Name='" .$_SESSION['Name'] ."'" ." AND Seats IS NULL";
			
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
				echo '<script> alert("Booking failed");';
				echo'window.location.href="../userpage.php";</script>';
				exit();
			}
			
			
			$nrow = mysqli_num_rows($res);
			
			if($nrow < 1){
				mysqli_close($conn);
				mysqli_free_result($res);
				echo '<script> alert("Invalid user");';
				echo'window.location.href="../userpage.php";</script>';
				exit();
			} else{
				
				$query1 = "SELECT * FROM stops FOR UPDATE";
				$query2 = "SELECT * FROM members FOR UPDATE";
	
				// selecting the stops and the members
				try{
					mysqli_autocommit($conn, false);
					$res = mysqli_query($conn, $query1);
					$resm = mysqli_query($conn, $query2);
					
					if(!$res || !$resm){
						throw new Exception();
					}
					
					//mysqli_commit($conn);
				} catch(Exception $e){
					mysqli_rollback($conn);
					mysqli_close($conn);
					mysqli_free_result($res);
					echo '<script> alert("Booking failed");';
					echo'window.location.href="../userpage.php";</script>';
					exit();
				}
				
				// storing the stops and initializing the seats
				for($i=0; $i < mysqli_num_rows($res); $i++){
					$t = mysqli_fetch_assoc($res);
					$stops[$i] = $t['stop'];
					$seats[$i] = 0;
				}
				
				// flag for the insert in the stops table
				$newdep = false;
				$newarr = false;
				$founddep = false;
				$foundarr = false;
				
				// check if the departure or the arrival are yet present
				for($k=0; $k < count($stops); $k++){	
					if(strcasecmp($dep, $stops[$k]) == 0){
						$founddep = true;
					}
					
					if(strcasecmp($arr, $stops[$k]) == 0){
						$foundarr = true;
					}
				}
				
				// if not found the departure in the array of the stops add it
				if(!$founddep){
					$stops[$i] = $dep;
					$seats[$i] = 0;
					$newdep = true;
					$i++;
				}
				
				// if not found the arrival in the array of the stops add it
				if(!$foundarr){
					$stops[$i] = $arr;
					$seats[$i] = 0;
					$newarr = true;
				}
				
				sort($stops);
				
				// calculete the number of seats for each segment
				for($i=0; $i < mysqli_num_rows($resm); $i++){
					$usr = mysqli_fetch_assoc($resm);
					
					$min = 0;
					$max = 0;
		
					for($k=0; $k < count($stops); $k++){
						if(strcasecmp($stops[$k], $usr['Departure']) == 0){
							$min = $k;
						}
						if(strcasecmp($stops[$k], $usr['Arrival']) == 0){
							$max = $k;
						}
					}
					
					// from min to max of the vector seats, add the seats prenoted by the member
					for($j=$min; $j < $max; $j++){
						$seats[$j] += $usr['Seats'];	
					}
				}
				
				// store the position of the departure and the arrival in the array
				for($k=0; $k < count($stops); $k++){	
					if(strcasecmp($dep, $stops[$k]) == 0){
						$min = $k;
					}
					
					if(strcasecmp($arr, $stops[$k]) == 0){
						$max = $k;
					}
				}
				
				// check if the number of seats to prenote is available
				for($i=$min; $i < $max; $i++){
					$seats[$i] += $nseats;
					
					if($seats[$i] > $maxseats){
							mysqli_close($conn);
							echo '<script> alert("No seats available for this segment");';
							echo'window.location.href="../userpage.php";</script>';
							mysqli_free_result($res);
							mysqli_free_result($resm);
							exit();
						}
					
				}
				
				// the user can prenote the travel
				$sql1 = "UPDATE members SET Departure='" .$dep ."', Arrival='" .$arr. "', Seats='" .$nseats ."' WHERE Name='" .$_SESSION['Name'] ."'" ;
				
				try{
					mysqli_autocommit($conn, false);
					$res1 = mysqli_query($conn, $sql1);
					$res2 = true;
					$res3 = true;
					
					// if the flag is true add a new stops
					if($newdep){
						$sql2 = "INSERT INTO stops (stop) VALUES ('" .$dep ."')";
						$res2 = mysqli_query($conn, $sql2);
					}
					
					if($newarr){
						$sql3 = "INSERT INTO stops (stop) VALUES ('" .$arr ."')";
						$res3 = mysqli_query($conn, $sql3);
					}
					
					if(!$res1 || !$res2 || !$res3){
						throw new Exception();
					}
					
					mysqli_commit($conn);
				} catch(Exception $e){
					mysqli_rollback($conn);
					mysqli_close($conn);
					echo '<script> alert("Booking failed");';
					echo'window.location.href="../userpage.php";</script>';
					mysqli_free_result($resm);
					mysqli_free_result($res);
					mysqli_free_result($res1);
					mysqli_free_result($res2);
					mysqli_free_result($res3);
					exit();
				}
				
				
				echo '<script> alert("Booking success!");';
				echo'window.location.href="../userpage.php";</script>';
				mysqli_free_result($res);
				mysqli_free_result($res1);
				mysqli_free_result($res2);
				mysqli_free_result($res3);
				exit();
			}
	
		}
		
	} else {
		echo '<script> alert("Booking failed");';
		echo'window.location.href="../userpage.php";</script>';
		exit();
	}
	
?>
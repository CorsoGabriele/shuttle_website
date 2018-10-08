<?php
	
	session_start();
	
	if(isset($_POST['deletet'])){
		
		include_once 'database.php';
		
		$deldep = false;
		$delarr = false;
		
		// select the user to store his data
		$sql = "SELECT * FROM members WHERE Name='" .$_SESSION['Name'] ."' FOR UPDATE";
				
		try{
			mysqli_autocommit($conn, false);
			$res = mysqli_query($conn, $sql);
			
			if(!$res){
				throw new Exception();
			}
			
			//mysqli_commit($conn);
		} catch(Exception $e){
			mysqli_rollback($conn);
			mysqli_close($conn);
			mysqli_free_result($res);
			header("Location: ../userpage.php?delete1queryfailed");
			exit();
		}
		
		$t = mysqli_fetch_assoc($res);
		
		$dep = $t['Departure'];
		$arr = $t['Arrival'];
		
		// check if the departure or the arrival can be deleted
		$sql1 = "SELECT * FROM members WHERE Departure='" .$dep ."' OR Arrival='" .$dep ."'";
		$sql2 = "SELECT * FROM members WHERE Departure='" .$arr ."' OR Arrival='" .$arr ."'";
		
		try{
			mysqli_autocommit($conn, false);
			$res1 = mysqli_query($conn, $sql1);
			$res2 = mysqli_query($conn, $sql2);
			
			if(!$res1 || !$res2){
				throw new Exception();
			}
			
			//mysqli_commit($conn);
		} catch(Exception $e){
			mysqli_rollback($conn);
			mysqli_close($conn);
			mysqli_free_result($res);
			mysqli_free_result($res1);
			mysqli_free_result($res2);
			header("Location: ../userpage.php?delete2queryfailed");
			exit();
		}
		
		// the departure can be deleted
		if(mysqli_num_rows($res1) == 1){
			$deldep = true;
		}
		
		// the arrival can be deleted
		if(mysqli_num_rows($res2) == 1){
			$delarr = true;
		}
		
		// delete the user's prenotation from the table by updating and udating the stops teble if necessary
		$sql1 = "UPDATE members SET Departure=NULL, Arrival=NULL, Seats=NULL WHERE Name='" .$_SESSION['Name'] ."'" ;
		
		try{
			mysqli_autocommit($conn, false);
			$res1 = mysqli_query($conn, $sql1);
			$res2 = true;
			$res3 = true;
			
			// delete the departure
			if($deldep){
				$sql2 = "DELETE FROM stops WHERE stop='" .$dep ."'";
				$res2 = mysqli_query($conn, $sql2);
			}
			
			// delete the arrival
			if($delarr){
				$sql3 = "DELETE FROM stops WHERE stop='" .$arr ."'";
				$res3 = mysqli_query($conn, $sql3);
			}
			
			if(!$res1 || !$res2 || !$res3){
				throw new Exception();
			}
			
			mysqli_commit($conn);
		} catch(Exception $e){
			mysqli_rollback($conn);
			mysqli_close($conn);
			mysqli_free_result($res);
			mysqli_free_result($res1);
			mysqli_free_result($res2);
			mysqli_free_result($res3);
			header("Location: ../userpage.php?delete4queryfailed");
			exit();
		}
		
		mysqli_free_result($res);
		mysqli_free_result($res1);
		mysqli_free_result($res2);
		mysqli_free_result($res3);
		header("Location: ../userpage.php?deletesuccess");
		
	}else {
		header("Location: ../userpage.php");
		exit();
	}
		
?>
<?php
	
	$maxseats = 5;
	$stops = null;
	$query = "SELECT * FROM stops";
	
	// selecting the stops
	try{
		mysqli_autocommit($conn, false);
		$res = mysqli_query($conn, $query);
		
		if(!$res){
			throw new Exception();
		}
		
		mysqli_commit($conn);
	} catch(Exception $e){
		mysqli_rollback($conn);
		mysqli_close($conn);
		mysqli_free_result($res);
		header("Location: index.php?indexqueryfailed");
		exit();
	}
	
	// storing the stops and initializing the seats
	for($i=0; $i < mysqli_num_rows($res); $i++){
		$t = mysqli_fetch_assoc($res);
		$stops[$i] = $t['stop'];
		$seats[$i] = 0;
	}
	
	if(count($stops) > 0){
		sort($stops);
	}
	
	$query = "SELECT * FROM members";
	
	// selecting all the members
	try{
		mysqli_autocommit($conn, false);
		$res = mysqli_query($conn, $query);
		
		if(!$res){
			throw new Exception();
		}
		
		mysqli_commit($conn);
	} catch(Exception $e){
		mysqli_rollback($conn);
		mysqli_close($conn);
		mysqli_free_result($res);
		header("Location: index.php?indexqueryfailed");
		exit();
	}
	
	// storing all the members and storing the fields in the different vectors
	for($i=0; $i < mysqli_num_rows($res); $i++){
		$usr = mysqli_fetch_assoc($res);
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
		
		$departure[$usr['Name']] = $usr['Departure'];
		$arrival[$usr['Name']] = $usr['Arrival'];
		$usrseats[$usr['Name']] = $usr['Seats'];
		$members[$i] = $usr['Name'];
		
		// from min to max of the vector seats, add the seats prenoted by the member
		for($j=$min; $j < $max; $j++){
			$seats[$j] += $usr['Seats'];
		}
		
	}
	
	for($i=0; $i < count($stops)-1; $i++){
		
		echo '
			<br><br><br>
			<div class="alert alert-dark" role="alert">';
				
				// checking if the departure or the arrival of the member of the session are the same, if the member has not prenoted these values are null
				if((strcasecmp($stops[$i], $departure[$_SESSION['Name']]) == 0)){
					echo '<b style="color:red">' .$stops[$i] .'</b>';
				} else{
					echo '<b>' .$stops[$i] .'</b>';
				}
				
				if(strcasecmp($stops[$i+1], $arrival[$_SESSION['Name']]) == 0){
					echo '<b> -> </b> <b style="color:red">' .$stops[$i+1] .'</b> <b>, Total Booked Seats: </b>'; //.$seats[$i] .'/' .$maxseats .'</b>';
				} else{
					echo '<b> -> ' .$stops[$i+1] .', Total Booked Seats: </b>'; //.$seats[$i] .'/' .$maxseats .'</b>';
				}
				
				if($seats[$i] == 0){
					echo '<b> The shuttle is empty for this segment </b>';
				} else{
					echo  '<b>' .$seats[$i] .'/' .$maxseats .'</b>';
				}
				
		echo '
			</div>
			<table class="table table-striped table-dark">
			
				<thead>
					<tr>
					<th scope="col">Users</th>
					<th scope="col">Booked Seats</th>
					</tr>
				</thead>
				<tbody>
		';
		
		// check if the segment is inside the member's travel
		for($j=0; $j < count($members); $j++){
			
			if( ((strcasecmp($stops[$i], $departure[$members[$j]]) == 0) || (strcasecmp($stops[$i], $departure[$members[$j]]) > 0)) &&
				((strcasecmp($stops[$i+1], $arrival[$members[$j]]) == 0) || (strcasecmp($stops[$i+1], $arrival[$members[$j]]) < 0)) ){
				
				if(strcmp($_SESSION['Name'], $members[$j]) == 0){
					echo '
						<tr>
							<th scope="row"> <p style="color:red">' .$members[$j] .'</p> </th>
					';
				} else{
					echo '
						<tr>
							<th scope="row">' .$members[$j] .'</th>
					';
				}
				
				echo '<td>' .$usrseats[$members[$j]] .'</td>';
				
			}
			
		}
		
		echo '
				</tbody>
			</table>
		';
		
	}
	
	echo '<br><br><br>';
	
	if(empty($usrseats[$_SESSION['Name']])){
		
		echo'
			<h1 id="journey" style="color: white" class="text-center">Book your journey!</h1>
			
			<div id="prenotation" class="rounded">
				<form action="interactions/prenote.php" method="POST">
					<div class="form-group col-10">
						<label style="color: white">Departure</label>
						<input list="ldep" type="text" name="sdep" class="form-control" placeholder="Departure">
							<datalist id="ldep">';
								for($i=0; $i < count($stops); $i++){
									echo '<option value="' .$stops[$i] .'">';
								}
		echo
		'
							</datalist>
					</div>
					<div class="form-group col-10">
						<label style="color: white">Arrival</label>
						<input list="larr" type="text" name="sarr" class="form-control" placeholder="Arrival">
							<datalist id="larr">';
								for($i=0; $i < count($stops); $i++){
									echo '<option value="' .$stops[$i] .'">';
								}
		echo
		'
							</datalist>
					</div>
					<div class="form-group col-10">
						<label style="color: white">#Seats</label>
						<input type="number" name="seats" class="form-control" value="1" min="1" max="5">
					</div>
					<button name="prenote" id="prenoteb" type="submit" class="btn btn-primary" method="POST">Book</button>
				</form>
			</div>
		';
	} else{
		echo '
			<form action="interactions/delete.php" method="POST">
				<div class="text-center">
					<button name="deletet" id="deleteb" type="submit" class="btn btn-primary btn-lg" method="POST">Delete Travel</button>
				</div>
			</form>
		';
	}
	
	mysqli_free_result($res);
?>
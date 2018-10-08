<?php
	
	$stops = null;
	
	// select all the stops
	$query = "SELECT * FROM stops";
	
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
		header("Location: index.php?indexqueryfailed");
		exit();
	}
	
	// store the stops and initialize the seats
	for($i=0; $i < mysqli_num_rows($res); $i++){
		$t = mysqli_fetch_assoc($res);
		$stops[$i] = $t['stop'];
		$seats[$i] = 0;
	}
	
	if(count($stops) > 0){
		sort($stops);
	}
	
	// select all the members
	$query = "SELECT * FROM members";
	
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
		header("Location: index.php?indexqueryfailed");
		exit();
	}
	
	// count the seats for each stop
	for($i=0; $i < mysqli_num_rows($res); $i++){
		$usr = mysqli_fetch_assoc($res);
		$min = 0;
		$max = 0;
		
		// search the index of the departure and the arrival
		for($k=0; $k < count($stops); $k++){
			if(strcasecmp($stops[$k], $usr['Departure']) == 0){
				$min = $k;
			}
			if(strcasecmp($stops[$k], $usr['Arrival']) == 0){
				$max = $k;
			}
		}
		
		for($j=$min; $j < $max; $j++){
			$seats[$j] += $usr['Seats'];
		}
		
	}
	
	if(count($stops) != 0){
		echo '
				<table class="table table-striped table-dark">
				  <thead>
					<tr>
					  <th scope="col">#</th>
					  <th scope="col">Stops</th>
					  <th scope="col">#Seats</th>
					</tr>
				  </thead>
				  <tbody>
		';
		
		for($i=0; $i < count($stops)-1; $i++){
			echo '
					<tr>
						<th scope="row">' .($i+1)
						.'</th>
			';
			
			echo '<td>' .$stops[$i] .' -> ' .$stops[$i+1] .'</td>';
			echo '<td>';

			if($seats[$i] == 0){
				echo 'Empty for this segment';
			} else{
				echo $seats[$i];
			}
			
			echo '</td>';
		}
		
		echo '
				</tbody>
			</table>
		';
	} else{
		echo '<div id="notravel"><b style="color: red">There are no booked travel!</b></div>';
	}
	
	mysqli_free_result($res);
?>
<?php
	$conn = mysqli_connect('localhost', 'shubhamc_selim', 'shubhamc_selim', 'shubhamc_selim');
	
	if (!$conn) {
		echo "Error: " . mysqli_connect_error();
		exit();
	}
	
	date_default_timezone_set("Asia/Kolkata"); 
?>
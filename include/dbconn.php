<?php
function connect(){
	$link = mysqli_connect('localhost', 'root', '', 'expensetracker');
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	mysqli_set_charset($link,'utf8');
	return $link;
}

function disconnect($link){
	mysqli_close($link);
}
?>
<?php
function connect()
{
	$url='127.0.0.1:3306';
	$username='root';
	$password='root';
	$conn=mysqli_connect($url,$username,$password,"art");
	if(!$conn){
 		die('Could not Connect My Sql:' .mysql_error());
	}
	return($conn);	
}?>
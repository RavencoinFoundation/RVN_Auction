<?php
function connect()
{
	$url='127.0.0.1:3306';
	$username='art';
	$password='art';
	$conn=new mysqli($url,$username,$password,"art");
	if(!$conn){
 		die('Could not Connect MySql:'.$conn->connect_error);
	}
	return($conn);	
}?>
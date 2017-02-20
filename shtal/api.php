<?php
	if(empty($_GET['url']))
	{
		echo 'Error: no URLs specified';
	} else {
		include_once('assets/includes/function_shorturl.php');
		
		echo 'https://sht.al/' . shortURL($_GET['url']);
	}
?>
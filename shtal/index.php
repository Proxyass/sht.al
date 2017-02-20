<?php

	include_once('assets/includes/function_shorturl.php');
	include('assets/includes/bdd.php');

	if(isset($_POST['submit']))
	{
		$short_url = 'https://sht.al/' . shortURL($_POST['url']);
	}


?>

<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="keywords" content="url shortener, url, shortener, short, free, webservice, swiss, suisse">
		<meta name="description" content="Sht.al is an free alternative of goo.gl to create short URLs that can be easily shared, tweeted, or emailed to friends." />
		<meta name="Author" content="Cafe31">

		<link rel="stylesheet" type="text/css" href="assets/css/style.css">
		<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
		<script scr="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

		<title>SHT.AL - Your free URL shortener</title>
	</head>
	<body>
		<header>
		    <nav>
		      <a href="#">Home</a>
		      <a href="#steezy">My URLs</a>
		      <a href="#real">API</a>
		      <a href="#contact">Contact us</a>
		    </nav>
		</header>
		<div class="wrapper">
		    <section id='steezy'>
				<center><h2>SHORT URL</h2></center>
		      
				<form action="index.php" method="post">
					<center><input type="url" name="url" class="login-field" id="url" required="required" id="login-name" placeholder="http://mywebsite.com" value='<?php isset($short_url) AND print($short_url); ?>'><br></center>
		  			<input type="submit" id="login-name" value="SHORT" class="btn btn-primary btn-large btn-block" name="submit" >
		  			<label class="login-field-icon fui-user" for="login-name"></label>
				</form> 
		    </section>
		    <?php
		    	$quoted_ip_adress = $sht_bdd->quote($_SERVER['REMOTE_ADDR']);
		    	$get_user_urls_query = $sht_bdd->query("SELECT * FROM `user_urls` WHERE ip_adress=$quoted_ip_adress");
		    	$rows = $get_user_urls_query->rowCount();

		    	if($rows >= 1)
		    	{
		    		echo "<section id='real'><table class='table table-bordered'><h3><tr class='active'><th>Short URL</th><th>Long URL</th></tr></h3>";
			  		while($donnees_urls = $get_user_urls_query->fetch())
					{	
						echo "<h4><tr><td><a href='https://sht.al/" . $donnees_urls['short_url'] . "' target='_blank'>sht.al/" . $donnees_urls['short_url'] . "</a></td><td>" . $donnees_urls['long_url'] . "</td></tr></h4>" ;
					}
					echo "</table></section>";
				}
			?>

		    <section id='api'>
		    	<center>
					<h2> How to use our API ?</h2>

					<p>In command line, using CURL</p>
					<img style="margin-top: -3%;" src="assets/images/curl.png" /></br><br>

					<p>With PHP, using this function</p>
					<img style="margin-top: -3%;" src="assets/images/php.png" /></br>
				</center>
			</section>

		    <section id='contact'>
		    	<center>
					<h2>Contact us</h2>
			        <form id="contactform" method="post" action="assets/php/submitform.php" autocomplete="on">
			        	<div class="form-items-holder">
				            <input type="text" name="name" placeholder="Name" required>
				            <input type="email" name="email" placeholder="Email" required><br><br>
				            <input style="width: 500px; height: 150px;" type="text" name="description" placeholder="Message" required><br><br>
				            <div class="ajax-button">
				            	<div class="fa fa-check done"></div>
				            	<div class="fa fa-close failed"></div>
				            	<input id="login-name" class="btn btn-primary btn-large btn-block" type="submit" value="Send my message">
				            </div>
			        	</div>
			        </form>
		    	</center>
			</section>
	  	</div>
	</body>
</html>
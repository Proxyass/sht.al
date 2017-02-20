<?php
	function shortURL($long_url)
	{
	
		function random($car) {
			$string = "";
			$chaine = "0123456789abcdefghijklmnopkrstuvwxyzABCEDFGHIJKLMNOPQRSTUVWXYZ";
			for($i=0; $i<$car; $i++) {
			$string .= $chaine[rand()%strlen($chaine)];
			}
			return $string;
		}
		
		include('bdd.php');

		// On vérifie si l'URL est valable
		
		if (preg_match('#^(http|https)://[\w-]+[\w.-]+\.[a-zA-Z]{2,6}#i', $long_url))
		{
		
			// On regarde si elle n'existe pas déjà
			
			$reponse1 = $sht_bdd->query("SELECT * FROM `urls` WHERE long_url='".$long_url."'");
			$rows = $reponse1->rowCount();
			
			if($rows == 1)
			{
				while($donnees1 = $reponse1->fetch())
				{
					$short_url = $donnees1['short_url'];	
				}
				
				$already_exists_user_url = $sht_bdd->query("SELECT * FROM `user_urls` WHERE ip_adress='".$_SERVER['REMOTE_ADDR']."' AND short_url='".$short_url."'");
				$rows = $already_exists_user_url->rowCount();

				if($rows == 0)
				{
					$sht_bdd->query("INSERT INTO `user_urls` (`long_url`, `short_url`, `ip_adress`, `date`) VALUES ('".$long_url."', '".$short_url."', '" . $_SERVER['REMOTE_ADDR'] . "', NOW())");
				}

				return $short_url;
			}
			else {
			// Sinon, on en crée une puis on l'inscrit dans la bdd
			
			$reponse = $short_url = 1;
			
			while($short_url == $reponse)
			{
				$short_url = random(6);
				
				$reponse2 = $sht_bdd->query("SELECT * FROM `urls` WHERE short_url='".$short_url."'");
				
				while($donnees2 = $reponse2->fetch())
				{
					$reponse = $donnees2['short_url'];
				}
			}
		
			$long_url = str_replace(' ', '%20', $long_url);
		
			$sht_bdd->query("INSERT INTO `urls` (`long_url`, `short_url`, `ip_adress`, `add_time`) VALUES ('".$long_url."', '".$short_url."', '" . $_SERVER['REMOTE_ADDR'] . "', NOW())");
			$sht_bdd->query("INSERT INTO `user_urls` (`long_url`, `short_url`, `ip_adress`, `date`) VALUES ('".$long_url."', '".$short_url."', '" . $_SERVER['REMOTE_ADDR'] . "', NOW())");

			// On inscrit aussi dans le htaccess
			$file = fopen('.htaccess', 'r+');
			fseek($file, 0, SEEK_END);

			fputs($file, "\n");
			fputs($file, "Redirect 301 /" . $short_url . "/ " . $long_url . "");

			fputs($file, "\n");
			fputs($file, "Redirect 301 /" . $short_url . " " . $long_url . "");

			fclose($file);
			
			return $short_url;
			}
		}
		else {
			echo 'Error: the entered URL is invalid';
		}
	}
?>
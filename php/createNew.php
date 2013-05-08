		<?php
		//Conncet to the database
		require_once("../dataAuth.php");
		//Get the title and text of the post
		$title = strip_tags($_POST[title]);
		$text = strip_tags($_POST[text]);
		
		//Get all of the tags that the user has entered
		$tag = strip_tags($_POST[tag]);
		$tags = explode(" ", $tag);
		
		//Username of the poster and the time it was posted.
		$user = strip_tags($_SERVER['WEBAUTH_USER']);
		$time = time();

		
		//Add the new Entry to the database
		
		$sql= $mysqli->prepare("INSERT INTO Entries (Title, Text, UTime, User) VALUES (?,?,?,?)");
		$sql->bind_param('ssis', $title, $text, $time, $user);
		$sql->execute();
		$sql->close();

		//Get the ID of the entry.
		if($stmt = $mysqli->prepare("SELECT ID FROM Entries WHERE Title=? AND Text=? AND UTime=?")){
			
			$stmt->bind_param("ssi", $title, $text, $time);
			$stmt->execute();
			$stmt->bind_result($idVal);
			$stmt->fetch();		
			$stmt->close();
		
		}

		//Strip out tags that are the same.
		for($i = 0; $i < count($tags) - 1; $i++){
			for($j = $i + 1; $j < count($tags); $j++){
				if(strcmp($tags[$i], $tags[$j]) == 0){
					$tags[$j] = "";
				}
			}
		}
		
		//Strip out tags that are the null string. 
		foreach($tags as &$value){
			if(strcmp($value, "") != 0){
				//Insert each tag. 
				$sql = $mysqli->prepare("INSERT INTO Tags (ID, Tag) VALUES(?,?)");
				$sql->bind_param('is', $idVal, $value);
				$sql->execute();
				$sql->close();
			}
		}
		
		
		mysqli_close($mysqli);
		//header("Location: entry.php?id=$idVal");
		echo("<script>window.location = 'entry.php?id=" . $idVal . "';</script>");
	?>

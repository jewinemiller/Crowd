<?php
	//Connect  to the database
	require_once("../dataAuth.php");
	//Sanitize Inputs
	$text = strip_tags($_POST['text']);
	$user = strip_tags($_POST['user']);
	$parent =strip_tags( $_POST['parent']);
	//Fix a bit of POST Nastiness.
	$parent = str_replace('"', '', $parent);
	
	//Create a new Comment using the entered data
	$sql = $mysqli->prepare("INSERT INTO Comments (Text, Parent, User) VALUES (?,?,?)");
	$sql->bind_param('sis', $text, $parent, $user);
	$sql->execute();
	$sql->close();

	//Code required to email
	
	//Get the username of the person who made the original post
	if($sql = $mysqli->prepare("SELECT User from Entries WHERE ID=?")){
		$sql->bind_param('i', $parent);
		$sql->execute();
		$sql->bind_result($parentUser);
		$sql->fetch();
		$sql->close();
	}

	//If the person who posted it was not the person who commented, email them.
	if(strcmp($parentUser, "") != 0 && strcmp($parentUser, $user)){
		$to = $parentUser . "@csh.rit.edu";
		$subject = $user . " Commented on Your Post";
		$message = $user . " Commented on Your Post.\n Respond to them at http://crowd.csh.rit.edu/php/entry.php?id=" . $parent;
		$headers = "From: " . $user . "@csh.rit.edu \r\n";
		mail($to, $subject, $message, $headers);

	}
	
	mysqli_close($mysqli);
	//Redirect back to the entry page. 
	echo("<script>window.location = 'entry.php?id=" . $parent . "';</script>");
	//header("Location: entry.php?id=" . $parent);
?>

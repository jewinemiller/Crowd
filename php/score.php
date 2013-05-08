<?php
	//Connect to the databse. 
	require_once("../dataAuth.php");
	//Get the comment ID, the person voting, and the score that they are giving it. 
	$commentID = $_POST['cID'];
	$voteUser = strip_tags($_POST['vUser']);
	$score = $_POST['score'];
	
	//Get the total score from the target comment. 
	if($stmt = $mysqli->prepare("SELECT Score FROM Comments WHERE ID=?")){
		$stmt->bind_param('i', $commentID);
		$stmt->execute();
		$stmt->bind_result($totalScore);
		$stmt->fetch();
		$stmt->close();
		

	}
	else{
		
	}
		//Get the score that the user gave the post previously.
		if($stmt = $mysqli->prepare("SELECT Score FROM Scores WHERE User=? AND ID=?")){
			$stmt->bind_param('si', $voteUser, $commentID);
			$stmt->execute();
			$stmt->bind_result($tempScore);
			$stmt->fetch();
			$stmt->close();

			
		}
		
		//If the user is not giving it the same score
		if($tempScore != $score){

			//Update the total score
			$totalScore += $score; 
			
			//If this is the first time that the user has given a comment a score, add them to the score database
			if($tempScore == NULL){
				$stmt = $mysqli->prepare("INSERT INTO Scores (User, ID, Score) VALUES (?, ?, ?)");
				$stmt->bind_param('sii', $voteUser, $commentID, $score);
				$stmt->execute();
				$stmt->close();
			
			}
			//Otherwise, update the score that they have given. 
			else{
				$stmt = $mysqli->prepare("UPDATE Scores SET Score=? WHERE User=? AND ID=?");
				$stmt->bind_param('isi', $score, $voteUser, $commentID);
				$stmt->execute();
				$stmt->close();
			

			}
			//Update the score for the comment in the database. 
			$stmt = $mysqli->prepare("UPDATE Comments SET Score=? WHERE ID=?");
			$stmt->bind_param('ii', $totalScore, $commentID);
			$stmt->execute();
			$stmt->close();
			//Return a success
			echo(1);
		}
		else{
			//Return a failure.
			echo(0);
		}
		
				
		
	
	
	mysqli_close($mysqli);

?>
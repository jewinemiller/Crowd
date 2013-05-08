<?php
	//Connect to the database
	require_once("../dataAuth.php");
	//Get the offset
	$offVal = $_GET['offset'];
	$offset = 25  * $offVal;
	//Should the page forward
	$shouldFwd = true;
	//Prepare the SQL to get Entries
	if($stmt = $mysqli->prepare("SELECT Title, ID, Date, User FROM Entries ORDER BY Date DESC LIMIT ? , 25")){
		$stmt->bind_param('i', $offset);
		$stmt->execute();
		$stmt->bind_result($titles, $ids, $dates, $users);
		$i = 0; 
		//Get all of the entries and pass the data to the proper arrays. 
		while($stmt->fetch()){
			$title[$i] = strip_tags($titles);
			$date[$i] = strip_tags($dates); 
			$id[$i] = strip_tags($ids);
			$user[$i] = strip_tags($users);
			$i++;
			
		}
		//If there are less than 25 elements, you can't go forward. 
		if($i < 25){
			$shouldFwd = false;
		}
		$stmt->close();
	}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Crowd</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="../css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }

    </style>
    <link href="../css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

	<script type="text/javascript">
		//Opens the page of the user entered in the text box. 
		function openUserPage(){
			var page = document.getElementById('username').value;
			window.location = "user.php?user=" + page; 
		}
	</script>
   
  </head>

<body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="../index.html">Crowd</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li><a href="../index.html">Home</a></li>
             	<li><a href="../post.html">Post</a></li>
	     	<li class="active"><a href="browse.php">Browse</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

	<div class="container"><h2 class="page-header">Browse Posts</h2>
		<h4>Browse a User's Posts</h4>
		<div class="row">
			<div class="span5"><input type="text" placeholder="User Name" class ="span5" id="username"></div>
			<button class="btn btn-primary span2" type="button" onclick="openUserPage()">Browse</button> 
			
		</div>

		<h4>Last 25 Topics</h4>
		
		<?php
			//Previous and next offset
			$prevOff = $offVal - 1;
			$nextOff = $offVal + 1;
			//Print the data to the table. 
			echo('<table class="table table-striped">');
			for($j = 0; $j < $i; $j++){
				$openCode = "entry.php?id=" . $id[$j];
				echo("<tr><td><div class='row'><span class='span5'><a href='" . $openCode . "' id='extLink' style='display:block;'>" . $title[$j] . "</a></span>");
				echo("<span class='span3 pull-left'>Posted: "  . $date[$j] . "</span>");
				echo("<span class='span3 pull-right'>By: "  . $user[$j] . "</span></div></td></tr>");
			}
			echo('</table>');
			//The next and previous buttons
			echo('<ul class="pager">');
			//If you can go back, enable the back button
			if($offset > 0){
				echo('<li><a href="browse.php?offset=' . $prevOff . '">Previous</a></li>');
			}
			//If you can go forward, enable the next button. 
			if($shouldFwd == true){
				echo('<li><a href="browse.php?offset=' . $nextOff . '">Next</a></li>');
			}
			echo('</ul>');

		?>
	</div>

</body>

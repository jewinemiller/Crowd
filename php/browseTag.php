<?php
	//Connect to the database
	require_once("../dataAuth.php");
	//Get the tag that was clicked
	$tag = $_GET['tag'];

	//Prepare the SQL to select every post where that tag was used
	if($stmt = $mysqli->prepare("SELECT Entries.Title, Entries.Date, Entries.User, Entries.ID FROM Entries JOIN Tags ON Entries.ID = Tags.ID WHERE Tags.Tag=?")){
		$stmt->bind_param('s', $tag);
		$stmt->execute();
		$stmt->bind_result($title, $date, $user, $id);
		$i = 0;
		//Get all of the data and pass it to the correct arrays 
		while($stmt->fetch()){
			$titles[$i] = $title;
			$dates[$i] = $date;
			$users[$i] = $user;
			$ids[$i] = $id;
			$i++;
		}
		$stmt->close();
		
	}
	else{
		echo("Fail");
	}
	mysqli_close($mysqli);
	
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
	     	<li><a href="browse.php">Browse</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

	<div class="container"><h2 class="page-header">Posts Using <?php echo($tag); ?></h2>
		<h4>Posts</h4>
		<table class="table table-striped">
		<?php
			//Print out all of the entries that used the selected tag. 
			for($j = 0; $j < $i; $j++){
				$openCode = "entry.php?id=" . $ids[$j];
				echo("<tr><td><div class='row'><span class='span5'><a href='" . $openCode . "' id='extLink' style='display:block;'>" . $titles[$j] . "</a></span>");
				echo("<span class='span3 pull-left'>Posted: "  . $dates[$j] . "</span>");
				echo("<span class='span3 pull-right'>By: "  . $users[$j] . "</span></div></td></tr>");
			}

		?></table>
	</div>
	
	
</body>

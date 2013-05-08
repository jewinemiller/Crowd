

<?php
	//Connect to the Database
	require_once("../dataAuth.php");
	
	//Get the username and offset number that were passed
	$username = strip_tags($_GET['user']);
	$offVal = $_GET['offset'];
	//Calculate the display offset (Which posts are shown)
	$offset = 25  * $offVal;
	//Whether or not the page should display the "Next" option
	$shouldFwd = true;

	//Prepare the SQL Statement
	if($stmt = $mysqli->prepare("SELECT Title, ID, Date FROM Entries WHERE User=? ORDER BY Date DESC LIMIT ?, 25")){
		$stmt->bind_param('si', $username, $offset);
		$stmt->execute();
		$stmt->bind_result($titles, $ids, $dates);

		$i = 0; 
		//Get all of the values that the query returned and send them to the appropriate arrays. 
		while($stmt->fetch()){
			$title[$i] = strip_tags($titles);
			$id[$i] = strip_tags($ids);
			$date[$i] = strip_tags($dates); 
			$i++;
			
		}
		//If there were less than 25 entries loaded, disable next. 
		if($i < 25){
			$shouldFwd = false; 
		}
		$stmt->close();
	}
	else{echo("Fail");}

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

	#extLink{
		cursor:pointer;
		display:block;
	}
    </style>
    <link href="../css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

   
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

    <div class="container">
			<div class="page-header">
				<?php echo("<h3>" . $username . "</h3>") ?>
			</div>
			<h4>Entries</h4>
			<table class="table table-striped">
				<?php
					if(count($title) > 0){
						for($j = 0; $j < $i; $j++){
							//Code that will trigger when an entry is clicked
							$openCode = "entry.php?id=" . $id[$j];
							//Add each entry to the table with Title, Date, and User. 
							echo("<tr><td><div class'row'><span class='span5'><a href='" . $openCode . "' id='extLink'>" . $title[$j] . "</a></span>");
							echo("<span class='span5 pull-right'>Posted: "  . $date[$j] . "</span></div></td></tr>");
						}
					}
		
				?>
			</table>
			<?php
					//Code for Next and previous buttons
					echo('<ul class="pager">');
					//Previous offset and next offset
					$prevOff = $offVal - 1;
					$nextOff = $offVal + 1; 
					//If the offset is greater than 0, you can go backward. 
					if($offset > 0){
						echo('<li><a href="user.php?user=' . $username . '&offset=' . $prevOff . '">Previous</a></li>');
					}
					
					//If the page can go forward, display the next button.
					if($shouldFwd == true){
						echo('<li><a href="user.php?user=' . $username . '&offset=' . $nextOff . '">Next</a></li>');
					}
					echo('</ul>');

			?>

	  
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>
  

  </body>
</html>

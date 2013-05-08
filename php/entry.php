<?php
	//Connect to the database
	require_once("../dataAuth.php");
	//Get the post ID
	$id = $_GET['id'];
	//Get the title and text of the post
	if($stmt = $mysqli->prepare("SELECT Title, Text FROM Entries WHERE ID=?")){
		
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->bind_result($title, $text);
		$stmt->fetch();
		$stmt->close();
		$title = strip_tags($title);
		$text = strip_tags($text);
	}
		
	//Get all of the tags from the post.
	if($stmt = $mysqli->prepare("SELECT Tag FROM Tags WHERE ID=?")){
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->bind_result($tags);
		while($stmt->fetch()){
			$tag[$tags] = strip_tags($tags);
		}
		$stmt->close();
		$i = count($tag);
	}

	//Get all of the comments from the post
	if($stmt = $mysqli->prepare("SELECT User, Text, Score, ID FROM Comments WHERE Parent=? ORDER BY Score DESC")){
		$j = 0;
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->bind_result($users, $comments, $scores, $cIDs);
		while($stmt->fetch()){
			$score[$j] = $scores; 
			$user[$j] = strip_tags($users);
			$comment[$j] = strip_tags($comments);
			$cID[$j] = $cIDs;
			$j++;
		}
		$stmt->close();

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

	#btnComment{
		position:absolute;
		right: 0;
		bottom: 0;
		padding-right: 10px;
		padding-bottom: 10px;
	}

	#contentContainer{
		position: relative;
	}
	
	.commentItem{
		padding-left: 20px;
		vertical-align:middle;
		clear:both;
	}
	.commentHead{
		padding-left: 20px;
		float: left;
		vertical-align:middle;
	}

	.tag{
		cursor:hand;
		cursor:pointer;
		margin-right: 5px;
	}
	
	.points{

		float: right;
	}
	
	.marginRight{
		margin-right: 5px; 
	}

    </style>
    <link href="../css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

	
   	<script type="text/javascript">
		//Used when you need to navigate to a specific tag.
		function navTab(target){
			window.location="browseTag.php?tag=" + target;
			
		}
		//Used to change the score of a target comment.
		function changePoints(target, pointInc, oldPoints, user){
			//Run the file score.php
			var request = $.ajax({
				type:"POST",
				url: "score.php",
				data: {cID: target, vUser: user, score: pointInc}});
			request.done(function(msg) {
 				//If the score has successfully been updated, update the on-page score. 
				if(msg==1){
					var totalPoints = pointInc + oldPoints;
					document.getElementById(target).innerHTML = totalPoints + " Points";
				} 
			});
			
			request.fail(function(jqXHR, textStatus) {
  					alert( "Request failed: " + textStatus );
			});
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

    <div class="container">
			<h2 name="title" class="page-header"><?php echo($title); ?></h2>
			<div name="content" class="hero-unit" id="contentContainer"><?php echo($text); ?>
				<div id="btnComment"><?php echo('<a href="comment.php?parent=' . $id . '" class="btn btn-primary btn-large">Comment &raquo;</a>'); ?></div></div>
			
			<h4>Tags</h4>
			<div name="tags"><?php 
					if($i > 0){
					//Print out all of the tags
					//If a tag is the null string, don't print it.
					foreach($tag as &$value){
						if(strcmp($value, "") != 0){
							echo("<span class='label label-info tag' onclick='navTab(\"" . $value . "\")'>" . $value . "</span>");
						}
					}
					}
					//If there were no tags, let the user know. 
					else{
						echo('<span class="label label-important">No Tags Entered</span>');
					}
			?></div>

			
			<h3>Comments</h3>
			<div id="commentcontainer">
				<?php if($j > 0){
					//Print out all of the comments on the page. 
					for($num = 0; $num < $j; $num++){
						echo("<h5 class='commentHead'>$user[$num] Said:</h5>");
						echo("<div class='points row'><b class='marginRight' id='" . $cID[$num] . "'>$score[$num] Points</b>");
						echo("<button class='btn btn-success btn-mini marginRight' type='button' onclick = 'changePoints(" . $cID[$num] . ",1," . $score[$num] . ",\"" . strip_tags($_SERVER['WEBAUTH_USER']) . "\");'>+</button>");
						echo("<button class='btn btn-danger btn-mini marginRight' type='button' onclick = 'changePoints(" . $cID[$num] . ",-1," . $score[$num] . ",\"" . strip_tags($_SERVER['WEBAUTH_USER']) . "\");'>-</button></div>");
						echo("<div class='well commentItem'>$comment[$num]</div>");
					}
				}
				else{
					echo("<div>No Comments Yet</div>");
				} ?>
			</div>	  
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>
  

  </body>
</html>

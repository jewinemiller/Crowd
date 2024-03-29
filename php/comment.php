<?php
	//Get the parent entry. 
	$parent = $_GET['parent'];

?>

	<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Comment</title>
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
	<h2 class="page-header">Comment</h2>
		<div class="well span9"><form action="enterComment.php" method="POST">
			<label>Comment</label>
			<textarea class="field span9" rows="10" name="text"></textarea>
			<?php echo('<input type="hidden" name="parent" value=' . $parent . '">'); ?>
<input type="hidden" name="user" value="<?php echo(strip_tags($_SERVER['WEBAUTH_USER'])); ?>" />
			<div><button class="btn btn-primary btn-large" type="submit">Post Comment</button></div>
		</form></div>
	  
	  
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>
  

  </body>
</html>

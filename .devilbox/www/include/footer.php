<nav class="navbar navbar-full footer navbar-dark bg-inverse">
	<div class="container">
		<ul class="nav navbar-nav">
		<li class="nav-item nav-link">Render time: <?php echo round((microtime(true) - $TIME_START), 2); ?> sec</li>
		<li class="nav-item float-xs-right"><a class="nav-link" href="/credits.php"><code>Credits</code></a></li>
		<li class="nav-item float-xs-right"><a class="nav-link" href="https://github.com/cytopia/devilbox"><code>Github</code></a></li>
	</div>
</nav>

<?php if ($GLOBALS['MY_MYSQL_LINK']) { my_mysqli_close($GLOBALS['MY_MYSQL_LINK']); } ?>

<?php /*
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="/vendor/jquery/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/vendor/bootstrap/bootstrap.min.js"></script>
*/


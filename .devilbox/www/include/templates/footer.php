<nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse footer">
	<div class="container justify-content-end">
		<ul class="nav navbar-nav">
			<li class="nav-item nav-link">Render time: <?php echo round((microtime(true) - $TIME_START), 2); ?> sec</li>
			<li class="nav-item"><a class="nav-link" href="https://github.com/cytopia/devilbox"><code>Github</code></a></li>
			<li class="nav-item"><a class="nav-link" href="/credits.php"><code>Credits</code></a></li>
			<li class="nav-item"><a class="nav-link" href="/debug.php"><code>Debug</code></a></li>
		</ul>
	</div>
</nav>

<script src="/vendor/jquery/jquery-3.1.1.slim.min.js"></script>
<script src="/vendor/tether/tether.min.js"></script>
<script src="/vendor/bootstrap/bootstrap.min.js"></script>

<footer class="footer">
	<div class="container">
		<p class="text-muted">Render time: <?php echo round((microtime(true) - $TIME_START), 2); ?> sec</p>
	</div>
</footer>
<?php my_mysqli_close($GLOBALS['MY_MYSQL_LINK']); ?>
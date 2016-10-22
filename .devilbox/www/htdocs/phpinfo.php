<?php require '../config.php'; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require '../include/head.php'; ?>
	</head>

	<body>
		<?php require '../include/navigation.php'; ?>

		<div class="container">

			<div class="row">
				<div class="col-md-12">
					<?php phpinfo(); ?>
				</div>
			</div>
		</div><!-- /.container -->

		<?php require '../include/footer.php'; ?>
		<script>
		// self executing function here
		(function() {
			// your page initialization code here
			// the DOM will be available here
		})();
		</script>
	</body>
</html>

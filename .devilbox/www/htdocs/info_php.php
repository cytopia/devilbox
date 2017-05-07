<?php require '../config.php'; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require '../include/head.php'; ?>
	</head>

	<body>
		<?php require '../include/navbar.php'; ?>

		<div class="container">

			<div class="row">
				<div class="col-md-12">
					<style>
						/* prevent hhvm phpinfo() from shrinking the width */
						body {width: 100% !important;}
					</style>
					<?php phpinfo(); ?>
				</div>
			</div>
		</div><!-- /.container -->

		<?php require '../include/footer.php'; ?>
	</body>
</html>

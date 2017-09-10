<?php require '../config.php'; ?>
<?php loadClass('Helper')->authPage(); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php echo loadClass('Html')->getHead(); ?>
	</head>

	<body>
		<?php echo loadClass('Html')->getNavbar(); ?>

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

		<?php echo loadClass('Html')->getFooter(); ?>
	</body>
</html>

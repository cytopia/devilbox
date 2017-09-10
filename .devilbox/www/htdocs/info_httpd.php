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

			<h1>Httpd info</h1>
			<br/>
			<br/>

			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<iframe style="width:100%; height:100vh; position:relative;" src="/devilbox-httpd-status" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>

		</div><!-- /.container -->

		<?php echo loadClass('Html')->getFooter(); ?>
	</body>
</html>

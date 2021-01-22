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
						body {width: 100% !important;}
					</style>
					<?php if (($version = phpversion('xdebug')) === false): ?>
						<p><code>xdebug</code> module is not enabled.</p>
					<?php elseif (!function_exists('xdebug_info')): ?>
						<p>Xdebug info only available with <code>xdebug 3.x.x</code> or greater. Your version is <code>xdebug <?php echo $version;?></code></p>
					<?php else:?>
						<?php xdebug_info(); ?>
					<?php endif; ?>
				</div>
			</div>
		</div><!-- /.container -->

		<?php echo loadClass('Html')->getFooter(); ?>
	</body>
</html>

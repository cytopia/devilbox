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

			<h1>Redis Keys</h1>
			<br/>
			<br/>

			<div class="row">
				<div class="col-md-12">

					<?php if (!loadClass('Redis')->isAvailable()): ?>
						<p>Redis container is not running.</p>
					<?php else: ?>
						<table class="table table-striped ">
							<thead class="thead-inverse ">
								<tr>
									<th>DB</th>
									<th>Key</th>
									<th>Value</th>
								</th>
							</thead>
							<tbody>
								<?php foreach (loadClass('Redis')->getKeys() as $db_name => $keys): ?>
									<tr class="table-info">
										<th colspan="3">
											<?php echo $db_name;?>
										</th>
									</tr>
									<?php foreach ($keys as $key=> $val): ?>
										<tr>
											<td></td>
											<td><?php echo $key;?></td>
											<td><code><?php print_r($val);?></code></td>
										</tr>
									<?php endforeach; ?>

								<?php endforeach; ?>
							</tbody>
						</table>
					<?php endif; ?>

				</div>
			</div>

		</div><!-- /.container -->

		<?php echo loadClass('Html')->getFooter(); ?>
	</body>
</html>

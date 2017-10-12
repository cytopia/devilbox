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

			<h1>RabbitMQ Info</h1>
			<br/>
			<br/>

			<div class="row">
				<div class="col-md-12">
					<?php if (!loadClass('Rabbit')->isAvailable()): ?>
						<p>RabbitMQ container is not running.</p>
					<?php else: ?>
						<table class="table table-striped">
							<thead class="thead-inverse">
								<tr>
									<th>Variable</th>
									<th>Value</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach (loadClass('Rabbit')->getInfo() as $key => $val): ?>
									<tr>
										<td><?php echo $key;?></td>
										<td class="break-word"><code><?php print_r($val);?></code></td>
									</tr>
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

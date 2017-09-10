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

			<h1>MongoDB Databases</h1>
			<br/>
			<br/>

			<div class="row">
				<div class="col-md-12">

					<?php if (!loadClass('Mongo')->isAvailable()): ?>
						<p>MongoDB container is not running.</p>
					<?php else: ?>
						<table class="table table-striped ">
							<thead class="thead-inverse ">
								<tr>
									<th>Name</th>
									<th>Size</th>
									<th>Empty</th>
								</th>
							</thead>
							<tbody>
								<?php foreach (loadClass('Mongo')->getDatabases() as $db): ?>
									<tr>
										<td><?php echo $db['name'];?></td>
										<td><?php echo round($db['size']/(1024*1024), 2);?> MB</td>
										<td><?php echo $db['empty'];?></td>
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

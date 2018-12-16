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

			<h1>Memcached Keys</h1>
			<br/>
			<br/>

			<div class="row">
				<div class="col-md-12">

					<?php if (!loadClass('Memcd')->isAvailable()): ?>
						<p>Memcached container is not running.</p>
					<?php else: ?>
						<table class="table table-striped ">
							<thead class="thead-inverse ">
								<tr>
									<th>Key</th>
									<th>Size</th>
									<th>TTL</th>
									<th>Value</th>
								</th>
							</thead>
							<tbody>
								<?php foreach (loadClass('Memcd')->getKeys() as $data): ?>
									<tr>
										<td><?php print_r($data['key']);?></td>
										<td><?php print_r($data['size']);?></td>
										<td><?php print_r($data['ttl']);?></td>
										<td><?php print_r($data['val']);?></td>
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

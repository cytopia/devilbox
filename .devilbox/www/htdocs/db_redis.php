<?php require '../config.php'; ?>
<?php loadClass('Helper')->authPage(); ?>
<?php
if (isset($_GET['redisdb'])) {

	loadClass('Redis')->flushDB($_GET['redisdb']);
	loadClass('Helper')->redirect('/db_redis.php');
}
?>
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

			<?php $databases = array_keys(loadClass('Redis')->getKeys()); ?>
			<?php if (count($databases)): ?>
				<div class="row">
					<div class="col-md-12">
						<form class="form-inline">
							<div class="form-group">
								<label class="sr-only" for="redisdb">Redis DB</label>
								<select class="form-control" name="redisdb">
									<option value="" selected disabled>Select Redis DB</option>
									<?php foreach ($databases as $db): ?>
										<option value="<?php echo $db;?>"><?php echo $db;?></option>
									<?php endforeach; ?>
								</select>
							</div>&nbsp;&nbsp;
							<button type="submit" class="btn btn-primary">Flush database</button>
						</form>
						<br/>
					</div>
				</div>
			<?php endif; ?>

			<div class="row">
				<div class="col-md-12">

					<?php if (!loadClass('Redis')->isAvailable()): ?>
						<p>Redis container is not running.</p>
					<?php else: ?>
						<table class="table table-striped ">
							<tbody>
								<?php $redisKeys = loadClass('Redis')->getKeys(); ?>
								<?php if (count($redisKeys)): ?>
									<?php foreach ($redisKeys as $db_name => $keys): ?>
										<tr class="thead-inverse ">
											<th colspan="5">
												Database: <?php echo $db_name; ?>&nbsp;&nbsp;&nbsp;&nbsp;
												Items: <?php echo count($keys); ?>
											</th>
										</th>
										<tr class="table-info">
											<tr>
												<th>DB</th>
												<th>Key</th>
												<th>Expires</th>
												<th>Type</th>
												<th>Value</th>
											</th>
										</tr>
										<?php foreach ($keys as $key): ?>
											<tr>
												<td><?php echo $db_name;?></td>
												<td class="break-word" style="width:30%;"><?php echo $key['name'];?></td>
												<td><?php echo $key['ttl'];?>s</td>
												<td><?php echo $key['type'];?></td>
												<td class="break-word">
													<code>
														<?php echo htmlentities($key['val']);?>
													</code>
												</td>
											</tr>
										<?php endforeach; ?>

									<?php endforeach; ?>
								<?php else: ?>
									<p>No keys set.</p>
								<?php endif; ?>
							</tbody>
						</table>
					<?php endif; ?>

				</div>
			</div>

		</div><!-- /.container -->

		<?php echo loadClass('Html')->getFooter(); ?>
	</body>
</html>

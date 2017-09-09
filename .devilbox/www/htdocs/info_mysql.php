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

			<h1>MySQL Info</h1>
			<br/>
			<br/>

			<div class="row">
				<div class="col-md-12">

					<?php if (!loadClass('Mysql')->isAvailable()): ?>
						<p>MySQL container is not running.</p>
					<?php else: ?>
						<p>For reference see here:</p>
						<ul>
							<li><a target="_blank" href="https://dev.mysql.com/doc/refman/5.5/en/server-system-variables.html">https://dev.mysql.com/doc/refman/5.5/en/server-system-variables.html</a></li>
							<li><a target="_blank" href="https://dev.mysql.com/doc/refman/5.6/en/server-system-variables.html">https://dev.mysql.com/doc/refman/5.6/en/server-system-variables.html</a></li>
							<li><a target="_blank" href="https://dev.mysql.com/doc/refman/5.7/en/server-system-variables.html">https://dev.mysql.com/doc/refman/5.7/en/server-system-variables.html</a></li>
						</ul>

						<table class="table table-striped">
							<thead class="thead-inverse">
								<tr>
									<th>Variable</th>
									<th>Value</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach (loadClass('Mysql')->getConfig() as $key => $val): ?>
									<tr>
										<td><?php echo $key;?></td>
										<td class="break-word"><code><?php echo $val;?></code></td>
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

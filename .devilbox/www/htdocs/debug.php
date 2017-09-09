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

			<h1>Devilbox Debug</h1>
			<br/>
			<br/>

			<div class="row">
				<div class="col-md-12">

				<?php $errors = loadClass('Logger')->getAll(); ?>
				<?php if ($errors === false): ?>
						<p>Writing to logfile is not possible. Errors will be sent as mail instead. Check the mail section.</p>
					<?php elseif (count($errors) === 0): ?>
						<p>No errors detected.</div>
					<?php else: ?>
						<?php $total = count($errors); ?>
						<table class="table table-striped">
							<thead class="thead-inverse">
								<tr>
									<th>#</th>
									<th>date</th>
									<th>Errors (<?php echo $total;?>)</th>
								</tr>
							</thead>
							<tbody>
								<?php for ($i=($total-1); $i>=0; --$i): ?>
									<tr>
										<th><?php echo ($i+1);?></th>
										<th><code><?php echo $errors[$i]['date'];?></code></th>
										<th><code><?php echo $errors[$i]['head'];?></code></th>
										<?php if (isset($errors[$i]['body'])): ?>
											</tr>
											<tr>
												<td colspan="3">
												<?php
													$dump = implode('', $errors[$i]['body']);
													//$dump = str_replace("=&gt;</font> \n", '=&gt;</font>', $dump);

												?>
													<code><?php echo $dump; ?></code>
												</td>
											</tr>
										<?php endif; ?>
									</tr>
								<?php endfor; ?>
							</tbody>
						</table>
					<?php endif; ?>

				</div>
			</div>

		</div><!-- /.container -->

		<?php echo loadClass('Html')->getFooter(); ?>
	</body>
</html>

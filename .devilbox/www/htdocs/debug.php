<?php require '../config.php'; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require '../include/head.php'; ?>
	</head>

	<body>
		<?php require '../include/navigation.php'; ?>

		<div class="container">

			<h1>Devilbox Debug</h1>
			<br/>
			<br/>

			<div class="row">
				<div class="col-md-12">

					<?php $errors = $Logger->getAll(); ?>
					<?php if ($errors === false): ?>
						<p>Writing to logfile is not possible. Errors will be sent as mail instead. Check the mail section.</p>
					<?php else: ?>
						<?php $total = count($errors); ?>
						<table class="table table-striped">
							<thead class="thead-inverse">
								<tr>
									<th>#</th>
									<th>Errors (<?php echo $total;?>)</th>
								</tr>
							</thead>
							<tbody>
								<?php for ($i=($total-1); $i>=0; --$i): ?>
									<tr>
										<td><?php echo ($i+1);?></td>
										<td><code><?php echo $errors[$i];?></code></td>
									</tr>
								<?php endfor; ?>
							</tbody>
						</table>
					<?php endif; ?>

				</div>
			</div>

		</div><!-- /.container -->

		<?php require '../include/footer.php'; ?>
	</body>
</html>

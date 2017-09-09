<?php require '../config.php'; ?>
<?php loadClass('Helper')->authPage(); ?>
<?php
// Also required for JS calls (see bottom of this page)
$len_table = 4;
$len_size = 9;
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php echo loadClass('Html')->getHead(); ?>
	</head>

	<body>
		<?php echo loadClass('Html')->getNavbar(); ?>

		<div class="container">

			<h1>PostgreSQL Databases</h1>
			<br/>
			<br/>

			<div class="row">
				<div class="col-md-12">

					<?php if (!loadClass('Pgsql')->isAvailable()): ?>
						<p>PgSQL container is not running.</p>
					<?php else: ?>
						<table class="table table-striped ">
							<thead class="thead-inverse ">
								<tr>
									<th>Name</th>
									<th>Charset</th>
									<th>Collation</th>
									<th>Tables</th>
									<th>Size</th>
								</th>
							</thead>
							<tbody>
								<?php foreach (loadClass('Pgsql')->getDatabases() as $name => $database): ?>
									<tr class="table-info">
										<th>
											<?php echo $name;?>
										</th>
										<td><?php echo $database['charset'];?></td>
										<td><?php echo $database['collation'];?></td>
										<td colspan="2"></td>
									</tr>
									<?php foreach ($database['schemas'] as $schema => $data): ?>
										<tr>
											<td><?php echo $schema;?></td>
											<td colspan="2"></td>
											<td><code><span class="table" id="table-<?php echo $name.'-'.$schema;?>"><?php echo str_repeat('&nbsp;', $len_table);?></span></code></td>
											<td><code><span class="size" id="size-<?php echo $name.'-'.$schema;?>"><?php echo str_repeat('&nbsp;', $len_size);?></span></code></td>
										</tr>
									<input type="hidden" name="schema[]" data-database="<?php echo $name;?>" class="schema" value="<?php echo $schema;?>" />
									<?php endforeach; ?>

								<?php endforeach; ?>
							</tbody>
						</table>
					<?php endif; ?>

				</div>
			</div>

		</div><!-- /.container -->

		<?php echo loadClass('Html')->getFooter(); ?>
		<script>
		// self executing function here
		(function() {
			// your page initialization code here
			// the DOM will be available here

			function updateData(database, schema) {
				var xhttp = new XMLHttpRequest();

				xhttp.onreadystatechange = function() {
					var res = null;
					var size = 0;
					var tables = 0;
					var i;

					if (this.readyState == 4 && this.status == 200) {
						res = JSON.parse(this.response);

						// Normalize size output
						size = res.size == 0 ? '0sss MB' : res.size + ' MB';
						if (size.length < <?php echo $len_size;?>) {
							for (i = size.length; i < <?php echo $len_size;?>; ++i) {
								size = '&nbsp;' + size;
							}
						}
						size = size.replace('sss', '&nbsp;&nbsp;&nbsp;');

						// Normalize tables output
						tables = res.table;
						if (tables.length < <?php echo $len_table;?>) {
							for (i = tables.length; i < <?php echo $len_table;?>; ++i) {
								tables = '&nbsp;' + tables;
							}
						}

						document.getElementById('size-' + database + '-' + schema).innerHTML = size;
						document.getElementById('table-' + database + '-' + schema).innerHTML = tables;
					}
				};
				xhttp.open('GET', '_ajax_callback.php?type=postgres&database=' + database + '&schema=' + schema, true);
				xhttp.send();
			}

			var schemas = document.getElementsByName('schema[]');
			var schema;
			var database;

			for (i = 0; i < schemas.length; i++) {
				schema = schemas[i].value;
				database = schemas[i].getAttribute('data-database');
				updateData(database, schema);
			}
		})();
		</script>

	</body>
</html>

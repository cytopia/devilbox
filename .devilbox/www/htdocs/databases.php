<?php $CONNECT = TRUE; require '../config.php'; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require '../include/head.php'; ?>
	</head>

	<body>
		<?php require '../include/navigation.php'; ?>

		<div class="container">

			<h1>Databases</h1>

			<div class="row">
				<div class="col-md-12">

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
							<?php foreach (getDatabases() as $name => $keys): ?>
								<tr>
									<td><?php echo $name;?></td>
									<td><?php echo $keys['charset'];?></td>
									<td><?php echo $keys['collation'];?></td>
									<td><code><span class="table" id="table-<?php echo $name;?>">&nbsp;&nbsp;&nbsp;&nbsp;</span></code></td>
									<td><code><span class="size" id="size-<?php echo $name;?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></code></td>
								</tr>
								<input type="hidden" name="database[]" class="database" value="<?php echo $name;?>" />
							<?php endforeach; ?>
						</tbody>
					</table>

				</div>
			</div>

		</div><!-- /.container -->

		<?php require '../include/footer.php'; ?>
		<script>
		// self executing function here
		(function() {
			// your page initialization code here
			// the DOM will be available here

			function updateSizes(database) {
				var xhttp = new XMLHttpRequest();

				xhttp.onreadystatechange = function() {
					var fill = '';
					var res = '';
					var len;
					var i;

					if (this.readyState == 4 && this.status == 200) {
						res	= (this.responseText) == 0 ? '0sss MB' : this.responseText+' MB';
						len		= res.length;
						if (len < 9) {
							for (i=len; i<9; i++) {
								fill = '&nbsp;' + fill;
							}

						}
						res = res.replace('sss', '&nbsp;&nbsp;&nbsp;');
						res = fill + res;
						document.getElementById('size-' + database).innerHTML = res;
					}
				};
				xhttp.open('GET', '_ajax_db.php?size=' + database, true);
				xhttp.send();
			}


			function updateCount(database) {
				var xhttp = new XMLHttpRequest();

				xhttp.onreadystatechange = function() {
					var fill = '';
					var i;

					if (this.readyState == 4 && this.status == 200) {
						if (this.responseText.length < 4) {
							for (i=this.responseText.length; i<4; i++) {
								fill = '&nbsp;' + fill;
							}
						}
						document.getElementById('table-' + database).innerHTML = fill + this.responseText;
					}
				};
				xhttp.open('GET', '_ajax_db.php?table=' + database, true);
				xhttp.send();
			}

			var databases = document.getElementsByName('database[]');
			var database;

			for (i = 0; i < databases.length; i++) {
				database = databases[i].value;
				updateSizes(database);
				updateCount(database);
			}
		})();
		</script>

	</body>
</html>

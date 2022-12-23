<?php require '../config.php';  ?>
<?php loadClass('Helper')->authPage(); ?>
<?php if ( isset($_POST['watcherd']) && $_POST['watcherd'] == 'reload' ) {
	loadClass('Helper')->exec('supervisorctl -c /etc/supervisor/custom.d/supervisorctl.conf restart watcherd');
	sleep(1);
	loadClass('Helper')->redirect('/cnc.php');
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php echo loadClass('Html')->getHead(true); ?>
	</head>

	<body>
		<?php echo loadClass('Html')->getNavbar(); ?>

		<div class="container">
			<h1>Command & Control</h1>
			<br/>
			<br/>

			<div class="row">
				<div class="col-md-12">

					<?php
						$status_w = loadClass('Helper')->exec('supervisorctl -c /etc/supervisor/custom.d/supervisorctl.conf status watcherd');
						$status_h = loadClass('Helper')->exec('supervisorctl -c /etc/supervisor/custom.d/supervisorctl.conf status httpd');

						$words = preg_split("/\s+/", $status_w);
						$data_w = array(
							'name' => $words[0],
							'state' => $words[1],
							'pid' => preg_replace('/,$/', '', $words[3]),
							'uptime' => $words[5],
						);
						$words = preg_split("/\s+/", $status_h);
						$data_h = array(
							'name' => $words[0],
							'state' => $words[1],
							'pid' => preg_replace('/,$/', '', $words[3]),
							'uptime' => $words[5],
						);
					?>
					<h3>Daemon overview</h3><br/>
					<p>If you made a change to any vhost settings, you can trigger a manual reload here.</p>
					<table class="table table-striped">
						<thead class="thead-inverse">
							<tr>
								<th>Daemon</th>
								<th>Status</th>
								<th>Pid</th>
								<th>Uptime</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php echo $data_w['name']; ?></td>
								<td><?php echo $data_w['state']; ?></td>
								<td><?php echo $data_w['pid']; ?></td>
								<td><?php echo $data_w['uptime']; ?></td>
								<td><form method="post"><button type="submit" name="watcherd" value="reload" class="btn btn-primary">Reload</button></form></td>
							</tr>
							<tr>
								<td><?php echo $data_h['name']; ?></td>
								<td><?php echo $data_h['state']; ?></td>
								<td><?php echo $data_h['pid']; ?></td>
								<td><?php echo $data_h['uptime']; ?></td>
								<td></td>
							</tr>
						</tbody>
					</table>
					<br/>
					<br/>

					<h3>watcherd stderr</h3>
					<br/>
					<?php
						$output = loadClass('Helper')->exec('supervisorctl -c /etc/supervisor/custom.d/supervisorctl.conf  tail -1000000 watcherd stderr');
						echo '<pre>' . $output . '</pre>';
					?>
					<h3>watcherd stdout</h3>
					<br/>
					<?php
						$output = loadClass('Helper')->exec('supervisorctl -c /etc/supervisor/custom.d/supervisorctl.conf  tail -1000000 watcherd');
						echo '<pre>' . $output . '</pre>';
					?>

				</div>
			</div>

		</div><!-- /.container -->

		<?php echo loadClass('Html')->getFooter(); ?>
		<script>
		$(function() {
			$('.subject').click(function() {
				const id = ($(this).attr('id'));
				const row = $('#mail-'+id);
				row.toggle();

				const bodyElement = row.find('.email-body')[0];
				if(bodyElement.shadowRoot !== null){
					// We've already fetched the message content.
					return;
				}

				bodyElement.attachShadow({ mode: 'open' });
				bodyElement.shadowRoot.innerHTML = 'Loading...';

				$.get('?get-body=' + id, function(response){
					response = JSON.parse(response);
					row.find('.raw-email-body').html(response.raw);

					const body = response.body;
					if(body === null){
						row.find('.alert').show();
					}
					else{
						bodyElement.shadowRoot.innerHTML = body;
					}
				})
			})
			// Handler for .ready() called.
		});
		</script>
	</body>
</html>

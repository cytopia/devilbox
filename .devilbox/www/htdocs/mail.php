<?php
require '../config.php';

if (isset($_GET['email']) && isset($_GET['subject']) && isset($_GET['message'])) {
	$mail = $_GET['email'];
	$subj = $_GET['subject'];
	$mess = $_GET['message'];
	mail($mail, $subj, $mess);
	header('Location: /mail.php');
}




require '../include/vendor/Mail/Mbox.php';
require '../include/vendor/Mail/mimeDecode.php';

$params['include_bodies'] = true;
$params['decode_bodies']  = true;
$params['decode_headers'] = true;
$params['crlf']           = "\r\n";

$mbox = new Mail_Mbox('/var/mail/mailtrap');

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require '../include/head.php'; ?>
	</head>

	<body>
		<?php require '../include/navigation.php'; ?>

		<div class="container">

			<h1>Mail</h1>
			<br/>
			<br/>

			<div class="row">
				<div class="col-md-12">
					<h3>Send test Email</h3>
					<br/>
				</div>
			</div>


			<div class="row">
				<div class="col-md-12">

					<form class="form-inline">
						<div class="form-group">
							<label class="sr-only" for="exampleInputEmail1">Email to</label>
							<input name="email" type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter to email">
						</div>

						<div class="form-group">
							<label class="sr-only" for="exampleInputEmail2">Subject</label>
							<input name="subject" type="text" class="form-control" id="exampleInputEmail2" placeholder="Subject">
						</div>

						<div class="form-group">
							<label class="sr-only" for="exampleInputEmail3">Message</label>
							<input name="message" type="text" class="form-control" id="exampleInputEmail3" placeholder="Message">
						</div>

						<button type="submit" class="btn btn-primary">Send Email</button>
					</form>
					<br/>
					<br/>

				</div>
			</div>


			<div class="row">
				<div class="col-md-12">
					<h3>Received Emails</h3>
					<br/>
				</div>
			</div>


			<div class="row">
				<div class="col-md-12">

					<?php $mbox->open(); ?>
					<table class="table table-striped table-hover">
						<thead class="thead-inverse">
							<tr>
								<th>#</th>
								<th style="word-break: normal;">Date</th>
								<th>To</th>
								<th>Subject</th>
							</tr>
						</thead>
						<tbody>
							<?php for ($n = ($mbox->size()-1); $n >= 0; $n--): ?>
								<?php
									$message = $mbox->get($n);
									$decode = new Mail_mimeDecode($message, "\r\n");
									$structure = $decode->decode($params);
								 ?>
								<tr id="<?php echo $n;?>" class="subject">
									<td><?php echo $n;?></td>
									<td>
										<?php echo date('H:i', strtotime($structure->headers['date']));?><br/>
										<small><?php echo date('Y-m-d', strtotime($structure->headers['date']));?></small>
									</td>
									<td><?php echo $structure->headers['x-original-to'];?></td>
									<td><?php echo $structure->headers['subject'];?></td>
								</tr>
								<tr></tr>
								<tr id="mail-<?php echo $n;?>" style="display:none">
									<td></td>
									<td colspan="3">
										<pre><?php echo $message;?></pre>
									</td>
								</tr>
							<?php endfor; ?>
						</tbody>
					</table>
					<?php $mbox->close(); ?>
				</div>
			</div>

		</div><!-- /.container -->

		<?php require '../include/footer.php'; ?>
		<script>
		$(function() {
			$('.subject').each(function() {
				$(this).click(function() {
					var id = ($(this).attr('id'));
					$('#mail-'+id).toggle();

				})
			})
			// Handler for .ready() called.
		});
		</script>
	</body>
</html>

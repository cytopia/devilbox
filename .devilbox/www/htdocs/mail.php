<?php require '../config.php';  ?>
<?php loadClass('Helper')->authPage(); ?>
<?php

//
// $_POST submit for sending a test email
//
if (isset($_POST['email']) && isset($_POST['subject']) && isset($_POST['message'])) {
	$mail = $_POST['email'];
	$subj = $_POST['subject'];
	$mess = $_POST['message'];
	if (! mail($mail, $subj, $mess)) {
		loadClass('Logger')->error('Could not send mail to: '.$mail.' | subject: '.$subj);
	}
	header('Location: /mail.php');
	exit();
}

//
// Includes
//
require $VEN_DIR . DIRECTORY_SEPARATOR . 'Mail' . DIRECTORY_SEPARATOR .'Mbox.php';
require $VEN_DIR . DIRECTORY_SEPARATOR . 'Mail' . DIRECTORY_SEPARATOR .'mimeDecode.php';
require $LIB_DIR . DIRECTORY_SEPARATOR . 'Mail.php';
require $LIB_DIR . DIRECTORY_SEPARATOR . 'Sort.php';

if (isset($_GET['get-body']) && is_numeric($_GET['get-body'])) {
	$messageNumber = $_GET['get-body'];
	$MyMbox = new \devilbox\Mail('/var/mail/devilbox');
	$message = $MyMbox->getMessage($messageNumber-1);
	$structure = $message['decoded'];

	$body = null;
	if (isset($structure->body)) {
		$body = $structure->body;
	}
	elseif(isset($structure->parts[1]->body)) {
		$body = $structure->parts[1]->body;
	}
	elseif(isset($structure->parts[0]->body)) {
		$body = $structure->parts[0]->body;
	}

	exit(json_encode(array(
		'raw' => htmlentities($message['raw']),
		'body' => $body,
	)));
}

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
	$message = $_GET['delete'];
	$MyMbox = new \devilbox\Mail('/var/mail/devilbox');
	$MyMbox->delete($message);
	header('Location: /mail.php');
	exit();
}



//
// Setup Sort/Order
//

// Sort/Order settings
$defaultSort	= array('sort' => 'date', 'order' => 'DESC');
$allowedSorts	= array('date', 'subject', 'x-original-to', 'from');
$allowedOrders	= array('ASC', 'DESC');
$GET_sortKeys	= array('sort' => 'sort', 'order' => 'order');

// Get sort/order
$MySort = new \devilbox\Sort($defaultSort, $allowedSorts, $allowedOrders, $GET_sortKeys);
$sort = $MySort->getSort();
$order = $MySort->getOrder();

// Evaluate Sorters/Orderers
$orderDate	= '<a href="/mail.php?sort=date&order=ASC"><i class="fa fa-sort" aria-hidden="true"></i></a>';
$orderFrom	= '<a href="/mail.php?sort=from&order=ASC"><i class="fa fa-sort" aria-hidden="true"></i></a>';
$orderTo	= '<a href="/mail.php?sort=x-original-to&order=ASC"><i class="fa fa-sort" aria-hidden="true"></i></a>';
$orderSubj	= '<a href="/mail.php?sort=subject&order=ASC"><i class="fa fa-sort" aria-hidden="true"></i></a>';

if ($sort == 'date') {
	if ($order == 'ASC') {
		$orderDate = '<a href="/mail.php?sort=date&order=DESC"><i class="fa fa-sort" aria-hidden="true"></i></a> <i class="fa fa-sort-numeric-asc" aria-hidden="true"></i>';
	} else {
		$orderDate = '<a href="/mail.php?sort=date&order=ASC"><i class="fa fa-sort" aria-hidden="true"></i></a> <i class="fa fa-sort-numeric-desc" aria-hidden="true"></i> ';
	}
} else if ($sort == 'subject') {
	if ($order == 'ASC') {
		$orderSubj = '<a href="/mail.php?sort=subject&order=DESC"><i class="fa fa-sort" aria-hidden="true"></i></a> <i class="fa fa-sort-alpha-asc" aria-hidden="true"></i>';
	} else {
		$orderSubj = '<a href="/mail.php?sort=subject&order=ASC"><i class="fa fa-sort" aria-hidden="true"></i></a> <i class="fa fa-sort-alpha-desc" aria-hidden="true"></i>';
	}
} else if ($sort == 'x-original-to') {
	if ($order == 'ASC') {
		$orderTo = '<a href="/mail.php?sort=x-original-to&order=DESC"><i class="fa fa-sort" aria-hidden="true"></i></a> <i class="fa fa-sort-alpha-asc" aria-hidden="true"></i>';
	} else {
		$orderTo = '<a href="/mail.php?sort=x-original-to&order=ASC"><i class="fa fa-sort" aria-hidden="true"></i></a> <i class="fa fa-sort-alpha-desc" aria-hidden="true"></i>';
	}
} else if ($sort == 'from') {
	if ($order == 'ASC') {
		$orderFrom = '<a href="/mail.php?sort=from&order=DESC"><i class="fa fa-sort" aria-hidden="true"></i></a> <i class="fa fa-sort-alpha-asc" aria-hidden="true"></i>';
	} else {
		$orderFrom = '<a href="/mail.php?sort=from&order=ASC"><i class="fa fa-sort" aria-hidden="true"></i></a> <i class="fa fa-sort-alpha-desc" aria-hidden="true"></i>';
	}
}


//
// Mbox Reader
//
$MyMbox = new \devilbox\Mail('/var/mail/devilbox');

// If default sort is on, use NULL, so we do not have to sort the mails after retrieval,
// because they are being read in the default sort/order anyway
$sortOrderArr = $MySort->isDefault($sort, $order) ? null : array($sort => $order);
$messages = $MyMbox->get($sortOrderArr);

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php echo loadClass('Html')->getHead(true); ?>
	</head>

	<body>
		<?php echo loadClass('Html')->getNavbar(); ?>

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

					<form method="post" class="form-inline">
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
							<textarea name="message" rows="1" class="form-control" id="exampleInputEmail3" placeholder="Message"></textarea>
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
					<table class="table table-striped table-hover">
						<thead class="thead-inverse">
							<tr>
								<th>#</th>
								<th>Date <?php echo $orderDate;?></th>
								<th>From <?php echo $orderFrom;?></th>
								<th>To <?php echo $orderTo;?></th>
								<th>Subject <?php echo $orderSubj;?></th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($messages as $data): ?>
								<?php
									$message = htmlentities($data['raw']);
									$structure = $data['decoded'];
								 ?>
								<tr id="<?php echo $data['num'];?>" class="subject">
									<td><?php echo $data['num'];?></td>
									<td>
										<?php echo date('H:i', strtotime($structure->headers['date']));?><br/>
										<small><?php echo date('Y-m-d', strtotime($structure->headers['date']));?></small>
									</td>
									<td><?php echo htmlentities($structure->headers['from']);?></td>
									<td><?php echo htmlentities($structure->headers['x-original-to']);?></td>
									<td><?php echo htmlentities($structure->headers['subject']);?></td>
									<td><a href="/mail.php?delete=<?php echo $data['num']-1;?>" title="Delete Email"><i class="fa fa-trash"></i></a></td>
								</tr>
								<tr></tr>
								<tr id="mail-<?php echo $data['num'];?>" style="display:none">
									<td></td>
									<td colspan="5">
										<div class="email-body"></div>
										<div class="alert alert-warning" role="alert" style="display:none">
											No valid body found
										</div>
										<hr>
										<p><a class="btn btn-primary" data-toggle="collapse" href="#email-<?php echo $data['num'];?>" aria-expanded="false" aria-controls="email-<?php echo $data['num'];?>">Raw source</a></p>
										<div class="collapse" id="email-<?php echo $data['num'];?>"><pre class="raw-email-body"></pre></div>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
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

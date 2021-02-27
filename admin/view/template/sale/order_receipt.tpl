<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">

<head>
	<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<title>
		<?php echo $title_receipt; ?>
	</title>
	<base href="<?php echo $base; ?>" />
	<link href="view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="all" />
	<script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
	<link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
	<link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="all" />
	<link type="text/css" href="view/stylesheet/print.css" rel="stylesheet" media="all" />
	<style>
		@page {
			margin-top: 7mm;
		}
	</style>
</head>

<body>
	<div class="container">
		<?php if ($preview) { ?>
		<div id="background">
			<p class="bg-text" id="bg-receipt">
				<?php echo $text_mark; ?>
			</p>
		</div>
		<?php } ?>
		<?php for ($x = 0; $x < 2; $x++) { ?>
		<div style="page-break-after: always;" class="<?= $letter_content; ?> <?= $x ? 'screen-hide' : ''; ?>">
			<div class="col-xs-3">
				<div class="text-center"><img src="<?php echo $store_logo; ?>" />
					<h3>
						<?php echo $store_name; ?>
					</h3>
					<small>
						<?php echo $store_slogan; ?>
					</small>
				</div><br />
				<div>
					<?php echo $store_owner; ?><br />
					<?php echo $store_address; ?><br />
					<?php echo 't.&nbsp;' . $store_telephone; ?><br />
					<?php echo 'e.&nbsp;' . $store_email; ?><br /><br />
					<?php if ($store_fax) { ?>
					<?php echo 'f. ' . $store_fax; ?><br />
					<?php } ?>
					<?php echo $store_url; ?>
				</div>
			</div>
			<div class="col-xs-9">
				<h2>
					<?php echo $title_receipt; ?>
					<?php if ($invoice_no) { ?>
					<span class="small">
						<?php echo $text_invoice_no; ?>
						<?php echo $invoice_no; ?>
					</span>
					<?php } ?>
				</h2>
				<table class="table table-receipt">
					<tbody>
						<tr>
							<td style="width: 22%;">
								<?php echo $text_subject; ?>
							</td>
							<td style="width: 1%;">:</td>
							<td style="width: 77%;">
								<?php echo $name; ?>
							</td>
						</tr>
						<tr>
							<td>
								<?php echo $text_address; ?>
							</td>
							<td>:</td>
							<td>
								<?php echo $address; ?>
							</td>
						</tr>
						<tr>
							<td>
								<?php echo $text_attn; ?>
							</td>
							<td>:</td>
							<td class="text-justify">
								<?php echo $text_hal; ?>
							</td>
						</tr>
						<tr>
							<td class="text-center" colspan="4">
								<h5>
									<?php echo $text_sejumlah; ?>
								</h5>
								<b>
									<h3>
										<?php echo $amount; ?>
									</h3>
								</b>
								<h5>
									<?php echo $terbilang; ?>
								</h5>
							</td>
						</tr>
						<tr>
							<td colspan="4">
							</td>
						</tr>
					</tbody>
				</table>
				<table class="table table-application text-center">
					<tbody>
						<tr>
							<td style="width: 50%;"></td>
							<td>
								<?php echo $date; ?>
							</td>
						</tr>
						<tr>
							<td>
								<?php echo $text_pihak; ?>
							</td>
							<td>
								<?php echo $store_owner; ?><br /><br /><br /><br /><br /><br /><br />
							</td>
						</tr>
						<tr>
							<td><u>
									<?php echo $tanda_tangan; ?>
								</u><br />
								<?php echo $company; ?>
							</td>
							<td><u>
									<?php echo $manajemen; ?>
								</u><br />
								<?php echo $text_manajemen; ?>
							</td>
						</tr>
						<?php if ($notes) { ?>
						<tr class="text-left">
							<td colspan="2">
								<?php foreach ($notes as $note) { ?>
								<br />
								<?php echo $note; ?>
								<?php } ?>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
		<?php } ?>
	</div>
</body>

</html>
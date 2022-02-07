<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">

<head>
	<meta charset="UTF-8" />
	<title>
		<?php echo $title_admission; ?>
	</title>
	<base href="<?php echo $base; ?>" />
	<link href="view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="all" />
	<script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
	<link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
	<link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="all" />
	<link type="text/css" href="view/stylesheet/print.css" rel="stylesheet" media="all" />
  <!-- <style> -->
    <!-- @page { -->
      <!-- margin-top: 75mm; -->
    <!-- } -->
  <!-- </style> -->
</head>

<body>
	<div class="container">
		<?php if ($preview) { ?>
		<div id="background">
			<p class="bg-text" id="bg-admission">
				<?php echo $text_mark; ?>
			</p>
		</div>
		<?php } ?>
		<?php for ($x = 0; $x < 2; $x++) { ?>
		<div style="page-break-after: always;" class="<?= $letter_content; ?> <?= $x ? 'visible-print' : ''; ?>">
			<div class="letter-head">
				<img src="<?php echo $letter_head; ?>" class="img-responsive" />
			</div>
			<div>
				<table class="table table-application">
					<tbody>
						<tr>
							<td colspan="4" class="text-center">
								<h3 class="text-center">
									<?php echo $title_admission; ?>
								</h3>
								<h4>
									<?php echo $text_reference ?>
									<?php echo $reference ?>
								</h4>
							</td>
						</tr>
						<tr>
							<td colspan="4">
								<?php echo $text_sehubungan; ?>
							</td>
						</tr>
						<tr>
							<td style="width: 1%;"></td>
							<td style="width: 30%;">
								<?php echo $text_day_date; ?>
							</td>
							<td style="width: 1%;">:</td>
							<td style="width: 68%;">
								<?php echo $event_date; ?>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<?php echo $text_package; ?>
							</td>
							<td>:</td>
							<td>
								<?php echo $package; ?>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<?php echo $text_venue; ?>
							</td>
							<td>:</td>
							<td>
								<?php echo $venue; ?>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<?php echo $text_slot; ?>
							</td>
							<td>:</td>
							<td>
								<?php echo $slot; ?>
							</td>
						</tr>
						<tr>
							<td colspan="4">
								<br />
								<?php echo $text_dengan_ini; ?>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<?php echo $text_vendor_name; ?>
							</td>
							<td>:</td>
							<td>
								<?php echo $vendor_name; ?>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<?php echo $text_vendor_type; ?>
							</td>
							<td>:</td>
							<td>
								<?php echo $vendor_type; ?>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<?php echo $text_address; ?>
							</td>
							<td>:</td>
							<td>
								<?php echo $address; ?>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<?php echo $text_telephone; ?>
							</td>
							<td>:</td>
							<td>
								<?php echo $telephone; ?>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<?php echo $text_contact_person; ?>
							</td>
							<td>:</td>
							<td>
								<?php echo $contact_person; ?>
							</td>
						</tr>
						<tr>
							<td colspan="4" class="text-justify">
								<br />
								<?php echo $text_persiapan; ?>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<?php echo $text_day_date; ?>
							</td>
							<td>:</td>
							<td>
								<?php echo $preparation_date; ?>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<?php echo $text_time; ?>
							</td>
							<td>:</td>
							<td>
								<?php echo $preparation_time; ?>
							</td>
						</tr>
						<tr>
							<td colspan="4" class="text-justify">
								<br />
								<?php echo $text_demikian_2; ?><br />
							</td>
						</tr>
					</tbody>
				</table>
				<table class="table table-application text-center">
					<tbody>
						<tr>
							<td style="width: 50%;"></td>
							<td>
								<?php echo $store_owner; ?><br /><br /><br /><br /><br /><br />
							</td>
						</tr>
						<tr>
							<td></td>
							<td><u>
									<?php echo $manajemen; ?>
								</u><br />
								<?php echo $text_manajemen; ?>
							</td>
						</tr>
						<tr>
							<td class="text-left" colspan="2">
								<br />
								<?php echo $text_catatan; ?><br />
							</td>
						</tr>
						<tr>
							<td class="text-left" colspan="2">
								<ul>
									<li>
										<?php echo $text_lampirkan; ?>
									</li>
								</ul>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<?php } ?>
	</div>
</body>

</html>
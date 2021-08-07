<!DOCTYPE html>
<html dir="<?= $direction; ?>" lang="<?= $lang; ?>">

<head>
	<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<title>
		<?= $title_checklist; ?>
	</title>
	<base href="<?= $base; ?>" />
	<link href="view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="all" />
	<script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
	<link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
	<link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="all" />
	<link type="text/css" href="view/stylesheet/print.css" rel="stylesheet" media="all" />
</head>

<body>
	<div class="container">
		<div>
			<div class="clearfix">
				<div class="col-xs-4">
					<div class="text-center"><img src="<?= $store_logo; ?>" />
						<h3>
							<?= $store_name; ?>
						</h3>
						<small>
							<?= $store_slogan; ?>
						</small>
					</div><br />
					<div>
						<?= $store_owner; ?><br />
						<?= $store_address; ?><br />
						<?= 't.&nbsp;' . $store_telephone; ?><br />
						<?= 'e.&nbsp;' . $store_email; ?><br /><br />
						<?php if ($store_fax) { ?>
						<?= 'f. ' . $store_fax; ?><br />
						<?php } ?>
						<?= $store_url; ?>
					</div>
				</div>
				<div class="col-xs-8 mt-2">
					<h2>
						<?= $title_checklist; ?>
						<?php if ($reference) { ?>
						<div class="small">
							<?= $text_reference; ?>
							<?= $reference; ?>
						</div>
						<?php } ?>
					</h2>
					<hr>
					<table class="table table-application">
						<tbody>
							<tr>
								<td style="width: 25%;" rowspan="7">
								</td>
								<td colspan="3">
									<?= $text_order_detail; ?>
								</td>
							</tr>
							<tr>
								<td style="width: 29%;">
									<?= $text_event_title; ?>
								</td>
								<td style="width: 1%;">:</td>
								<td style="width: 45%;">
									<?= $event_title; ?>
								</td>
							</tr>
							<tr>
								<td>
									<?= $text_package; ?>
								</td>
								<td>:</td>
								<td>
									<?= $package; ?>
								</td>
							</tr>
							<tr>
								<td>
									<?= $text_venue; ?>
								</td>
								<td>:</td>
								<td>
									<?= $venue; ?>
								</td>
							</tr>
							<tr>
								<td>
									<?= $text_customer; ?>
								</td>
								<td>:</td>
								<td>
									<?= $customer; ?>
								</td>
							</tr>
							<tr>
								<td>
									<?= $text_event_date; ?>
								</td>
								<td>:</td>
								<td>
									<?= $event_date; ?>
								</td>
							</tr>
							<tr>
								<td>
									<?= $text_slot; ?>
								</td>
								<td>:</td>
								<td>
									<?= $slot; ?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="table-responsive">
				<legend>
					<?= $text_checklist_detail; ?>
				</legend>
				<table class="table table-bordered table-receipt text-left">
					<thead>
						<tr>
							<td style="width: 30%;">
								<?= $column_item; ?>
							</td>
							<td>
								<?= $column_description; ?>
							</td>
							<td class="text-center">
								<?= $column_check; ?>
							</td>
							<td style="width: 30%;">
								<?= $column_remark; ?>
							</td>
						</tr>
					</thead>
					<tbody style="height:500px">
						<?php if ($products) { ?>
						<?php if (isset($product_primary['attribute'])) { ?>
						<?php foreach ($product_primary['attribute'] as $attribute_group => $attributes) { ?>
						<tr>
							<td colspan="2">
								<?= $attribute_group; ?>
							</td>
							<td class="text-center align-middle"><i class="fa fa-square-o fa-lg"></i></td>
							<td></td>
						</tr>
						<?php foreach ($attributes as $attribute) { ?>
						<tr class="text-italic">
							<td>&nbsp;&nbsp;-&nbsp;
								<?= $attribute['name']; ?>
							</td>
							<td>
								<?= $attribute['value']; ?>
							</td>
							<td class="text-center align-middle"><i class="fa fa-square-o fa-lg"></i></td>
							<td></td>
						</tr>
						<?php } ?>
						<?php } ?>
						<?php } ?>
						<?php foreach ($products['included'] as $product) { ?>
						<tr>
							<td colspan="2">
								<?= $product['name'] . ' - ' . $product['quantity']; ?>
							</td>
							<td class="text-center align-middle"><i class="fa fa-square-o fa-lg"></i></td>
							<td></td>
						</tr>
						<?php foreach ($product['attribute'] as $attribute_group => $attributes) { ?>
						<?php foreach ($attributes as $attribute) { ?>
						<tr class="text-italic">
							<td>&nbsp;&nbsp;-&nbsp;
								<?= $attribute['name']; ?>
							</td>
							<td>
								<?= $attribute['value']; ?>
							</td>
							<td class="text-center"><i class="fa fa-square-o fa-lg"></i></td>
							<td></td>
						</tr>
						<?php } ?>
						<?php } ?>
						<?php foreach ($product['option'] as $option) { ?>
						<tr>
							<td>
								<?= $option['name']; ?>
							</td>
							<td>
								<?= $option['value']; ?>
							</td>
							<td class="text-center"><i class="fa fa-square-o fa-lg"></i></td>
							<td></td>
						</tr>
						<?php } ?>
						<?php } ?>
						<?php foreach ($products['additional'] as $product) { ?>
						<tr>
							<td colspan="2">
								<?= $product['name'] . ' - ' . $product['quantity']; ?>
							</td>
							<td class="text-center align-middle"><i class="fa fa-square-o fa-lg"></i></td>
							<td></td>
						</tr>
						<?php foreach ($product['attribute'] as $attribute_group => $attributes) { ?>
						<?php foreach ($attributes as $attribute) { ?>
						<tr class="text-italic">
							<td>&nbsp;&nbsp;-&nbsp;
								<?= $attribute['name']; ?>
							</td>
							<td>
								<?= $attribute['value']; ?>
							</td>
							<td class="text-center"><i class="fa fa-square-o fa-lg"></i></td>
							<td></td>
						</tr>
						<?php } ?>
						<?php } ?>
						<?php foreach ($product['option'] as $option) { ?>
						<tr>
							<td>
								<?= $option['name']; ?>
							</td>
							<td>
								<?= $option['value']; ?>
							</td>
							<td class="text-center"><i class="fa fa-square-o fa-lg"></i></td>
							<td></td>
						</tr>
						<?php } ?>
						<?php } ?>
						<tr style="height:40%">
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<?php } else { ?>
						<tr style="height:100%">
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<table class="table table-application text-center">
					<tbody>
						<tr>
							<td style="width: 75%;">
								<div class="div-comment text-left">
									<?= $text_comment; ?>
								</div>
							</td>
							<td style="width: 25%;">
								<br />
								<?= $text_inspector; ?><br /><br /><br /><br /><br />
								<?= $text_tanda_tangan; ?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<br>
		</div>
	</div>
</body>

</html>
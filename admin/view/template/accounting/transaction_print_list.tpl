<?= $header; ?>
<div id="content">
	<div class="page-header hidden-print">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="button" id="button-print" data-toggle="tooltip" title="<?= $button_print; ?>"
					class="btn btn-info"><i class="fa fa-print"></i></button>
				<a href="<?= $back; ?>" data-toggle="tooltip" title="<?= $button_back; ?>"
					class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
			<h1>
				<?= $heading_title; ?>
			</h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?= $breadcrumb['href']; ?>">
						<?= $breadcrumb['text']; ?>
					</a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-bar-chart"></i>
					<?= $text_print_list; ?>
				</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<td class="text-left">
									<?= $column_date; ?>
								</td>
								<td class="text-left">
									<?= $column_transaction_type; ?>
								</td>
								<td class="text-left">
									<?= $column_reference; ?>
								</td>
								<td class="text-left">
									<?= $column_description; ?>
								</td>
								<td class="text-left">
									<?= $column_customer_name; ?>
								</td>
								<td class="text-right">
									<?= $column_amount; ?>
								</td>
								<td class="text-left">
									<?= $column_account_debit; ?>
								</td>
								<td class="text-left">
									<?= $column_account_credit; ?>
								</td>
							</tr>
						</thead>
						<tbody>
							<?php if ($transactions) { ?>
							<?php foreach ($transactions as $transaction) { ?>
							<tr>
								<td class="text-left">
									<?= $transaction['date']; ?>
								</td>
								<td class="text-left">
									<?= $transaction['transaction_type']; ?>
								</td>
								<td class="text-left">
									<?= $transaction['reference']; ?>
								</td>
								<td class="text-left">
									<?= $transaction['description']; ?>
								</td>
								<td class="text-left">
									<?= $transaction['customer_name']; ?>
								</td>
								<td class="text-right">
									<?= $transaction['amount']; ?>
								</td>
								<td class="text-left">
									<?php foreach ($transaction['account']['debit'] as $account) { ?>
									<?= $account; ?><br>
									<?php } ?>
								</td>
								<td class="text-left">
									<?php foreach ($transaction['account']['credit'] as $account) { ?>
									<?= $account; ?><br>
									<?php } ?>
								</td>
							</tr>
							<?php } ?>
							<?php } else { ?>
							<tr>
								<td class="text-center" colspan="8">
									<?= $text_no_results; ?>
								</td>
							</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<td class="text-right" colspan="5">
									<?= $text_total; ?>
								</td>
								<td class="text-right">
									<?= $total; ?>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
				<div>
					<p>
						<?= $results; ?>
					</p>
					<p>
						<?= $signature; ?>
					</p>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$('#button-print').on('click', function () {
			print();
		});
	</script>
</div>
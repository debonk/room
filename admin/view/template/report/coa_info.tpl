<div class="table-responsive">
	<table class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<td class="text-left">
					<?php echo $column_account_id; ?>
				</td>
				<td class="text-left">
					<?php echo $column_name; ?>
				</td>
				<td class="text-left">
					<?php echo $column_type; ?>
				</td>
				<td class="text-right">
					<?php echo $column_debit; ?>
				</td>
				<td class="text-right">
					<?php echo $column_credit; ?>
				</td>
				<td class="text-right">
					<?php echo $column_balance; ?>
				</td>
			</tr>
		</thead>
		<tbody>
			<?php if ($accounts) { ?>
			<?php foreach ($components as $component) { ?>
				<tr>
					<th class="text-left" colspan="6">
						<?php echo $component['text']; ?>
					</th>
				</tr>
			<?php foreach ($accounts[$component['code']] as $account) { ?>
			<tr>
				<?php if ($account['header_status']) { ?>
				<th class="text-left" colspan="6">
					<?php echo $account['account_id'] . ' - ' . $account['name']; ?>
				</th>
				<?php } else { ?>
				<td class="text-right">
					<?php echo $account['account_id']; ?>
				</td>
				<td class="text-left">
					<?php echo $account['name']; ?>
				</td>
				<td class="text-left">
					<?php echo $account['type']; ?>
				</td>
				<td class="text-right">
					<?php echo $account['debit']; ?>
				</td>
				<td class="text-right">
					<?php echo $account['credit']; ?>
				</td>
				<td class="text-right"><b>
						<?php echo $account['balance']; ?>
					</b></td>
				<?php } ?>

				<!-- <td class="text-left"><a href="<?php echo $account['href']; ?>" target="_blank"><?php echo $account['reference']; ?></a><br><?php echo $account['description']; ?></td> -->
			</tr>
			<?php } ?>
			<?php } ?>
			<?php } else { ?>
			<tr>
				<td class="text-center" colspan="6">
					<?php echo $text_no_results; ?>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<div class="row">
	<div class="col-sm-6 text-left">
		<?php echo $pagination; ?>
	</div>
	<div class="col-sm-6 text-right">
		<?php echo $results; ?>
	</div>
</div>
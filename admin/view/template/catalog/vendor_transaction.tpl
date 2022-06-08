<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
		<td class="text-left"><?php echo $column_date; ?></td>
		<td class="text-left"><?php echo $column_transaction_type; ?></td>
		<td class="text-left"><?php echo $column_reference; ?></td>
		<td class="text-left"><?php echo $column_description; ?></td>
		<td class="text-left"><?php echo $column_payment_method; ?></td>
		<td class="text-right"><?php echo $column_amount; ?></td>
		<td class="text-right"><?php echo $column_username; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($transactions) { ?>
      <?php foreach ($transactions as $transaction) { ?>
      <tr>
		<td class="text-left"><?php echo $transaction['date']; ?></td>
		<td class="text-left"><?php echo $transaction['transaction_type']; ?></td>
		<td class="text-left"><a href="<?php echo $transaction['order_url']; ?>" target="_blank" rel="noopener noreferrer"><?php echo $transaction['reference']; ?></a></td>
		<td class="text-left"><?php echo $transaction['description']; ?></td>
		<td class="text-left"><?php echo $transaction['payment_method']; ?></td>
		<td class="text-right"><?php echo $transaction['amount']; ?></td>
		<td class="text-left"><?php echo $transaction['username']; ?></td>
      </tr>
      <?php } ?>
      <tr>
        <td class="text-right" colspan="5"><b><?php echo $text_balance; ?></b></td>
        <td class="text-right"><?php echo $balance; ?></td>
      </tr>
      <?php } else { ?>
      <tr>
        <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>

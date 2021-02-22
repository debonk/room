<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-transaction-type" data-toggle="tooltip" title="<?php echo $button_save; ?>"
					class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>"
					class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
			<h1>
				<?php echo $heading_title; ?>
			</h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>">
						<?php echo $breadcrumb['text']; ?>
					</a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>
			<?php echo $error_warning; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i>
					<?php echo $text_form; ?>
				</h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-transaction-type"
					class="form-horizontal">
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-name">
							<?php echo $entry_name; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>"
								id="input-name" class="form-control" />
							<?php if ($error_name) { ?>
							<div class="text-danger">
								<?php echo $error_name; ?>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-client-label">
							<?php echo $entry_client_label; ?>
						</label>
						<div class="col-sm-10">
							<select name="client_label" id="input-client-label" class="form-control">
								<option value="">
									<?php echo $text_select; ?>
								</option>
								<?php foreach ($clients_label as $label) { ?>
								<?php if ($label['value'] == $client_label) { ?>
								<option value="<?php echo $label['value']; ?>" selected="selected">
									<?php echo $label['text']; ?>
								</option>
								<?php } else { ?>
								<option value="<?php echo $label['value']; ?>">
									<?php echo $label['text']; ?>
								</option>
								<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-category-label">
							<?php echo $entry_category_label; ?>
						</label>
						<div class="col-sm-10">
							<select name="category_label" id="input-category-label" class="form-control">
								<option value="">
									<?php echo $text_select; ?>
								</option>
								<?php foreach ($categories_label as $label) { ?>
								<?php if ($label['value'] == $category_label) { ?>
								<option value="<?php echo $label['value']; ?>" selected="selected">
									<?php echo $label['text']; ?>
								</option>
								<?php } else { ?>
								<option value="<?php echo $label['value']; ?>">
									<?php echo $label['text']; ?>
								</option>
								<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-account-type">
							<?php echo $entry_account_type; ?>
						</label>
						<div class="col-sm-10">
							<select name="account_type" id="input-account-type" class="form-control">
								<?php if ($account_type == 'D') { ?>
								<option value="D" selected="selected">
									<?php echo $text_debit; ?>
								</option>
								<option value="C">
									<?php echo $text_credit; ?>
								</option>
								<?php } else { ?>
								<option value="D">
									<?php echo $text_debit; ?>
								</option>
								<option value="C" selected="selected">
									<?php echo $text_credit; ?>
								</option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-account-debit">
							<?php echo $entry_account_debit; ?>
						</label>
						<div class="col-sm-10">
							<select name="account_debit_id" id="input-account-debit" class="form-control">
								<option value="0">
									<?php echo $text_none; ?>
								</option>
								<?php foreach ($accounts as $account) { ?>
								<optgroup label="<?php echo $account['text']; ?>">
									<?php if ($account['child']) { ?>
									<?php foreach ($account['child'] as $child) { ?>
									<?php if ($child['account_id'] == $account_debit_id) { ?>
									<option value="<?php echo $child['account_id']; ?>" selected="selected">
										<?php echo $child['text']; ?>
									</option>
									<?php } else { ?>
									<option value="<?php echo $child['account_id']; ?>">
										<?php echo $child['text']; ?>
									</option>
									<?php } ?>
									<?php } ?>
									<?php } ?>
								</optgroup>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-account-credit">
							<?php echo $entry_account_credit; ?>
						</label>
						<div class="col-sm-10">
							<select name="account_credit_id" id="input-account-credit" class="form-control">
								<option value="0">
									<?php echo $text_none; ?>
								</option>
								<?php foreach ($accounts as $account) { ?>
								<optgroup label="<?php echo $account['text']; ?>">
									<?php if ($account['child']) { ?>
									<?php foreach ($account['child'] as $child) { ?>
									<?php if ($child['account_id'] == $account_credit_id) { ?>
									<option value="<?php echo $child['account_id']; ?>" selected="selected">
										<?php echo $child['text']; ?>
									</option>
									<?php } else { ?>
									<option value="<?php echo $child['account_id']; ?>">
										<?php echo $child['text']; ?>
									</option>
									<?php } ?>
									<?php } ?>
									<?php } ?>
								</optgroup>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-manual-select">
							<?php echo $entry_manual_select; ?>
						</label>
						<div class="col-sm-10">
							<?php if ($manual_select) { ?>
							<input type="checkbox" name="manual_select" value="1" id="input-manual-select" 
								checked="checked" />
							<?php } else { ?>
							<input type="checkbox" name="manual_select" value="1" id="input-manual-select" />
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-sort-order">
							<?php echo $entry_sort_order; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" name="sort_order" value="<?php echo $sort_order; ?>"
								placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php echo $footer; ?>
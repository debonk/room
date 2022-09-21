<!DOCTYPE html>
<html dir="<?= $direction; ?>" lang="<?= $lang; ?>">

<head>
	<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<title>
		<?= $heading_title; ?>
	</title>
	<base href="<?= $base; ?>" />
	<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2"
		crossorigin="anonymous"></script> -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<!-- <link href="view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="all" /> -->
	<!-- <script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script> -->
	<script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
	<link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
	<link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="all" />
	<!-- <link type="text/css" href="view/stylesheet/print.css" rel="stylesheet" media="all" /> -->
</head>

<body>
	<div class="container" style="width: 200mm; max-width: 768px;">
		<!-- <div> -->
		<div class="row">
			<div class="col-4">
				<div class="text-center"><img src="<?= $store_logo; ?>" />
					<h3>
						<?= $store_name; ?>
					</h3>
					<small>
						<?= $store_slogan; ?>
					</small>
				</div><br />
			</div>
			<div class="col-8 mt-auto">
				<h2 class="text-end">
					<?= $heading_title; ?>
				</h2>
				<hr>
			</div>
		</div>
		<?php if ($products) { ?>
		<div class="row row-cols-1 row-cols-sm-2 g-4">
			<?php foreach ($products as $product) { ?>
			<div class="col">
				<div class="card">
					<div class="card-body">
						<h3 class="card-title">
							<?= $product['name'] . ($product['venue'] ? ' (' . $product['venue'] . ')' : ''); ?>
						</h3>
						<p class="card-text fst-italic">
							<?php foreach ($product['include'] as $include) { ?>
							&nbsp;&nbsp;-&nbsp;
							<?= $include; ?>
							<br>
							<?php } ?>
							<?php foreach ($product['attribute'] as $attribute) { ?>
							&nbsp;&nbsp;-&nbsp;
							<?= $attribute['attribute'] . ': ' . $attribute['text']; ?>
							<br>
							<?php } ?>
						</p>
					</div>
					<div class="card-footer text-end text-muted">
						<?php if(!$product['special']) { ?>
						<h4>
							<?= $product['price']; ?>
						</h4>
						<?php } else { ?>
						<s>
							<?= $product['price']; ?>
						</s>
						<h4>
							<?= $product['special']; ?>
						</h4>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
</body>

</html>
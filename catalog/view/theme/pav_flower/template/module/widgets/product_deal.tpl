<?php
	$config = $sconfig;
	$theme  = $themename;
	$themeConfig = (array)$config->get('themecontrol');
	$listingConfig = array(
		'category_pzoom'        => 1,
		'quickview'             => 0,
		'show_swap_image'       => 0,
		'product_layout'		=> 'default',
		'enable_paneltool'	    => 0
	);
	$listingConfig = array_merge($listingConfig, $themeConfig );
	$categoryPzoom = $listingConfig['category_pzoom'];
	$quickview     = $listingConfig['quickview'];
	$swapimg       = $listingConfig['show_swap_image'];
	$categoryPzoom = isset($themeConfig['category_pzoom']) ? $themeConfig['category_pzoom']:0; 
 
 
	$productLayout = DIR_TEMPLATE.$theme.'/template/common/product/deal_default.tpl';	
	
	$id = rand(1,9)+rand(); 

 	$ourl = $this->registry->get('url');
 	$objlang = $this->registry->get('language');
	$load = $this->registry->get("load");
	$language = $load->language("module/themecontrol");
	$text_sale = $language['text_sale'];
	$text_days = $language['text_days'];
	$text_hours = $language['text_hours'];
	$text_minutes = $language['text_minutes'];
	$text_seconds = $language['text_seconds'];
	$text_finish = $language['text_finish'];
?>

<div class="productdeals panel panel-default <?php echo $addition_cls; ?> productcarousel">
	<?php if( $show_title ) { ?>
  <div class="widget-heading panel-heading block-border"><h3 class="panel-title"><span><?php echo $heading_title?></span></h3></div>
	<?php } ?>
	<div class="widget-inner">
		


		<div class="box-products listcarousel<?php echo $id;?> carousel slide " id="pavdeals<?php echo $id;?>">
			<?php if( count($products) > $itemsperpage ) { ?>
				<div class="carousel-controls carousel-center">
					<a class="carousel-control left" href="#pavdeals<?php echo $id;?>"   data-slide="prev"><i class="fa fa-long-arrow-left"></i></a>
					<a class="carousel-control right" href="#pavdeals<?php echo $id;?>"  data-slide="next"><i class="fa fa-long-arrow-right"></i></a>
				</div> 
			<?php } ?>
			<div class="carousel-inner">	
			 <?php
				$pages = array_chunk( $products, $itemsperpage);
			 ?>

		  <?php foreach ($pages as  $k => $tproducts ) {   ?>
        <div class="item <?php if($k==0) {?>active<?php } ?> products-block">
          <?php foreach( $tproducts as $i => $product ) {  $i=$i+1;?>
            <?php if( $i%$cols == 1 || $cols == 1) { ?>
            <div class="row products-row <?php ;if($i == count($tproducts) - $cols +1) { echo "last";} ?>"><?php //start box-product?>
            <?php } ?>

            <?php
              if ((12 % $cols) == 0) {
                $span = 12 / $cols;
              } else {
                $span = intval(100 / $cols);          
              }
            ?>  
              <div class="col-sm-4 col-xs-12 col-lg-<?php echo $span;?> col-md-<?php echo $span;?> <?php if($i%$cols == 0) { echo "last";} ?> product-col">
                <?php require( $productLayout );  ?>
              </div>

            <?php if( $i%$cols == 0 || $i==count($tproducts) ) { ?>
            </div><?php //end box-product?>
            <?php } ?>
          <?php } //endforeach; ?>
        </div>
      <?php } ?>

			</div> 
		</div>
	</div>
</div>


 <!-- javascript -->
<script type="text/javascript">
$(function () {
$('#pavdeals<?php echo $id;?> a:first').tab('show');
})
$('.listcarousel<?php echo $id;?>').carousel({interval:false,auto:false,pause:'hover'});
</script>
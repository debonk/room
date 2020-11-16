<?php
    $config 		= $sconfig;
    $theme  		= $themename;
	$cols 			= isset($customcols)? $customcols : 3;
	$span 			= 12/$cols;
	$id = md5(rand()+time()+(int)$heading_title);
    $themeConfig = (array)$config->get('themecontrol');
    $listingConfig = array(
        'category_pzoom'        => 1,
        'show_swap_image'       => 0,
        'quickview'             => 0,
        'product_layout'        => 'default',
        'catalog_mode'          => '',
    );
    $listingConfig              = array_merge($listingConfig, $themeConfig );
    $categoryPzoom              = $listingConfig['category_pzoom'];
    $quickview                  = $listingConfig['quickview'];
    $swapimg                    = ($listingConfig['show_swap_image'])?'swap':'';

    // Product Layout
    if( isset($_COOKIE[$theme.'_productlayout']) && $listingConfig['enable_paneltool'] && $_COOKIE[$theme.'_productlayout'] ){
        $listingConfig['product_layout'] = trim($_COOKIE[$theme.'_productlayout']);
    }
    $productLayout = DIR_TEMPLATE.$theme.'/template/common/product/'.$listingConfig['product_layout'].'.tpl';
    if( !is_file($productLayout) ){
        $listingConfig['product_layout'] = 'default';
    }
    $productLayout = DIR_TEMPLATE.$theme.'/template/common/product/'.$listingConfig['product_layout'].'.tpl'; 
?>
<div class="panel-heading space-20">
	<h4 class="panel-title panel-v2"><?php echo $heading_title?></h4>
</div>
<div class="products-owl-carousel owl-carousel-play" id="wrap<?php echo $id; ?>" data-ride="owlcarousel">
    <?php
    if(count($products)>$cols){
    ?>
    <div class="carousel-style carousel-controls-v3">
        <a class="carousel-control left" href="#image-additional" data-slide="next">
			<i class="fa fa-angle-left ltr"></i>
			<i class="fa fa-angle-right rtl"></i>
        </a>
        <a class="carousel-control right" href="#image-additional" data-slide="prev">
			<i class="fa fa-angle-right ltr"></i>
			<i class="fa fa-angle-left rtl"></i>
        </a>
    </div>
    <?php } ?>
	<div class="products-block products-owl owl-carousel" id="<?php echo $id; ?>" data-show="4" data-pagination="false" data-navigation="true">
		<?php foreach ($products as $i => $product) { $i=$i+1; ?>
			<div class="product-col-wrap products-row"><div class="product-col">
				<?php require( $productLayout );  ?>
			</div></div>
		<?php } ?>
	</div>
	 <!-- Controls -->
</div>
 <script type="text/javascript">
	$(document).ready(function() {
	   var $carousel =  $("#<?php echo $id; ?>"); 
	   $carousel.owlCarousel({
	        autoPlay: false, //Set AutoPlay to 3 seconds
	        items : <?php echo $cols; ?>,
	        lazyLoad : true,
			navigation: false,
			navigationText:false,
			rewindNav: false,
			pagination:false
 
	    }); 
	    $("#wrap<?php echo $id; ?> .carousel-control.left").click(function(){
	         $carousel.trigger('owl.prev');
	    })
	   $("#wrap<?php echo $id; ?> .carousel-control.right").click(function(){
	        $carousel.trigger('owl.next');
	    })
	});
</script>
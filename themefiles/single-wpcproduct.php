<?php get_header(); ?>

<!--Content-->
<div class="content-middle">
<div class="titulo-seccion" style="margin-bottom:0;">
		<img src="<? echo get_stylesheet_directory_uri(); ?>/images/icono-sm-tumpar.png"/>
		<h1 id="titulo-empresa"><? the_title(); ?></h1>
	</div><!-- titulo-seccion -->
	<div style="clear:both;"></div>
	<!-- <h2><? the_title(); ?></h2> -->
<div id="content" role="main">			
    	<div id="wpc-catalogue-wrapper">

        <!--col-2-->

        <div id="wpc-col-2">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php 
			$img1		=	get_post_meta($post->ID,'product_img1',true);
			$img2		=	get_post_meta($post->ID,'product_img2',true);
			$img3		=	get_post_meta($post->ID,'product_img3',true);
		?>	 
        <div id="wpc-product-gallery">
        <?php 
			if(get_option('image_height')){
				$img_height	=	get_option('image_height');
			}else{
				$img_height	=	348;
			}
			if(get_option('image_width')){
				$img_width	=	get_option('image_width'); 
			}else{
				$img_width	=	490;
			}
			$icroping	=	get_option('croping');
		?>
        <div class="product-img-view" style="width:<?php echo $img_width; ?>px; height:<?php echo $img_height; ?>px;">
        <img src="<?php echo $img1; ?>" alt="" id="img1" height="<?php echo $img_height; ?>" width="<?php echo $img_width; ?>" />
        <img src="<?php echo $img2; ?>" alt="" id="img2" height="<?php echo $img_height; ?>" width="<?php echo $img_width; ?>" style="display:none;" />
        <img src="<?php echo $img3; ?>" alt="" id="img3" height="<?php echo $img_height; ?>" width="<?php echo $img_width; ?>" style="display:none;"  />
        </div>
        <div class="wpc-product-img">
        <?php if($img1): ?>
        <div class="new-prdct-img"><img src="<?php echo $img1; ?>" alt="" width="151" id="img1" /></div>
		<?php endif; if($img2): ?>
        <div class="new-prdct-img"><img src="<?php echo $img2; ?>" alt="" width="151" id="img2"/></div>
		<?php endif; if($img3):?>
        <div class="new-prdct-img"><img src="<?php echo $img3; ?>" alt="" width="151" id="img3"/></div>
		<?php endif; ?>
        </div>
        <div class="clear"></div>
        </div>
        <?php $product_price = get_post_meta($post->ID, 'product_price', true); ?>
        <h4>Características  <?php if($product_price): ?><span class="product-price">Price: <span><?php echo $product_price; ?></span></span><?php endif; ?></h4>
<article class="post">
		<div class="entry-content"> 
			<?php the_content(); ?>
        <?php
			if(get_option('next_prev')==1){
		echo '<p class="wpc-next-prev">';
		previous_post_link('%link', 'Anterior');
		next_post_link('%link', 'Siguiente');
		echo '</p>';
		
	
		}
		?>
        </div>

</article>
        <?php endwhile; endif; ?>
        </div>
        <!--/col-2-->
    <div class="clear"></div>    
    </div>
</div>
</div>
  <!--/Content-->
<?php get_footer();
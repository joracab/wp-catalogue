<?php get_header(); ?>

<!--Content-->
<div class="content-middle">
	<div class="titulo-seccion">
		<img src="<? echo get_stylesheet_directory_uri(); ?>/images/icono-sm-tumpar.png"/>
		<h1 id="titulo-empresa">Resultados</h1>
	</div><!-- titulo-seccion -->
	<div style="clear:both;"></div>
	<!-- <h2><? the_title(); ?></h2> -->
<div id="content" role="main"> 
<?php wowslider(6); ?>
	<?php echo do_shortcode('[wp-catalogue]'); ?>
<div class="clear"></div>
</div>
</div>
<!--/Content-->

<?php get_footer(); ?>

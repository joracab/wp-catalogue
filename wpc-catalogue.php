<?php


//Example
function arrow_pagination($pages = '', $range = 2)
{  
$showitems = ($range * 2)+1;  

     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   

     if(1 != $pages)
     {
         echo "<div class='pagination'>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo;</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a>";

         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
             }
         }

         if ($paged < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a>";  
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>&raquo;</a>";
         echo "</div>\n";
     }
}
 
function wp_catalogue_breadcumb(){
	
    $catalogue_page_url	=	get_option('catalogue_page_url');
	 $terms	=	get_terms('wpccategories');
		global $post;
		$terms1 = get_the_terms($post->id, 'wpccategories');
		if($terms1){
		foreach( $terms1 as $term1 ){
			$slug	= $term1->slug;
			$tname	=	$term1->name;
			$cat_url	=	get_bloginfo('siteurl').'/wpccategories/'.$slug;
		};
	}

		if(is_single()){
			$pname	=	'>> '.get_the_title();	
		}
	
	echo '<div class="wp-catalogue-breadcrumb"> <a href="'.$catalogue_page_url.'">All Products</a> >> <a href="'.$cat_url.'">'.$tname.'</a>  ' . $pname . '</div>' ;
	
}

function wp_catalog_select($taxonomy,$parent)
{
$terms = get_terms($taxonomy, array('parent' => $parent, 'hide_empty' => false));
	//$out.='<select name="fil_tipo" class="select-filtro" id="fil_tipo">';
		//     $out.='<option class="wpc-category ' . $class . '">All Products</a></option>';	
			//$taxonid=$term->term_taxonomy_id;	
		  // '</a></option>'; 	
	
	if(count($terms) > 0)
    {
	
        //Displaying as a list
    $out.='<select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" name="fil_tipo" class="select-filtro" id="fil_tipo" style="font-size:12px; background-color:#202020; border:none; color:#FFF; margin-bottom:15px;">';
        $out.='<option class="wpc-category ' . $class . '">Todos</a></option>';	
		//Cycle though the terms
        foreach ($terms as $term)
        {
			
		//onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);
		$url = get_bloginfo('siteurl').'/wpccategories/'.$term->slug;
            //Secret sauce.  Function calls itself to display child elements, if any
            $out.= '<option class="wpc-category '. $class .'" value="'.$url.'">'.$term->name.'</option>'; 
		//	$out .='<option  class="wpc-category '. $class .'">' . $term->name  . '</a></option>'; 	
	
        }
        $out .= "</select>";    
        return $out;
    }

	

}
 
function wp_catalogue_filter($taxonomy,$parent = 0){

 $terms = get_terms($taxonomy, array('parent' => $parent, 'hide_empty' => false));
    
		$taxonid=0;
		
		$out.= '<div class="filtro">Filtro';
		$out.= '<div class="coverfiltro">';
		$out.= '<ul class="ul-filtro" id="filtro-masvistos">';
		
			if(count($terms) > 0)
			{
	
			
       		foreach($terms as $term){

				if($term_slug==$term->slug){
				$class	=	'active-wpc-cat';
			}else{
				$class	=	'';
			}
			//echo $term->name;
			//echo $term->parent;
			if((int)$term->parent < 1){
			$out.='<li id="tipo"><img src="../wp-content/themes/tumpar/images/'.$term->name.'.png" style="float:left; margin:10px 10px 0 0;" /><p>'.$term->name.'</p>';
			//$out.='';
			//$taxonid=$term->term_taxonomy_id;	
              $out.=wp_catalog_select($taxonomy,$term->term_id);			
		
			}
			else
			{
			
			//$out.='<li id="tipo"><img src="../wp-content/themes/tumpar/images/tipo.png" /><p>'.$term->name.custom_taxonomy_walker($taxonomy, $term->term_id).'</p></li>';
		   $out.='<select name="fil_tipo" class="select-filtro" id="fil_tipo">';
		     $out.='<option class="wpc-category ' . $class . '" value="'.get_option('catalogue_page_url').'">All Products</option>';	
			//$taxonid=$term->term_taxonomy_id;	
		    $out.= '<option class="wpc-category '. $class .' value="'.get_term_link($term->slug, 'wpccategories').'">'.$term->name.'</option>'; 	
			 }
			/*if($taxonid>0 && $taxonid==$term->term_taxonomy_id)
			{
		
			}
			
            }else{
			echo '<option class="wpc-category"><a href="#">No category</a></option>';	
				*/
				}
			}
        $out.= '
		</li></ul>
        </div></div>';
	echo $out;
}

function wp_catalogue(){
	global $post;
	$post_data = get_post($post->ID, ARRAY_A);
	if(get_queried_object()->taxonomy){
		$slug	=	get_queried_object()->taxonomy.'/'.get_queried_object()->slug;
	}else{
		$slug = $post_data['post_name'];
	}
	$crrurl	=	get_bloginfo('wpurl').'/'.$slug;
	if(get_query_var('paged')){
		$paged	=	get_query_var('paged');	
	}else{
		 $paged	=	1;	
	}
	$args1 = array(
			'orderby' => 'term_order',
			'order' => 'ASC',
			'hide_empty' => true,
		);

	$terms	=	get_terms('wpccategories',$args1);
	$count	=	count($terms);
	$post_content	=	get_queried_object()->post_content;
	
		if(strpos($post_content,'[wp-catalogue]')!==false){
		
		
		 $siteurl	=	get_bloginfo('siteurl');
		 global $post;
		 $pid	= $post->ID;
		 $guid	=	 $siteurl.'/?page_id='.$pid;
		 if(get_option('catalogue_page_url')){
			update_option( 'catalogue_page_url', $guid );	 
		}else{
			add_option( 'catalogue_page_url', $guid );	
		}
	}
	$term_slug	=	get_queried_object()->slug;
	if(!$term_slug){
		$class	=	"active-wpc-cat";	
	}

echo '<div id="wpc-catalogue-wrapper">'; ?>
<?php //wp_catalogue_breadcumb();
	echo '<div id="wpc-col-2">';
   /*  echo   '<ul class="wpc-categories">';
	echo '<li class="wpc-category ' . $class . '"><a href="'. get_option('catalogue_page_url') .'">Lo mas visto</a></li>';	
	*/
        echo '</div>';
		
			

 wp_catalogue_filter('wpccategories');

$per_page	=	get_option('pagination');
if($per_page==0){
	$per_page	=	"-1";
}
$term_slug	=	get_queried_object()->slug;
if($term_slug){
$args = array(
	'post_type'=> 'wpcproduct',
	'order'     => 'ASC',
    'orderby'   => 'menu_order',
	'posts_per_page'	=> $per_page,
	'paged'	=> $paged,
	'tax_query' => array(
		array(
			'taxonomy' => 'wpccategories',
			'field' => 'slug',
			'terms' => get_queried_object()->slug
		)
	)
);

}else{
	$args = array(
	'post_type'=> 'wpcproduct',
	'order'     => 'ASC',
    'orderby'   => 'menu_order',
	'posts_per_page'	=> $per_page,
	'paged'	=> $paged,
	);
}
$products	=	new WP_Query($args); 
if($products->have_posts()){
	$tcropping	=	get_option('tcroping');
	if(get_option('thumb_height')){
	$theight	=	get_option('thumb_height');
	}else{
		$theight	=	142;
	}
	if(get_option('thumb_width')){
		$twidth		=	get_option('thumb_width');
	}else{
		$twidth		=	205;
	}
	$i = 1;
		
	echo '  <!--col-2-->
				<div id="wpc-col-2">
        		<div id="wpc-products">';
        while($products->have_posts()): $products->the_post();
  		
		$title		=	get_the_title(); 
		$description = get_the_content();
		$permalink	=	get_permalink(); 
		$img		=	get_post_meta(get_the_id(),'product_img1',true);
	  	$price		=	get_post_meta(get_the_id(),'product_price',true); 
		 echo '<!--wpc product-->';
         echo '<div class="wpc-product">';
         echo '<div class="wpc-img" style="width: '. $twidth . 'px; height:' . $theight . 'px"><a href="'. $permalink .'" class="wpc-product-link"><img src="'. $img .'" alt="" height="' . $theight . '" width="' . $twidth . '" /></a></div>';
		 echo '<p class="wpc-title">' . $title . '</p>';
		 echo '<p class="wpc-title">' . $description . '</p>';
		echo '</div>';
         echo '<!--/wpc-product-->';
		if($i == get_option('grid_rows'))
    {
        echo '<br clear="all" />';
        $i = 0; // reset counter
	}
		$i++;
		endwhile; wp_reset_postdata;
		echo '</div>';
	    if(get_option('pagination')!=0){
		$pages	=	ceil($products->found_posts/get_option('pagination'));
		}

		
	echo arrow_pagination($pages,10);
	
	/*if($pages>1){
	echo '<div class="wpc-paginations">';
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
	for($p=1; $p<=$pages; $p++){
		$cpage	=	'active-wpc-page';
		if($paged==$p){
				echo    '<a href="' . $crrurl . '/page/'. $p .'" class="pagination-number '. $cpage .'">'. $p .'</a>';
			}else{
				echo    '<a href="' . $crrurl . '/page/'. $p .'" class="pagination-number">'. $p .'</a>';	
			}
			
		
}
	echo '</div>'; 
	}*/
	echo '</div>';
}else{
echo 'No Products';
}
 echo  ' </div><div class="clear"></div></div>
       <!--/col-2-->';
	   echo '<div id="wpc-catalogue-wrapper">'; ?>
<?php //wp_catalogue_breadcumb();
	echo '<div id="wpc-col-1">';
     echo   '<ul class="wpc-categories">';
	echo '<li class="wpc-category ' . $class . '">Recomendados</li>';	
	
        echo '</ul>
        </div>';
		
$per_page	=	get_option('pagination');
if($per_page==0){
	$per_page	=	"-1";
}
$term_slug="Recomendados";
if($term_slug){
$args1 = array(
	'post_type'=> 'wpcproduct',
	'order'     => 'ASC',
    'orderby'   => 'menu_order',
	'posts_per_page'	=> $per_page,
	'paged'	=> $paged,
	'tax_query' => array(
		array(
			'taxonomy' => 'wpccategories',
			'field' => 'name',
			'terms' => $term_slug,
		)
	)
);

}


$products	=	new WP_Query($args1); 
if($products->have_posts()){
	$tcropping	=	get_option('tcroping');
	if(get_option('thumb_height')){
	$theight	=	get_option('thumb_height');
	}else{
		$theight	=	142;
	}
	if(get_option('thumb_width')){
		$twidth		=	get_option('thumb_width');
	}else{
		$twidth		=	205;
	}
	$i = 1;
	echo '  <!--col-2-->
				<div id="wpc-col-2">
        		<div id="wpc-products">';
        while($products->have_posts()): $products->the_post();
		
  		$title		=	get_the_title(); 
		$description = get_the_content();
		$permalink	=	get_permalink(); 
		$img		=	get_post_meta(get_the_id(),'product_img1',true);
	  	$price		=	get_post_meta(get_the_id(),'product_price',true); 
		 echo '<!--wpc product-->';
         echo '<div class="wpc-product">';
         echo '<div class="wpc-img" style="width: '. $twidth . 'px; height:' . $theight . 'px"><a href="'. $permalink .'" class="wpc-product-link"><img src="'. $img .'" alt="" height="' . $theight . '" width="' . $twidth . '" /></a></div>';
		 echo '<p class="wpc-title">' . $title . '</p>';
		 echo '<p class="wpc-title">' . $description . '</p>';
		 
		 echo '</div>';
         echo '<!--/wpc-product-->';
		if($i == get_option('grid_rows'))
    {
        echo '<br clear="all" />';
        $i = 0; // reset counter
	}
		$i++;
		endwhile; wp_reset_postdata;
		echo '</div>';
	    if(get_option('pagination')!=0){
		$pages	=	ceil($products->found_posts/get_option('pagination'));
		}

	if($pages>1){
	echo '<div class="wpc-paginations">';
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
	for($p=1; $p<=$pages; $p++){
		$cpage	=	'active-wpc-page';
		if($paged==$p){
				echo    '<a href="' . $crrurl . '/page/'. $p .'" class="pagination-number '. $cpage .'">'. $p .'</a>';
			}else{
				echo    '<a href="' . $crrurl . '/page/'. $p .'" class="pagination-number">'. $p .'</a>';	
			}
}
 echo '</div>'; 
}
}else{
echo 'No Products';
}
 echo  ' </div><div class="clear"></div></div>
       <!--/col-2-->';	   

}
	   
	   
	   
add_shortcode('wp-catalogue','wp_catalogue');

/*	   
echo '<div id="wpc-catalogue-wrapper">'; ?>
<?php //wp_catalogue_breadcumb();
	echo '<div id="wpc-col-1">';
     echo   '<ul class="wpc-categories">';
	echo '<li class="wpc-category ' . $class . '"><a href="'. get_option('catalogue_page_url') .'">Recomendados</a></li>';	
	
        echo '</ul>
        </div>';
		
$per_page	=	get_option('pagination');
if($per_page==0){
	$per_page	=	"-1";
}
$term_slug	=	get_queried_object()->slug;
if($term_slug){
$args = array(
	'post_type'=> 'wpcproduct',
	'order'     => 'ASC',
    'orderby'   => 'menu_order',
	'posts_per_page'	=> $per_page,
	'paged'	=> $paged,
	'tax_query' => array(
		array(
			'taxonomy' => 'wpccategories',
			'field' => 'slug',
			'terms' => get_queried_object()->slug
		)
	)
);

}else{
	$args = array(
	'post_type'=> 'wpcproduct',
	'order'     => 'ASC',
    'orderby'   => 'menu_order',
	'posts_per_page'	=> $per_page,
	'paged'	=> $paged,
	);
}
$products	=	new WP_Query($args); 
if($products->have_posts()){
	$tcropping	=	get_option('tcroping');
	if(get_option('thumb_height')){
	$theight	=	get_option('thumb_height');
	}else{
		$theight	=	142;
	}
	if(get_option('thumb_width')){
		$twidth		=	get_option('thumb_width');
	}else{
		$twidth		=	205;
	}
	$i = 1;
	echo '  <!--col-2-->
				<div id="wpc-col-2">
        		<div id="wpc-products">';
        while($products->have_posts()): $products->the_post();
		
  		$title		=	get_the_title(); 
		$description = get_the_content();
		$permalink	=	get_permalink(); 
		$img		=	get_post_meta(get_the_id(),'product_img1',true);
	  	$price		=	get_post_meta(get_the_id(),'product_price',true); 
		 echo '<!--wpc product-->';
         echo '<div class="wpc-product">';
         echo '<div class="wpc-img" style="width: '. $twidth . 'px; height:' . $theight . 'px"><a href="'. $permalink .'" class="wpc-product-link"><img src="'. $img .'" alt="" height="' . $theight . '" width="' . $twidth . '" /></a></div>';
		 echo '<p class="wpc-title">' . $title . '</p>';
		 echo '<p class="wpc-title">' . $description . '</p>';
		 
		 echo '</div>';
         echo '<!--/wpc-product-->';
		if($i == get_option('grid_rows'))
    {
        echo '<br clear="all" />';
        $i = 0; // reset counter
	}
		$i++;
		endwhile; wp_reset_postdata;
		echo '</div>';
	    if(get_option('pagination')!=0){
		$pages	=	ceil($products->found_posts/get_option('pagination'));
		}

	if($pages>1){
	echo '<div class="wpc-paginations">';
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
	for($p=1; $p<=$pages; $p++){
		$cpage	=	'active-wpc-page';
		if($paged==$p){
				echo    '<a href="' . $crrurl . '/page/'. $p .'" class="pagination-number '. $cpage .'">'. $p .'</a>';
			}else{
				echo    '<a href="' . $crrurl . '/page/'. $p .'" class="pagination-number">'. $p .'</a>';	
			}
}
 echo '</div>'; 
}
}else{
echo 'No Products';
}
 echo  ' </div><div class="clear"></div></div>
       <!--/col-2-->';	   

}

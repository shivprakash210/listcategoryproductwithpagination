function ntc_list_category_products($atts) {     
 extract( shortcode_atts( array(
        'category' => 0,
         'per_page' => 1,
         'pagerange' =>2,
    ), $atts ) );
   $paged = ( get_query_var('page') ) ? get_query_var('page') : 1;
   $cat_name = $category;
   $per_page=$per_page;
   $pagerange=$pagerange;
    $args = array( 'post_type' => 'product', 'posts_per_page' => $per_page,'paged' => $paged, 'product_cat' => $cat_name );
        //$woocommerce_loop['columns'] = $columns;
		$products = new WP_Query( $args );
		//$woocommerce_loop['columns'] = $columns;
        if ( $products->have_posts() ) :
		   woocommerce_product_loop_start();
		   while ( $products->have_posts() ) : $products->the_post();
		      woocommerce_get_template_part( 'content', 'product' );
                   endwhile; // end of the loop.
                      woocommerce_product_loop_end();

        endif;  
        echo '<div style="clear:both;"></div>';
      if (function_exists(ntc_custom_pagination)) {
        ntc_custom_pagination($products->max_num_pages,$pagerange,$paged);
      }
    
		wp_reset_postdata();
}
add_shortcode('ntclistcategoryproducts', 'ntc_list_category_products');

function ntc_custom_pagination($numpages = '', $pagerange = '', $paged='') {

  if (empty($pagerange)) {
    $pagerange = 2;
  }

  /**
   * This first part of our function is a fallback
   * for custom pagination inside a regular loop that
   * uses the global $paged and global $wp_query variables.
   * 
   * It's good because we can now override default pagination
   * in our theme, and use this function in default quries
   * and custom queries.
   */
  global $paged;
  if (empty($paged)) {
    $paged = 1;
  }
  if ($numpages == '') {
    global $wp_query;
    $numpages = $wp_query->max_num_pages;
    if(!$numpages) {
        $numpages = 1;
    }
  }

  /** 
   * We construct the pagination arguments to enter into our paginate_links
   * function. 
   */
  $pagination_args = array(
    'base'            => get_pagenum_link(1) . '%_%',
    'format'          => 'page/%#%',
    'total'           => $numpages,
    'current'         => $paged,
    'show_all'        => False,
    'end_size'        => 1,
    'mid_size'        => $pagerange,
    'prev_next'       => True,
    'prev_text'       => __('&laquo;'),
    'next_text'       => __('&raquo;'),
    'type'            => 'plain',
    'add_args'        => false,
    'add_fragment'    => ''
  );

  $paginate_links = paginate_links($pagination_args);

  if ($paginate_links) {
    echo "<nav class='custom-pagination'>";
      echo "<span class='page-numbers page-num'>Page " . $paged . " of " . $numpages . "</span> ";
      echo $paginate_links;
    echo "</nav>";
  }

}

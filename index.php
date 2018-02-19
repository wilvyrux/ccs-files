<?php
/*
 * This file is used to generate main index page.
 */
	
	/* Precautionary Measure */
	$eventco_maintenance_mode = '';
	$eventco_maintenace_title	= '';
	$eventco_countdown_time = '';
	$eventco_email_mainte = '';
	$eventco_mainte_description = '';
	$eventco_social_icons_mainte = '';
	
	/* Fetch the theme Option Values */
	$eventco_maintenance_mode = eventco_get_themeoption_value('maintenance_mode','general_settings');
	$eventco_maintenace_title = eventco_get_themeoption_value('maintenace_title','general_settings');
	$eventco_countdown_time = eventco_get_themeoption_value('countdown_time','general_settings');
	$eventco_email_mainte = eventco_get_themeoption_value('email_mainte','general_settings');
	$eventco_mainte_description = eventco_get_themeoption_value('mainte_description','general_settings');
	$eventco_social_icons_mainte = eventco_get_themeoption_value('social_icons_mainte','general_settings');
	
	if($eventco_maintenance_mode <> 'disable'){
		/* If Logged in then Remove Maintenance Page */
		if ( is_user_logged_in() ) {
			$eventco_maintenance_mode = 'disable';
		} else {
			$eventco_maintenance_mode = 'enable';
		}
	}
	
	$eventco_num_excerpt = '';
	$eventco_default_settings = get_option('default_pages_settings');

	if($eventco_default_settings != ''){
		$eventco_default = new DOMDocument ();
		$eventco_default->loadXML ( $eventco_default_settings );
		$eventco_num_excerpt = eventco_find_xml_value($eventco_default->documentElement,'default_excerpt');
	}
	if($eventco_num_excerpt == '' || $eventco_num_excerpt == 0 ) {
		$eventco_num_excerpt = 300;
	}
	
	if($eventco_maintenance_mode == 'enable'){
		/* Trigger the Maintenance Mode Function Here */
		eventco_maintenance_mode_fun();
	}else{

		@get_header();
		$eventco_header_style = '';
		
		
		
		$eventco_item_class = '';
		$eventco_sidebar = '';
		/* Fetch sidebar theme option */
		$eventco_default_settings = get_option('default_pages_settings');		
		if($eventco_default_settings != ''){		
			$eventco_default = new DOMDocument ();
			$eventco_default->loadXML ( $eventco_default_settings );
			$eventco_sidebar = eventco_find_xml_value($eventco_default->documentElement,'sidebar_default');
			$eventco_right_sidebar = eventco_find_xml_value($eventco_default->documentElement,'right_sidebar_default');
			$eventco_left_sidebar = eventco_find_xml_value($eventco_default->documentElement,'left_sidebar_default');
		}
			
		$eventco_breadcrumbs = '';
		$eventco_breadcrumbs = eventco_get_themeoption_value('breadcrumbs','general_settings');
		/* Get Sidebar for index */
		$eventco_sidebar_class = eventco_sidebar_func($eventco_sidebar);	
		
	/* Inner Banner */ ?>
	<div class="cp-inner-banner" id="cp-inner-blog">
		<div class="container">
			<div class="cp-inner-banner-outer">
				<h2>
					<?php esc_html_e('Blog Posts','eventco');?>
				</h2>
			</div>
		</div>
	</div>
	
	<!-- Main Content Section Start -->
	<div class = "cp-main-content"> 
			
			<section class="cp-blog-section">
				
				<div class="container">
					
					<div class="row">	
						 <?php
							if($eventco_sidebar == "left-sidebar" || $eventco_sidebar == "both-sidebar" || $eventco_sidebar == "both-sidebar-left"){?>
								<div id="block_first" class="sidebar side-bar <?php echo esc_attr($eventco_sidebar_class[0]);?>">
									<aside>
										<div class="cp-sidebar">
											<?php dynamic_sidebar( $eventco_left_sidebar ); ?>
										</div>
									</aside>
								</div>
								<?php
							}
							if($eventco_sidebar == 'both-sidebar-left'){?>
								<div id="block_first_left" class="sidebar side-bar <?php echo esc_attr($eventco_sidebar_class[0]);?>">
									<aside>
										<div class="cp-sidebar">
											<?php dynamic_sidebar( $eventco_right_sidebar );?>
										</div>
									</aside>
							    </div>
						<?php } ?>
						
						<div class="<?php echo esc_attr($eventco_sidebar_class[1]);?>">
							
							<div class="cp-post-box">
							
							<?php
								/* Feature Sticky Post */
								if ( is_front_page() && has_post_thumbnail() ) { 
									/* Include the featured content template. */
									get_template_part( 'featured-content' );
								}

								while ( have_posts() ) : the_post(); ?>
										
									<div <?php post_class(); ?>>
										
										<div class="cp-blog-item">
											
											<?php if(has_post_thumbnail()){ /* Featured Image */ ?>
												
												<figure class="cp-thumb">
												 
												 <div class="post_featured_image thumbnail_image"><?php echo get_the_post_thumbnail($post->ID,array(850,450));?></div>
												 
												 <figcaption class="cp-caption">
													
													<a href="<?php echo esc_url(get_permalink());?>"><i class="fa fa-link"></i></a>
												 
												 </figcaption>
												
												</figure>
											
											<?php } 
											
											
											$archive_year  = get_the_time('Y'); 
											$archive_month = get_the_time('m'); 
											$archive_day   = get_the_time('d');
											
											?>
											
											
										
											<div class="cp-text">
											  
											  <h3><a href="<?php echo esc_url(get_permalink());?>"><?php echo esc_attr(get_the_title());?></a> </h3>
											  
											  <ul class="post-meta">
												
												<li><a href = "<?php echo esc_url(get_day_link( $archive_year, $archive_month, $archive_day)); ?>" ><i class="fa fa-calendar"></i><?php echo esc_attr(get_the_date());?></li>
												
												<li>
													<a href = "<?php echo get_author_posts_url( $post->post_author); ?>"><i class="fa fa-user"></i><?php echo esc_attr(get_the_author()); ?>
													</a>
												
												</li>
											  
											  </ul>
											 
												<?php 
												
												/* The Content */
												echo wpautop(the_content()); 
												
												/* Post Inner Pagination */
												wp_link_pages( array(
													'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'eventco' ) . '</span>',
													'after'       => '</div>',
													'link_before' => '<span>',
													'link_after'  => '</span>',
													'pagelink'    => '<span class="screen-reader-text"></span>%',
													'separator'   => '<span class="screen-reader-text"></span>',
												) );
											 ?>
											 
											 <a href="<?php echo esc_url(get_permalink());?>" class="read-btn"><?php esc_html_e('Read More','eventco');?></a>
											
											</div>
										
										</div>
									
									</div>
										
									<?php endwhile; /* endwhile */ ?>
									
									<div class = "cp-pagination">
										<nav>
											<ul class="pagination">
												<li>
													<?php echo eventco_pagination();?>
												</li>
											</ul>
										</nav>
									</div>
							
							</div>
						
						</div> <!-- col ends -->
				
						<?php
							if($eventco_sidebar == "both-sidebar-right"){?>
								<div class="<?php echo esc_attr($eventco_sidebar_class[0]);?> side-bar">
								<aside>
									<div class="cp-sidebar">
										<?php dynamic_sidebar( $eventco_left_sidebar ); ?>
									</div>
								</aside>
								</div>
								<?php
							}
							if($eventco_sidebar == 'both-sidebar-right' || $eventco_sidebar == "right-sidebar" || $eventco_sidebar == "both-sidebar"){?>
								<div class="<?php echo esc_attr($eventco_sidebar_class[0]);?> side-bar">
									<aside>
										<div class="cp-sidebar">
											<?php dynamic_sidebar( $eventco_right_sidebar ); ?>
										</div>
								</aside>
								</div>
						<?php } ?>	
					</div>	
			
			</div>
		
		</section>
   
	</div> 
<?php 			
	@get_footer();
}
?>
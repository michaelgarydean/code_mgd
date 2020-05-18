<?php

add_action( 'wp_enqueue_scripts', 'perfect_portfolio_parent_theme_enqueue_styles' );

function perfect_portfolio_parent_theme_enqueue_styles() {
    wp_enqueue_style( 'perfect-portfolio-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'code_mgd-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'perfect-portfolio-style' )
    );

}

/**
 * Functions related to funtionality.
 */

if ( ! function_exists( 'perfect_portfolio_tag' ) ) :
/**
 * Prints tags
 */
function code_mgd_tag(){
    // Hide category and tag text for pages.
    if ( 'post' === get_post_type() ) {
        /* translators: used between list items, there is a space after the comma */
        $tags_list = get_the_tag_list( '', ' ' );
        if ( $tags_list ) {
            /* translators: 1: list of tags. */
            echo '<div class="tags" itemprop="about">' . $tags_list . '</div>';
        }
    }
}
endif;

if ( function_exists('register_sidebar') )
  register_sidebar(array(
    'name' => 'Bottom Footer',
    'before_widget' => '<div class = "bottom-footer-widget-area">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
  )
);


/**
 * Functions to build template pages.
 */
//require get_template_directory() . '/inc/template-functions.php';

/*
 * =======
 * CONTENT
 * =======
 */

if ( ! function_exists( 'code_mgd_post_thumbnail' ) ) :
/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 */
function code_mgd_post_thumbnail() {
    global $wp_query;
    $image_size  = 'thumbnail';
    $blog_page_layout = get_theme_mod( 'blog_page_layout', 'with-masonry-description grid' );
    $ed_featured = get_theme_mod( 'ed_featured_image', false );
    $sidebar     = perfect_portfolio_sidebar();
    
    if( is_front_page() && is_home() ){

        /*
         * EDIT: Instead of the mixed layout, just make all posts full width.
         */
        //if( $blog_page_layout == 'normal-grid-first-large' && $wp_query->current_post === 0 ) {
        if( $blog_page_layout == 'normal-grid-first-large') {
            $image_size = 'perfect-portfolio-fullwidth';
        }else{
            $image_size = 'perfect-portfolio-blog';
        }
        echo '<figure class="post-thumbnail"><a href="' . esc_url( get_permalink() ) . '" itemprop="thumbnailUrl">';            
        if( has_post_thumbnail() ){                        
            the_post_thumbnail( $image_size, array( 'itemprop' => 'image' ) );    
        }else{
            perfect_portfolio_get_fallback_svg( $image_size );    
        }        
        echo '</a></figure>';
    }elseif( is_home() ){ 

        /*
         * EDIT: Instead of the mixed layout, just make all posts full width.
         */
        //if( $blog_page_layout == 'normal-grid-first-large' && $wp_query->current_post === 0 ) {

        if( $blog_page_layout == 'normal-grid-first-large') {
            $image_size = 'perfect-portfolio-fullwidth';
        }else{
            $image_size = 'perfect-portfolio-blog';
        }       
        echo '<figure class="post-thumbnail"><a href="' . esc_url( get_permalink() ) . '" itemprop="thumbnailUrl">';
        if( has_post_thumbnail() ){                        
            the_post_thumbnail( $image_size, array( 'itemprop' => 'image' ) );    
        }else{
            perfect_portfolio_get_fallback_svg( $image_size );      
        }        
        echo '</a></figure>';
    }elseif( is_archive() || is_search() ){
        echo '<figure class="post-thumbnail"><a href="' . esc_url( get_permalink() ) . '" itemprop="thumbnailUrl">';
        if( has_post_thumbnail() ){
            the_post_thumbnail( 'perfect-portfolio-blog', array( 'itemprop' => 'image' ) );    
        }else{
            perfect_portfolio_get_fallback_svg( $image_size );  
        }
        echo '</a></figure>';
    }elseif( is_singular() ){
        $image_size = ( $sidebar ) ? 'perfect-portfolio-with-sidebar' : 'perfect-portfolio-fullwidth';
        if( is_single() && ( !$ed_featured ) ){
            if( has_post_thumbnail() ) {
                echo '<figure class="post-thumbnail">';
                the_post_thumbnail( $image_size, array( 'itemprop' => 'image' ) );
                echo '</figure>';
            }
        }elseif( is_page() ){
            if( has_post_thumbnail() ) {
                echo '<figure class="post-thumbnail">';
                the_post_thumbnail( $image_size, array( 'itemprop' => 'image' ) );
                echo '</figure>';
            }
        }
    }
}
endif;

add_action( 'code_mgd_before_post_entry_content', 'code_mgd_post_thumbnail', 20 );

if( ! function_exists( 'code_mgd_entry_post_content' ) ) :
/**
 * Entry Content
*/
function code_mgd_entry_post_content(){ 
    $ed_excerpt = get_theme_mod( 'ed_excerpt', true );
    ?>
    <div class="post-content-wrap">
        <header class="entry-header">
            <h2 class="entry-title" itemprop="headline">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>
            <div class="entry-meta">
                <?php 
                    /* 
                     * EDIT: Let's show the tags instead of the post date.
                      */
                    //perfect_portfolio_posted_on();
                    code_mgd_tag();
                ?>

                
            </div>
        </header>
        <div class="entry-content">
            <?php
            if( $ed_excerpt ) {
                the_excerpt();
            } else{
                the_content();
            }
            ?>
        </div>
    </div>
    <?php
}
endif;
add_action( 'code_mgd_post_entry_content', 'code_mgd_entry_post_content', 10 );

/*
 * =======
 * SINGLES
 * =======
 */

/*
 * ------
 * FOOTER
 * ------
 */

if( ! function_exists( 'perfect_portfolio_tc_wrapper' ) ) :
/**
 * Author Section
*/
function perfect_portfolio_tc_wrapper() { 
    $sidebar = perfect_portfolio_sidebar( true );

    if( $sidebar != 'rightsidebar' && $sidebar != 'leftsidebar' ) { 
        echo '<div class="tc-wrapper">';    
    }
}
endif;
add_action( 'code_mgd_after_post_content', 'perfect_portfolio_tc_wrapper', 10 );

if( ! function_exists( 'perfect_portfolio_navigation' ) ) :
/**
 * Navigation
*/
function perfect_portfolio_navigation(){
    if( is_single() ){
        $previous = get_previous_post_link(
            '<div class="nav-previous nav-holder">%link</div>',
            '<span class="meta-nav">' . esc_html__( 'Previous Article', 'perfect-portfolio' ) . '</span><span class="post-title">%title</span>',
            false,
            '',
            'category'
        );
    
        $next = get_next_post_link(
            '<div class="nav-next nav-holder">%link</div>',
            '<span class="meta-nav">' . esc_html__( 'Next Article', 'perfect-portfolio' ) . '</span><span class="post-title">%title</span>',
            false,
            '',
            'category'
        ); 
        
        if( $previous || $next ){?>            
            <nav class="navigation post-navigation" role="navigation">
                <h2 class="screen-reader-text"><?php esc_html_e( 'Post Navigation', 'perfect-portfolio' ); ?></h2>
                <div class="nav-links">
                    <?php
                        if( $previous ) echo $previous;
                        if( $next ) echo $next;
                    ?>
                </div>
            </nav>        
            <?php
        }
    }else{
        the_posts_pagination( array(
            'prev_text'          => __( 'Previous', 'perfect-portfolio' ),
            'next_text'          => __( 'Next', 'perfect-portfolio' ),
            'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'perfect-portfolio' ) . ' </span>',
        ) );
    }
}
endif;
//add_action( 'code_mgd_after_post_content', 'perfect_portfolio_navigation', 15 );
add_action( 'perfect_portfolio_after_posts_content', 'perfect_portfolio_navigation' );

if( ! function_exists( 'perfect_portfolio_entry_footer' ) ) :
/**
 * Entry Footer
*/
function perfect_portfolio_entry_footer(){ ?>
    <div class="entry-footer">
        <?php
                        
            if( get_edit_post_link() ){
                edit_post_link(
                    sprintf(
                        wp_kses(
                            /* translators: %s: Name of current post. Only visible to screen readers */
                            __( 'Edit <span class="screen-reader-text">%s</span>', 'perfect-portfolio' ),
                            array(
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ),
                        get_the_title()
                    ),
                    '<span class="edit-link">',
                    '</span>'
                );
            }
            if( is_single() ) {
                code_mgd_tag();
                perfect_portfolio_category();
            }
        ?>
    </div><!-- .entry-footer -->
    <?php 
}
endif;
add_action( 'code_mgd_after_post_content', 'perfect_portfolio_entry_footer', 13 );

if( ! function_exists( 'perfect_portfolio_author' ) ) :
/**
 * Author Section
*/
function perfect_portfolio_author(){ 
    $ed_author    = get_theme_mod( 'ed_author', false );
    $author_title = get_the_author_meta( 'display_name' );
    $author_description = get_the_author_meta( 'description' );

    if( ! $ed_author && $author_title && $author_description ){ ?>
        <div class="about-author">
            <figure class="author-img"><?php echo get_avatar( get_the_author_meta( 'ID' ), 230 ); ?></figure>
            <div class="author-info-wrap">
                <?php 
                    if( is_author() ) echo '<span class="sub-title">' . esc_html__( 'All Posts by','perfect-portfolio' ) . '</span>';
                    echo '<h3 class="name">' . esc_html( $author_title ) . '</h3>';
                    echo '<div class="author-info">' . wpautop( wp_kses_post( $author_description ) ) . '</div>';
                ?>      
            </div>
        </div>
    <?php }
}
endif;
add_action( 'code_mgd_after_post_content', 'perfect_portfolio_author', 20 );

if( ! function_exists( 'perfect_portfolio_related_posts' ) ) :
/**
 * Related Posts 
*/
function perfect_portfolio_related_posts(){ 
    $ed_related_post = get_theme_mod( 'ed_related', false );
    if( $ed_related_post ){
        perfect_portfolio_get_posts_list( 'related' );    
    }
}
endif;                                                                               
add_action( 'code_mgd_after_post_content', 'perfect_portfolio_related_posts', 35 );

if( ! function_exists( 'perfect_portfolio_popular_posts' ) ) :
/**
 * Popular Posts
*/
function perfect_portfolio_popular_posts(){ 
    $ed_popular_post = get_theme_mod( 'ed_popular', false );
    if( $ed_popular_post ){
        perfect_portfolio_get_posts_list( 'popular' );  
    }
}
endif;
add_action( 'code_mgd_after_post_content', 'perfect_portfolio_popular_posts', 30 );

if( ! function_exists( 'perfect_portfolio_latest_posts' ) ) :
/**
 * Latest Posts
*/
function perfect_portfolio_latest_posts(){ 
    perfect_portfolio_get_posts_list( 'latest' );
}
endif;
add_action( 'perfect_portfolio_latest_posts', 'perfect_portfolio_latest_posts' );

if( ! function_exists( 'perfect_portfolio_tc_wrapper_end' ) ) :
/**
 * Comments Template 
*/
function perfect_portfolio_tc_wrapper_end(){
    $sidebar = perfect_portfolio_sidebar( true );

    if( $sidebar != 'rightsidebar' && $sidebar != 'leftsidebar' ) { 
        echo '</div>';    
    }
}
endif;
add_action( 'code_mgd_after_post_content', 'perfect_portfolio_tc_wrapper_end', 40 );

if( ! function_exists( 'code_mgd_footer_start' ) ) :
/**
 * Footer Start
*/
function code_mgd_footer_start(){
    ?>
</div><!-- #main-content-area -->
    <footer id="colophon" class="site-footer" itemscope itemtype="http://schema.org/WPFooter">
    <?php
}
endif;

add_action( 'code_mgd_footer', 'code_mgd_footer_start', 20 );

/*
 * Footer Top
*/
/*function code_mgd_footer_top(){    
    if( is_active_sidebar( 'footer-one' ) || is_active_sidebar( 'footer-two' ) || is_active_sidebar( 'footer-three' ) ){ ?>
    <div class="top-footer">
        <div class="tc-wrapper">
            <?php if( is_active_sidebar( 'footer-one' ) ){ ?>
                <div class="col">
                   <?php dynamic_sidebar( 'footer-one' ); ?>    
                </div>
            <?php } ?>
            
            <?php if( is_active_sidebar( 'footer-two' ) ){ ?>
                <div class="col">
                   <?php dynamic_sidebar( 'footer-two' ); ?>    
                </div>
            <?php } ?>
            
            <?php if( is_active_sidebar( 'footer-three' ) ){ ?>
                <div class="col">
                   <?php dynamic_sidebar( 'footer-three' ); ?>  
                </div>
            <?php } ?>
        </div>
    </div>
    <?php 
    }   
}
endif;*/
add_action( 'code_mgd_footer', 'perfect_portfolio_footer_top', 30 );




if( ! function_exists( 'code_mgd_footer_top' ) ) :
/**
/**
 * Footer Bottom
*/
function code_mgd_footer_bottom(){ ?>
    <div class="bottom-footer">
        <div class="tc-wrapper">
            <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Bottom Footer") ) : ?>
<?php endif;?>

            <!-- 
            <div class="copyright">           
                <?php if ( function_exists( 'the_privacy_policy_link' ) ) {
                    the_privacy_policy_link( '', '<span role="separator" aria-hidden="true"></span>' );
                } 
                perfect_portfolio_get_footer_copyright();
                esc_html_e( 'Perfect Portfolio | Developed By ', 'perfect-portfolio' );
                echo '<a href="' . esc_url( 'https://rarathemes.com/' ) .'" rel="nofollow" target="_blank">' . esc_html__( 'Rara Theme', 'perfect-portfolio' ) . '</a>.';
                
                printf( esc_html__( ' Powered by %s', 'perfect-portfolio' ), '<a href="'. esc_url( __( 'https://wordpress.org/', 'perfect-portfolio' ) ) .'" target="_blank">WordPress</a>.' );
            ?>               
            </div> 
            -->
            <div class="foot-social">
                <?php perfect_portfolio_social_links(); ?>
            </div>
        </div>
    </div>
    <?php
}
endif;
add_action( 'code_mgd_footer', 'code_mgd_footer_bottom', 40 );



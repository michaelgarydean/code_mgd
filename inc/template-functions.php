<?php
/**
 * Perfect Portfolio Template Functions which enhance the theme by hooking into WordPress
 *
 * @package code_mgd
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

        if( $blog_page_layout == 'normal-grid-first-large' && $wp_query->current_post === 0 ) {
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

        if( $blog_page_layout == 'normal-grid-first-large' && $wp_query->current_post === 0 ) {
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
//add_action( 'perfect_portfolio_before_single_header', 'code_mgd_post_thumbnail' );
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
                <?php perfect_portfolio_posted_on(); ?>
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
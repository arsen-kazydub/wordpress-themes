<?php

# ========== MODULES ========== #

require_once get_template_directory() . '/inc/shortcodes.php';


# ========== THEME SETUP ========== #

// Disable default WordPress block patterns
remove_theme_support( 'core-block-patterns' );


// Register pattern categories
add_action( 'init', function(): void {
  register_block_pattern_category( 'layout', [
    'label' => __( 'Layout', 'finguide' )
  ] );
} );


# ========== IMAGES ========== #

// Exclude certain image sizes from being generated on upload
add_filter( 'intermediate_image_sizes_advanced', function( array $sizes ): array {
  $excluded_sizes = [ 'thumbnail', 'medium', 'large', 'medium_large', '1536x1536', '2048x2048' ];
  foreach ( $excluded_sizes as $slug ) {
    unset( $sizes[ $slug ] );
  }
  return $sizes;
} );


// Images are scaled proportionally without cropping
add_action( 'after_setup_theme', function(): void {
  add_image_size( 'size-small', 420, 9999, false );
  add_image_size( 'size-medium', 840, 9999, false );
  add_image_size( 'size-large', 1260, 9999, false );
} );


// Automatically adds loading="lazy" to <img> tags that don't have "loading" attribute
add_filter( 'the_content', function( string $content ): string {
  $result = preg_replace_callback(
    '/<img[^>]*>/i',
    function( $matches ) {
      $img_tag = $matches[ 0 ];

      if ( str_contains( $img_tag, 'loading=' ) ) {
        return $img_tag;
      }

      return str_replace( '<img', '<img loading="lazy"', $img_tag );
    },
    $content
  );

  return $result ?? $content;
}, 100 );


# ========== THEME CSS ========== #

add_action( 'enqueue_block_assets', function(): void {
  wp_enqueue_style(
    'finguide-style',
    get_stylesheet_uri()
  );
} );
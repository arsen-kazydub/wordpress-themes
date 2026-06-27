<?php

# ========== POST TITLE ========== #

add_shortcode( 'fg_post_title', function(): string {
  return get_the_title();
} );


# ========== BREADCRUMBS ========== #

add_shortcode( 'fg_breadcrumbs', function(): string {
  if ( function_exists( 'bcn_display' ) ) {
    ob_start();
    echo '<nav class="fg-breadcrumbs" aria-label="Breadcrumb">';
    bcn_display();
    echo '</nav>';
    return (string) ob_get_clean();
  }
  return '';
} );


# ========== ACF ========== #

add_shortcode( 'fg_acf', function( array $atts = [] ): string {
  $atts = shortcode_atts( [ 'field' => '' ], $atts );
  $field = $atts[ 'field' ];

  if ( empty( $field ) ) {
    return '';
  }

  $value = get_field( $field );

  return esc_html( (string) $value );
} );


# ========== PROJECT ========== #

# PROJECT: TITLE
add_shortcode( 'fg_project_title', function(): string {
  $title = get_the_title();
  $headline = get_field( 'project_headline' );
  $project_title = array_filter( [ $title, $headline ] );

  if ( empty( $project_title ) ) {
    return '';
  }

  return implode( ': ', $project_title );
} );


# PROJECT: CATEGORY
add_shortcode( 'fg_project_category', function(): string {
  $terms = get_the_terms( get_the_ID(), 'project_category' );

  if ( empty( $terms ) || is_wp_error( $terms ) ) {
    return '';
  }

  return esc_html( $terms[0]->name );
} );


# PROJECT: HIGHLIGHTS
add_shortcode( 'fg_project_highlights', function(): string {
  $data = get_field( 'project_highlights' );

  if ( empty( $data ) ) {
    return '';
  }

  $items = preg_split( '/\r\n|\r|\n/', $data );

  if ( ! is_array( $items ) ) {
    return '';
  }

  $items = array_map( 'trim', $items );
  $items = array_filter( $items );

  $html = '<div class="wp-block-group fg-features-list">';
  $html .= '<ul class="wp-block-list fg-grid fg-grid--3-cols fg-gap-row-xs">';
  foreach ( $items as $item ) {
    $html .= '<li>' . esc_html( $item ) . '</li>';
  }
  $html .= '</ul>';
  $html .= '</div>';

  return $html;
} );
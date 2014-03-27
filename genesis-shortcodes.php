<?php

/*
Plugin Name: Genesis Shortcodes
Plugin URI: http://www.wpsmith.net/genesis-shortcodes
Description: Provides commonly used shortcodes for the Genesis Framework
Version: 0.7.0
Author: Travis Smith
Author URI: http://www.wpsmith.net/
License: GPLv2

    Copyright 2012  Travis Smith  (email : http://wpsmith.net/contact)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Shortcodes
 *
 * This file creates all the shortcodes used throughout the site. It also
 * enables code to execute shortcodes in the text widget. It replaces the Genesis [post_author_posts_link]
 * shortcode. Contains the 
 * following shortcodes: [post_field], [child] or [child_url], [wpurl], [url], [uploads], 
 * [site_url], [genesis_comments], [plugin_info], [genesis_avatar] or [avatar], 
 * [genesis_email_link] or [email_link], [genesis_email] or [email], [genesis_modified_date] or [modified_date]
 * & (*UGLY*) content columns shortcodes:
 * e.g. [one_half_first]CONTENT[/one_half_first][one_half]CONTENT[/one_half]
 *
 * e.g. [one_third_first]CONTENT[/one_third_first][one_third]CONTENT[/one_third]
 * e.g. [two_thirds_first]CONTENT[/two_thirds_first][two_thirds]CONTENT[/two_thirds]
 *
 * e.g. [one_fourth_first]CONTENT[/one_fourth_first][one_fourth]CONTENT[/one_fourth]
 * e.g. [two_fourths_first]CONTENT[/two_fourths_first][two_fourths]CONTENT[/two_fourths]
 * e.g. [three_fourths_first]CONTENT[/three_fourths_first][three_fourths]CONTENT[/three_fourths]
 *
 * e.g. [one_fifth_first]CONTENT[/one_fifth_first][one_fifth]CONTENT[/one_fifth]
 * e.g. [two_fifths_first]CONTENT[/two_fifths_first][two_fifths]CONTENT[/two_fifths]
 * e.g. [three_fifths_first]CONTENT[/three_fifths_first][three_fifths]CONTENT[/three_fifths]
 * e.g. [four_fifths_first]CONTENT[/four_fifths_first][four_fifths]CONTENT[/four_fifths]
 *
 * e.g. [one_sixth_first]CONTENT[/one_sixth_first][one_sixth]CONTENT[/one_sixth]
 * e.g. [two_sixths_first]CONTENT[/two_sixths_first][two_sixths]CONTENT[/two_sixths]
 * e.g. [three_sixths_first]CONTENT[/three_sixths_first][three_sixths]CONTENT[/three_sixths]
 * e.g. [four_sixths_first]CONTENT[/four_sixths_first][four_sixths]CONTENT[/four_sixths]
 * e.g. [five_sixths_first]CONTENT[/five_sixths_first][five_sixths]CONTENT[/five_sixths]
 *
 */

define( 'GS_DOMAIN' , 'genesis-shortcodes' );
global $gsc_plugins;
$gsc_plugins = array();

/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
    wp_die( __( "Sorry, you are not allowed to access this page directly.", GS_DOMAIN ) );
}

add_filter( 'widget_text', 'do_shortcode' );
add_filter( 'genesis_term_intro_text_output', 'do_shortcode', 9 );
add_filter( 'genesis_author_intro_text_output', 'do_shortcode', 9 );
add_filter( 'genesis_cpt_archive_intro_text_output', 'do_shortcode', 9 );

register_activation_hook( __FILE__, 'gs_activation_check' );

/**
 * Checks for minimum Genesis Theme version before allowing plugin to activate
 *
 * @author Nathan Rice
 * @uses gfi_truncate()
 * @since 0.1
 * @version 0.2
 */
function gs_activation_check() {

    $latest = '1.7';

    $theme_info = get_theme_data( TEMPLATEPATH . '/style.css' );

    if ( basename( TEMPLATEPATH ) != 'genesis' ) {
        deactivate_plugins( plugin_basename( __FILE__ ) ); // Deactivate ourself
        wp_die( sprintf( __( 'Sorry, you can\'t activate unless you have installed and actived %1$sGenesis%2$s or a %3$sGenesis Child Theme%2$s', 'GFI' ), '<a href="http://wpsmith.net/go/genesis">', '</a>', '<a href="http://wpsmith.net/go/spthemes">' ) );
    }

    $version = gs_truncate( $theme_info['Version'], 3 );

    if ( version_compare( $version, $latest, '<' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) ); // Deactivate ourself
        wp_die( sprintf( __( 'Sorry, you can\'t activate without %1$sGenesis %2$s%3$s or greater', 'GFI' ), '<a href="http://wpsmith.net/go/genesis">', $latest, '</a>' ) );
    }
}

/**
 *
 * Used to cutoff a string to a set length if it exceeds the specified length
 *
 * @author Nick Croft
 * @since 0.1
 * @version 0.2
 * @param string $str Any string that might need to be shortened
 * @param string $length Any whole integer
 * @return string
 */
function gs_truncate( $str, $length=10 ) {

    if ( strlen( $str ) > $length ) {
        return substr( $str, 0, $length );
    } else {
        $res = $str;
    }

    return $res;
}

/**
 * Use shortcodes in widgets
 */
add_filter('widget_text', 'do_shortcode' );

/**
 * Returns the value of anti-spam email.
 *
 * Supported shortcode attributes are:
 *   email,
 *
 * @param array $atts Shortcode attributes
 * @return string Shortcode output
 */
add_shortcode( 'genesis_avatar', 'gsc_avatar_shortcode' );
add_shortcode( 'avatar', 'gsc_avatar_shortcode' );

function gsc_avatar_shortcode( $atts ) {
 
    $defaults = array(
        'id' => '',
        'email' => '',
        'id_or_email' => '',
        'size'        => '96',
        'default'     => '',
        'class'     => '',
    );
    $atts = shortcode_atts( $defaults, $atts );
    if ( empty( $atts['id_or_email'] ) )
        $atts['id_or_email'] = empty( $atts['id'] ) ? $atts['email'] : $atts['id'];
    
    if ( 'alignleft' == $atts['class'] )
        add_filter( 'get_avatar', 'gsc_avatar_add_alignleft' );
    if ( 'alignright' == $atts['class'] )
        add_filter( 'get_avatar', 'gsc_avatar_add_alignright' );
    $avatar = get_avatar( $atts['id_or_email'], $atts['size'], $atts['default'] );
    remove_filter( 'get_avatar', 'gsc_avatar_add_alignleft' );
    remove_filter( 'get_avatar', 'gsc_avatar_add_alignright' );
    return $avatar;
}

function gsc_avatar_add_alignleft( $class ) {
    $class = str_replace( "alignnone", "alignleft", $class );
    // $class = str_replace( "class='avatar", "class='author_gravatar avatar alignleft ", $class );
    return $class;
}
function gsc_avatar_add_alignright( $class ) {
    $class = str_replace( "alignnone", "alignright", $class );
    // $class = str_replace( "class='avatar", "class='author_gravatar avatar alignright ", $class );
    return $class;
}

/**
 * Returns the value of anti-spam email.
 *
 * Supported shortcode attributes are:
 *   email,
 *
 * @param array $atts Shortcode attributes
 * @return string Shortcode output
 */
add_shortcode( 'genesis_email_link', 'gsc_email_link_shortcode' );
add_shortcode( 'email_link', 'gsc_email_link_shortcode' );

function gsc_email_link_shortcode( $atts ) {
 
    $defaults = array(
        'email'  => '',
        'text'   => '',
    );
    $atts = shortcode_atts( $defaults, $atts );
    $atts['text'] = empty( $atts['text'] ) ? $atts['email'] : $atts['text'];
 
    return sprintf( '<a href="%s">%s</a>', antispambot( $atts['email'], 1 ), $atts['text'] );
}

/**
 * Returns the value of anti-spam email.
 *
 * Supported shortcode attributes are:
 *   email,
 *
 * @param array $atts Shortcode attributes
 * @return string Shortcode output
 */
add_shortcode( 'genesis_email', 'gsc_email_shortcode' );
add_shortcode( 'email', 'gsc_email_shortcode' );

function gsc_email_shortcode( $atts ) {
 
    $defaults = array(
        'email'  => '',
    );
    $atts = shortcode_atts( $defaults, $atts );
 
    return antispambot( $atts['email'], 1 );
}

/**
 * Returns the value of modified date of the post.
 *
 * Supported shortcode attributes are:
 *   format (date format),
 *
 * @param array $atts Shortcode attributes
 * @return string Shortcode output
 */
add_shortcode( 'genesis_modified_date', 'gsc_moddate_shortcode' );
add_shortcode( 'modified_date', 'gsc_moddate_shortcode' );

function gsc_moddate_shortcode( $atts ) {
    global $post;
	
	$defaults = array(
        'format'  => get_option( 'date_format' ) . ' ' . get_option( 'time_format' ),
        'post_id' => '',
    );
    $atts = shortcode_atts( $defaults, $atts );
	
	if ( '' == $atts['post_id'] )
		global $post;
	else
		$post = get_post( $atts['post_id'] );
    return date( $atts['format'], strtotime( $post->post_modified ) );
}

/**
 * Returns the value of a custom field.
 *
 * Supported shortcode attributes are:
 *   field (field name),
 *
 * @param array $atts Shortcode attributes
 * @return string Shortcode output
 */
add_shortcode( 'post_field', 'gsc_post_field_shortcode' );

function gsc_post_field_shortcode( $atts ) {
 
    $defaults = array(
        'field'  => '',
    );
    $atts = shortcode_atts( $defaults, $atts );
 
    return genesis_get_custom_field( $atts['field'] );
}

/**
 * Uploads Folder Shortcode
 *
 * @param	null
 * @return	string	Site URL
 */

add_shortcode( 'uploads' , 'gsc_uploads_shortcode' );

function gsc_uploads_shortcode( $atts ) {
	$upload_dir = wp_upload_dir();
	return $upload_dir['baseurl']; 
}

/**
 * URL Shortcode
 *
 * @param	null
 * @return	string	Site URL
 */

add_shortcode( 'url' , 'gsc_url_shortcode' );

function gsc_url_shortcode( $atts ) {
	return get_bloginfo( 'url' );
}

/**
 * WP URL Shortcode
 *
 * @param	null
 * @return	string	WordPress URL
 */

add_shortcode( 'wpurl' , 'gsc_wpurl_shortcode' );

function gsc_wpurl_shortcode( $atts ) {
	return get_bloginfo( 'wpurl' );
}

/**
 * Child Shortcode
 *
 * @param	null
 * @return	string	Child Theme URL
 */

add_shortcode( 'child_url', 'gsc_child_shortcode' );
add_shortcode( 'child', 'gsc_child_shortcode' );

function gsc_child_shortcode( $atts ) {
	return get_bloginfo( 'stylesheet_directory' );
}

add_shortcode( 'site_url' , 'gsc_site_url_shortcode' );
/**
 * URL Shortcode
 *
 * @param	null
 * @return	string	Site URL
 */
function gsc_site_url_shortcode( $atts ) {
	 extract( 
		shortcode_atts(
			array(
			  'site'   => '',
			  'path'   => '',
			  'scheme' => null,
			), 
			$atts
		)
	);
	 
	return get_site_url( $site, $path, $scheme );
}

/**
 * Genesis Comments Shortcode
 *
 * @param	null
 * @return	string	Genesis Comments
 */
 
add_shortcode ('genesis_comments', 'gsc_genesis_comments_shortcode');

function gsc_genesis_comments_shortcode() {
	ob_start();
	genesis_get_comments_template();
	$genesis_comments = ob_get_clean();
	return $genesis_comments;
}

add_action( 'init', 'gsc_replace_genesis_shortcodes' );

function gsc_replace_genesis_shortcodes() {
    if ( shortcode_exists( 'post_author_posts_link' ) ) {
        remove_shortcode( 'post_author_posts_link' );
    }
    add_shortcode( 'post_author_posts_link', 'gsc_genesis_post_author_posts_link_shortcode' );
}
/**
 * Produces the author of the post (link to author archive).
 *
 * Supported shortcode attributes are:
 *   after (output after link, default is empty string),
 *   before (output before link, default is empty string).
 *
 * Output passes through 'genesis_post_author_posts_link_shortcode' filter before returning.
 *
 * @since 1.1.0
 *
 * @param array|string $atts Shortcode attributes. Empty string if no attributes.
 * @return string Shortcode output
 */
function gsc_genesis_post_author_posts_link_shortcode( $atts ) {

	$defaults = array(
		'after'  => '',
		'before' => '',
	);

	$atts = shortcode_atts( $defaults, $atts, 'post_author_posts_link' );

	$author = get_the_author();
	$url    = get_author_posts_url( get_the_author_meta( 'ID' ) );

	if ( genesis_html5() ) {
		$output  = sprintf( '<span %s>', genesis_attr( 'entry-author' ) );
		$output .= $atts['before'];
		$output .= sprintf( '<a href="%s" %s>', $url, genesis_attr( 'entry-author-link' ) );
		$output .= sprintf( '<span %s>', genesis_attr( 'entry-author-name' ) );
		$output .= esc_html( $author );
		$output .= '</span></a>' . $atts['after'] . '</span>';
	} else {
		$link   = sprintf( '<a href="%s" title="%s" rel="author">%s</a>', esc_url( $url ), esc_attr( $author ), esc_html( $author ) );
		$output = sprintf( '<span class="author vcard">%2$s<span class="fn">%1$s</span>%3$s</span>', $link, $atts['before'], $atts['after'] );
	}

	return apply_filters( 'genesis_post_author_posts_link_shortcode', $output, $atts );

}


function gsc_column_shortcode( $column_class, $content, $args = array() ) {
	$defaults = array(
		'class' => '',
		'tag'   => 'div',
	);
	extract( wp_parse_args( $args, $defaults ) );
	$classes = explode( ' ', $class );
	$classes[] = $column_class;
	return sprintf( '<%1$s class="%2$s">%3$s</%1$s>', $tag, implode( ' ', (array)$classes ), do_shortcode ( $content ) );
}

add_shortcode( 'one_half', 'gsc_one_half_shortcode' );
add_shortcode( 'two_fourths', 'gsc_one_half_shortcode' );
add_shortcode( 'three_sixths', 'gsc_one_half_shortcode' );
/**
 * Genesis Content Columns 1/2 Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 1/2
 */
function gsc_one_half_shortcode( $atts, $content ) {

	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'one-half', $content, $atts );
	
}

add_shortcode( 'one_half_first', 'gsc_one_half_first_shortcode' );
add_shortcode( 'two_fourths_first', 'gsc_one_half_first_shortcode' );
add_shortcode( 'three_sixths_first', 'gsc_one_half_first_shortcode' );
/**
 * Genesis Content Columns 1/2 First Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 1/2 (first column)
 */
function gsc_one_half_first_shortcode( $atts, $content ) {

	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'one-half first', $content, $atts );
	
}

add_shortcode( 'one_half_last', 'gsc_one_half_last_shortcode' );
add_shortcode( 'two_fourths_last', 'gsc_one_half_last_shortcode' );
add_shortcode( 'three_sixths_last', 'gsc_one_half_last_shortcode' );
/**
 * Genesis Content Columns 1/2 Last Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 1/2 (last column)
 */
function gsc_one_half_last_shortcode( $atts, $content ) {

	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'one-half last', $content, $atts );
}

/**
 * Genesis Content Columns 1/3 Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 1/3
 */

add_shortcode( 'one_third', 'gsc_one_third_shortcode' );
add_shortcode( 'two_sixths', 'gsc_one_third_shortcode' );

function gsc_one_third_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'one-third', $content, $atts );
}

/**
 * Genesis Content Columns 1/3 First Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 1/3 (first)
 */

add_shortcode( 'one_third_first', 'gsc_one_third_first_shortcode' );
add_shortcode( 'two_sixths_first', 'gsc_one_third_first_shortcode' );

function gsc_one_third_first_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'one-third first', $content, $atts );
}

/**
 * Genesis Content Columns 1/3 Last Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 1/3 (last)
 */

add_shortcode( 'one_third_last', 'gsc_one_third_last_shortcode' );
add_shortcode( 'two_sixths_last', 'gsc_one_third_last_shortcode' );

function gsc_one_third_last_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'one-third last', $content, $atts );
}

/**
 * Genesis Content Columns 2/3 Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 2/3
 */

add_shortcode( 'two_thirds', 'gsc_two_thirds_shortcode' );
add_shortcode( 'four_sixths', 'gsc_two_thirds_shortcode' );

function gsc_two_thirds_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'two-thirds', $content, $atts );
}

/**
 * Genesis Content Columns 2/3 First Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 2/3 (first)
 */

add_shortcode( 'two_thirds_first', 'gsc_two_thirds_first_shortcode' );
add_shortcode( 'four_sixths_first', 'gsc_two_thirds_first_shortcode' );

function gsc_two_thirds_first_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'two-thirds first', $content, $atts );
}

/**
 * Genesis Content Columns 2/3 Last Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 2/3 (last)
 */

add_shortcode( 'two_thirds_last', 'gsc_two_thirds_last_shortcode' );
add_shortcode( 'four_sixths_last', 'gsc_two_thirds_last_shortcode' );

function gsc_two_thirds_last_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'two-thirds last', $content, $atts );
}

/**
 * Genesis Content Columns 1/4 Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 1/4
 */

add_shortcode( 'one_fourth', 'gsc_one_fourth_shortcode' );

function gsc_one_fourth_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'one-fourth', $content, $atts );
}

/**
 * Genesis Content Columns 1/4 First Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 1/4 (first)
 */

add_shortcode( 'one_fourth_first', 'gsc_one_fourth_first_shortcode' );

function gsc_one_fourth_first_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'one-fourth first', $content, $atts );
}

/**
 * Genesis Content Columns 1/4 Last Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 1/4 (last)
 */

add_shortcode( 'one_fourth_last', 'gsc_one_fourth_last_shortcode' );

function gsc_one_fourth_last_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'one-fourth last', $content, $atts );
}

/**
 * Genesis Content Columns 3/4 Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 1/4
 */

add_shortcode( 'three_fourths', 'gsc_three_fourth_shortcode' );

function gsc_three_fourth_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'three-fourths', $content, $atts );
}

/**
 * Genesis Content Columns 3/4 First Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 1/4 (first)
 */

add_shortcode( 'three_fourths_first', 'gsc_three_fourths_first_shortcode' );

function gsc_three_fourths_first_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'three-fourths first', $content, $atts );
}

/**
 * Genesis Content Columns 3/4 Last Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 1/4 (last)
 */

add_shortcode( 'three_fourths_last', 'gsc_three_fourths_last_shortcode' );

function gsc_three_fourths_last_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'three-fourths last', $content, $atts );
}

/**
 * Genesis Content Columns 1/5 Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 1/5
 */

add_shortcode( 'one_fifth', 'gsc_one_fifth_shortcode' );

function gsc_one_fifth_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'one-fifth', $content, $atts );
}

/**
 * Genesis Content Columns 1/5 First Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 1/5 (first)
 */

add_shortcode( 'one_fifth_first', 'gsc_one_fifth_first_shortcode' );

function gsc_one_fifth_first_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'one-fifth first', $content, $atts );
}

/**
 * Genesis Content Columns 1/5 Last Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 1/5 (last)
 */

add_shortcode( 'one_fifth_last', 'gsc_one_fifth_last_shortcode' );

function gsc_one_fifth_last_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'one-fifth last', $content, $atts );
}

/**
 * Genesis Content Columns 2/5 Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 2/5
 */

add_shortcode( 'two_fifths', 'gsc_two_fifths_shortcode' );

function gsc_two_fifths_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'two-fifths', $content, $atts );
}

/**
 * Genesis Content Columns 2/5 First Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 2/5 (first)
 */

add_shortcode( 'two_fifths_first', 'gsc_two_fifths_first_shortcode' );

function gsc_two_fifths_first_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'two-fifths first', $content, $atts );
}

/**
 * Genesis Content Columns 2/5 Last Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 2/5 (last)
 */

add_shortcode( 'two_fifths_last', 'gsc_two_fifths_last_shortcode' );

function gsc_two_fifths_last_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'two-fifths last', $content, $atts );
}

/**
 * Genesis Content Columns 3/5 Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 3/5
 */

add_shortcode( 'three_fifths', 'gsc_three_fifths_shortcode' );

function gsc_three_fifths_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'three-fifths', $content, $atts );
}

/**
 * Genesis Content Columns 3/5 First Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 3/5 (first)
 */

add_shortcode( 'three_fifths_first', 'gsc_three_fifths_first_shortcode' );

function gsc_three_fifths_first_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'three-fifths first', $content, $atts );
}

/**
 * Genesis Content Columns 3/5 Last Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 3/5 (last)
 */

add_shortcode( 'three_fifths_last', 'gsc_three_fifths_last_shortcode' );

function gsc_three_fifths_last_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'three-fifths last', $content, $atts );
}

/**
 * Genesis Content Columns 4/5 Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 4/5
 */

add_shortcode( 'four_fifths', 'gsc_four_fifths_shortcode' );

function gsc_four_fifths_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'four-fifths', $content, $atts );
}

/**
 * Genesis Content Columns 4/5 First Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 4/5 (first)
 */

add_shortcode( 'four_fifths_first', 'gsc_four_fifths_first_shortcode' );

function gsc_four_fifths_first_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'four-fifths first', $content, $atts );
}

/**
 * Genesis Content Columns 4/5 Last Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 4/5 (last)
 */

add_shortcode( 'four_fifths_last', 'gsc_four_fifths_last_shortcode' );

function gsc_four_fifths_last_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'four-fifths last', $content, $atts );
}

/**
 * Genesis Content Columns 1/6 Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 1/6
 */

add_shortcode( 'one_sixth', 'gsc_one_sixth_shortcode' );

function gsc_one_sixth_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'one-sixth', $content, $atts );
}

/**
 * Genesis Content Columns 1/6 first Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 1/6 first
 */

add_shortcode( 'one_sixth_first', 'gsc_one_sixth_first_shortcode' );

function gsc_one_sixth_first_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'one-sixth first', $content, $atts );
}

/**
 * Genesis Content Columns 1/6 Last Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 1/6 last
 */

add_shortcode( 'one_sixth_last', 'gsc_one_sixth_last_shortcode' );

function gsc_one_sixth_last_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'one-sixth last', $content, $atts );
}

/**
 * Genesis Content Columns 5/6 Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 5/6
 */

add_shortcode( 'five_sixths', 'gsc_five_sixths_shortcode' );

function gsc_five_sixths_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'five-sixths', $content, $atts );
}

/**
 * Genesis Content Columns 5/6 First Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 5/6 (first)
 */

add_shortcode( 'five_sixths_first', 'gsc_five_sixths_first_shortcode' );

function gsc_five_sixths_first_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'five-sixths first', $content, $atts );
}

/**
 * Genesis Content Columns 5/6 Last Shortcode
 *
 * @param	content
 * @return	string	HTML Genesis Content Columns 5/6 (last)
 */

add_shortcode( 'five_sixths_last', 'gsc_five_sixths_last_shortcode' );

function gsc_five_sixths_last_shortcode( $atts, $content ) {
	
	$atts = shortcode_atts( array(
		'class'   => '',
		'tag'     => 'div',
	) , $atts );

	return gsc_column_shortcode( 'five-sixths last', $content, $atts );
}

add_shortcode( 'plugin_info', 'gsc_plugin_info_shortcode' );
/**
 * Genesis Get Plugin Information.
 *
 * @param	array  $atts Shortcode attributes
 * @return	string       Plugin information
 */
add_shortcode( 'plugin_info', 'gsc_plugin_info_shortcode' );
function gsc_plugin_info_shortcode( $atts, $content ) {
	global $gsc_plugins;

	$defaults = array(
        'slug'      => '',
        'data'      => '', //name, version, author (linked), author_profile, contributors, banner
        'section'   => '',
        'transient' => true,
        'time'      => 60 * 60 * 24,
    );
    extract( shortcode_atts( $defaults, $atts ) );
    if ( '' === $slug || ( '' === $data && '' === $section ) ) {
    	return '';
    }

    $transient = apply_filters( 'gsc_plugins_info_transient', $transient, $slug );
    $time      = apply_filters( 'gsc_plugins_info_transient_time', $time, $slug );

    // Cache Plugin Readme Information for faster delivery
    if ( isset( $gsc_plugins[ $slug ] ) ) {
    	$readme = $gsc_plugins[ $slug ];
    } else {

		if ( false === ( $readme = get_transient( 'gsc_plugin_' . $slug ) ) ) {
		    require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
		    $readme = plugins_api( 'plugin_information', array('slug' => $slug, 'fields' => array( 'short_description' => true, 'banners' => true, ) ) );
		    $gsc_plugins[ $slug ] = $readme;
		    
		    if ( $transient ) {
		    	set_transient( 'gsc_plugin_' . $slug, $readme, $time );
		    }
		}
	}

	$output = '';
	switch ( $data ) {
		case 'banner_url':
		case 'banner':
			if ( isset( $gsc_plugins[ $slug ]->banner ) ) {
				return $gsc_plugins[ $slug ]->banner;
			}

			if ( false === ( $banner_url = get_transient( 'gsc_plugin_banner_' . $slug ) ) ) {
			    $banner_url = '';
			    foreach ( array( 'png', 'jpg', ) as $extension ) {
					$url = "http://plugins.svn.wordpress.org/{$slug}/assets/banner-772x250.{$extension}";
					$result = wp_remote_head( $url );
					if ( !is_wp_error( $result ) && 200 == $result['response']['code'] ) {
						if ( ! isset( $gsc_plugins[ $slug ] ) || ! is_object( $gsc_plugins[ $slug ] ) ) {
							$gsc_plugins[ $slug ] = (object)array();
						}
						$gsc_plugins[ $slug ]->banner = $url;
						return $url;
					}
				}
			}

			return $banner_url;
		case 'tags':
			$t = array();
	    	foreach( $readme->tags as $tag ) {
	    		$t[] =
	    		sprintf( '<a href="http://wordpress.org/plugins/tags/%s">%s</a>', $tag, str_replace( '-', ' ', $tag ) );
	    	}
	    	return implode( ', ', $t );
    	case 'contributors':
    		foreach( $readme->contributors as $username => $profile ) {
	    		$output .= sprintf( '<a href="%s">%s</a>', $profile, $username );
	    	}
	    	return $output;
    	case 'sections':
    		foreach ( $readme->sections as $section => $d ) {
    			$output .= sprintf( '<h3>%s</h3>', ucwords( $section ) ) . $d;
    		}
    		return $output;
		case 'downloaded':
			return number_format( $readme->{$data} );
		case 'rating':
			return $readme->{$data} . '/100';
    	default:
    		return $readme->{$data};
    		break;
	}

	if ( '' !== $section ) {
		return $readme->sections[ $section ];
    }
    return '';
}
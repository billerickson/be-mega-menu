<?php
/**
 * Plugin Name: BE Mega Menu
 * Plugin URI:  https://github.com/billerickson/be-mega-menu
 * Description: Use a visual editor for managing mega menu dropdowns
 * Author:      Bill Erickson
 * Author URI:  http://www.billerickson.net
 * Version:     1.0.0
 *
 * BE Mega Menu is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * BE Mega Menu is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with BE Mega Menu. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    BE_Mega_Menu
 * @author     Bill Erickson
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2015
 */

/**
 * Register Mega Menu post type
 *
 */
function be_mega_menu_cpt() {

	$labels = array( 
		'name'               => 'Mega Menus',
		'singular_name'      => 'Mega Menu',
		'add_new'            => 'Add New',
		'add_new_item'       => 'Add New Mega Menu',
		'edit_item'          => 'Edit Mega Menu',
		'new_item'           => 'New Mega Menu',
		'view_item'          => 'View Mega Menu',
		'search_items'       => 'Search Mega Menus',
		'not_found'          => 'No Mega Menus found',
		'not_found_in_trash' => 'No Mega Menus found in Trash',
		'parent_item_colon'  => 'Parent Mega Menu:',
		'menu_name'          => 'Mega Menus',
	);

	$args = array( 
		'labels'              => $labels,
		'hierarchical'        => false,
		'supports'            => array( 'title', 'editor', 'revisions' ),   
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => 'themes.php',
		'show_in_nav_menus'   => false,
		'publicly_queryable'  => true,
		'exclude_from_search' => true,
		'has_archive'         => false,
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => array( 'slug' => 'megamenu', 'with_front' => false ),
		'menu_icon'           => 'dashicons-editor-table', // https://developer.wordpress.org/resource/dashicons/
	);

	register_post_type( 'megamenu', apply_filters( 'be_mega_menu_post_type_args', $args ) );

}
add_action( 'init', 'be_mega_menu_cpt' );

/**
 * Display Mega Menus 
 *
 */
function be_mega_menu_display( $item_output, $item, $depth, $args ) {

	$theme_location = apply_filters( 'be_mega_menu_location', 'header' );
	
	if( ! ( $theme_location == $args->theme_location && 0 == $depth ) )
		return $item_output;
		
	$submenu_object = get_page_by_title( $item->title, false, 'megamenu' );
	if( !empty( $submenu_object ) && ! is_wp_error( $submenu_object ) ) {
		
		$submenu = '<div class="sub-menu"><div class="wrap">' . $submenu_object->post_content . '</div></div>';
		$item_output = str_replace( '</a>', '</a>' . $submenu, $item_output );

	}
			
	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'be_mega_menu_display', 10, 4 );

/**
 * Limit Menu Depth
 *
 */
function be_mega_menu_limit_depth( $args ) {

	$theme_location = apply_filters( 'be_mega_menu_location', 'header' );

	if( $theme_location == $args['theme_location'] )
		$args['depth'] = 1;
		
	return $args;
}
add_filter( 'wp_nav_menu_args', 'be_mega_menu_limit_depth' );
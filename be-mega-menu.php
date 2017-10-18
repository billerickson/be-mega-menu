<?php
/**
 * Plugin Name: BE Mega Menu
 * Plugin URI:  https://github.com/billerickson/be-mega-menu
 * Description: Use a visual editor for managing mega menu dropdowns
 * Author:      Bill Erickson
 * Author URI:  http://www.billerickson.net
 * Version:     1.1.0
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


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Main class
 *
 * @since 1.1.0
 * @package BE_Mega_Menu
 */
final class BE_Mega_Menu {

	/**
	 * Instance of the class.
	 *
	 * @since 1.1.0
	 * @var object
	 */
	private static $instance;

	/**
	 * Plugin version.
	 *
	 * @since 1.1.0
	 * @var string
	 */
	private $version = '1.1.0';

	/**
	 * Menu Location
	 *
	 */
	public $menu_location = 'header';

	/**
	 * Plugin Instance.
	 *
	 * @since 1.1.0
	 * @return BE_Mega_Menu
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof BE_Mega_Menu ) ) {
			self::$instance = new BE_Mega_Menu;
			add_action( 'init', array( self::$instance, 'init' ) );
		}
		return self::$instance;
	}

	/**
	 * Initialize
	 *
	 * @since 1.1.0
	 */
	function init() {

		// Set new location
		$this->menu_location = apply_filters( 'be_mega_menu_location', $this->menu_location );

		add_action( 'init', array( $this, 'register_cpt' ), 20 );
		add_filter( 'wp_nav_menu_args', array( $this, 'limit_menu_depth' ) );
		add_filter( 'nav_menu_css_class', array( $this, 'menu_item_classes' ), 10, 4 );
		add_filter( 'walker_nav_menu_start_el', array( $this, 'display_mega_menus' ), 10, 4 );

	}

	/**
	 * Register Mega Menu post type
	 *
	 */
	function register_cpt() {

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

	/**
	 * Limit Menu Depth
	 *
	 */
	function limit_menu_depth( $args ) {

		if( $this->menu_location == $args['theme_location'] )
			$args['depth'] = 1;

		return $args;
	}

	/**
	 * Menu Item Classes
	 *
	 */
	function menu_item_classes( $classes, $item, $args, $depth ) {

		if( $this->menu_location != $args->theme_location )
			return $classes;

		if( in_array( 'menu-item-has-children', $classes ) )
			$classes = array_diff( $classes, array( 'menu-item-has-children' ) );

		if( $this->mega_menu( $item ) )
			$classes[] = 'menu-item-has-children';

		return $classes;
	}

	/**
	 * Display Mega Menus
	 *
	 */
	function display_mega_menus( $item_output, $item, $depth, $args ) {

		if( ! ( $this->menu_location == $args->theme_location && 0 == $depth ) )
			return $item_output;

		$submenu_object = $this->mega_menu( $item );

		if( !empty( $submenu_object ) && ! is_wp_error( $submenu_object ) ) {

			$opening_markup = apply_filters( 'be_mega_menu_opening_markup', '<div class="mega-menu"><div class="wrap">' );
			$closing_markup = apply_filters( 'be_mega_menu_closing_markup', '</div></div>' );

			$submenu = $opening_markup . apply_filters( 'ea_the_content', $submenu_object->post_content ) . $closing_markup;
			$item_output = str_replace( '</a>', '</a>' . $submenu, $item_output );

		}

		return $item_output;
	}

	/**
	 * Mega Menu
	 *
	 */
	function mega_menu( $item ) {

		$submenu_object = false;
		foreach( $item->classes as $class ) {
			if( strpos( $class, 'megamenu-' ) !== false )
				$submenu_object = get_post( intval( str_replace( 'megamenu-', '', $class ) ) );
		}

		if( ! $submenu_object )
			$submenu_object = get_page_by_title( $item->title, false, 'megamenu' );

		// WPML Support
		if( function_exists( 'icl_object_id' ) && $submenu_object ) {
			$translation = icl_object_id( $submenu_object->ID, 'megamenu', false );
			if( $translation ) {
				$submenu_object = get_post( intval( $translation ) );
			}
		}

		return $submenu_object;

	}
}

/**
 * The function provides access to the internal methods.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * @since 1.1.0
 * @return object
 */
function be_mega_menu() {
	return BE_Mega_Menu::instance();
}
be_mega_menu();

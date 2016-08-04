# BE Mega Menu #
**Contributors:** billerickson  
**Requires at least:** 4.1  
**Tested up to:** 4.5.3  
**Stable tag:** 1.0.0  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html

BE Mega Menu lets you use a visual editor for managing mega menu dropdowns. 

Go to Appearance > Menus to create your top level menu items. Go to Appearance > Mega Menus to manage the mega menu dropdowns. Create a Mega Menu with the same title as the top level menu item under which it should appear.

This plugin does not add any CSS, so you'll need to customize the styling in your theme. Here is [sample CSS](https://gist.github.com/billerickson/c6c27cd06d9e24305f7d1d2fd8e46425).

## Screenshots ##

#### Backend
![backend screenshot](https://d3vv6lp55qjaqc.cloudfront.net/items/2a2o0R3V3l3y0v1S3U40/Screen%20Shot%202016-08-04%20at%2012.52.55%20PM.png?v=6ba79d66)

#### Frontend
![frontend screenshot](https://d3vv6lp55qjaqc.cloudfront.net/items/180v3e0A1D0E2D3y3R2d/Screen%20Shot%202016-08-04%20at%2012.53.25%20PM.png?v=e6d40355)

## Customization ##

The mega menu applies to the 'header' menu theme location. You can change this using the `be_mega_menu_location` filter. Example:

```php
/**
 * Mega Menu on Primary Menu 
 *
 */
function ea_primary_mega_menu( $theme_location ) {
	return 'primary';
}
add_filter( 'be_mega_menu_location', 'ea_primary_mega_menu' );
```

The opening markup on mega menus is `<div class="mega-menu"><div class="wrap">`. This can be customized using the `be_mega_menu_opening_markup` filter.

The mega menu content can be customized using the `ea_the_content` filter. [See this example](http://www.billerickson.net/code/duplicate-the_content-filters/) to duplicate the filters on `the_content`

The closing markup on mega menus is `</div></div>`. This can be customized using the `be_mega_menu_closing_markup` filter.

You can customize the post type arguments used to create the Mega Menu post type with the `be_mega_menu_post_type_args` filter.

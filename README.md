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

## Customization ##

The mega menu applies to the 'header' menu theme location. You can change this using the `be_mega_menu_location` filter.

The opening markup on mega menus is `<div class="mega-menu"><div class="wrap">`. This can be customized using the `be_mega_menu_opening_markup` filter.

The closing markup on mega menus is `</div></div>`. This can be customized using the `be_mega_menu_closing_markup` filter.

You can customize the post type arguments used to create the Mega Menu post type with the `be_mega_menu_post_type_args` filter.
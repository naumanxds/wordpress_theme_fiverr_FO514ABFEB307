<?php
/**
 * Functions to provide support for the One Click Demo Import plugin (wordpress.org/plugins/one-click-demo-import)
 *
 * @package Life_In_Balance
 * @since 1.07
 */


/**
 * Set import files
 */
if ( !function_exists( 'life_in_balance_set_import_files' ) ) {
    function life_in_balance_set_import_files() {
        return array(
            //Default Life_In_Balance demo
            array(
                'import_file_name'              => __('Demo content', 'life_in_balance'),
                'local_import_file'             => ST_DIR . 'demo-content/demo-content.xml',           
                'local_import_widget_file'      => ST_DIR . 'demo-content/demo-widgets.wie',
                'local_import_customizer_file'  => ST_DIR . 'demo-content/demo-customizer.dat',           
            ),
        );
    }
} 
add_filter( 'pt-ocdi/import_files', 'life_in_balance_set_import_files' );

/**
 * Define actions that happen after import
 */
if ( !function_exists( 'life_in_balance_set_after_import_mods' ) ) {
    function life_in_balance_set_after_import_mods() {

        //Assign the menu
        $main_menu = get_term_by( 'name', 'Main', 'nav_menu' );
        set_theme_mod( 'nav_menu_locations', array(
                'primary' => $main_menu->term_id,
            )
        );

        //Asign the static front page and the blog page
        $front_page = get_page_by_title( 'Home' );
        $blog_page  = get_page_by_title( 'Blog' );

        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', $front_page -> ID );
        update_option( 'page_for_posts', $blog_page -> ID );

        //Assign the Front Page template
        update_post_meta( $front_page -> ID, '_wp_page_template', 'page-templates/page_front-page.php' );
    }
}
add_action( 'pt-ocdi/after_import', 'life_in_balance_set_after_import_mods' );

/**
* Remove branding
*/
add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );
<?php
  /*
Plugin Name: Table Colum Restriction
Plugin URI: 
Description: This plugin is created for datatable colum show or hide .
Version: 1.0.0
Author: Seerox
Author URI: http://seerox.com
*/


function get_version_from_srx( $url, $old_version ) {

	$url = $http_url = $url.http_build_query( compact( 'version', 'locale' ), null, '&' );

	dump($url);

	dump($old_version);

	if ( $ssl = wp_http_supports( array( 'ssl' ) ) )
		$url = set_url_scheme( $url, 'https' );

	$options = array(
		'timeout' => wp_doing_cron() ? 30 : 3,
	);

	$response = wp_remote_get( $url, $options );

	if ( $ssl && is_wp_error( $response ) ) {
		trigger_error(
			sprintf(
				/* translators: %s: support forums URL */
				__( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="%s">support forums</a>.' ),
				__( 'https://wordpress.org/support/' )
			) . ' ' . __( '(WordPress could not establish a secure connection to WordPress.org. Please contact your server administrator.)' ),
			headers_sent() || WP_DEBUG ? E_USER_WARNING : E_USER_NOTICE
		);
		$response = wp_remote_get( $http_url, $options );
	}



	if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) )
		return false;

	$body = trim( wp_remote_retrieve_body( $response ) );
	$body = json_decode( $body, true );

	if ( ! isset( $body['version'] ) ){
		return false;
	}

	return $body['version'];
}


function get_srx_exists_plugin_version($plugin_Dir, $plugin_Filename) {
	
	$plugins = get_site_transient( 'update_plugins' );
	return $plugins->checked[$plugin_Dir.'/'.$plugin_Filename] ;
}

 $srx_version_dir = 'https://www.seerox.com/srx_plugin_version/version.txt';
 $srx_exists_plugin_dir = 'table-colum-restriction';
 $srx_exists_plugin_file_name = 'table-colum-restriction.php';

echo get_version_from_srx(
	 $srx_version_dir ,
	 get_srx_exists_plugin_version($srx_exists_plugin_dir, $srx_exists_plugin_file_name)
);



// require_once 'E:\xampp\htdocs\forceSell\wp-admin\includes\list-table.php';

// require_once 'E:\xampp\htdocs\forceSell\wp-admin\includes\update.php';

// /** @var WP_Plugins_List_Table $wp_list_table */
// 	$wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );

// dump($wp_list_table );





function data_table_show(){

ob_start();

   if (is_user_logged_in()){

      echo do_shortcode('[product_table columns="sku,name,att:color,att:size,att:edition" width="100px,auto,auto" shortcodes="true" product_limit="4000" lazy_load="true"]');
   }else{

     echo do_shortcode('[product_table columns="sku,name" width="100px,auto,auto" shortcodes="true" product_limit="4000" lazy_load="true"]');

   }     

return ob_get_clean();

}
add_shortcode('NEW_CAPABILITIES', 'data_table_show');


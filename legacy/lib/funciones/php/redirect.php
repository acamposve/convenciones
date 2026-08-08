<?
#=============================================================================
# lib/funcs/redirect.php   
#		Fully compliant HTTP redirect
#
#		Version: 1.0
#		Author: Sebastian Delmont <sdelmont@loquesea.com>
#		Date: 2000-03-10
#		License: this file is distributed under the UCO License
#		         Use 'Coño' Once and you can use it.
#=============================================================================

if( ! defined( '_LIB_FUNCS_REDIRECT' )) {
	define( '_LIB_FUNCS_REDIRECT', '1.0' );

	function Redirect( $url, $status = "302 Found" ) {
		header( "Status: $status" );
		header( "Location: $url" );
	}
}
?>
<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | https://github.com/hybridauth/hybridauth
*  (c) 2009-2011 HybridAuth authors | hybridauth.sourceforge.net/licenses.html
*/

// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

return 
	array(
//		"base_url" => "http://cahier-sauvage.boo.jp/blm_dev/hybridauth/", 
		"base_url" => "http://booklovesmusic.com/hybridauth/", 

		"providers" => array ( 
			// openid providers
			"OpenID" => array (
				"enabled" => true
			),

			"Yahoo" => array ( 
				"enabled" => true 
			),

			"AOL"  => array ( 
				"enabled" => true 
			),

			"Google" => array ( 
				"enabled" => true,
				"keys"    => array ( "id" => "", "secret" => "" ),
				"scope"   => ""
			),

			"Facebook" => array ( 
				"enabled" => true,
				"keys"    => array ( "id" => "214452115301088", "secret" => "83d85bb1aec46c918573017b5ae1a47c" ),
				"scope"   => ""
			),

			"Twitter" => array ( 
				"enabled" => true,
				"keys"    => array ( "key" => "AvaxawWUKNZTyUAx9dCURA", "secret" => "49r88KhFfbcBVtAD3XA40WovkrGAMbzGZ76qgSyQ" ) 
//				"keys"    => array ( "key" => "JhcWhypaWoUC9bhgbX35ew", "secret" => "anNWx1MsmzNL8BZwfZRrLqnnC2aiwZGFqKb6h0kMM" ) 
			),

			// windows live
			"Live" => array ( 
				"enabled" => true,
				"keys"    => array ( "id" => "", "secret" => "" ) 
			),

			"MySpace" => array ( 
				"enabled" => true,
				"keys"    => array ( "key" => "", "secret" => "" ) 
			),

			"LinkedIn" => array ( 
				"enabled" => true,
				"keys"    => array ( "key" => "", "secret" => "" ) 
			),

			"Foursquare" => array (
				"enabled" => true,
				"keys"    => array ( "id" => "", "secret" => "" ) 
			),
		),

		// if you want to enable logging, set 'debug_mode' to true  then provide a writable file by the web server on "debug_file"
		"debug_mode" => false,

		"debug_file" => "",
	);

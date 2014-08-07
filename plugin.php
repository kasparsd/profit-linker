<?php
/*
	Plugin Name: Envato Affiliate Linker
	Description: Adds your Envato affiliate link to all links to all Envato stores.
	Author: Kaspars Dambis
	Author URI: http://kaspars.net
	Version: 1.0
*/


add_action( 'wp_enqueue_scripts', 'enqueue_envato_aff_linker' );

function enqueue_envato_aff_linker() {

	$ref = get_option( 'envato_aff_ref' );

	if ( empty( $ref ) )
		$ref = 'Preseto';

	$domains = array(
		'codecanyon.net',
		'themeforest.com'
	);

	wp_enqueue_script(
		'envato-aff-linker',
		plugins_url( '/envato-link.js', __FILE__ ),
		null,
		'1.0',
		true
	);

	wp_localize_script(
		'envato-aff-linker',
		'envato_aff_data',
		array(
			'ref' => $ref,
			'domains' => $domains
		)
	);

}

add_action( 'admin_init', 'envato_aff_linker_settings' );

function envato_aff_linker_settings() {

	add_settings_field(
		'envato_aff_ref',
		__( 'Your Envato Username', 'envato-aff-linker' ),
		'envato_aff_linker_settings_field',
		'general'
	);

	register_setting( 'general', 'envato_aff_ref' );

}

function envato_aff_linker_settings_field( $args ) {

	$value = get_option( 'envato_aff_ref' );

	printf( 
		'<input type="text" name="envato_aff_ref" value="%s" />
		<p class="description">%s</p>',
		esc_attr( $value ),
		__( 'It will be appended to URLs as <code>ref=username</code>.', 'envato-aff-linker' )
	);

}





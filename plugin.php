<?php
/*
	Plugin Name: Profit Affiliate Linker
	Description: Automatically turns all Envato, Amazon, eBay, etc. store links into affiliate links.
	Author: Kaspars Dambis
	Author URI: http://kaspars.net
	Version: 1.1
*/


profitAffLinker::instance();

class profitAffLinker {

	private static $instance;
	private $affs;
	private $settings;

	private function __construct() {

		add_action( 'init', array( $this, 'init' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );

	}

	public static function instance() {

		if ( ! self::$instance )
			self::$instance = new self();

		return self::$instance;

	}

	function init() {

		$this->affs = array(
				'envato' => array(
					'label' => __( 'Envato' ),
					'domains' => array(
						'codecanyon.net',
						'themeforest.net',
						'graphicriver.net',
						'videohive.net',
						'photodune.net',
						'3docean.net',
						'audiojungle.net',
						'activeden.net'
					),
					'arg' => 'ref'
				),
				'amazon' => array(
					'label' => __( 'Amazon' ),
					'domains' => array(
						'amazon.'
					),
					'arg' => 'tag'
				),
				'ebay' => array(
					'label' => __( 'eBay' ),
					'domains' => array(
						'ebay.'
					),
					'arg' => 'pub'
				)
			);

		$this->settings = wp_parse_args(
				get_option( 'profit_aff_settings' ),
				array(
					'networks' => array()
				)
			);

	}

	function enqueue_scripts() {
		
		wp_enqueue_script(
			'profit-aff-linker',
			plugins_url( '/profit-linker.js', __FILE__ ),
			null,
			'1.1',
			true
		);

		$settings = array();

		foreach ( $this->affs as $network => $aff_settings )
			foreach ( $aff_settings['domains'] as $domain )
				if ( isset( $this->settings['networks'][ $network ]['tag'] ) && ! empty( $this->settings['networks'][ $network ]['tag'] ) )
					$settings[ $domain ] = array(
							$aff_settings['arg'] => $this->settings['networks'][ $network ]['tag']
						);

		wp_localize_script(
			'profit-aff-linker',
			'profit_linker',
			$settings
		);

	}



	function register_settings() {

		add_settings_field(
			'profit_aff_settings',
			__( 'Profit Linker Settings', 'profit-aff-linker' ),
			array( $this, 'admin_settings' ),
			'general'
		);

		register_setting( 'general', 'profit_aff_settings' );

	}

	function admin_settings( $args ) {

		$items = array();

		foreach ( $this->affs as $network => $aff_settings ) {

			if ( isset( $this->settings['networks'][ $network ]['tag'] ) )
				$tag = $this->settings['networks'][ $network ]['tag'];
			else
				$tag = '';

			$items[] = sprintf(
					'<li>
						<label>
							<strong>%s</strong>
							<input type="text" name="profit_aff_settings[networks][%s][tag]" value="%s" />
						</label>
					</li>',
					esc_html( $aff_settings['label'] ),
					esc_attr( $network ),
					esc_attr( $tag )
				);

		}

		printf( '<ul>%s</ul>', implode( '', $items ) );

	}

}


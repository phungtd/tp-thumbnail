<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://tpcrawl.com
 * @since      1.0.0
 *
 * @package    Tp_Thumbnail
 * @subpackage Tp_Thumbnail/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Tp_Thumbnail
 * @subpackage Tp_Thumbnail/public
 * @author     Phung Tran <contact@tpcrawl.com>
 */
class Tp_Thumbnail_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tp_Thumbnail_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tp_Thumbnail_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tp-thumbnail-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tp_Thumbnail_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tp_Thumbnail_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tp-thumbnail-public.js', array( 'jquery' ), $this->version, false );

	}

	public function has_post_thumbnail() {
		return true;
	}

	public function create_fake_thumbnail( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
		$options = get_option( 'tp_thumbnail' );
		$replace = $options['force'] ?? '';
		if ( $html == '' || $replace == 1 ) {
			$title           = get_the_title( $post_id );
			$custom          = get_post_meta( $post_id, 'tp_thumbnail_text', true );
			$options['text'] = empty( $custom ) ? $title : $custom;

			$html = sprintf(
				'<img src="%s" alt="%s">',
				$this->draw_text( $options ),
				esc_attr( $title )
			);
		}

		return $html;
	}

	private function draw_text( $args ) {
		global $tp_thumbnail_rand;
		$tp_thumbnail_rand ++;

		$args['width']      = $args['width'] ?? 1200;
		$args['height']     = $args['height'] ?? floor( $args['width'] / 1.91 );
		$args['fontName']   = $args['fontName'] ?? 'Oswald-Regular';
		$args['fontName']   = __DIR__ . '/font/' . implode( '/', explode( '-', $args['fontName'] ) ) . '.ttf';
		$args['fontSize']   = $args['fontSize'] ?? ( $args['width'] / 1200 ) * 64;
		$args['color']      = $args['color'] ?? '#ffffff';
		$args['background'] = $args['background'] ?? '#003f5c';
		$args['background'] = array_map( 'trim', explode( ',', $args['background'] ) );
		$args['background'] = $args['background'][ $tp_thumbnail_rand % count( $args['background'] ) ];

		$args['shadow'] = $args['shadow'] ?? '#000000';
		$args['text']   = str_replace( "\xc2\xa0", ' ', $args['text'] );
		$args['text']   = mb_strtoupper( $args['text'], 'UTF-8' );

		$tmp        = imagettfbbox( $args['fontSize'], 0, $args['fontName'], 'j' );
		$charHeight = ( $tmp[1] - $tmp[5] );
		$lineHeight = $charHeight * 1.5;

		if ( strpos( $args['text'], "\n" ) ) {
			$lines = array_map( 'trim', explode( "\n", $args['text'] ) );
		} else {
			$words = array_map( 'trim', explode( " ", $args['text'] ) );
			$lines = array();
			$lineN = 0;
			foreach ( $words as $w ) {
				if ( ! isset( $lines[ $lineN ] ) ) {
					$lines[ $lineN ] = '';
				}

				$tmp = imagettfbbox( $args['fontSize'], 0, $args['fontName'], implode( ' ', array(
					$lines[ $lineN ],
					$w
				) ) );

				if ( ( $tmp[4] - $tmp[0] ) <= ( $args['width'] * 0.8 ) ) {
					$lines[ $lineN ] = implode( ' ', array( $lines[ $lineN ], $w ) );
				} else {
					$lines[ ++ $lineN ] = $w;
				}
			}
		}

		$topY = ( $args['height'] - count( $lines ) * $lineHeight ) / 2;

		$posY = array();
		foreach ( $lines as $k => $v ) {
			$posY[ $k ] = $topY + ( $lineHeight * $k ) + $charHeight;
		}

		$posX = array();
		foreach ( $lines as $k => $line ) {
			$tmp        = imagettfbbox( $args['fontSize'], 0, $args['fontName'], $line );
			$posX[ $k ] = ( $tmp[4] - $tmp[0] );
		}

		foreach ( $posX as $k => $v ) {
			$posX[ $k ] = ( $args['width'] - $v ) / 2;
		}

		$img = imagecreate( $args['width'], $args['height'] );

		list( $r, $g, $b ) = sscanf( $args['background'], "#%02x%02x%02x" );
		imagecolorallocate( $img, $r, $g, $b ); // background

		list( $r, $g, $b ) = sscanf( $args['color'], "#%02x%02x%02x" );
		$color = imagecolorallocate( $img, $r, $g, $b );

		list( $r, $g, $b ) = sscanf( $args['shadow'], "#%02x%02x%02x" );
		$shadow = imagecolorallocate( $img, $r, $g, $b );

		foreach ( $lines as $k => $line ) {
			imagettftext( $img, $args['fontSize'], 0, $posX[ $k ] + 1, $posY[ $k ] + 1, $shadow, $args['fontName'], $line );
			imagettftext( $img, $args['fontSize'], 0, $posX[ $k ], $posY[ $k ], $color, $args['fontName'], $line );
		}
		ob_start();
		imagepng( $img );

		return 'data:image/png;base64, ' . base64_encode( ob_get_clean() );
	}

	private function mb_wordwrap( $str, $width = 75, $break = "\n", $cut = false ) {
		$lines = explode( $break, $str );
		foreach ( $lines as &$line ) {
			$line = rtrim( $line );
			if ( mb_strlen( $line ) <= $width ) {
				continue;
			}
			$words  = explode( ' ', $line );
			$line   = '';
			$actual = '';
			foreach ( $words as $word ) {
				if ( mb_strlen( $actual . $word ) <= $width ) {
					$actual .= $word . ' ';
				} else {
					if ( $actual != '' ) {
						$line .= rtrim( $actual ) . $break;
					}
					$actual = $word;
					if ( $cut ) {
						while ( mb_strlen( $actual ) > $width ) {
							$line   .= mb_substr( $actual, 0, $width ) . $break;
							$actual = mb_substr( $actual, $width );
						}
					}
					$actual .= ' ';
				}
			}
			$line .= trim( $actual );
		}

		return implode( $break, $lines );
	}

}

global $tp_thumbnail_rand;
$tp_thumbnail_rand = 0;

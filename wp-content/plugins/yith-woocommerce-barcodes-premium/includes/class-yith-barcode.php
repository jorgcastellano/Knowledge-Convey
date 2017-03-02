<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


if ( ! class_exists( 'YITH_Barcode' ) ) {

	/**
	 *
	 * @class   YITH_Barcode
	 * @package Yithemes
	 * @since   1.0.0
	 * @author  Your Inspiration Themes
	 */
	class YITH_Barcode {

		/**
		 * @var int the object(Order or Product) id related to the current barcode
		 */
		public $object_id;

		/**
		 * @var string barcode protocol
		 */
		private $protocol;

		/**
		 * @var string barcode value
		 */
		private $value;

		/**
		 * @var string the value being displayed
		 */
		private $display_value;

		/**
		 * @var string the filename of the image
		 */
		//todo save an external reference to a file  public $image_filename = '';

		/**
		 * Constructor
		 *
		 * Initialize plugin and registers actions and filters to be used
		 *
		 * @param int $object_id
		 *
		 * @since  1.0
		 * @author Lorenzo Giuffrida
		 */
		public function __construct( $object_id = 0 ) {

			if ( $object_id ) {
				$this->object_id = $object_id;

				$this->protocol       = get_post_meta( $object_id, YITH_YWBC_META_KEY_BARCODE_PROTOCOL, true );
				$this->value          = get_post_meta( $object_id, YITH_YWBC_META_KEY_BARCODE_VALUE, true );
				$this->display_value  = get_post_meta( $object_id, YITH_YWBC_META_KEY_BARCODE_DISPLAY_VALUE, true );
				$this->image          = get_post_meta( $object_id, YITH_YWBC_META_KEY_BARCODE_IMAGE, true );
				$this->image_filename = get_post_meta( $object_id, YITH_YWBC_META_KEY_BARCODE_FILENAME, true );
			}
		}

		/**
		 * Retrieve the barcode by id
		 *
		 * @param int $id
		 *
		 * @return YITH_Barcode
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		public static function get( $id ) {
			return new YITH_Barcode( $id );
		}

		/**
		 * Retrieve current formatted value
		 * @return mixed|string
		 */
		public function get_display_value() {
			return $this->display_value;
		}

		/**
		 * Retrieve current formatted value
		 * @return mixed|string
		 */
		public function get_protocol() {
			return $this->protocol;
		}

		/**
		 * save the barcode instance
		 *
		 */
		public function save() {
			if ( $this->object_id ) {

				update_post_meta( $this->object_id, YITH_YWBC_META_KEY_BARCODE_PROTOCOL, $this->protocol );
				update_post_meta( $this->object_id, YITH_YWBC_META_KEY_BARCODE_VALUE, $this->value );
				update_post_meta( $this->object_id, YITH_YWBC_META_KEY_BARCODE_DISPLAY_VALUE, $this->display_value );
				update_post_meta( $this->object_id, YITH_YWBC_META_KEY_BARCODE_IMAGE, $this->image );
				update_post_meta( $this->object_id, YITH_YWBC_META_KEY_BARCODE_FILENAME, $this->image_filename );
			}
		}

		/**
		 * Generate a barcode image
		 *
		 * @param string $protocol
		 * @param string $value
		 * @param string $path
		 */
		public function generate( $protocol, $value, $path = '' ) {

			$this->protocol       = $protocol;
			$this->value          = $value;
			$this->image_filename = $path;

			if ( 'qrcode' == strtolower( $this->protocol ) ) {
				$this->create_qrcode_image();
			} else {
				$this->create_barcode_image();
			}
		}

		/**
		 * Retrieve if the barcode exists for the current object
		 * @return bool
		 */
		public function exists() {
			return $this->image_filename || $this->image;
		}

		public static function get_protocols() {
			$defaults = array(
				'EAN13'   => 'EAN-13',
				'EAN8'    => 'EAN-8',
				'STD25'   => 'STD 25',
				'INT25'   => 'INT 25',
				'CODE39'  => 'CODE 39',
				'code93'  => 'CODE 93',
				'code128' => 'CODE 128',
				'Codabar' => 'Codabar',
				'QRcode'  => 'QR code',
			);

			return $defaults;
		}

		/**
		 * Check if the value is in a valid format and fix it if possible
		 *
		 * @param string $protocol
		 * @param string $value
		 *
		 * @return null|string
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 */
		private function formatted_value( $protocol, $value ) {
			/*
			 * //  return a value obtained appending [sum of single order_id digit] and the order_id
						return array_sum( str_split( $order->id ) ) . $order->id;
			 */
			$formatted_value = $value;

			switch ( strtolower( $protocol ) ) {
				case 'ean8' :
					$formatted_value = sprintf( '%07d', $value );
					break;
				case 'ean13' :
					$formatted_value = sprintf( '%012d', $value );
					break;
			}

			return $formatted_value;
		}

		/**
		 * Create a QR code image
		 *
		 * @return string
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 *
		 */
		private function create_qrcode_image() {

			$formatted_value = $this->formatted_value( $this->protocol, $this->value );

			if ( $this->image_filename ) {
				QRcode::png( $formatted_value, $this->image_filename, QR_ECLEVEL_L, 3, 4, true );

			} else {
				ob_start();
				QRcode::png( $formatted_value );
				$image_data = ob_get_contents();

				ob_end_clean();

				$this->image = base64_encode( $image_data );
			}
			$this->display_value = $formatted_value;
		}

		/**
		 * Create a barcode image
		 *
		 * @return string
		 * @author Lorenzo Giuffrida
		 * @since  1.0.0
		 *
		 */
		private function create_barcode_image() {

			$formatted_value = $this->formatted_value( $this->protocol, $this->value );

			// Settings for barcode
			$x      = 100;  // barcode center
			$y      = 25;   //15;  // barcode center
			$height = 50;   // barcode height in 1D ; module size in 2D
			$width  = 2;    // barcode height in 1D ; not use in 2D
			$angle  = 0;    // rotation in degrees : nb : non horizontable barcode might not be usable because of pixelisation
			$type   = $this->protocol;

			// ALLOCATE GD RESOURCE
			$im    = imagecreatetruecolor( 200, 50 );
			$black = ImageColorAllocate( $im, 0x00, 0x00, 0x00 );
			$white = ImageColorAllocate( $im, 0xff, 0xff, 0xff );
			imagefilledrectangle( $im, 0, 0, 200, 50, $white );

			// Create barcode
			$data = Barcode::gd( $im, $black, $x, $y, $angle, $type, array(
				'code' => $formatted_value,
				'crc'  => false
			), $width, $height );

			if ( $this->image_filename ) {
				$this->image = null;
				imagegif( $im, $this->image_filename );
			} else {
				ob_start();
				imagegif( $im );
				$image_data = ob_get_contents();
				ob_end_clean();

				$this->image = base64_encode( $image_data );
			}
			$this->display_value = $data['hri'];
		}
	}
}
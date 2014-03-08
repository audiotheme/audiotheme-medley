<?php
/**
 * Textarea control for the Theme Customizer.
 *
 * @package AudioTheme\Medley
 * @copyright Copyright (c) 2014, AudioTheme, LLC
 * @license GPL-2.0+
 * @since 1.0.0
 */

if ( class_exists( 'WP_Customize_Control' ) ) :
/**
 * Textarea control.
 *
 * @package AudioTheme\Medley
 * @since 1.0.0
 */
class ATMedley_Customize_Control_Textarea extends WP_Customize_Control {
	/**
	 * Control type.
	 *
	 * @since 1.0.0
	 * @type string
	 */
	public $type = 'textarea';

	/**
	 * Number of rows.
	 *
	 * @since 1.0.0
	 * @type int
	 */
	public $rows = 4;

	/**
	 * Constructor.
	 *
	 * Overrides the parent constructor to support the rows argument, then calls
	 * the parent constructor to continue setup.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $manager Customizer manager.
	 * @param string $id Control id.
	 * @param array $args Additional args to modify the control.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		$this->rows = ( isset( $args['rows'] ) ) ? absint( $args['rows'] ) : 4;
		parent::__construct( $manager, $id, $args );
	}

	/**
	 * Render the control's content.
	 *
	 * @since 1.0.0
	 */
	public function render_content() {
		?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<textarea rows="<?php echo absint( $this->rows ); ?>" <?php $this->link(); ?> style="width: 98%"><?php echo esc_textarea( $this->value() ); ?></textarea>
		</label>
		<?php
	}
}
endif;

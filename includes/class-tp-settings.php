<?php
// Settings Page: Tp_Thumbnail
// Retrieving values: get_option( 'your_field_id' )
class Tp_Thumbnail_Settings_Page {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wph_create_settings' ) );
		add_action( 'admin_init', array( $this, 'wph_setup_sections' ) );
		add_action( 'admin_init', array( $this, 'wph_setup_fields' ) );
	}

	public function wph_create_settings() {
		$page_title = 'TP Thumbnail';
		$menu_title = 'TP Thumbnail';
		$capability = 'manage_options';
		$slug       = 'Tp_Thumbnail';
		$callback   = array( $this, 'wph_settings_content' );
		add_options_page( $page_title, $menu_title, $capability, $slug, $callback );
	}

	public function wph_settings_content() { ?>
        <div class="wrap">
            <h1>TP Thumbnail</h1>
            <form method="POST" action="options.php">
				<?php
				settings_fields( 'Tp_Thumbnail' );
				do_settings_sections( 'Tp_Thumbnail' );
				submit_button();
				?>
            </form>
        </div> <?php
	}

	public function wph_setup_sections() {
		add_settings_section( 'Tp_Thumbnail_Section', '', array(), 'Tp_Thumbnail' );
	}

	public function wph_setup_fields() {
		$fields = array(
			array(
				'section' => 'Tp_Thumbnail_Section',
				'label'   => 'Width',
				'id'      => 'width',
				'type'    => 'number',
			),

			array(
				'section' => 'Tp_Thumbnail_Section',
				'label'   => 'Height',
				'id'      => 'height',
				'type'    => 'number',
			),

			array(
				'section' => 'Tp_Thumbnail_Section',
				'label'   => 'Font Name',
				'id'      => 'fontName',
				'type'    => 'text',
			),

			array(
				'section' => 'Tp_Thumbnail_Section',
				'label'   => 'Font Size',
				'id'      => 'fontSize',
				'type'    => 'number',
			),

			array(
				'section' => 'Tp_Thumbnail_Section',
				'label'   => 'Text Color',
				'id'      => 'color',
				'type'    => 'text',
			),

			array(
				'section' => 'Tp_Thumbnail_Section',
				'label'   => 'Background Color',
				'id'      => 'background',
				'type'    => 'text',
			),

			array(
				'section' => 'Tp_Thumbnail_Section',
				'label'   => 'Shadow Color',
				'id'      => 'shadow',
				'type'    => 'text',
			),

			array(
				'section' => 'Tp_Thumbnail_Section',
				'label'   => 'Force Replace',
				'id'      => 'force',
				'type'    => 'checkbox',
			)
		);
		foreach ( $fields as $field ) {
			add_settings_field( $field['id'], $field['label'], array(
				$this,
				'wph_field_callback'
			), 'Tp_Thumbnail', $field['section'], $field );
		}
		register_setting( 'Tp_Thumbnail', 'tp_thumbnail' );
	}

	public function wph_field_callback( $field ) {
		$value       = get_option( 'tp_thumbnail' );
		$value       = $value[ $field['id'] ] ?? '';
		$placeholder = '';
		if ( isset( $field['placeholder'] ) ) {
			$placeholder = $field['placeholder'];
		}
		switch ( $field['type'] ) {
			case 'checkbox':
				printf( '<input %s id="%s" name="tp_thumbnail[%s]" type="checkbox" value="1">',
					$value === '1' ? 'checked' : '',
					$field['id'],
					$field['id']
				);
				break;

			default:
				printf( '<input name="tp_thumbnail[%1$s]" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />',
					$field['id'],
					$field['type'],
					$placeholder,
					$value
				);
		}
		if ( isset( $field['desc'] ) ) {
			if ( $desc = $field['desc'] ) {
				printf( '<p class="description">%s </p>', $desc );
			}
		}
	}

}

new Tp_Thumbnail_Settings_Page();

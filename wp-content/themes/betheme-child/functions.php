<?php
// Agregamos los campos adicionales al formulario de registro
function add_fields_to_users_register_form() {
  $user_licencia = ( isset( $_POST['user_licencia'] ) ) ? $_POST['user_licencia'] : '';?>

  <p>
    <label for="user_licencia">License number of PMI<br />
    <input type="text" id="user_licencia" name="user_licencia" class="input" size="10" pattern="\d{10}" value="<?php echo esc_attr($user_licencia);?>"></label>
  </p>

<?php }
add_action('register_form', 'add_fields_to_users_register_form' );

// Validamos los campos adicionales
function validate_user_fields ($errors, $sanitized_user_login, $user_email) {
  if ( empty( $_POST['user_licencia'] ) ) {
    $errors->add( 'user_licencia_error', __('<strong>ERROR</strong>: Please enter a valid license') );
  }

  return $errors;
}
add_filter('registration_errors', 'validate_user_fields', 10, 3);

// Guardamos los campos adicionales en base de datos
function save_user_fields ($user_id) {
  if ( isset($_POST['user_licencia']) ){
    update_user_meta($user_id, 'user_licencia', sanitize_text_field($_POST['user_licencia']));
  }
}
add_action('user_register', 'save_user_fields');

?>

<?php
// Agregamos los campos adicionales a Tu Perfil y Editar Usuario
function add_custom_fields_to_users( $user ) {
  $user_licencia = esc_attr( get_the_author_meta( 'user_licencia', $user->ID ) );
?>

  <h3>Fields custom</h3>

  <table class="form-table">
    <tr>
      <th><label for="user_licencia">License number of PMI</label></th>
      <td><input type="text" name="user_licencia" id="user_licencia" class="regular-text" value="<?php echo $user_licencia;?>" /></td>
    </tr>
  </table>
<?php }
add_action( 'show_user_profile', 'add_custom_fields_to_users' );
add_action( 'edit_user_profile', 'add_custom_fields_to_users' );

add_action( 'personal_options_update', 'save_user_fields' );
add_action( 'edit_user_profile_update', 'save_user_fields' );
?>


<?php

	//FUNCTIONS ORIGINAL

/* ---------------------------------------------------------------------------
 * Child Theme URI | DO NOT CHANGE
 * --------------------------------------------------------------------------- */
define( 'CHILD_THEME_URI', get_stylesheet_directory_uri() );


/* ---------------------------------------------------------------------------
 * Define | YOU CAN CHANGE THESE
 * --------------------------------------------------------------------------- */

// White Label --------------------------------------------
define( 'WHITE_LABEL', false );

// Static CSS is placed in Child Theme directory ----------
define( 'STATIC_IN_CHILD', false );


/* ---------------------------------------------------------------------------
 * Enqueue Style
 * --------------------------------------------------------------------------- */
add_action( 'wp_enqueue_scripts', 'mfnch_enqueue_styles', 101 );
function mfnch_enqueue_styles() {
	
	// Enqueue the parent stylesheet
// 	wp_enqueue_style( 'parent-style', get_template_directory_uri() .'/style.css' );		//we don't need this if it's empty
	
	// Enqueue the parent rtl stylesheet
	if ( is_rtl() ) {
		wp_enqueue_style( 'mfn-rtl', get_template_directory_uri() . '/rtl.css' );
	}
	
	// Enqueue the child stylesheet
	wp_dequeue_style( 'style' );
	wp_enqueue_style( 'style', get_stylesheet_directory_uri() .'/style.css' );
	
}


/* ---------------------------------------------------------------------------
 * Load Textdomain
 * --------------------------------------------------------------------------- */
add_action( 'after_setup_theme', 'mfnch_textdomain' );
function mfnch_textdomain() {
    load_child_theme_textdomain( 'betheme',  get_stylesheet_directory() . '/languages' );
    load_child_theme_textdomain( 'mfn-opts', get_stylesheet_directory() . '/languages' );
}


/* ---------------------------------------------------------------------------
 * Override theme functions
 * 
 * if you want to override theme functions use the example below
 * --------------------------------------------------------------------------- */
// require_once( get_stylesheet_directory() .'/includes/content-portfolio.php' );

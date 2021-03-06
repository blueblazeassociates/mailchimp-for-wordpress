<?php

/**
 *
 */
class MC4WP_Forms_Assets {

	/**
	 * @var array
	 */
	protected $options = array();

	/**
	 * @var bool Is the inline CSS printed already?
	 */
	protected $inline_css_printed = false;

	/**
	 * @var bool Is the inline JavaScript printed to the page already?
	 */
	protected $inline_js_printed = false;

	/**
	 * @var bool Whether to print the JS snippet "fixing" date fields
	 */
	public $print_date_fallback = false;

	/**
	 * Constructor
	 */
	public function __construct( array $options ) {
		$this->options = $options;
	}

	/**
	 * Init all form related functionality
	 */
	public function init() {
		$this->add_hooks();
		$this->register_scripts();
	}

	/**
	 * Adds the necessary hooks
	 */
	protected function add_hooks() {
		// load checkbox css if necessary
		add_action( 'wp_head', array( $this, 'print_css' ), 90 );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_stylesheet' ) );

		// enable shortcodes in text widgets
		add_filter( 'widget_text', 'shortcode_unautop' );
		add_filter( 'widget_text', 'do_shortcode', 11 );
	}

	/**
	 * Register the various JS files used by the plugin
	 */
	protected function register_scripts() {
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		// register placeholder script, which will later be enqueued for IE only
		wp_register_script( 'mc4wp-placeholders', MC4WP_PLUGIN_URL . 'assets/js/third-party/placeholders.min.js', array(), MC4WP_VERSION, true );

		// register non-AJAX script (that handles form submissions)
		wp_register_script( 'mc4wp-form-request', MC4WP_PLUGIN_URL . 'assets/js/form-request' . $suffix . '.js', array(), MC4WP_VERSION, true );
	}

	/**
	 * Load the form stylesheet(s)
	 */
	public function load_stylesheet( ) {

		if ( ! $this->options['css'] ) {
			return false;
		}

		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		switch( $this->options['css'] ) {
			case 'blue':
			case 'red':
			case 'green':
			case 'dark':
			case 'light':
				return $this->load_theme_stylesheet( $suffix );
				break;

			default:
			case 'default':
			case 1:
				wp_enqueue_style( 'mailchimp-for-wp-form', MC4WP_PLUGIN_URL . 'assets/css/form-basic' . $suffix . '.css', array(), MC4WP_VERSION, 'all' );
				break;

		}

		return true;
	}

	/**
	 * @param string $suffix
	 *
	 * @return bool
	 */
	protected function load_theme_stylesheet( $suffix = '' ) {
		// load one of the default form themes
		$theme = $this->options['css'];
		if( in_array( $theme, array( 'blue', 'green', 'dark', 'light', 'red' ) ) ) {
			wp_enqueue_style( 'mailchimp-for-wp-form-theme-' . $theme, MC4WP_PLUGIN_URL . 'assets/css/form-theme-' . $theme . $suffix . '.css', array(), MC4WP_VERSION, 'all' );
			return true;
		}

		return false;
	}

	/**
	 * Prints some inline CSS that hides the honeypot field
	 * @param bool $echo
	 * @return string
	 */
	public function print_css( $echo = true ) {

		if( $this->inline_css_printed ) {
			return '';
		}

		$html = '<style type="text/css">.mc4wp-form input[name="_mc4wp_required_but_not_really"] { display: none !important; }</style>';

		if( $echo !== false ) {
			echo $html;
		}

		// make sure this function only runs once
		$this->inline_css_printed = true;

		return $html;
	}

	/**
	 * Prints some JavaScript to enhance the form functionality
	 *
	 * This is only printed on pages that actually contain a form.
	 */
	public function print_js() {

		if( $this->inline_js_printed === true ) {
			return false;
		}

		// We prefer to print this in `wp_footer`
		if( current_action() !== 'wp_footer' && ! did_action( 'wp_footer' ) ) {
			add_action( 'wp_footer', array( $this, 'print_js' ), 99 );
			return false;
		}

		// Print vanilla JavaScript
		?><script type="text/javascript">
			(function() {
				function addSubmittedClassToFormContainer(e) {
					var form = e.target.form.parentNode;
					var className = 'mc4wp-form-submitted';
					(form.classList) ? form.classList.add(className) : form.className += ' ' + className;
				}

				function hideHoneypot(h) {
					var n = document.createElement('input');
					n.type = 'hidden';
					n.name = h.name;
					n.style.display = 'none';
					n.value = h.value;
					h.parentNode.replaceChild(n,h);
				}

				var forms = document.querySelectorAll('.mc4wp-form');
				for (var i = 0; i < forms.length; i++) {
					(function(f) {

						// make sure honeypot is hidden
						var h = f.querySelector('input[name="_mc4wp_required_but_not_really"]');
						if(h) {
							hideHoneypot(h);
						}

						// add class on submit
						var b = f.querySelector('[type="submit"]');
						if(b.addEventListener) {
							b.addEventListener('click', addSubmittedClassToFormContainer);
						} else {
							b.attachEvent('click', addSubmittedClassToFormContainer);
						}

					})(forms[i]);
				}
			})();

			<?php if( $this->print_date_fallback ) { ?>
			(function() {
				// test if browser supports date fields
				var testInput = document.createElement('input');
				testInput.setAttribute('type', 'date');
				if( testInput.type !== 'date') {

					// add placeholder & pattern to all date fields
					var dateFields = document.querySelectorAll('.mc4wp-form input[type="date"]');
					for(var i=0; i<dateFields.length; i++) {
						if(!dateFields[i].placeholder) {
							dateFields[i].placeholder = 'yyyy/mm/dd';
						}
						if(!dateFields[i].pattern) {
							dateFields[i].pattern = '(?:19|20)[0-9]{2}/(?:(?:0[1-9]|1[0-2])/(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])/(?:30))|(?:(?:0[13578]|1[02])-31))';
						}
					}
				}
			})();
			<?php } ?>
		</script><?php

		// make sure this function only runs once
		$this->inline_js_printed = true;
		return true;
	}

	/**
	 * @param MC4WP_Form $form
	 */
	public function print_form_assets( MC4WP_Form $form ) {

		// make sure to print date fallback later on if form contains a date field
		if( $form->contains_field_type( 'date' ) ) {
			$this->print_date_fallback = true;
		}

		// if form was submitted, print scripts (only once)
		if( $form->is_submitted() && ! wp_script_is( 'mc4wp-form-request', 'enqueued' ) ) {

			// enqueue scripts (in footer) if form was submited
			wp_enqueue_script( 'mc4wp-form-request' );
			wp_localize_script( 'mc4wp-form-request', 'mc4wpFormRequestData', array(
					'success' => ( $form->request->success ) ? 1 : 0,
					'formElementId' => $form->request->config['form_element_id'],
					'data' => $form->request->data,
				)
			);

		}

		// make sure scripts are enqueued later
		global $is_IE;
		if( isset( $is_IE ) && $is_IE ) {
			wp_enqueue_script( 'mc4wp-placeholders' );
		}

		// print snippet of JS
		$this->print_js();
	}

}

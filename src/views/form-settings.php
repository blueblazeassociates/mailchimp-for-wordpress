<?php
// die if accessed directly
defined( 'ABSPATH' ) or exit;
?>
<div id="mc4wp-admin" class="wrap mc4wp-settings">

	<h2><img src="<?php echo MC4WP_PLUGIN_URL . 'assets/img/menu-icon.png'; ?>" /> <?php _e( 'MailChimp for WordPress', 'mailchimp-for-wp' ); ?>: <?php _e( 'Form Settings', 'mailchimp-for-wp' ); ?></h2>

	<div id="mc4wp-content">

		<?php settings_errors(); ?>

		<p><?php printf( __( 'To use the MailChimp sign-up form, configure the form below and then either paste %s in the content of a post or page or use the  widget.', 'mailchimp-for-wp' ), '<input size="10" type="text" onfocus="this.select();" readonly="readonly" value="[mc4wp_form]" class="shortcode-example">' ); ?></p>

			<form action="options.php" method="post">
				<?php settings_fields( 'mc4wp_form_settings' ); ?>
				<input type="hidden" name="mc4wp_form[required_fields]" value="<?php echo esc_attr( $opts['required_fields'] ); ?>" id="input-required-fields" />
				
				<h3 class="mc4wp-title"><?php _e( 'Required form settings', 'mailchimp-for-wp' ); ?></h3>
				<table class="form-table">

					<tr valign="top">
						<th scope="row"><label for="mc4wp_load_stylesheet_select"><?php _e( 'Load form styles (CSS)?' ,'mailchimp-for-wp' ); ?></label></th>
						<td class="nowrap valigntop">
							<select name="mc4wp_form[css]" id="mc4wp_load_stylesheet_select">
								<option value="0" <?php selected( $opts['css'], 0 ); ?>><?php _e( 'No', 'mailchimp-for-wp' ); ?></option>
								<option value="default" <?php selected( $opts['css'], 'default' ); ?><?php selected( $opts['css'], 1 ); ?>><?php _e( 'Yes, load basic form styles', 'mailchimp-for-wp' ); ?></option>
								<option disabled><?php _e( '(PRO ONLY)', 'mailchimp-for-wp' ); ?> <?php _e( 'Yes, load my custom form styles', 'mailchimp-for-wp' ); ?></option>
								<optgroup label="<?php _e( 'Yes, load default form theme', 'mailchimp-for-wp' ); ?>">
									<option value="light" <?php selected( $opts['css'], 'light' ); ?>><?php _e( 'Light Theme', 'mailchimp-for-wp' ); ?></option>
									<option value="red" <?php selected( $opts['css'], 'red' ); ?>><?php _e( 'Red Theme', 'mailchimp-for-wp' ); ?></option>
									<option value="green" <?php selected( $opts['css'], 'green' ); ?>><?php _e( 'Green Theme', 'mailchimp-for-wp' ); ?></option>
									<option value="blue" <?php selected( $opts['css'], 'blue' ); ?>><?php _e( 'Blue Theme', 'mailchimp-for-wp' ); ?></option>
									<option value="dark" <?php selected( $opts['css'], 'dark' ); ?>><?php _e( 'Dark Theme', 'mailchimp-for-wp' ); ?></option>
									<option disabled><?php _e( '(PRO ONLY)', 'mailchimp-for-wp' ); ?> <?php _e( 'Custom Color Theme', 'mailchimp-for-wp' ); ?></option>
								</optgroup>
							</select>
						</td>
						<td class="desc">
							<?php _e( 'If you want to load some default CSS styles, select "basic formatting styles" or choose one of the color themes' , 'mailchimp-for-wp' ); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Lists this form subscribes to', 'mailchimp-for-wp' ); ?></th>
					<?php // loop through lists
					if( empty( $lists ) ) {
						?><td colspan="2"><?php printf( __( 'No lists found, <a href="%s">are you connected to MailChimp</a>?', 'mailchimp-for-wp' ), admin_url( 'admin.php?page=mailchimp-for-wp' ) ); ?></td><?php
					} else { ?>
					<td>

						<ul id="mc4wp-lists">
						<?php foreach($lists as $list) { ?>
							<li>
								<label>
									<input type="checkbox" name="mc4wp_form[lists][<?php echo esc_attr( $list->id ); ?>]" value="<?php echo esc_attr( $list->id ); ?>" <?php if(array_key_exists( $list->id, $opts['lists'] )) { echo 'checked="checked"'; } ?>> <?php echo esc_html( $list->name ); ?>
								</label>
							</li>
						<?php } ?>
						</ul>

					</td>
					<td class="desc"><?php _e( 'Select the list(s) to which people who submit this form should be subscribed.' ,'mailchimp-for-wp' ); ?></td>
					<?php } ?>

					</tr>
					<tr valign="top">
						<td colspan="3">
							<h4><?php _e( 'Form mark-up', 'mailchimp-for-wp' ); ?></h4>

							<div class="row">
								<div class="col" style="padding-left: 0;">
									<?php
									if( function_exists( 'wp_editor' ) ) {
										wp_editor( esc_textarea( $opts['markup'] ), 'content', array( 'tinymce' => false, 'media_buttons' => true, 'textarea_name' => 'mc4wp_form[markup]') );
									} else {
										?><textarea class="widefat" cols="160" rows="20" id="mc4wpformmarkup" name="mc4wp_form[markup]"><?php echo esc_textarea( $opts['markup'] ); ?></textarea><?php
									} ?>
									<p class="mc4wp-form-usage"><?php printf( __( 'Use the shortcode %s to display this form inside a post, page or text widget.' ,'mailchimp-for-wp' ), '<input type="text" onfocus="this.select();" readonly="readonly" value="[mc4wp_form]" class="shortcode-example">' ); ?></p>
								</div>

								<div class="col" style="padding-right: 0;">
									<?php include('parts/field-wizard.php'); ?>
								</div>
							</div>
						</td>
					</tr>
			</table>

			<?php include 'parts/missing-fields-notice.php'; ?>

		<?php submit_button(); ?>

		<h3 class="mc4wp-title"><?php _e( 'MailChimp Settings', 'mailchimp-for-wp' ); ?></h3>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e( 'Double opt-in?', 'mailchimp-for-wp' ); ?></th>
				<td class="nowrap">
					<label>
						<input type="radio"  name="mc4wp_form[double_optin]" value="1" <?php checked( $opts['double_optin'], 1 ); ?> />
						<?php _e( 'Yes', 'mailchimp-for-wp' ); ?>
					</label> &nbsp;
					<label>
						<input type="radio" name="mc4wp_form[double_optin]" value="0" <?php checked( $opts['double_optin'], 0 ); ?> />
						<?php _e( 'No', 'mailchimp-for-wp' ); ?>
					</label>
				</td>
				<td class="desc"><?php _e( 'Select "yes" if you want people to confirm their email address before being subscribed (recommended)', 'mailchimp-for-wp' ); ?></td>
			</tr>
			<?php $enabled = ! $opts['double_optin']; ?>
			<tr id="mc4wp-send-welcome" valign="top" <?php if( ! $enabled ) { ?>class="hidden"<?php } ?>>
				<th scope="row"><?php _e( 'Send Welcome Email?', 'mailchimp-for-wp' ); ?></th>
				<td class="nowrap">
					<label>
						<input type="radio"  name="mc4wp_form[send_welcome]" value="1" <?php if( $enabled ) { checked( $opts['send_welcome'], 1 ); } else { echo 'disabled'; } ?> />
						<?php _e( 'Yes', 'mailchimp-for-wp' ); ?>
					</label> &nbsp;
					<label>
						<input type="radio" name="mc4wp_form[send_welcome]" value="0" <?php if( $enabled ) { checked( $opts['send_welcome'], 0 ); } else { echo 'disabled'; } ?> />
						<?php _e( 'No', 'mailchimp-for-wp' ); ?>
					</label>
				</td>
				<td class="desc"><?php _e( 'Select "yes" if you want to send your lists Welcome Email if a subscribe succeeds (only when double opt-in is disabled).' ,'mailchimp-for-wp' ); ?></td>
			</tr>
			<tr class="pro-feature" valign="top">
				<th scope="row"><?php _e( 'Update existing subscribers?', 'mailchimp-for-wp' ); ?></th>
				<td class="nowrap">
					<input type="radio" readonly /> 
					<label><?php _e( 'Yes', 'mailchimp-for-wp' ); ?></label> &nbsp; 
					<input type="radio" checked readonly /> 
					<label><?php _e( 'No', 'mailchimp-for-wp' ); ?></label> &nbsp;
				</td>
				<td class="desc"><?php _e( 'Select "yes" if you want to update existing subscribers (instead of showing the "already subscribed" message).', 'mailchimp-for-wp' ); ?></td>
			</tr>
			<tr class="pro-feature" valign="top">
				<th scope="row"><?php _e( 'Replace interest groups?', 'mailchimp-for-wp' ); ?></th>
				<td class="nowrap">
					<label>
						<input type="radio" checked readonly />
						<?php _e( 'Yes', 'mailchimp-for-wp' ); ?>
					</label> &nbsp;
					<label>
						<input type="radio" readonly />
						<?php _e( 'No', 'mailchimp-for-wp' ); ?>
					</label>
				</td>
				<td class="desc"><?php _e( 'Select "yes" if you want to replace the interest groups with the groups provided instead of adding the provided groups to the member\'s interest groups (only when updating a subscriber).', 'mailchimp-for-wp' ); ?></td>
			</tr>
		</table>

		<h3 class="mc4wp-title"><?php _e( 'Form Settings & Messages', 'mailchimp-for-wp' ); ?></h3>

		<table class="form-table mc4wp-form-messages">
			<tr valign="top" class="pro-feature">
				<th scope="row"><?php _e( 'Enable AJAX form submission?', 'mailchimp-for-wp' ); ?></th>
				<td class="nowrap">
					<label>
						<input type="radio" disabled />
						<?php _e( 'Yes', 'mailchimp-for-wp' ); ?>
					</label> &nbsp;
					<label>
						<input type="radio" checked disabled />
						<?php _e( 'No', 'mailchimp-for-wp' ); ?>
					</label>
				</td>
				<td class="desc"><?php _e( 'Select "yes" if you want to use AJAX (JavaScript) to submit forms.', 'mailchimp-for-wp' ); ?> <a href="https://mc4wp.com/demo/#utm_source=wp-plugin&utm_medium=mailchimp-for-wp&utm_campaign=settings-demo-link">(demo)</a></td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Hide form after a successful sign-up?', 'mailchimp-for-wp' ); ?></th>
				<td class="nowrap">
					<label>
						<input type="radio" name="mc4wp_form[hide_after_success]" value="1" <?php checked( $opts['hide_after_success'], 1 ); ?> />
						<?php _e( 'Yes', 'mailchimp-for-wp' ); ?>
					</label> &nbsp;
					<label>
						<input type="radio" name="mc4wp_form[hide_after_success]" value="0" <?php checked( $opts['hide_after_success'], 0 ); ?> />
						<?php _e( 'No', 'mailchimp-for-wp' ); ?>
					</label>
				</td>
				<td class="desc"><?php _e( 'Select "yes" to hide the form fields after a successful sign-up.', 'mailchimp-for-wp' ); ?></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mc4wp_form_redirect"><?php _e( 'Redirect to URL after successful sign-ups', 'mailchimp-for-wp' ); ?></label></th>
				<td colspan="2">
					<input type="text" class="widefat" name="mc4wp_form[redirect]" id="mc4wp_form_redirect" placeholder="<?php printf( __( 'Example: %s', 'mailchimp-for-wp' ), esc_attr( site_url( '/thank-you/' ) ) ); ?>" value="<?php echo esc_attr( $opts['redirect'] ); ?>" />
					<p class="help"><?php _e( 'Leave empty or enter <code>0</code> for no redirect. Otherwise, use complete (absolute) URLs, including <code>http://</code>.', 'mailchimp-for-wp' ); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mc4wp_form_text_subscribed"><?php _e( 'Successfully subscribed', 'mailchimp-for-wp' ); ?></label></th>
				<td colspan="2" >
					<input type="text" class="widefat" id="mc4wp_form_text_subscribed" name="mc4wp_form[text_subscribed]" value="<?php echo esc_attr( $opts['text_subscribed'] ); ?>" required />
					<p class="help"><?php _e( 'The text that shows when an email address is successfully subscribed to the selected list(s).', 'mailchimp-for-wp' ); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mc4wp_form_text_invalid_email"><?php _e( 'Invalid email address', 'mailchimp-for-wp' ); ?></label></th>
				<td colspan="2" >
					<input type="text" class="widefat" id="mc4wp_form_text_invalid_email" name="mc4wp_form[text_invalid_email]" value="<?php echo esc_attr( $opts['text_invalid_email'] ); ?>" required />
					<p class="help"><?php _e( 'The text that shows when an invalid email address is given.', 'mailchimp-for-wp' ); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mc4wp_form_text_required_field_missing"><?php _e( 'Required field missing', 'mailchimp-for-wp' ); ?></label></th>
				<td colspan="2" >
					<input type="text" class="widefat" id="mc4wp_form_text_required_field_missing" name="mc4wp_form[text_required_field_missing]" value="<?php echo esc_attr( $opts['text_required_field_missing'] ); ?>" required />
					<p class="help"><?php _e( 'The text that shows when a required field for the selected list(s) is missing.', 'mailchimp-for-wp' ); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mc4wp_form_text_already_subscribed"><?php _e( 'Already subscribed', 'mailchimp-for-wp' ); ?></label></th>
				<td colspan="2" >
					<input type="text" class="widefat" id="mc4wp_form_text_already_subscribed" name="mc4wp_form[text_already_subscribed]" value="<?php echo esc_attr( $opts['text_already_subscribed'] ); ?>" required />
					<p class="help"><?php _e( 'The text that shows when the given email is already subscribed to the selected list(s).', 'mailchimp-for-wp' ); ?></p>
				</td>
			</tr>
			<?php if( true === $this->has_captcha_plugin ) { ?>
				<tr valign="top">
					<th scope="row"><label for="mc4wp_form_text_invalid_captcha"><?php _e( 'Invalid CAPTCHA', 'mailchimp-for-wp' ); ?></label></th>
					<td colspan="2" ><input type="text" class="widefat" id="mc4wp_form_text_invalid_captcha" name="mc4wp_form[text_invalid_captcha]" value="<?php echo esc_attr( $opts['text_invalid_captcha'] ); ?>" required /></td>
				</tr>
			<?php } ?>
			<tr valign="top">
				<th scope="row"><label for="mc4wp_form_text_error"><?php _e( 'General error' ,'mailchimp-for-wp' ); ?></label></th>
				<td colspan="2" >
					<input type="text" class="widefat" id="mc4wp_form_text_error" name="mc4wp_form[text_error]" value="<?php echo esc_attr( $opts['text_error'] ); ?>" required />
					<p class="help"><?php _e( 'The text that shows when a general error occured.', 'mailchimp-for-wp' ); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mc4wp_form_text_unsubscribed"><?php _e( 'Unsubscribed', 'mailchimp-for-wp' ); ?></label></th>
				<td colspan="2" >
					<input type="text" class="widefat" id="mc4wp_form_text_unsubscribed" name="mc4wp_form[text_unsubscribed]" value="<?php echo esc_attr( $opts['text_unsubscribed'] ); ?>" required />
					<p class="help"><?php _e( 'When using the unsubscribe method, this is the text that shows when the given email address is successfully unsubscribed from the selected list(s).', 'mailchimp-for-wp' ); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mc4wp_form_text_not_subscribed"><?php _e( 'Not subscribed', 'mailchimp-for-wp' ); ?></label></th>
				<td colspan="2" >
					<input type="text" class="widefat" id="mc4wp_form_text_not_subscribed" name="mc4wp_form[text_not_subscribed]" value="<?php echo esc_attr( $opts['text_not_subscribed'] ); ?>" required />
					<p class="help"><?php _e( 'When using the unsubscribe method, this is the text that shows when the given email address is not on the selected list(s).', 'mailchimp-for-wp' ); ?></p>
				</td>
			</tr>
			<tr>
				<th></th>
				<td colspan="2">
					<p class="help"><?php printf( __( 'HTML tags like %s are allowed in the message fields.', 'mailchimp-for-wp' ), '<code>' . esc_html( '<strong><em><a>' ) . '</code>' ); ?></p>
				</td>
			</tr>
		</table>

	<?php submit_button(); ?>
	</form>

	<?php include 'parts/admin-footer.php'; ?>
</div>
<div id="mc4wp-sidebar">

	<?php do_action( 'mc4wp_admin_before_sidebar' ); ?>

	<div class="mc4wp-box" id="mc4wp-info-tabs">
		<h3 class="mc4wp-title"><?php _e( 'Form Styling', 'mailchimp-for-wp' ); ?></h3>
		<p><?php printf( __( 'Alter the visual appearance of the form by applying CSS rules to %s.', 'mailchimp-for-wp' ), '<b>.mc4wp-form</b>' ); ?></p>
		<p><?php printf( __( 'You can add the CSS rules to your theme stylesheet using the <a href="%s">Theme Editor</a> or by using a plugin like %s', 'mailchimp-for-wp' ), admin_url( 'theme-editor.php?file=style.css' ), '<a href="https://wordpress.org/plugins/simple-custom-css/">Simple Custom CSS</a>' ); ?>.</p>
		<p><?php printf( __( 'The <a href="%s" target="_blank">plugin FAQ</a> lists the various CSS selectors you can use to target the different form elements.', 'mailchimp-for-wp' ), 'https://wordpress.org/plugins/mailchimp-for-wp/faq/' ); ?></p>
		<p><?php printf( __( 'If you need an easier way to style your forms, consider <a href="%s">upgrading to MailChimp for WordPress Pro</a> which comes with an easy Styles Builder.', 'mailchimp-for-wp' ), 'https://mc4wp.com/#utm_source=wp-plugin&utm_medium=mailchimp-for-wp&utm_campaign=form-settings' ); ?></p>

		<h3 class="mc4wp-title"><?php _e( 'Variables', 'mailchimp-for-wp' ); ?></h3>
		<?php include 'parts/variables-overview.php'; ?>
	</div>

	<?php include 'parts/admin-need-support.php'; ?>
	<?php do_action( 'mc4wp_admin_after_sidebar' ); ?>

</div>
</div>
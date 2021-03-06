<?php
// die if accessed directly
defined( 'ABSPATH' ) or exit;
?>
<div id="mc4wp-field-wizard" class="well">

	<h4 class="mc4wp-title"><?php _e( 'Add a new field', 'mailchimp-for-wp' ); ?></h4>

	<p><?php _e( 'Use the tool below to generate the HTML for your form fields.', 'mailchimp-for-wp' ); ?></p>
	<p>
		<select class="widefat" id="mc4wp-fw-mailchimp-fields" style="<?php if( empty( $opts['lists'] ) ) { echo 'display: none;'; } ?>">
			<option class="default" value="" disabled selected><?php _e( 'Select MailChimp field..', 'mailchimp-for-wp' ); ?></option>
			<optgroup label="MailChimp merge fields" class="merge-fields"></optgroup>
			<optgroup label="Interest groupings" class="groupings"></optgroup>
			<optgroup label="Other" class="other">
				<option class="default" value="submit"><?php _e( 'Submit Button' ,'mailchimp-for-wp' ); ?></option>
				<option class="default" value="_action"><?php echo __( 'Subscribe / unsubscribe choice', 'mailchimp-for-wp' ); ?></option>
				<option class="default" disabled><?php echo __( '(PRO ONLY)' ,'mailchimp-for-wp' ) . ' ' . __( 'List choice' ,'mailchimp-for-wp' ); ?></option>
			</optgroup>
		</select>
	</p>

	<div id="mc4wp-fw-fields">

		<p class="row label">
			<label for="mc4wp-fw-label"><?php _e( 'Label', 'mailchimp-for-wp' ); ?> <small><?php _e( '(optional)', 'mailchimp-for-wp' ); ?></small></label>
			<input class="widefat" type="text" id="mc4wp-fw-label" />
		</p>

		<p class="row placeholder">
			<label for="mc4wp-fw-placeholder"><?php _e( 'Placeholder', 'mailchimp-for-wp' ); ?> <small><?php _e( '(optional)', 'mailchimp-for-wp' ); ?></small></label>
			<input class="widefat" type="text" id="mc4wp-fw-placeholder" />
		</p>

		<p class="row value">
			<label for="mc4wp-fw-value"><span id="mc4wp-fw-value-label"><?php _e( 'Initial value', 'mailchimp-for-wp' ); ?> <small><?php _e( '(optional)', 'mailchimp-for-wp' ); ?></small></span></label>
			<input class="widefat" type="text" id="mc4wp-fw-value" />
		</p>

		<p class="row values" id="mc4wp-fw-values">
			<label for="mc4wp-fw-values"><?php _e( 'Labels', 'mailchimp-for-wp' ); ?> <small><?php _e( '(leave empty to hide)', 'mailchimp-for-wp' ); ?></small></label>
		</p>

		<p class="row wrap-p">
			<input type="checkbox" id="mc4wp-fw-wrap-p" value="1" checked /> 
			<label for="mc4wp-fw-wrap-p"><?php printf( __( 'Wrap in paragraph %s tags?', 'mailchimp-for-wp' ), '(<code>&lt;p&gt;</code>)' ); ?></label>
		</p>

		<p class="row required">
			<input type="checkbox" id="mc4wp-fw-required" value="1" /> 
			<label for="mc4wp-fw-required"><?php _e( 'Required field?' ,'mailchimp-for-wp' ); ?></label>
		</p>

		<p>
			<input class="button button-large" type="button" id="mc4wp-fw-add-to-form" value="&laquo; <?php _e( 'Add to form' ,'mailchimp-for-wp' ); ?>" />
		</p>

		<p>
			<label for="mc4wp-fw-preview"><?php _e( 'Generated HTML', 'mailchimp-for-wp' ); ?></label>
			<textarea class="widefat code-preview" id="mc4wp-fw-preview" rows="5" readonly></textarea>
		</p>

	</div>

	<p class="mc4wp-notice no-lists-selected" <?php if( ! empty($opts['lists'])) { ?>style="display: none;" <?php } ?>>
		<?php _e( 'Select at least one list first.', 'mailchimp-for-wp' ); ?>
	</p>

</div>
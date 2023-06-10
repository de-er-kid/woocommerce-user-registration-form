<?php
/*
Plugin Name: WooCommerce User Registration
Plugin URI: https://github.com/de-er-kid/woocommerce-user-registration-form/
Description: Adds custom fields to the WooCommerce registration form.
Version: 0.0.2
Author: Sinan
Author URI: https://github.com/de-er-kid/
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain: woocommerce-user-registration-form
Domain Path: /languages
GitHub Plugin URI: de-er-kid/woocommerce-user-registration-form
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function wooc_extra_register_fields() { ?>
    <p class="form-row form-row-wide">
        <label for="reg_billing_phone"><?php _e( 'Phone', 'woocommerce-user-registration-form' ); ?></label>
        <input type="text" class="input-text" name="billing_phone" id="reg_billing_phone" value="<?php echo isset( $_POST['billing_phone'] ) ? esc_attr( $_POST['billing_phone'] ) : ''; ?>" />
    </p>
    <p class="form-row form-row-first">
        <label for="reg_billing_first_name"><?php _e( 'First name', 'woocommerce-user-registration-form' ); ?><span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) echo esc_attr( $_POST['billing_first_name'] ); ?>" />
    </p>
    <p class="form-row form-row-last">
        <label for="reg_billing_last_name"><?php _e( 'Last name', 'woocommerce-user-registration-form' ); ?><span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php if ( ! empty( $_POST['billing_last_name'] ) ) echo esc_attr( $_POST['billing_last_name'] ); ?>" />
    </p>
    <p class="form-row form-row-wide">
        <label for="reg_password"><?php _e( 'Password', 'woocommerce-user-registration-form' ); ?><span class="required">*</span></label>
        <input type="password" class="input-text" name="password" id="reg_password" value="" autocomplete="new-password" />
    </p>
    <p class="form-row form-row-wide">
        <label for="reg_confirm_password"><?php _e( 'Confirm Password', 'woocommerce-user-registration-form' ); ?><span class="required">*</span></label>
        <input type="password" class="input-text" name="confirm_password" id="reg_confirm_password" value="" autocomplete="new-password" />
    </p>
    <div class="clear"></div>
<?php }

add_action( 'woocommerce_register_form_start', 'wooc_extra_register_fields' );

function wooc_validate_extra_register_fields( $username, $email, $validation_errors ) {
    if ( isset( $_POST['billing_first_name'] ) && empty( $_POST['billing_first_name'] ) ) {
        $validation_errors->add( 'billing_first_name_error', __( '<strong>Error</strong>: First name is required!', 'woocommerce-user-registration-form' ) );
    }

    if ( isset( $_POST['billing_last_name'] ) && empty( $_POST['billing_last_name'] ) ) {
        $validation_errors->add( 'billing_last_name_error', __( '<strong>Error</strong>: Last name is required!.', 'woocommerce-user-registration-form' ) );
    }

    if ( isset( $_POST['password'] ) && empty( $_POST['password'] ) ) {
        $validation_errors->add( 'password_error', __( '<strong>Error</strong>: Password is required!', 'woocommerce-user-registration-form' ) );
    }

    if ( isset( $_POST['confirm_password'] ) && empty( $_POST['confirm_password'] ) ) {
        $validation_errors->add( 'confirm_password_error', __( '<strong>Error</strong>: Confirm Password is required!', 'woocommerce-user-registration-form' ) );
    }

    if ( isset( $_POST['password'] ) && isset( $_POST['confirm_password'] ) && $_POST['password'] !== $_POST['confirm_password'] ) {
        $validation_errors->add( 'password_mismatch_error', __( '<strong>Error</strong>: Passwords do not match!', 'woocommerce-user-registration-form' ) );
    }

    return $validation_errors;
}
add_action( 'woocommerce_register_post', 'wooc_validate_extra_register_fields', 10, 3 );

function wooc_save_extra_register_fields( $customer_id ) {
    if ( isset( $_POST['billing_phone'] ) ) {
        update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
    }

    if ( isset( $_POST['billing_first_name'] ) ) {
        update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
        update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
    }

    if ( isset( $_POST['billing_last_name'] ) ) {
        update_user_meta( $customer_id, 'last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
        update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
    }
}
add_action( 'woocommerce_created_customer', 'wooc_save_extra_register_fields' );

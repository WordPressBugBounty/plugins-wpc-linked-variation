<?php
defined( 'ABSPATH' ) || exit;

register_activation_hook( defined( 'WPCLV_LITE' ) ? WPCLV_LITE : WPCLV_FILE, 'wpclv_activate' );
register_deactivation_hook( defined( 'WPCLV_LITE' ) ? WPCLV_LITE : WPCLV_FILE, 'wpclv_deactivate' );
add_action( 'admin_init', 'wpclv_check_version' );

function wpclv_check_version() {
	if ( ! empty( get_option( 'wpclv_version' ) ) && ( get_option( 'wpclv_version' ) < WPCLV_VERSION ) ) {
		wpc_log( 'wpclv', 'upgraded' );
		update_option( 'wpclv_version', WPCLV_VERSION, false );
	}
}

function wpclv_activate() {
	wpc_log( 'wpclv', 'installed' );
	update_option( 'wpclv_version', WPCLV_VERSION, false );
}

function wpclv_deactivate() {
	wpc_log( 'wpclv', 'deactivated' );
}

if ( ! function_exists( 'wpc_log' ) ) {
	function wpc_log( $prefix, $action ) {
		$logs = get_option( 'wpc_logs', [] );
		$user = wp_get_current_user();

		if ( ! isset( $logs[ $prefix ] ) ) {
			$logs[ $prefix ] = [];
		}

		$logs[ $prefix ][] = [
			'time'   => current_time( 'mysql' ),
			'user'   => $user->display_name . ' (ID: ' . $user->ID . ')',
			'action' => $action
		];

		update_option( 'wpc_logs', $logs, false );
	}
}
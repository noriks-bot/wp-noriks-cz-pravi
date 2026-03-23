<?php
/**
 * Plugin Name: Advanced Country Blocker
 * Plugin URI: https://sparkcan.com/acb.html
 * Description: Blocks all traffic to the website unless it meets the country filtering rules or accesses via a secret URL parameter. On activation, the admin’s country is auto‐added to the country list. Supports logging, blacklisting of IP addresses, custom block page, admin bypass, and optional email alerts. You can choose whether the country list acts as an allow‑list or a block‑list.
 * Version: 2.3.1
 * Author: Sparkcan
 * Author URI: https://sparkcan.com
 * License: GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}

require_once plugin_dir_path( __FILE__ ) . 'includes/class-advcb-geoip-locator.php';

add_action( 'plugins_loaded', 'advcb_load_textdomain' );
function advcb_load_textdomain() {
        load_plugin_textdomain( 'advcb', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

/**
 * HELPER FUNCTION: Returns an array of ISO country codes mapped to country names.
 */
function advcb_get_countries() {
	return array(
		''   => 'Select a country',
		'AF' => 'Afghanistan',
		'AL' => 'Albania',
		'DZ' => 'Algeria',
		'AS' => 'American Samoa',
		'AD' => 'Andorra',
		'AO' => 'Angola',
		'AI' => 'Anguilla',
		'AQ' => 'Antarctica',
		'AG' => 'Antigua and Barbuda',
		'AR' => 'Argentina',
		'AM' => 'Armenia',
		'AW' => 'Aruba',
		'AU' => 'Australia',
		'AT' => 'Austria',
		'AZ' => 'Azerbaijan',
		'BS' => 'Bahamas',
		'BH' => 'Bahrain',
		'BD' => 'Bangladesh',
		'BB' => 'Barbados',
		'BY' => 'Belarus',
		'BE' => 'Belgium',
		'BZ' => 'Belize',
		'BJ' => 'Benin',
		'BM' => 'Bermuda',
		'BT' => 'Bhutan',
		'BO' => 'Bolivia',
		'BA' => 'Bosnia and Herzegovina',
		'BW' => 'Botswana',
		'BR' => 'Brazil',
		'BN' => 'Brunei',
		'BG' => 'Bulgaria',
		'BF' => 'Burkina Faso',
		'BI' => 'Burundi',
		'KH' => 'Cambodia',
		'CM' => 'Cameroon',
		'CA' => 'Canada',
		'CV' => 'Cape Verde',
		'KY' => 'Cayman Islands',
		'CF' => 'Central African Republic',
		'TD' => 'Chad',
		'CL' => 'Chile',
		'CN' => 'China',
		'CO' => 'Colombia',
		'KM' => 'Comoros',
		'CG' => 'Congo - Brazzaville',
		'CD' => 'Congo - Kinshasa',
		'CR' => 'Costa Rica',
		'CI' => 'Côte d’Ivoire',
		'HR' => 'Croatia',
		'CU' => 'Cuba',
		'CY' => 'Cyprus',
		'CZ' => 'Czech Republic',
		'DK' => 'Denmark',
		'DJ' => 'Djibouti',
		'DM' => 'Dominica',
		'DO' => 'Dominican Republic',
		'EC' => 'Ecuador',
		'EG' => 'Egypt',
		'SV' => 'El Salvador',
		'GQ' => 'Equatorial Guinea',
		'ER' => 'Eritrea',
		'EE' => 'Estonia',
		'ET' => 'Ethiopia',
		'FJ' => 'Fiji',
		'FI' => 'Finland',
		'FR' => 'France',
		'GF' => 'French Guiana',
		'PF' => 'French Polynesia',
		'GA' => 'Gabon',
		'GM' => 'Gambia',
		'GE' => 'Georgia',
		'DE' => 'Germany',
		'GH' => 'Ghana',
		'GI' => 'Gibraltar',
		'GR' => 'Greece',
		'GL' => 'Greenland',
		'GD' => 'Grenada',
		'GP' => 'Guadeloupe',
		'GU' => 'Guam',
		'GT' => 'Guatemala',
		'GG' => 'Guernsey',
		'GN' => 'Guinea',
		'GW' => 'Guinea-Bissau',
		'GY' => 'Guyana',
		'HT' => 'Haiti',
		'HN' => 'Honduras',
		'HK' => 'Hong Kong',
		'HU' => 'Hungary',
		'IS' => 'Iceland',
		'IN' => 'India',
		'ID' => 'Indonesia',
		'IR' => 'Iran',
		'IQ' => 'Iraq',
		'IE' => 'Ireland',
		'IM' => 'Isle of Man',
		'IL' => 'Israel',
		'IT' => 'Italy',
		'JM' => 'Jamaica',
		'JP' => 'Japan',
		'JE' => 'Jersey',
		'JO' => 'Jordan',
		'KZ' => 'Kazakhstan',
		'KE' => 'Kenya',
		'KI' => 'Kiribati',
		'KP' => 'North Korea',
		'KR' => 'South Korea',
		'KW' => 'Kuwait',
		'KG' => 'Kyrgyzstan',
		'LA' => 'Laos',
		'LV' => 'Latvia',
		'LB' => 'Lebanon',
		'LS' => 'Lesotho',
		'LR' => 'Liberia',
		'LY' => 'Libya',
		'LI' => 'Liechtenstein',
		'LT' => 'Lithuania',
		'LU' => 'Luxembourg',
		'MO' => 'Macao',
		'MK' => 'North Macedonia',
		'MG' => 'Madagascar',
		'MW' => 'Malawi',
		'MY' => 'Malaysia',
		'MV' => 'Maldives',
		'ML' => 'Mali',
		'MT' => 'Malta',
		'MH' => 'Marshall Islands',
		'MQ' => 'Martinique',
		'MR' => 'Mauritania',
		'MU' => 'Mauritius',
		'MX' => 'Mexico',
		'FM' => 'Micronesia',
		'MD' => 'Moldova',
		'MC' => 'Monaco',
		'MN' => 'Mongolia',
		'ME' => 'Montenegro',
		'MA' => 'Morocco',
		'MZ' => 'Mozambique',
		'MM' => 'Myanmar (Burma)',
		'NA' => 'Namibia',
		'NR' => 'Nauru',
		'NP' => 'Nepal',
		'NL' => 'Netherlands',
		'NC' => 'New Caledonia',
		'NZ' => 'New Zealand',
		'NI' => 'Nicaragua',
		'NE' => 'Niger',
		'NG' => 'Nigeria',
		'NO' => 'Norway',
		'OM' => 'Oman',
		'PK' => 'Pakistan',
		'PW' => 'Palau',
		'PS' => 'Palestinian Territories',
		'PA' => 'Panama',
		'PG' => 'Papua New Guinea',
		'PY' => 'Paraguay',
		'PE' => 'Peru',
		'PH' => 'Philippines',
		'PL' => 'Poland',
		'PT' => 'Portugal',
		'QA' => 'Qatar',
		'RO' => 'Romania',
		'RU' => 'Russia',
		'RW' => 'Rwanda',
		'SM' => 'San Marino',
		'SA' => 'Saudi Arabia',
		'SN' => 'Senegal',
		'RS' => 'Serbia',
		'SC' => 'Seychelles',
		'SL' => 'Sierra Leone',
		'SG' => 'Singapore',
		'SK' => 'Slovakia',
		'SI' => 'Slovenia',
		'SB' => 'Solomon Islands',
		'SO' => 'Somalia',
		'ZA' => 'South Africa',
		'ES' => 'Spain',
		'LK' => 'Sri Lanka',
		'SD' => 'Sudan',
		'SR' => 'Suriname',
		'SE' => 'Sweden',
		'CH' => 'Switzerland',
		'SY' => 'Syria',
		'TW' => 'Taiwan',
		'TJ' => 'Tajikistan',
		'TZ' => 'Tanzania',
		'TH' => 'Thailand',
		'TL' => 'Timor-Leste',
		'TG' => 'Togo',
		'TO' => 'Tonga',
		'TT' => 'Trinidad and Tobago',
		'TN' => 'Tunisia',
		'TR' => 'Turkey',
		'TM' => 'Turkmenistan',
		'UG' => 'Uganda',
		'UA' => 'Ukraine',
		'AE' => 'United Arab Emirates',
		'GB' => 'United Kingdom',
		'US' => 'United States',
		'UY' => 'Uruguay',
		'UZ' => 'Uzbekistan',
		'VU' => 'Vanuatu',
		'VE' => 'Venezuela',
		'VN' => 'Vietnam',
		'YE' => 'Yemen',
		'ZM' => 'Zambia',
		'ZW' => 'Zimbabwe'
	);
}

/**
 * Plugin Activation:
 * 1) Detect the activating admin’s IP and set that country in the country list.
 * 2) Create a custom DB table to log blocked attempts.
 * 3) Set default options including the filtering mode and logging.
 */
register_activation_hook( __FILE__, 'advcb_plugin_activation' );
function advcb_plugin_activation() {
	// Set the activating admin's country (fallback is RS)
	$admin_ip     = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$country_code = 'RS';

        if ( ! empty( $admin_ip ) ) {
                $detected_country = advcb_get_country_code_for_ip( $admin_ip );
                if ( ! empty( $detected_country ) ) {
                        $country_code = $detected_country;
                }
        }
	// In allow mode, the admin’s country is the only allowed country.
	update_option( 'advcb_allowed_countries', array( $country_code ) );

	// Create DB table for logs.
	global $wpdb;
	$table_name      = $wpdb->prefix . 'advcb_block_logs';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        ip varchar(100) NOT NULL,
        country_code varchar(5) DEFAULT '' NOT NULL,
        blocked_time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        reason varchar(255) DEFAULT '' NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	// Set default options.
        add_option( 'advcb_secret_key', 'OpenSesame' );
        add_option( 'advcb_blacklisted_ips', array() );
        add_option( 'advcb_trusted_ips', array() );
        add_option( 'advcb_send_email_alerts', false );
        add_option( 'advcb_alert_email', get_option( 'admin_email' ) );
        add_option( 'advcb_mode', 'allow' );
        // New option: enable logging (default true).
        add_option( 'advcb_enable_logs', true );
        add_option( 'advcb_block_page_title', 'Access Restricted' );
        add_option( 'advcb_block_message', 'We’re sorry, but your location is not allowed to view this site.' );
        add_option( 'advcb_enable_redirect', false );
        add_option( 'advcb_redirect_url', '' );
        add_option( 'advcb_redirect_status_code', 302 );
        add_option( 'advcb_http_status_code', 403 );
        add_option( 'advcb_log_retention_days', 30 );
        add_option( 'advcb_geoip_source', 'api' );
        add_option( 'advcb_geoip_db_path', '' );
        // CAPTCHA challenge options.
        add_option( 'advcb_enable_captcha', false );
        add_option( 'advcb_captcha_provider', 'recaptcha_v2' );
        add_option( 'advcb_captcha_site_key', '' );
        add_option( 'advcb_captcha_secret_key', '' );
        add_option( 'advcb_captcha_duration', 24 ); // Hours
        add_option( 'advcb_captcha_threshold', 0.5 ); // For reCAPTCHA v3

        if ( ! wp_next_scheduled( 'advcb_cleanup_logs_event' ) ) {
                wp_schedule_event( time(), 'daily', 'advcb_cleanup_logs_event' );
        }
}

register_deactivation_hook( __FILE__, 'advcb_plugin_deactivation' );
function advcb_plugin_deactivation() {
        wp_clear_scheduled_hook( 'advcb_cleanup_logs_event' );
}

add_action( 'init', 'advcb_ensure_cleanup_schedule' );
function advcb_ensure_cleanup_schedule() {
        if ( ! wp_next_scheduled( 'advcb_cleanup_logs_event' ) ) {
                wp_schedule_event( time(), 'daily', 'advcb_cleanup_logs_event' );
        }
}

/**
 * MAIN BLOCKING LOGIC
 */
function advcb_block_non_allowed_countries() {
	// Allow admins to bypass the blocking logic.
	if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
		return;
	}

	// Allow CAPTCHA verification AJAX requests to pass through.
	if ( wp_doing_ajax() && isset( $_REQUEST['action'] ) && 'advcb_verify_captcha' === $_REQUEST['action'] ) {
		return;
	}

	// Retrieve settings.
	$allowed_countries       = get_option( 'advcb_allowed_countries', array() );
	$secret_key              = get_option( 'advcb_secret_key', 'OpenSesame' );
	$temporary_access_duration = HOUR_IN_SECONDS;
        $blacklisted_ips         = get_option( 'advcb_blacklisted_ips', array() );
        $trusted_ips             = get_option( 'advcb_trusted_ips', array() );
	$send_email_alerts       = get_option( 'advcb_send_email_alerts', false );
	$alert_email             = get_option( 'advcb_alert_email', get_option( 'admin_email' ) );
	$mode                    = get_option( 'advcb_mode', 'allow' ); // 'allow' or 'block'

	// Get visitor's IP address, properly handling proxy headers.
	// Uses WordPress function which respects trusted proxies and prevents spoofing.
	if ( function_exists( 'wp_http_get_original_ip' ) ) {
		$ip = wp_http_get_original_ip();
	} else {
		// Fallback for older WordPress versions.
		$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
	}

	// Validate IP format to prevent invalid IPs from being processed.
	if ( ! filter_var( $ip, FILTER_VALIDATE_IP ) ) {
		$ip = 'unknown';
	}

        if ( ! is_array( $blacklisted_ips ) ) {
                $blacklisted_ips = explode( ',', $blacklisted_ips );
        }
        if ( ! is_array( $trusted_ips ) ) {
                $trusted_ips = explode( ',', $trusted_ips );
        }

        $blacklisted_ips = array_map( 'trim', $blacklisted_ips );
        $trusted_ips     = array_map( 'trim', $trusted_ips );

        // 1) Check if IP is whitelisted explicitly.
        if ( in_array( $ip, $trusted_ips, true ) ) {
                return;
        }

        // 2) Check if IP is blacklisted.
        if ( in_array( $ip, $blacklisted_ips, true ) ) {
                $reason = __( 'Blacklisted IP', 'advcb' );
                advcb_record_block( $ip, 'XX', $reason, $send_email_alerts, $alert_email );
                advcb_show_block_page( array(
                        'ip'           => $ip,
                        'country_code' => 'XX',
                        'reason'       => $reason,
                ) );
                exit;
        }

        // 3) Check if IP is in the temporary whitelist.
        $whitelisted_ips = get_transient( 'advcb_whitelisted_ips' );
        if ( is_array( $whitelisted_ips ) && in_array( $ip, $whitelisted_ips ) ) {
                return; // temporary access granted
	}

        // 3.5) Check if IP has passed CAPTCHA challenge.
        if ( advcb_is_captcha_verified( $ip ) ) {
                return;
        }

        // 4) Determine visitor's country code.
	$country_cache_key = 'advcb_country_' . md5( $ip );
	$country_code      = get_transient( $country_cache_key );

        if ( ! $country_code ) {
                $country_code = advcb_get_country_code_for_ip( $ip );

                if ( $country_code ) {
                        set_transient( $country_cache_key, $country_code, DAY_IN_SECONDS );
                } else {
                        // If we cannot determine the visitor's country, allow access to avoid blocking legitimate users.
                        return;
                }
        }

        /*
         * 5) Country Filtering Logic:
	 * In "allow" mode: if the visitor’s country is NOT in the list, then block.
	 * In "block" mode: if the visitor’s country IS in the list, then block.
	 * In both cases, if the secret key parameter is provided, grant temporary access.
	 */
	if ( $country_code ) {
		if ( $mode === 'allow' && ! in_array( $country_code, $allowed_countries ) ) {
			// Check if the secret key parameter exists and has a truthy value.
			if ( isset( $_GET[ $secret_key ] ) && '1' === $_GET[ $secret_key ] ) {
				// Grant temporary access.
				if ( ! is_array( $whitelisted_ips ) ) {
					$whitelisted_ips = array();
				}
				$whitelisted_ips[] = $ip;
				$whitelisted_ips = array_unique( $whitelisted_ips );
				set_transient( 'advcb_whitelisted_ips', $whitelisted_ips, $temporary_access_duration );

				// Optional: enqueue a redirect alert.
                                add_action( 'wp_enqueue_scripts', function() use ( $secret_key ) {
                                        wp_enqueue_script( 'advcb-alert', plugin_dir_url( __FILE__ ) . 'advcb-alert.js', array(), '1.0', true );
					wp_localize_script( 'advcb-alert', 'advcb_redirect', array(
						'url' => esc_url( remove_query_arg( $secret_key ) ),
					) );
				} );
				return;
			}

                        $reason = __( 'Country not allowed', 'advcb' );
                        advcb_record_block( $ip, $country_code, $reason, $send_email_alerts, $alert_email );

                        // Check if CAPTCHA challenge is enabled - if so, show CAPTCHA instead of block page.
                        $captcha_enabled = get_option( 'advcb_enable_captcha', false );
                        if ( $captcha_enabled ) {
                                advcb_show_captcha_challenge( array(
                                        'ip'           => $ip,
                                        'country_code' => $country_code,
                                        'reason'       => $reason,
                                ) );
                        }

                        advcb_show_block_page( array(
                                'ip'           => $ip,
                                'country_code' => $country_code,
                                'reason'       => $reason,
                        ) );
                        exit;
                } elseif ( $mode === 'block' && in_array( $country_code, $allowed_countries ) ) {
			// Check if the secret key parameter exists and has a truthy value.
			if ( isset( $_GET[ $secret_key ] ) && '1' === $_GET[ $secret_key ] ) {
				// Grant temporary access.
				if ( ! is_array( $whitelisted_ips ) ) {
					$whitelisted_ips = array();
				}
				$whitelisted_ips[] = $ip;
				$whitelisted_ips = array_unique( $whitelisted_ips );
				set_transient( 'advcb_whitelisted_ips', $whitelisted_ips, $temporary_access_duration );

                                add_action( 'wp_enqueue_scripts', function() use ( $secret_key ) {
                                        wp_enqueue_script( 'advcb-alert', plugin_dir_url( __FILE__ ) . 'advcb-alert.js', array(), '1.0', true );
					wp_localize_script( 'advcb-alert', 'advcb_redirect', array(
						'url' => esc_url( remove_query_arg( $secret_key ) ),
					) );
				} );
				return;
			}

                        $reason = __( 'Country blocked', 'advcb' );
                        advcb_record_block( $ip, $country_code, $reason, $send_email_alerts, $alert_email );

                        // Check if CAPTCHA challenge is enabled - if so, show CAPTCHA instead of block page.
                        $captcha_enabled = get_option( 'advcb_enable_captcha', false );
                        if ( $captcha_enabled ) {
                                advcb_show_captcha_challenge( array(
                                        'ip'           => $ip,
                                        'country_code' => $country_code,
                                        'reason'       => $reason,
                                ) );
                        }

                        advcb_show_block_page( array(
                                'ip'           => $ip,
                                'country_code' => $country_code,
                                'reason'       => $reason,
                        ) );
                        exit;
                }
        }
}
add_action( 'init', 'advcb_block_non_allowed_countries' );

/**
 * RECORD BLOCKED ATTEMPT IN DATABASE & (optionally) SEND EMAIL
 */
function advcb_record_block( $ip, $country_code, $reason, $send_email_alerts, $alert_email ) {
	// Check if logging is enabled.
	if ( ! get_option( 'advcb_enable_logs', true ) ) {
		// Logging is disabled; optionally still send an email alert.
		if ( $send_email_alerts && ! empty( $alert_email ) ) {
			$subject = 'Country Blocker Alert: A visitor was blocked';
			$message = sprintf(
				"A visitor from IP: %s (country: %s) was blocked.\nReason: %s\nTime: %s",
				$ip,
				$country_code,
				$reason,
				current_time( 'mysql' )
			);
			wp_mail( $alert_email, $subject, $message );
		}
		return;
	}

	global $wpdb;
	$table_name = $wpdb->prefix . 'advcb_block_logs';

	$wpdb->insert( $table_name, array(
		'ip'           => $ip,
		'country_code' => $country_code ?: '',
		'reason'       => $reason,
	), array( '%s', '%s', '%s' ) );

        if ( $send_email_alerts && ! empty( $alert_email ) ) {
                $subject = 'Country Blocker Alert: A visitor was blocked';
                $message = sprintf(
                        "A visitor from IP: %s (country: %s) was blocked.\nReason: %s\nTime: %s",
                        $ip,
                        $country_code,
                        $reason,
                        current_time( 'mysql' )
                );
                wp_mail( $alert_email, $subject, $message );
        }
}

/**
 * SHOW A CUSTOM BLOCK PAGE (instead of default 403).
 */
function advcb_show_block_page( $context = array() ) {
        $redirect_enabled = (bool) get_option( 'advcb_enable_redirect', false );
        $redirect_url     = get_option( 'advcb_redirect_url', '' );

        if ( $redirect_enabled && ! empty( $redirect_url ) ) {
                $redirect_status = (int) get_option( 'advcb_redirect_status_code', 302 );
                $allowed_redirect_statuses = array( 301, 302, 307, 308 );

                if ( ! in_array( $redirect_status, $allowed_redirect_statuses, true ) ) {
                        $redirect_status = 302;
                }

                wp_safe_redirect( esc_url_raw( $redirect_url ), $redirect_status );
                exit;
        }

        $title       = get_option( 'advcb_block_page_title', 'Access Restricted' );
        $message     = get_option( 'advcb_block_message', 'We’re sorry, but your location is not allowed to view this site.' );
        $status_code = (int) get_option( 'advcb_http_status_code', 403 );

        $message     = advcb_replace_placeholders( $message, $context );
        $status_code = in_array( $status_code, apply_filters( 'advcb_allowed_http_status_codes', array( 403, 410, 451 ) ), true )
                ? $status_code
                : 403;

        $content = '<h1>' . esc_html( $title ) . '</h1>' . wpautop( wp_kses_post( $message ) );

        wp_die(
                $content,
                esc_html( $title ),
                array( 'response' => $status_code )
        );
}

/**
 * REGISTER/INITIALIZE SETTINGS
 */
function advcb_register_settings() {
	// Register and sanitize the country codes list.
	register_setting( 'advcb_options_group', 'advcb_allowed_countries', array(
		'sanitize_callback' => 'advcb_sanitize_allowed_countries',
	) );

	// Register and sanitize secret key.
	register_setting( 'advcb_options_group', 'advcb_secret_key', array(
		'sanitize_callback' => 'sanitize_text_field',
	) );

	// Register and sanitize blacklisted IPs.
        register_setting( 'advcb_options_group', 'advcb_blacklisted_ips', array(
                'sanitize_callback' => 'advcb_sanitize_blacklisted_ips',
        ) );

        register_setting( 'advcb_options_group', 'advcb_trusted_ips', array(
                'sanitize_callback' => 'advcb_sanitize_blacklisted_ips',
        ) );

        // Register and sanitize email alert toggle.
        register_setting( 'advcb_options_group', 'advcb_send_email_alerts', array(
                'sanitize_callback' => 'advcb_sanitize_boolean',
        ) );

	// Register and sanitize alert email.
	register_setting( 'advcb_options_group', 'advcb_alert_email', array(
		'sanitize_callback' => 'sanitize_email',
	) );

        // Register and sanitize the filtering mode.
        register_setting( 'advcb_options_group', 'advcb_mode', array(
                'sanitize_callback' => 'advcb_sanitize_mode',
        ) );

        // Register and sanitize the logging option.
        register_setting( 'advcb_options_group', 'advcb_enable_logs', array(
                'sanitize_callback' => 'advcb_sanitize_boolean',
        ) );

        register_setting( 'advcb_options_group', 'advcb_block_page_title', array(
                'sanitize_callback' => 'sanitize_text_field',
        ) );

        register_setting( 'advcb_options_group', 'advcb_block_message', array(
                'sanitize_callback' => 'advcb_sanitize_textarea',
        ) );

        register_setting( 'advcb_options_group', 'advcb_http_status_code', array(
                'sanitize_callback' => 'advcb_sanitize_http_status',
        ) );

        register_setting( 'advcb_options_group', 'advcb_enable_redirect', array(
                'sanitize_callback' => 'advcb_sanitize_boolean',
        ) );

        register_setting( 'advcb_options_group', 'advcb_redirect_url', array(
                'sanitize_callback' => 'advcb_sanitize_url',
        ) );

        register_setting( 'advcb_options_group', 'advcb_redirect_status_code', array(
                'sanitize_callback' => 'advcb_sanitize_redirect_status',
        ) );

        register_setting( 'advcb_options_group', 'advcb_log_retention_days', array(
                'sanitize_callback' => 'advcb_sanitize_positive_int',
        ) );

        register_setting( 'advcb_options_group', 'advcb_geoip_source', array(
                'sanitize_callback' => 'advcb_sanitize_geoip_source',
        ) );

        register_setting( 'advcb_options_group', 'advcb_geoip_db_path', array(
                'sanitize_callback' => 'advcb_sanitize_file_path',
        ) );

        // Register CAPTCHA challenge settings.
        register_setting( 'advcb_options_group', 'advcb_enable_captcha', array(
                'sanitize_callback' => 'advcb_sanitize_boolean',
        ) );

        register_setting( 'advcb_options_group', 'advcb_captcha_provider', array(
                'sanitize_callback' => 'advcb_sanitize_captcha_provider',
        ) );

        register_setting( 'advcb_options_group', 'advcb_captcha_site_key', array(
                'sanitize_callback' => 'sanitize_text_field',
        ) );

        register_setting( 'advcb_options_group', 'advcb_captcha_secret_key', array(
                'sanitize_callback' => 'advcb_sanitize_captcha_secret_key',
        ) );

        register_setting( 'advcb_options_group', 'advcb_captcha_duration', array(
                'sanitize_callback' => 'advcb_sanitize_positive_int',
        ) );

        register_setting( 'advcb_options_group', 'advcb_captcha_threshold', array(
                'sanitize_callback' => 'advcb_sanitize_captcha_threshold',
        ) );
}
add_action( 'admin_init', 'advcb_register_settings' );

/**
 * Clean up log entries based on retention setting.
 */
function advcb_cleanup_logs() {
        $retention_days = absint( get_option( 'advcb_log_retention_days', 30 ) );

        if ( $retention_days <= 0 ) {
                return;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'advcb_block_logs';

        $threshold = gmdate( 'Y-m-d H:i:s', time() - ( $retention_days * DAY_IN_SECONDS ) );

        $wpdb->query( $wpdb->prepare( "DELETE FROM $table_name WHERE blocked_time < %s", $threshold ) );
}
add_action( 'advcb_cleanup_logs_event', 'advcb_cleanup_logs' );

/**
 * ADD MENU PAGE
 */
function advcb_register_options_page() {
	add_menu_page(
		'Country Blocker',
		'Country Blocker',
		'manage_options',
		'advcb_settings',
		'advcb_options_page',
		'dashicons-location-alt',
		60
	);

	// Add a sub-page for logs.
	add_submenu_page(
		'advcb_settings',
		'Block Logs',
		'Block Logs',
		'manage_options',
		'advcb_block_logs',
		'advcb_block_logs_page'
	);

	// Add a sub-page for analytics dashboard.
	add_submenu_page(
		'advcb_settings',
		'Analytics Dashboard',
		'Analytics',
		'manage_options',
		'advcb_analytics',
		'advcb_analytics_dashboard_page'
	);

	// Add a sub-page for real-time activity monitor.
	add_submenu_page(
		'advcb_settings',
		'Real-Time Monitor',
		'Live Monitor',
		'manage_options',
		'advcb_live_monitor',
		'advcb_live_monitor_page'
	);
}
add_action( 'admin_menu', 'advcb_register_options_page' );

/**
 * MAIN SETTINGS PAGE with Dynamic Country Select Boxes
 */
function advcb_options_page() {
	// Get current filtering mode to adjust labels.
	$mode       = get_option( 'advcb_mode', 'allow' );
	$list_label = ( $mode === 'block' ) ? 'Blocked Country Codes' : 'Allowed Country Codes';
        $list_desc  = ( $mode === 'block' )
                ? 'Select ISO country codes that should be blocked from accessing the site. Everyone else will be allowed automatically.'
                : 'Select ISO country codes that are allowed to access the site. Visitors from all other countries will be blocked.';

	// Retrieve the saved countries. Ensure we have an array.
	$selected_countries = get_option( 'advcb_allowed_countries', array() );
	if ( ! is_array( $selected_countries ) ) {
		$selected_countries = explode( ',', $selected_countries );
	}
	// Always display at least one select box.
	if ( empty( $selected_countries ) ) {
		$selected_countries = array( '' );
	}

	// Get the complete list of countries.
	$countries = advcb_get_countries();

	$trusted_ips = get_option( 'advcb_trusted_ips', array() );
	if ( is_array( $trusted_ips ) ) {
		$trusted_ips = implode( ',', array_filter( array_map( 'trim', $trusted_ips ) ) );
	}

	$geoip_source      = get_option( 'advcb_geoip_source', 'api' );
	$geoip_db_path     = advcb_get_geoip_database_path();
	$geoip_db_readable = $geoip_db_path && file_exists( $geoip_db_path ) && is_readable( $geoip_db_path );
	$geoip_storage_dir = advcb_get_geoip_storage_dir();

	$block_page_title    = get_option( 'advcb_block_page_title', 'Access Restricted' );
	$block_message       = get_option( 'advcb_block_message', 'We\'re sorry, but your location is not allowed to view this site.' );
	$http_status_code    = (int) get_option( 'advcb_http_status_code', 403 );
	$enable_redirect     = (bool) get_option( 'advcb_enable_redirect', false );
	$redirect_url        = get_option( 'advcb_redirect_url', '' );
	$redirect_status     = (int) get_option( 'advcb_redirect_status_code', 302 );
	$log_retention_days  = absint( get_option( 'advcb_log_retention_days', 30 ) );

	// Get current secret key for display.
	$current_secret_key = get_option( 'advcb_secret_key', 'OpenSesame' );

	// CAPTCHA settings.
	$captcha_enabled      = get_option( 'advcb_enable_captcha', false );
	$captcha_provider     = get_option( 'advcb_captcha_provider', 'recaptcha_v2' );
	$captcha_site_key     = get_option( 'advcb_captcha_site_key', '' );
	$captcha_duration     = get_option( 'advcb_captcha_duration', 24 );
	$captcha_threshold    = get_option( 'advcb_captcha_threshold', 0.5 );
	?>
	<div class="wrap advcb-settings-wrap">
		<h1><?php esc_html_e( 'Advanced Country Blocker Settings', 'advcb' ); ?></h1>

		<form method="post" action="options.php" id="advcb-settings-form">
			<?php settings_fields( 'advcb_options_group' ); ?>
			<?php do_settings_sections( 'advcb_options_group' ); ?>

			<!-- Settings Grid -->
			<div class="advcb-settings-grid">

				<!-- Country Filtering Card -->
				<div class="advcb-settings-card">
					<div class="advcb-card-header">
						<h2>🌍 <?php esc_html_e( 'Country Filtering', 'advcb' ); ?></h2>
					</div>
					<div class="advcb-card-content">
						<div class="advcb-field-group">
							<label>
								<input type="hidden" name="advcb_mode" value="allow">
								<input type="checkbox" name="advcb_mode" value="block" <?php checked( 'block', get_option( 'advcb_mode', 'allow' ) ); ?> />
								<span><?php esc_html_e( 'Use Blacklist Mode', 'advcb' ); ?></span>
							</label>
							<p class="advcb-field-description">
								<?php esc_html_e( 'In blacklist mode, the selected countries will be blocked. In allowlist mode (default), only the selected countries can access your site.', 'advcb' ); ?>
							</p>
						</div>

						<div class="advcb-field-group">
							<label><?php echo esc_html( $list_label ); ?></label>
							<p class="advcb-field-description"><?php echo esc_html( $list_desc ); ?></p>
							<div id="advcb_country_selector_container">
								<?php foreach ( $selected_countries as $country ) : ?>
									<div class="advcb_country_selector">
										<select name="advcb_allowed_countries[]">
											<?php foreach ( $countries as $code => $name ) : ?>
												<option value="<?php echo esc_attr( $code ); ?>" <?php selected( $country, $code ); ?>>
													<?php echo esc_html( $name ); ?>
												</option>
											<?php endforeach; ?>
										</select>
										<button type="button" class="button button-small advcb_remove_country">✕</button>
									</div>
								<?php endforeach; ?>
							</div>
							<button type="button" id="advcb_add_country" class="button button-secondary">
								+ <?php esc_html_e( 'Add Country', 'advcb' ); ?>
							</button>
						</div>
					</div>
				</div>

				<!-- GeoIP Settings Card -->
				<div class="advcb-settings-card">
					<div class="advcb-card-header">
						<h2>📍 <?php esc_html_e( 'GeoIP Lookup', 'advcb' ); ?></h2>
					</div>
					<div class="advcb-card-content">
						<div class="advcb-field-group">
							<label><?php esc_html_e( 'Lookup Method', 'advcb' ); ?></label>
							<select name="advcb_geoip_source" class="advcb-select">
								<option value="api" <?php selected( $geoip_source, 'api' ); ?>><?php esc_html_e( 'Remote API (ip-api.com)', 'advcb' ); ?></option>
								<option value="database" <?php selected( $geoip_source, 'database' ); ?>><?php esc_html_e( 'Local MaxMind Database', 'advcb' ); ?></option>
							</select>
							<p class="advcb-field-description">
								<?php esc_html_e( 'Use the remote API for quick setup. Switch to local database for fully offline lookups.', 'advcb' ); ?>
							</p>
							<?php if ( 'database' === $geoip_source && ! $geoip_db_readable ) : ?>
								<p class="advcb-field-warning">
									<span class="dashicons dashicons-warning"></span>
									<?php esc_html_e( 'Local lookup is selected but the database is not readable yet. Falling back to remote API.', 'advcb' ); ?>
								</p>
							<?php endif; ?>
						</div>

						<div class="advcb-field-group">
							<label><?php esc_html_e( 'Database Status', 'advcb' ); ?></label>
							<?php if ( $geoip_db_readable ) : ?>
								<div class="advcb-status advcb-status-success">
									<span class="dashicons dashicons-yes-alt"></span>
									<span><?php printf( esc_html__( 'Using %s', 'advcb' ), '<code>' . esc_html( basename( $geoip_db_path ) ) . '</code>' ); ?></span>
								</div>
								<?php
								$db_details = array();
								if ( $geoip_db_path ) {
									if ( function_exists( 'size_format' ) ) {
										$size = @filesize( $geoip_db_path );
										if ( false !== $size ) {
											$db_details[] = size_format( $size );
										}
									}
									$modified = @filemtime( $geoip_db_path );
									if ( $modified ) {
										$db_details[] = esc_html__( 'updated', 'advcb' ) . ' ' . date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $modified );
									}
								}
								if ( ! empty( $db_details ) ) {
									echo '<p class="advcb-field-description">(' . esc_html( implode( ', ', $db_details ) ) . ')</p>';
								}
								?>
							<?php else : ?>
								<div class="advcb-status advcb-status-warning">
									<span class="dashicons dashicons-warning"></span>
									<?php esc_html_e( 'No readable database found. Download or upload a GeoLite2 Country .mmdb file.', 'advcb' ); ?>
								</div>
							<?php endif; ?>
							<p class="advcb-field-description">
								<?php
								if ( $geoip_storage_dir ) {
									printf( esc_html__( 'Databases are stored in: %s', 'advcb' ), '<code>' . esc_html( $geoip_storage_dir ) . '</code>' );
								} else {
									esc_html_e( 'Databases are stored in your WordPress uploads directory.', 'advcb' );
								}
								?>
							</p>
						</div>

						<div class="advcb-field-group">
							<label><?php esc_html_e( 'Download Database', 'advcb' ); ?></label>
							<div class="advcb-button-group">
								<button type="submit" class="button button-secondary" form="advcb-download-geoip-gitio">
									<?php esc_html_e( 'Download from Git.io', 'advcb' ); ?>
								</button>
								<button type="submit" class="button button-secondary" form="advcb-download-geoip-mirror">
									<?php esc_html_e( 'Download from Mirror', 'advcb' ); ?>
								</button>
							</div>
							<div class="advcb-custom-download">
								<input type="url" class="regular-text" id="advcb_geoip_custom_url" name="advcb_geoip_url" placeholder="https://example.com/GeoLite2-Country.mmdb" form="advcb-download-geoip-custom" />
								<button type="submit" class="button button-primary" form="advcb-download-geoip-custom">
									<?php esc_html_e( 'Download from URL', 'advcb' ); ?>
								</button>
							</div>
						</div>

						<div class="advcb-field-group">
							<label><?php esc_html_e( 'Upload Database', 'advcb' ); ?></label>
							<div class="advcb-upload-section">
								<input type="file" name="advcb_geoip_file" accept=".mmdb" form="advcb-upload-geoip" />
								<button type="submit" class="button button-primary" form="advcb-upload-geoip">
									<?php esc_html_e( 'Upload & Use Database', 'advcb' ); ?>
								</button>
							</div>
						</div>
					</div>
				</div>

				<!-- Block Page Card -->
				<div class="advcb-settings-card">
					<div class="advcb-card-header">
						<h2>🚫 <?php esc_html_e( 'Block Page', 'advcb' ); ?></h2>
					</div>
					<div class="advcb-card-content">
						<div class="advcb-field-group">
							<label for="advcb_block_page_title"><?php esc_html_e( 'Page Title', 'advcb' ); ?></label>
							<input type="text" class="regular-text" id="advcb_block_page_title" name="advcb_block_page_title" value="<?php echo esc_attr( $block_page_title ); ?>" placeholder="<?php esc_attr_e( 'Access Restricted', 'advcb' ); ?>" />
						</div>

						<div class="advcb-field-group">
							<label for="advcb_block_message"><?php esc_html_e( 'Message', 'advcb' ); ?></label>
							<textarea id="advcb_block_message" name="advcb_block_message" rows="3" class="large-text" placeholder="<?php esc_attr_e( 'We\'re sorry, but your location is not allowed to view this site.', 'advcb' ); ?>"><?php echo esc_textarea( $block_message ); ?></textarea>
							<p class="advcb-field-description">
								<?php esc_html_e( 'Available placeholders:', 'advcb' ); ?> <code>{ip}</code>, <code>{country_code}</code>, <code>{reason}</code>
							</p>
						</div>

						<div class="advcb-field-row">
							<div class="advcb-field-group">
								<label for="advcb_http_status_code"><?php esc_html_e( 'HTTP Status', 'advcb' ); ?></label>
								<select name="advcb_http_status_code" id="advcb_http_status_code" class="advcb-select">
									<option value="403" <?php selected( $http_status_code, 403 ); ?>>403 Forbidden</option>
									<option value="410" <?php selected( $http_status_code, 410 ); ?>>410 Gone</option>
									<option value="451" <?php selected( $http_status_code, 451 ); ?>>451 Unavailable for Legal Reasons</option>
								</select>
							</div>
						</div>

						<div class="advcb-field-group advcb_enable_redirect">
							<label>
								<input type="checkbox" name="advcb_enable_redirect" value="1" <?php checked( $enable_redirect ); ?> />
								<?php esc_html_e( 'Redirect blocked visitors instead of showing block page', 'advcb' ); ?>
							</label>
						</div>

						<div class="advcb-field-group advcb-redirect-settings">
							<label for="advcb_redirect_url"><?php esc_html_e( 'Redirect URL', 'advcb' ); ?></label>
							<input type="url" class="regular-text" id="advcb_redirect_url" name="advcb_redirect_url" value="<?php echo esc_attr( $redirect_url ); ?>" placeholder="https://example.com/blocked" />
						</div>

						<div class="advcb-field-group advcb-redirect-settings">
							<label for="advcb_redirect_status_code"><?php esc_html_e( 'Redirect Status', 'advcb' ); ?></label>
							<select name="advcb_redirect_status_code" id="advcb_redirect_status_code" class="advcb-select">
								<option value="302" <?php selected( $redirect_status, 302 ); ?>>302 Temporary Redirect</option>
								<option value="301" <?php selected( $redirect_status, 301 ); ?>>301 Permanent Redirect</option>
								<option value="307" <?php selected( $redirect_status, 307 ); ?>>307 Temporary Redirect</option>
								<option value="308" <?php selected( $redirect_status, 308 ); ?>>308 Permanent Redirect</option>
							</select>
						</div>
					</div>
				</div>

				<!-- Access Control Card -->
				<div class="advcb-settings-card">
					<div class="advcb-card-header">
						<h2>🔐 <?php esc_html_e( 'Access Control', 'advcb' ); ?></h2>
					</div>
					<div class="advcb-card-content">
						<div class="advcb-field-group">
							<label for="advcb_secret_key"><?php esc_html_e( 'Secret Key for Temporary Access', 'advcb' ); ?></label>
							<input type="text" class="regular-text code" id="advcb_secret_key" name="advcb_secret_key" value="<?php echo esc_attr( $current_secret_key ); ?>" placeholder="OpenSesame" />
							<p class="advcb-field-description">
								<?php printf( esc_html__( 'Append %s to the URL to gain temporary access.', 'advcb' ), '<code>?<span id="advcb-secret-preview">' . esc_html( $current_secret_key ) . '</span>=1</code>' ); ?>
							</p>
						</div>

						<div class="advcb-field-group">
							<label for="advcb_blacklisted_ips"><?php esc_html_e( 'Blacklisted IP Addresses', 'advcb' ); ?></label>
							<input type="text" id="advcb_blacklisted_ips" name="advcb_blacklisted_ips" value="<?php echo esc_attr( is_array( get_option( 'advcb_blacklisted_ips', array() ) ) ? implode( ',', get_option( 'advcb_blacklisted_ips', array() ) ) : get_option( 'advcb_blacklisted_ips', '' ) ); ?>" placeholder="1.2.3.4, 5.6.7.8" />
							<p class="advcb-field-description"><?php esc_html_e( 'Comma-separated IPs that will be blocked regardless of country filtering.', 'advcb' ); ?></p>
						</div>

						<div class="advcb-field-group">
							<label for="advcb_trusted_ips"><?php esc_html_e( 'Trusted IP Addresses', 'advcb' ); ?></label>
							<input type="text" id="advcb_trusted_ips" name="advcb_trusted_ips" value="<?php echo esc_attr( $trusted_ips ); ?>" placeholder="1.2.3.4, 5.6.7.8" />
							<p class="advcb-field-description"><?php esc_html_e( 'Comma-separated IPs that always bypass the blocker. Useful for uptime monitors.', 'advcb' ); ?></p>
						</div>

						<div class="advcb-field-divider"></div>
						<h3><?php esc_html_e( 'CAPTCHA Challenge', 'advcb' ); ?></h3>

						<div class="advcb-field-group">
							<label>
								<input type="checkbox" name="advcb_enable_captcha" value="1" <?php checked( $captcha_enabled ); ?> />
								<?php esc_html_e( 'Enable CAPTCHA Challenge', 'advcb' ); ?>
							</label>
							<p class="advcb-field-description"><?php esc_html_e( 'Blocked visitors can solve a CAPTCHA to gain temporary access instead of being completely blocked.', 'advcb' ); ?></p>
						</div>

						<div class="advcb-field-row">
							<div class="advcb-field-group">
								<label for="advcb_captcha_provider"><?php esc_html_e( 'Provider', 'advcb' ); ?></label>
								<select name="advcb_captcha_provider" id="advcb_captcha_provider" class="advcb-select">
									<option value="recaptcha_v2" <?php selected( 'recaptcha_v2', $captcha_provider ); ?>>reCAPTCHA v2</option>
									<option value="recaptcha_v3" <?php selected( 'recaptcha_v3', $captcha_provider ); ?>>reCAPTCHA v3</option>
									<option value="hcaptcha" <?php selected( 'hcaptcha', $captcha_provider ); ?>>hCaptcha</option>
									<option value="turnstile" <?php selected( 'turnstile', $captcha_provider ); ?>>Cloudflare Turnstile</option>
								</select>
							</div>
							<div class="advcb-field-group">
								<label for="advcb_captcha_duration"><?php esc_html_e( 'Access Duration', 'advcb' ); ?></label>
								<input type="number" id="advcb_captcha_duration" name="advcb_captcha_duration" value="<?php echo esc_attr( $captcha_duration ); ?>" min="1" max="168" class="small-text" />
								<span><?php esc_html_e( 'hours', 'advcb' ); ?></span>
							</div>
						</div>

						<div class="advcb-field-row">
							<div class="advcb-field-group">
								<label for="advcb_captcha_site_key"><?php esc_html_e( 'Site Key', 'advcb' ); ?></label>
								<input type="text" id="advcb_captcha_site_key" name="advcb_captcha_site_key" value="<?php echo esc_attr( $captcha_site_key ); ?>" class="regular-text" />
							</div>
							<div class="advcb-field-group">
								<label for="advcb_captcha_secret_key"><?php esc_html_e( 'Secret Key', 'advcb' ); ?></label>
								<input type="password" id="advcb_captcha_secret_key" name="advcb_captcha_secret_key" value="<?php
								$secret_key = get_option( 'advcb_captcha_secret_key', '' );
								if ( isset( $_POST['advcb_captcha_secret_key'] ) && ! empty( $_POST['advcb_captcha_secret_key'] ) ) {
									echo esc_attr( sanitize_text_field( wp_unslash( $_POST['advcb_captcha_secret_key'] ) ) );
								} elseif ( ! empty( $secret_key ) ) {
									echo '••••••••••••••••';
								}
								?>" autocomplete="off" class="regular-text" />
							</div>
						</div>

						<div class="advcb-field-group advcb-recaptcha-v3-setting" style="display: none;">
							<label for="advcb_captcha_threshold"><?php esc_html_e( 'reCAPTCHA v3 Threshold', 'advcb' ); ?></label>
							<input type="number" id="advcb_captcha_threshold" name="advcb_captcha_threshold" value="<?php echo esc_attr( $captcha_threshold ); ?>" min="0" max="1" step="0.1" class="small-text" />
							<p class="advcb-field-description"><?php esc_html_e( 'Score threshold (0.0 - 1.0). Lower is stricter.', 'advcb' ); ?></p>
						</div>
					</div>
				</div>

				<!-- Logging & Alerts Card -->
				<div class="advcb-settings-card">
					<div class="advcb-card-header">
						<h2>📊 <?php esc_html_e( 'Logging & Alerts', 'advcb' ); ?></h2>
					</div>
					<div class="advcb-card-content">
						<div class="advcb-field-group">
							<label>
								<input type="checkbox" name="advcb_enable_logs" value="1" <?php checked( true, (bool) get_option( 'advcb_enable_logs', true ) ); ?> />
								<?php esc_html_e( 'Enable Logging', 'advcb' ); ?>
							</label>
							<p class="advcb-field-description"><?php esc_html_e( 'Keep a record of blocked attempts in the database.', 'advcb' ); ?></p>
						</div>

						<div class="advcb-field-group">
							<label for="advcb_log_retention_days"><?php esc_html_e( 'Log Retention', 'advcb' ); ?></label>
							<input type="number" id="advcb_log_retention_days" name="advcb_log_retention_days" value="<?php echo esc_attr( $log_retention_days ); ?>" min="0" class="small-text" />
							<span><?php esc_html_e( 'days', 'advcb' ); ?></span>
							<p class="advcb-field-description"><?php esc_html_e( 'Entries older than this will be automatically deleted. Use 0 to keep logs forever.', 'advcb' ); ?></p>
						</div>

						<div class="advcb-field-divider"></div>

						<div class="advcb-field-group">
							<label>
								<input type="checkbox" name="advcb_send_email_alerts" value="1" <?php checked( true, (bool) get_option( 'advcb_send_email_alerts', false ) ); ?> />
								<?php esc_html_e( 'Enable Email Alerts', 'advcb' ); ?>
							</label>
						</div>

						<div class="advcb-field-group">
							<label for="advcb_alert_email"><?php esc_html_e( 'Alert Email Address', 'advcb' ); ?></label>
							<input type="email" id="advcb_alert_email" name="advcb_alert_email" value="<?php echo esc_attr( get_option( 'advcb_alert_email', get_option( 'admin_email' ) ) ); ?>" class="regular-text" />
						</div>
					</div>
				</div>

			</div>

			<!-- Save Button -->
			<div class="advcb-save-section">
				<?php submit_button( null, 'primary', 'submit', true, array( 'id' => 'advcb-save-button' ) ); ?>
			</div>
		</form>

		<!-- Hidden Forms for GeoIP Operations -->
		<form id="advcb-download-geoip-gitio" class="advcb-hidden-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:none;" aria-hidden="true">
			<?php wp_nonce_field( 'advcb_geoip_download' ); ?>
			<input type="hidden" name="action" value="advcb_geoip_download" />
			<input type="hidden" name="advcb_geoip_url" value="https://git.io/GeoLite2-Country.mmdb" />
		</form>
		<form id="advcb-download-geoip-mirror" class="advcb-hidden-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:none;" aria-hidden="true">
			<?php wp_nonce_field( 'advcb_geoip_download' ); ?>
			<input type="hidden" name="action" value="advcb_geoip_download" />
			<input type="hidden" name="advcb_geoip_url" value="https://github.com/P3TERX/GeoLite.mmdb/raw/download/GeoLite2-Country.mmdb" />
		</form>
		<form id="advcb-download-geoip-custom" class="advcb-hidden-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:none;" aria-hidden="true">
			<?php wp_nonce_field( 'advcb_geoip_download' ); ?>
			<input type="hidden" name="action" value="advcb_geoip_download" />
		</form>
		<form id="advcb-upload-geoip" class="advcb-hidden-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data" style="display:none;" aria-hidden="true">
			<?php wp_nonce_field( 'advcb_geoip_upload' ); ?>
			<input type="hidden" name="action" value="advcb_geoip_upload" />
		</form>
	</div>

	<!-- Inline JavaScript -->
	<script>
	jQuery(document).ready(function($) {
		// Add new select box when "Add Country" is clicked.
		$('#advcb_add_country').on('click', function(){
			var $clone = $('#advcb_country_selector_container .advcb_country_selector:first').clone();
			$clone.find('select').val('');
			$('#advcb_country_selector_container').append($clone);
		});

		// Remove a select box when its remove button is clicked.
		$(document).on('click', '.advcb_remove_country', function(){
			if ($('#advcb_country_selector_container .advcb_country_selector').length > 1) {
				$(this).closest('.advcb_country_selector').remove();
			} else {
				alert('<?php esc_html_e( 'At least one country must be selected.', 'advcb' ); ?>');
			}
		});

		// Update secret key preview on input change.
		$('#advcb_secret_key').on('input', function() {
			$('#advcb-secret-preview').text($(this).val() || 'OpenSesame');
		});

		// Show/hide reCAPTCHA v3 threshold and redirect settings.
		function toggleDynamicSettings() {
			var provider = $('select[name="advcb_captcha_provider"]').val();
			if (provider === 'recaptcha_v3') {
				$('.advcb-recaptcha-v3-setting').show();
			} else {
				$('.advcb-recaptcha-v3-setting').hide();
			}

			var redirectEnabled = $('input[name="advcb_enable_redirect"]').prop('checked');
			if (redirectEnabled) {
				$('.advcb-redirect-settings').show();
			} else {
				$('.advcb-redirect-settings').hide();
			}
		}

		// Run on page load and when settings change.
		toggleDynamicSettings();
		$('select[name="advcb_captcha_provider"]').on('change', toggleDynamicSettings);
		$('input[name="advcb_enable_redirect"]').on('change', toggleDynamicSettings);
	});
	</script>

	<style>
		.advcb-settings-wrap {
			max-width: 1400px;
		}

		.advcb-settings-grid {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(450px, 1fr));
			gap: 20px;
			margin: 20px 0;
		}

		.advcb-settings-card {
			background: #fff;
			border: 1px solid #c3c4c7;
			border-radius: 8px;
			overflow: hidden;
			box-shadow: 0 1px 3px rgba(0,0,0,0.05);
		}

		.advcb-card-header {
			padding: 15px 20px;
			background: #f6f7f7;
			border-bottom: 1px solid #c3c4c7;
		}

		.advcb-card-header h2 {
			margin: 0;
			font-size: 1.1em;
			color: #1d2327;
			display: flex;
			align-items: center;
			gap: 8px;
		}

		.advcb-card-content {
			padding: 20px;
		}

		.advcb-card-content > h3 {
			margin-top: 0;
			padding-top: 15px;
			font-size: 1em;
			color: #1d2327;
			border-top: 1px solid #eee;
		}

		.advcb-field-group {
			margin-bottom: 18px;
		}

		.advcb-field-group:last-child {
			margin-bottom: 0;
		}

		.advcb-field-group label {
			display: block;
			font-weight: 600;
			margin-bottom: 6px;
			color: #1d2327;
		}

		.advcb-field-group input[type="text"],
		.advcb-field-group input[type="url"],
		.advcb-field-group input[type="email"],
		.advcb-field-group input[type="number"],
		.advcb-field-group input[type="password"],
		.advcb-field-group select,
		.advcb-field-group textarea {
			width: 100%;
			max-width: 400px;
		}

		.advcb-field-group input.code {
			font-family: Monaco, Consolas, monospace;
		}

		.advcb-field-group input.small-text {
			width: 80px;
		}

		.advcb-field-description {
			margin: 6px 0 0;
			font-size: 13px;
			color: #646970;
			line-height: 1.4;
		}

		.advcb-field-description code {
			background: #f0f0f1;
			padding: 2px 6px;
			border-radius: 3px;
			font-size: 12px;
		}

		.advcb-field-warning {
			margin: 8px 0 0;
			padding: 8px 12px;
			background: #fff8e5;
			border-left: 3px solid #dba617;
			color: #646970;
			font-size: 13px;
			display: flex;
			align-items: center;
			gap: 8px;
		}

		.advcb-field-warning .dashicons {
			color: #dba617;
		}

		.advcb-field-row {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 15px;
		}

		.advcb-field-divider {
			height: 1px;
			background: #eee;
			margin: 20px 0;
		}

		.advcb-status {
			display: inline-flex;
			align-items: center;
			gap: 8px;
			padding: 6px 12px;
			border-radius: 4px;
			font-size: 13px;
		}

		.advcb-status-success {
			background: #edfaef;
			color: #00a32a;
		}

		.advcb-status-success .dashicons {
			color: #00a32a;
		}

		.advcb-status-warning {
			background: #fff8e5;
			color: #dba617;
		}

		.advcb-button-group {
			display: flex;
			gap: 10px;
			flex-wrap: wrap;
		}

		.advcb-custom-download {
			display: flex;
			gap: 10px;
			margin-top: 10px;
		}

		.advcb-upload-section {
			display: flex;
			flex-direction: column;
			gap: 10px;
		}

		.advcb-upload-section input[type="file"] {
			max-width: 300px;
		}

		.advcb_country_selector {
			display: flex;
			gap: 8px;
			margin-bottom: 8px;
		}

		.advcb_country_selector select {
			flex: 1;
			max-width: none;
		}

        .advcb_enable_redirect {
            margin-top: 10px;
        }

		/* Responsive */
		@media (max-width: 1200px) {
			.advcb-settings-grid {
				grid-template-columns: 1fr;
			}
		}

		@media (max-width: 600px) {
			.advcb-field-row {
				grid-template-columns: 1fr;
			}

			.advcb-button-group {
				flex-direction: column;
			}

			.advcb-custom-download {
				flex-direction: column;
			}
		}
	</style>
	<?php
}

/**
 * SHOW THE BLOCK LOGS IN THE ADMIN
 */
function advcb_block_logs_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'advcb_block_logs';

        advcb_cleanup_logs();

        // Check for a truncate action and validate nonce.
        if ( isset( $_GET['action'] ) && $_GET['action'] === 'truncate_logs' && isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'truncate_logs' ) ) {
                $wpdb->query( "TRUNCATE TABLE $table_name" );
                echo '<div class="updated notice"><p>Logs have been cleared.</p></div>';
	}

	$paged  = isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1;
	$limit  = 20;
	$offset = ( $paged - 1 ) * $limit;

	$results = $wpdb->get_results( $wpdb->prepare(
		"SELECT * FROM $table_name ORDER BY blocked_time DESC LIMIT %d OFFSET %d",
		$limit,
		$offset
	) );

	$total       = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );
	$total_pages = ceil( $total / $limit );
        ?>
    <div class="wrap">
        <h1>Blocked Attempts Log</h1>
        <p class="description">
            <?php
            $retention_days = absint( get_option( 'advcb_log_retention_days', 30 ) );
            if ( $retention_days > 0 ) {
                    printf( esc_html__( 'Entries older than %d day(s) are removed automatically.', 'advcb' ), $retention_days );
            } else {
                    esc_html_e( 'Automatic log cleanup is currently disabled.', 'advcb' );
            }
            ?>
        </p>
        <!-- Add a Clear Logs button -->
        <p>
            <a href="<?php echo esc_url( add_query_arg( array(
                                'action'   => 'truncate_logs',
                                '_wpnonce' => wp_create_nonce( 'truncate_logs' )
			) ) ); ?>" class="button button-secondary" onclick="return confirm('Are you sure you want to clear all logs?');">
                Clear Logs
            </a>
        </p>
		<?php if ( $results ) : ?>
            <table class="widefat fixed striped">
                <thead>
                <tr>
                    <th width="50px">ID</th>
                    <th width="150px">IP</th>
                    <th width="100px">Country Code</th>
                    <th>Reason</th>
                    <th width="200px">Time</th>
                </tr>
                </thead>
                <tbody>
				<?php foreach ( $results as $row ) : ?>
                    <tr>
                        <td><?php echo esc_html( $row->id ); ?></td>
                        <td><?php echo esc_html( $row->ip ); ?></td>
                        <td><?php echo esc_html( $row->country_code ); ?></td>
                        <td><?php echo esc_html( $row->reason ); ?></td>
                        <td><?php echo esc_html( $row->blocked_time ); ?></td>
                    </tr>
				<?php endforeach; ?>
                </tbody>
            </table>
			<?php if ( $total_pages > 1 ) : ?>
                <div class="tablenav">
                    <div class="tablenav-pages">
						<?php
						// Limit pagination to a maximum of 10 buttons.
						if ( $total_pages > 10 ) {
							if ( $paged <= 6 ) {
								$start = 1;
								$end   = 10;
							} elseif ( $paged > $total_pages - 5 ) {
								$start = $total_pages - 9;
								$end   = $total_pages;
							} else {
								$start = $paged - 5;
								$end   = $paged + 4;
							}
						} else {
							$start = 1;
							$end   = $total_pages;
						}

						// Optionally, add a "Previous" button.
						if ( $paged > 1 ) {
							echo '<a class="button" href="' . esc_url( add_query_arg( array( 'page' => 'advcb_block_logs', 'paged' => $paged - 1 ) ) ) . '">&laquo; Prev</a> ';
						}

						for ( $i = $start; $i <= $end; $i++ ) {
							$class = ( $i == $paged ) ? ' class="button button-primary disabled"' : ' class="button"';
							echo '<a' . wp_kses_post($class) . ' href="' . esc_url( add_query_arg( array( 'page' => 'advcb_block_logs', 'paged' => $i ) ) ) . '">' . esc_html( $i ) . '</a> ';
						}

						// Optionally, add a "Next" button.
						if ( $paged < $total_pages ) {
							echo '<a class="button" href="' . esc_url( add_query_arg( array( 'page' => 'advcb_block_logs', 'paged' => $paged + 1 ) ) ) . '">Next &raquo;</a>';
						}
						?>
                    </div>
                </div>
			<?php endif; ?>
		<?php else : ?>
            <p>No blocked attempts logged yet.</p>
		<?php endif; ?>
    </div>
	<?php
}


/**
 * REAL-TIME ACTIVITY MONITOR
 */

/**
 * Track visitor for real-time monitoring.
 * Runs on every page load to update visitor activity.
 */
function advcb_track_visitor_activity() {
	// Don't track admin users in the monitor (optional).
	if ( current_user_can( 'manage_options' ) ) {
		return;
	}

	$ip = advcb_get_visitor_ip();

	if ( 'unknown' === $ip ) {
		return;
	}

	// Get existing activity data.
	$activity = get_transient( 'advcb_live_activity' );
	if ( ! is_array( $activity ) ) {
		$activity = array(
			'visitors'   => array(),
			'blocks'     => array(),
			'statistics' => array(
				'today_visitors' => 0,
				'today_blocks'   => 0,
				'active_now'     => 0,
			),
		);
	}

	$current_time = current_time( 'mysql' );
	$timestamp = time();

	// Update or add visitor.
	$found = false;
	foreach ( $activity['visitors'] as &$visitor ) {
		if ( $visitor['ip'] === $ip ) {
			$visitor['last_seen'] = $current_time;
			$visitor['timestamp'] = $timestamp;
			$visitor['requests']++;
			$found = true;
			break;
		}
	}
	unset( $visitor );

	if ( ! $found ) {
		// Get visitor country.
		$country_code = advcb_get_country_code_for_ip( $ip );
		$country_name = advcb_get_country_name( $country_code );

		$activity['visitors'][] = array(
			'ip'           => $ip,
			'country_code' => $country_code ?: 'XX',
			'country_name' => $country_name ?: 'Unknown',
			'first_seen'   => $current_time,
			'last_seen'    => $current_time,
			'timestamp'    => $timestamp,
			'requests'     => 1,
			'user_agent'   => isset( $_SERVER['HTTP_USER_AGENT'] ) ? substr( sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ), 0, 100 ) : '',
			'request_uri'  => isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '',
			'method'       => isset( $_SERVER['REQUEST_METHOD'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) : 'GET',
		);
		$activity['statistics']['today_visitors']++;
	}

	// Clean up old visitors (inactive for 5 minutes) and old blocks (older than 1 hour).
	$activity['visitors'] = array_filter( $activity['visitors'], function( $v ) use ( $timestamp ) {
		return ( $timestamp - $v['timestamp'] ) < 300; // 5 minutes
	} );

	$activity['blocks'] = array_filter( $activity['blocks'], function( $b ) use ( $timestamp ) {
		return ( $timestamp - $b['timestamp'] ) < 3600; // 1 hour
	} );

	// Re-index arrays.
	$activity['visitors'] = array_values( $activity['visitors'] );
	$activity['blocks']   = array_values( $activity['blocks'] );

	// Update statistics.
	$activity['statistics']['active_now'] = count( $activity['visitors'] );
	$activity['statistics']['today_blocks'] = count( $activity['blocks'] );

	// Store activity data with 5-minute expiration.
	set_transient( 'advcb_live_activity', $activity, 5 * MINUTE_IN_SECONDS );
}
add_action( 'init', 'advcb_track_visitor_activity', 5 ); // Run early but after plugins loaded.

/**
 * Record a block for the live monitor.
 * Hook into the blocking process.
 */
function advcb_record_live_block( $ip, $country_code, $reason ) {
	$activity = get_transient( 'advcb_live_activity' );
	if ( ! is_array( $activity ) ) {
		$activity = array(
			'visitors'   => array(),
			'blocks'     => array(),
			'statistics' => array(
				'today_visitors' => 0,
				'today_blocks'   => 0,
				'active_now'     => 0,
			),
		);
	}

	// Add block entry.
	$country_name = advcb_get_country_name( $country_code );
	$activity['blocks'][] = array(
		'ip'           => $ip,
		'country_code' => $country_code,
		'country_name' => $country_name ?: 'Unknown',
		'reason'       => $reason,
		'timestamp'    => time(),
		'time'         => current_time( 'mysql' ),
		'user_agent'   => isset( $_SERVER['HTTP_USER_AGENT'] ) ? substr( sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ), 0, 100 ) : '',
	);

	set_transient( 'advcb_live_activity', $activity, 5 * MINUTE_IN_SECONDS );
}
add_action( 'advcb_block_recorded', 'advcb_record_live_block', 10, 3 );

/**
 * Hook to trigger block recording after a block is logged.
 */
function advcb_trigger_block_record_hook( $ip, $country_code, $reason, $send_email_alerts, $alert_email ) {
	do_action( 'advcb_block_recorded', $ip, $country_code, $reason );
}
add_action( 'advcb_record_block', 'advcb_trigger_block_record_hook', 10, 5 );

/**
 * AJAX handler for getting live monitor data.
 */
function advcb_get_live_monitor_data() {
	check_ajax_referer( 'advcb_live_monitor_nonce', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => 'Permission denied' ) );
	}

	$activity = get_transient( 'advcb_live_activity' );

	if ( ! is_array( $activity ) ) {
		$activity = array(
			'visitors'   => array(),
			'blocks'     => array(),
			'statistics' => array(
				'today_visitors' => 0,
				'today_blocks'   => 0,
				'active_now'     => 0,
			),
		);
	}

	// Get additional stats.
	global $wpdb;
	$table_name = $wpdb->prefix . 'advcb_block_logs';

	$today_blocks = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(*) FROM $table_name WHERE DATE(blocked_time) = CURDATE()"
		)
	);

	$activity['statistics']['today_blocks'] = (int) $today_blocks;

	// Get top countries from all-time logs.
	$top_countries_all_time = $wpdb->get_results(
		"SELECT country_code, COUNT(*) as count FROM $table_name WHERE country_code != '' GROUP BY country_code ORDER BY count DESC LIMIT 10",
		ARRAY_A
	);

	$activity['top_countries'] = $top_countries_all_time;

	wp_send_json_success( $activity );
}
add_action( 'wp_ajax_advcb_get_live_monitor_data', 'advcb_get_live_monitor_data' );

/**
 * Get country name from code.
 */
function advcb_get_country_name( $code ) {
	if ( empty( $code ) || 'XX' === $code ) {
		return 'Unknown';
	}

	$countries = advcb_get_countries();
	return isset( $countries[ $code ] ) ? $countries[ $code ] : $code;
}

/**
 * Get country flag emoji from code.
 */
function advcb_get_country_flag( $code ) {
	if ( empty( $code ) || strlen( $code ) !== 2 ) {
		return '';
	}

	// Regional indicator symbols.
	$offset = 0x1F1E6;
	$flag = '';

	for ( $i = 0; $i < 2; $i++ ) {
		$char = ord( strtoupper( $code[ $i ] ) ) - ord( 'A' );
		if ( $char >= 0 && $char <= 25 ) {
			$flag .= mb_chr( $offset + $char );
		}
	}

	return $flag;
}

/**
 * Render the Real-Time Activity Monitor page.
 */
function advcb_live_monitor_page() {
	?>
	<div class="wrap advcb-live-monitor-wrap">
		<h1>
			<?php esc_html_e( 'Real-Time Activity Monitor', 'advcb' ); ?>
			<span class="advcb-live-indicator">
				<span class="advcb-live-dot"></span>
				<span class="advcb-live-text">LIVE</span>
			</span>
		</h1>

		<!-- Statistics Cards -->
		<div class="advcb-monitor-stats">
			<div class="advcb-stat-card advcb-stat-active">
				<div class="advcb-stat-icon">👥</div>
				<div class="advcb-stat-content">
					<div class="advcb-stat-value" id="advcb-active-now">-</div>
					<div class="advcb-stat-label"><?php esc_html_e( 'Active Now', 'advcb' ); ?></div>
				</div>
			</div>
			<div class="advcb-stat-card advcb-stat-visitors">
				<div class="advcb-stat-icon">🌍</div>
				<div class="advcb-stat-content">
					<div class="advcb-stat-value" id="advcb-today-visitors">-</div>
					<div class="advcb-stat-label"><?php esc_html_e( 'Today Visitors', 'advcb' ); ?></div>
				</div>
			</div>
			<div class="advcb-stat-card advcb-stat-blocks">
				<div class="advcb-stat-icon">🚫</div>
				<div class="advcb-stat-content">
					<div class="advcb-stat-value" id="advcb-today-blocks">-</div>
					<div class="advcb-stat-label"><?php esc_html_e( 'Today Blocks', 'advcb' ); ?></div>
				</div>
			</div>
			<div class="advcb-stat-card advcb-stat-requests">
				<div class="advcb-stat-icon">📊</div>
				<div class="advcb-stat-content">
					<div class="advcb-stat-value" id="advcb-requests-per-minute">-</div>
					<div class="advcb-stat-label"><?php esc_html_e( 'Requests/Min', 'advcb' ); ?></div>
				</div>
			</div>
		</div>

		<!-- Monitor Grid -->
		<div class="advcb-monitor-grid">
			<!-- Active Visitors Table -->
			<div class="advcb-monitor-panel">
				<div class="advcb-panel-header">
					<h2><?php esc_html_e( 'Active Visitors (Last 5 min)', 'advcb' ); ?></h2>
					<span class="advcb-panel-count" id="advcb-visitor-count">0</span>
				</div>
				<div class="advcb-panel-content">
					<table class="wp-list-table widefat fixed striped">
						<thead>
							<tr>
								<th><?php esc_html_e( 'IP', 'advcb' ); ?></th>
								<th><?php esc_html_e( 'Country', 'advcb' ); ?></th>
								<th><?php esc_html_e( 'Requests', 'advcb' ); ?></th>
								<th><?php esc_html_e( 'Last Seen', 'advcb' ); ?></th>
								<th><?php esc_html_e( 'Page', 'advcb' ); ?></th>
							</tr>
						</thead>
						<tbody id="advcb-visitors-table">
							<tr><td colspan="5" class="advcb-loading-row"><?php esc_html_e( 'Loading...', 'advcb' ); ?></td></tr>
						</tbody>
					</table>
				</div>
			</div>

			<!-- Recent Blocks Table -->
			<div class="advcb-monitor-panel">
				<div class="advcb-panel-header">
					<h2><?php esc_html_e( 'Recent Blocks (Last hour)', 'advcb' ); ?></h2>
					<span class="advcb-panel-count advcb-panel-count-warning" id="advcb-block-count">0</span>
				</div>
				<div class="advcb-panel-content">
					<table class="wp-list-table widefat fixed striped">
						<thead>
							<tr>
								<th><?php esc_html_e( 'IP', 'advcb' ); ?></th>
								<th><?php esc_html_e( 'Country', 'advcb' ); ?></th>
								<th><?php esc_html_e( 'Reason', 'advcb' ); ?></th>
								<th><?php esc_html_e( 'Time', 'advcb' ); ?></th>
							</tr>
						</thead>
						<tbody id="advcb-blocks-table">
							<tr><td colspan="4" class="advcb-loading-row"><?php esc_html_e( 'Loading...', 'advcb' ); ?></td></tr>
						</tbody>
					</table>
				</div>
			</div>

			<!-- World Map / Country Summary -->
			<div class="advcb-monitor-panel advcb-panel-full">
				<div class="advcb-panel-header">
					<h2><?php esc_html_e( 'Top Blocked Countries (All Time)', 'advcb' ); ?></h2>
				</div>
				<div class="advcb-panel-content">
					<div id="advcb-countries-chart" class="advcb-countries-list">
						<?php esc_html_e( 'Loading...', 'advcb' ); ?>
					</div>
				</div>
			</div>
		</div>

		<!-- Activity Feed -->
		<div class="advcb-monitor-panel advcb-panel-full">
			<div class="advcb-panel-header">
				<h2><?php esc_html_e( 'Live Activity Feed', 'advcb' ); ?></h2>
				<button type="button" class="button button-small" id="advcb-clear-feed"><?php esc_html_e( 'Clear Feed', 'advcb' ); ?></button>
			</div>
			<div class="advcb-panel-content">
				<div id="advcb-activity-feed" class="advcb-activity-feed">
					<div class="advcb-feed-placeholder"><?php esc_html_e( 'Waiting for activity...', 'advcb' ); ?></div>
				</div>
			</div>
		</div>
	</div>

	<script>
	jQuery(document).ready(function($) {
		var updateInterval = 3000; // Update every 3 seconds
		var lastVisitorCount = 0;
		var lastBlockCount = 0;
		var activityFeedLimit = 50;

		// Inline country names mapping.
		var countryMap = <?php
			$countries = advcb_get_countries();
			echo json_encode( array_slice( $countries, 1, null, true ) );
		?>;

		function getCountryName(code) {
			if (!code || code === 'XX') return 'Unknown';
			return countryMap[code] || code;
		}

		function getCountryFlag(code) {
			if (!code || code.length !== 2) return '';
			var offset = 0x1F1E6;
			var flag = '';
			for (var i = 0; i < 2; i++) {
				var char = code.toUpperCase().charCodeAt(i) - 65;
				if (char >= 0 && char <= 25) {
					flag += String.fromCodePoint(offset + char);
				}
			}
			return flag;
		}

		function formatTimeAgo(timestamp) {
			var seconds = Math.floor((Date.now() / 1000) - timestamp);
			if (seconds < 60) return seconds + 's ago';
			if (seconds < 3600) return Math.floor(seconds / 60) + 'm ago';
			return Math.floor(seconds / 3600) + 'h ago';
		}

		function updateMonitor() {
			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'advcb_get_live_monitor_data',
					nonce: '<?php echo wp_create_nonce( 'advcb_live_monitor_nonce' ); ?>'
				},
				timeout: 5000,
				success: function(response) {
					if (response.success) {
						renderMonitor(response.data);
					}
				},
				error: function() {
					// Continue trying on error
				}
			});
		}

		function renderMonitor(data) {
			// Update statistics cards.
			$('#advcb-active-now').text(data.statistics.active_now || 0);
			$('#advcb-today-visitors').text(data.statistics.today_visitors || 0);
			$('#advcb-today-blocks').text(data.statistics.today_blocks || 0);

			// Calculate requests per minute.
			var totalRequests = 0;
			if (data.visitors && data.visitors.length) {
				data.visitors.forEach(function(v) {
					totalRequests += (v.requests || 0);
				});
			}
			$('#advcb-requests-per-minute').text(Math.round(totalRequests / 5)); // Divide by 5 minutes window

			// Update visitor count badge.
			$('#advcb-visitor-count').text((data.visitors || []).length);
			$('#advcb-block-count').text((data.blocks || []).length);

			// Render visitors table.
			var $visitorsTable = $('#advcb-visitors-table');
			if (data.visitors && data.visitors.length > 0) {
				var html = '';
				data.visitors.slice(0, 20).forEach(function(v) {
					var isNew = v.requests === 1;
					var newRow = isNew ? ' advcb-new-row' : '';
					html += '<tr class="' + (isNew ? 'advcb-new-row' : '') + '">';
					html += '<td><code>' + escHtml(v.ip) + '</code></td>';
					html += '<td>' + getCountryFlag(v.country_code) + ' ' + escHtml(getCountryName(v.country_code)) + '</td>';
					html += '<td>' + v.requests + '</td>';
					html += '<td>' + formatTimeAgo(v.timestamp) + '</td>';
					html += '<td><span class="advcb-page-path" title="' + escHtml(v.request_uri) + '">' + escHtml(v.request_uri.substring(0, 40)) + '</span></td>';
					html += '</tr>';
				});
				$visitorsTable.html(html);

				// Add new visitor alerts to feed.
				var newVisitors = data.visitors.filter(function(v) { return v.requests === 1; });
				if (newVisitors.length > lastVisitorCount) {
					newVisitors.forEach(function(v) {
						if (v.requests === 1) {
							addFeedItem('visitor', v);
						}
					});
				}
				lastVisitorCount = data.visitors.filter(function(v) { return v.requests === 1; }).length;
			} else {
				$visitorsTable.html('<tr><td colspan="5" class="advcb-empty-state"><?php esc_html_e( 'No active visitors', 'advcb' ); ?></td></tr>');
			}

			// Render blocks table.
			var $blocksTable = $('#advcb-blocks-table');
			if (data.blocks && data.blocks.length > 0) {
				var html = '';
				data.blocks.slice(0, 20).forEach(function(b) {
					html += '<tr class="advcb-block-row">';
					html += '<td><code>' + escHtml(b.ip) + '</code></td>';
					html += '<td>' + getCountryFlag(b.country_code) + ' ' + escHtml(getCountryName(b.country_code)) + '</td>';
					html += '<td>' + escHtml(b.reason) + '</td>';
					html += '<td>' + formatTimeAgo(b.timestamp) + '</td>';
					html += '</tr>';
				});
				$blocksTable.html(html);

				// Add new block alerts to feed.
				if (data.blocks.length > lastBlockCount) {
					for (var i = lastBlockCount; i < data.blocks.length; i++) {
						addFeedItem('block', data.blocks[i]);
					}
				}
				lastBlockCount = data.blocks.length;
			} else {
				$blocksTable.html('<tr><td colspan="4" class="advcb-empty-state"><?php esc_html_e( 'No recent blocks', 'advcb' ); ?></td></tr>');
			}

			// Render top countries.
			if (data.top_countries && data.top_countries.length > 0) {
				var html = '<div class="advcb-countries-grid">';
				var maxCount = data.top_countries[0].count;
				data.top_countries.forEach(function(c) {
					var percent = Math.round((c.count / maxCount) * 100);
					html += '<div class="advcb-country-item">';
					html += '<div class="advcb-country-flag">' + getCountryFlag(c.country_code) + '</div>';
					html += '<div class="advcb-country-info">';
					html += '<div class="advcb-country-name">' + escHtml(getCountryName(c.country_code)) + '</div>';
					html += '<div class="advcb-country-bar"><div class="advcb-country-bar-fill" style="width: ' + percent + '%"></div></div>';
					html += '<div class="advcb-country-count">' + c.count.toLocaleString() + '</div>';
					html += '</div></div>';
				});
				html += '</div>';
				$('#advcb-countries-chart').html(html);
			} else {
				$('#advcb-countries-chart').html('<div class="advcb-empty-state"><?php esc_html_e( 'No data available', 'advcb' ); ?></div>');
			}
		}

		function addFeedItem(type, data) {
			var $feed = $('#advcb-activity-feed');
			$feed.find('.advcb-feed-placeholder').remove();

			var item = $('<div class="advcb-feed-item advcb-feed-' + type + '">');
			var time = new Date().toLocaleTimeString();

			if (type === 'visitor') {
				item.html('<span class="advcb-feed-time">[' + time + ']</span> ' +
					'<span class="advcb-feed-icon">👤</span> ' +
					'New visitor from <strong>' + getCountryFlag(data.country_code) + ' ' + escHtml(getCountryName(data.country_code)) + '</strong> ' +
					'<span class="advcb-feed-ip">(' + escHtml(data.ip) + ')</span>');
			} else if (type === 'block') {
				item.html('<span class="advcb-feed-time">[' + time + ']</span> ' +
					'<span class="advcb-feed-icon">🚫</span> ' +
					'Blocked <strong>' + getCountryFlag(data.country_code) + ' ' + escHtml(getCountryName(data.country_code)) + '</strong> ' +
					'<span class="advcb-feed-ip">(' + escHtml(data.ip) + ')</span> - ' +
					'<span class="advcb-feed-reason">' + escHtml(data.reason) + '</span>');
			}

			$feed.prepend(item);

			// Limit feed items.
			var items = $feed.find('.advcb-feed-item');
			if (items.length > activityFeedLimit) {
				items.slice(activityFeedLimit).remove();
			}
		}

		function escHtml(str) {
			if (!str) return '';
			return $('<div/>').text(str).html();
		}

		// Clear feed button.
		$('#advcb-clear-feed').on('click', function() {
			$('#advcb-activity-feed').html('<div class="advcb-feed-placeholder"><?php esc_html_e( 'Waiting for activity...', 'advcb' ); ?></div>');
		});

		// Start monitoring.
		updateMonitor();
		setInterval(updateMonitor, updateInterval);

		// Update time-ago displays every 10 seconds.
		setInterval(function() {
			$('.advcb-new-row').removeClass('advcb-new-row');
		}, 10000);
	});
	</script>

	<style>
		.advcb-live-monitor-wrap {
			max-width: 1600px;
		}

		.advcb-live-indicator {
			display: inline-flex;
			align-items: center;
			margin-left: 15px;
			padding: 4px 10px;
			background: #f0f0f1;
			border-radius: 12px;
			font-size: 12px;
			font-weight: 600;
		}

		.advcb-live-dot {
			width: 8px;
			height: 8px;
			background: #22c55e;
			border-radius: 50%;
			margin-right: 6px;
			animation: advcb-pulse 1.5s infinite;
		}

		@keyframes advcb-pulse {
			0%, 100% { opacity: 1; transform: scale(1); }
			50% { opacity: 0.7; transform: scale(1.1); }
		}

		.advcb-monitor-stats {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
			gap: 15px;
			margin: 20px 0;
		}

		.advcb-stat-card {
			background: #fff;
			border: 1px solid #c3c4c7;
			border-radius: 8px;
			padding: 20px;
			display: flex;
			align-items: center;
			transition: transform 0.2s, box-shadow 0.2s;
		}

		.advcb-stat-card:hover {
			transform: translateY(-2px);
			box-shadow: 0 4px 12px rgba(0,0,0,0.1);
		}

		.advcb-stat-icon {
			font-size: 2em;
			margin-right: 15px;
			opacity: 0.8;
		}

		.advcb-stat-content {
			flex: 1;
		}

		.advcb-stat-value {
			font-size: 1.8em;
			font-weight: 700;
			color: #1d2327;
			line-height: 1;
		}

		.advcb-stat-label {
			color: #646970;
			font-size: 0.85em;
			margin-top: 5px;
		}

		.advcb-stat-active { border-left: 4px solid #22c55e; }
		.advcb-stat-visitors { border-left: 4px solid #3b82f6; }
		.advcb-stat-blocks { border-left: 4px solid #ef4444; }
		.advcb-stat-requests { border-left: 4px solid #f59e0b; }

		.advcb-monitor-grid {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 15px;
			margin-bottom: 15px;
		}

		.advcb-monitor-panel {
			background: #fff;
			border: 1px solid #c3c4c7;
			border-radius: 8px;
			overflow: hidden;
		}

		.advcb-panel-full {
			grid-column: 1 / -1;
		}

		.advcb-panel-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 15px 20px;
			background: #f6f7f7;
			border-bottom: 1px solid #c3c4c7;
		}

		.advcb-panel-header h2 {
			margin: 0;
			font-size: 1.1em;
			color: #1d2327;
		}

		.advcb-panel-count {
			background: #2271b1;
			color: #fff;
			padding: 2px 8px;
			border-radius: 10px;
			font-size: 12px;
			font-weight: 600;
		}

		.advcb-panel-count-warning {
			background: #d63638;
		}

		.advcb-panel-content {
			padding: 0;
			max-height: 400px;
			overflow-y: auto;
		}

		.advcb-panel-content table {
			margin: 0;
			border: none;
		}

		.advcb-panel-content thead th {
			position: sticky;
			top: 0;
			background: #f6f7f7;
			z-index: 1;
		}

		.advcb-page-path {
			color: #646970;
			font-size: 0.9em;
		}

		.advcb-new-row {
			background: #dcfce7 !important;
			animation: advcb-flash 0.5s ease;
		}

		@keyframes advcb-flash {
			0% { background: #86efac; }
			100% { background: #dcfce7; }
		}

		.advcb-block-row {
			background: #fee2e2 !important;
		}

		.advcb-empty-state,
		.advcb-loading-row {
			text-align: center;
			color: #646970;
			padding: 30px !important;
		}

		/* Countries List */
		.advcb-countries-list {
			padding: 20px;
		}

		.advcb-countries-grid {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
			gap: 15px;
		}

		.advcb-country-item {
			display: flex;
			align-items: center;
			padding: 12px;
			background: #f9fafb;
			border-radius: 6px;
			transition: background 0.2s;
		}

		.advcb-country-item:hover {
			background: #f3f4f6;
		}

		.advcb-country-flag {
			font-size: 1.5em;
			margin-right: 12px;
		}

		.advcb-country-info {
			flex: 1;
		}

		.advcb-country-name {
			font-weight: 600;
			margin-bottom: 6px;
		}

		.advcb-country-bar {
			height: 6px;
			background: #e5e7eb;
			border-radius: 3px;
			overflow: hidden;
		}

		.advcb-country-bar-fill {
			height: 100%;
			background: linear-gradient(90deg, #2271b1, #3b82f6);
			border-radius: 3px;
			transition: width 0.5s ease;
		}

		.advcb-country-count {
			font-size: 0.85em;
			color: #646970;
			margin-top: 4px;
		}

		/* Activity Feed */
		.advcb-activity-feed {
			padding: 15px;
			max-height: 250px;
			overflow-y: auto;
			background: #1d2327;
			color: #fff;
			font-family: Monaco, Consolas, monospace;
			font-size: 13px;
			line-height: 1.6;
			border-radius: 0 0 7px 7px;
		}

		.advcb-feed-item {
			padding: 6px 0;
			border-bottom: 1px solid rgba(255,255,255,0.1);
			animation: advcb-slideIn 0.3s ease;
		}

		@keyframes advcb-slideIn {
			from { opacity: 0; transform: translateX(-10px); }
			to { opacity: 1; transform: translateX(0); }
		}

		.advcb-feed-item:last-child {
			border-bottom: none;
		}

		.advcb-feed-time {
			color: #8c8f94;
		}

		.advcb-feed-icon {
			margin: 0 6px;
		}

		.advcb-feed-ip {
			color: #8c8f94;
		}

		.advcb-feed-visitor .advcb-feed-reason {
			color: #22c55e;
		}

		.advcb-feed-block .advcb-feed-reason {
			color: #ef4444;
		}

		.advcb-feed-placeholder {
			text-align: center;
			color: #8c8f94;
			font-style: italic;
		}

		@media (max-width: 1200px) {
			.advcb-monitor-grid {
				grid-template-columns: 1fr;
			}
		}
	</style>
	<?php
}


/**
 * CAPTCHA CHALLENGE SYSTEM
 */

/**
 * Check if an IP has passed the CAPTCHA challenge.
 */
function advcb_is_captcha_verified( $ip ) {
	$verified_ips = get_transient( 'advcb_captcha_verified_ips' );
	return is_array( $verified_ips ) && in_array( $ip, $verified_ips, true );
}

/**
 * Add an IP to the CAPTCHA verified list.
 */
function advcb_add_captcha_verified_ip( $ip ) {
	$verified_ips = get_transient( 'advcb_captcha_verified_ips' );
	if ( ! is_array( $verified_ips ) ) {
		$verified_ips = array();
	}

	if ( ! in_array( $ip, $verified_ips, true ) ) {
		$verified_ips[] = $ip;
	}

	$duration = intval( get_option( 'advcb_captcha_duration', 24 ) );
	$expiration = $duration * HOUR_IN_SECONDS;

	set_transient( 'advcb_captcha_verified_ips', $verified_ips, $expiration );
}

/**
 * AJAX handler for CAPTCHA verification.
 */
function advcb_verify_captcha_challenge() {
	check_ajax_referer( 'advcb_captcha_nonce', 'nonce' );

	$provider = get_option( 'advcb_captcha_provider', 'recaptcha_v2' );
	$secret_key = get_option( 'advcb_captcha_secret_key', '' );

	if ( empty( $secret_key ) ) {
		wp_send_json_error( array( 'message' => __( 'CAPTCHA not configured.', 'advcb' ) ) );
	}

	$captcha_response = isset( $_POST['captcha_response'] ) ? sanitize_text_field( wp_unslash( $_POST['captcha_response'] ) ) : '';

	if ( empty( $captcha_response ) ) {
		wp_send_json_error( array( 'message' => __( 'Please complete the CAPTCHA.', 'advcb' ) ) );
	}

	$verified = false;
	$error_message = '';

	switch ( $provider ) {
		case 'recaptcha_v2':
		case 'recaptcha_v3':
			$response = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify', array(
				'body' => array(
					'secret' => $secret_key,
					'response' => $captcha_response,
					'remoteip' => advcb_get_visitor_ip(),
				),
				'timeout' => 10,
			) );

			if ( is_wp_error( $response ) ) {
				$error_message = __( 'Failed to verify CAPTCHA.', 'advcb' );
			} else {
				$body = json_decode( wp_remote_retrieve_body( $response ), true );
				if ( isset( $body['success'] ) && $body['success'] ) {
					if ( 'recaptcha_v3' === $provider ) {
						$threshold = floatval( get_option( 'advcb_captcha_threshold', 0.5 ) );
						$score = isset( $body['score'] ) ? floatval( $body['score'] ) : 0;
						if ( $score >= $threshold ) {
							$verified = true;
						} else {
							$error_message = __( 'Verification failed. Please try again.', 'advcb' );
						}
					} else {
						$verified = true;
					}
				} else {
					$error_message = __( 'CAPTCHA verification failed.', 'advcb' );
				}
			}
			break;

		case 'hcaptcha':
			$response = wp_remote_post( 'https://api.hcaptcha.com/siteverify', array(
				'body' => array(
					'secret' => $secret_key,
					'response' => $captcha_response,
					'remoteip' => advcb_get_visitor_ip(),
				),
				'timeout' => 10,
			) );

			if ( is_wp_error( $response ) ) {
				$error_message = __( 'Failed to verify CAPTCHA.', 'advcb' );
			} else {
				$body = json_decode( wp_remote_retrieve_body( $response ), true );
				if ( isset( $body['success'] ) && $body['success'] ) {
					$verified = true;
				} else {
					$error_message = __( 'CAPTCHA verification failed.', 'advcb' );
				}
			}
			break;

		case 'turnstile':
			$response = wp_remote_post( 'https://challenges.cloudflare.com/turnstile/v0/siteverify', array(
				'body' => array(
					'secret' => $secret_key,
					'response' => $captcha_response,
					'remoteip' => advcb_get_visitor_ip(),
				),
				'timeout' => 10,
			) );

			if ( is_wp_error( $response ) ) {
				$error_message = __( 'Failed to verify CAPTCHA.', 'advcb' );
			} else {
				$body = json_decode( wp_remote_retrieve_body( $response ), true );
				if ( isset( $body['success'] ) && $body['success'] ) {
					$verified = true;
				} else {
					$error_message = __( 'CAPTCHA verification failed.', 'advcb' );
				}
			}
			break;
	}

	if ( $verified ) {
		$ip = advcb_get_visitor_ip();
		advcb_add_captcha_verified_ip( $ip );
		wp_send_json_success( array(
			'message' => __( 'Verification successful! Redirecting...', 'advcb' ),
			'redirect' => home_url( '/' ),
		) );
	} else {
		wp_send_json_error( array( 'message' => $error_message ) );
	}
}
add_action( 'wp_ajax_advcb_verify_captcha', 'advcb_verify_captcha_challenge' );
add_action( 'wp_ajax_nopriv_advcb_verify_captcha', 'advcb_verify_captcha_challenge' );

/**
 * Get visitor IP address (centralized function).
 */
function advcb_get_visitor_ip() {
	if ( function_exists( 'wp_http_get_original_ip' ) ) {
		return wp_http_get_original_ip();
	}

	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';

	if ( ! filter_var( $ip, FILTER_VALIDATE_IP ) ) {
		$ip = 'unknown';
	}

	return $ip;
}

/**
 * Show CAPTCHA challenge page.
 */
function advcb_show_captcha_challenge( $context = array() ) {
	$provider = get_option( 'advcb_captcha_provider', 'recaptcha_v2' );
	$site_key = get_option( 'advcb_captcha_site_key', '' );

	if ( empty( $site_key ) ) {
		advcb_show_block_page( $context );
		return;
	}

	// Get scripts based on provider.
	$script_url = '';
	$inline_script = '';

	switch ( $provider ) {
		case 'recaptcha_v2':
			$script_url = 'https://www.google.com/recaptcha/api.js';
			$inline_script = '';
			break;
		case 'recaptcha_v3':
			$script_url = 'https://www.google.com/recaptcha/api.js?render=' . rawurlencode( $site_key );
			$inline_script = '';
			break;
		case 'hcaptcha':
			$script_url = 'https://hcaptcha.com/1/api.js';
			$inline_script = '';
			break;
		case 'turnstile':
			$script_url = 'https://challenges.cloudflare.com/turnstile/v0/api.js';
			$inline_script = '';
			break;
	}

	$title = get_option( 'advcb_block_page_title', 'Access Restricted' );
	$country_code = isset( $context['country_code'] ) ? $context['country_code'] : '';

	?>
	<!DOCTYPE html>
	<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo esc_html( $title ); ?></title>
		<?php wp_print_styles( 'login' ); ?>
		<style>
			body {
				background: #f0f0f1;
				display: flex;
				align-items: center;
				justify-content: center;
				min-height: 100vh;
				margin: 0;
				font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
			}
			.advcb-captcha-container {
				background: #fff;
				padding: 40px;
				border-radius: 8px;
				box-shadow: 0 4px 20px rgba(0,0,0,0.1);
				text-align: center;
				max-width: 400px;
				width: 100%;
			}
			.advcb-captcha-icon {
				font-size: 48px;
				margin-bottom: 20px;
			}
			.advcb-captcha-title {
				font-size: 24px;
				font-weight: 600;
				margin-bottom: 10px;
				color: #1d2327;
			}
			.advcb-captcha-message {
				color: #646970;
				margin-bottom: 25px;
				line-height: 1.5;
			}
			.advcb-captcha-widget {
				margin-bottom: 20px;
				min-height: 80px;
				display: flex;
				align-items: center;
				justify-content: center;
			}
			.advcb-captcha-button {
				background: #2271b1;
				color: #fff;
				border: none;
				padding: 12px 24px;
				border-radius: 4px;
				font-size: 16px;
				cursor: pointer;
				width: 100%;
			}
			.advcb-captcha-button:hover {
				background: #135e96;
			}
			.advcb-captcha-button:disabled {
				background: #a7aaad;
				cursor: not-allowed;
			}
			.advcb-captcha-error {
				background: #f8d7da;
				color: #721c24;
				padding: 10px;
				border-radius: 4px;
				margin-bottom: 15px;
				display: none;
			}
			.advcb-captcha-spinner {
				display: inline-block;
				width: 16px;
				height: 16px;
				border: 2px solid #fff;
				border-radius: 50%;
				border-top-color: transparent;
				animation: advcb-spin 1s linear infinite;
				margin-left: 10px;
				vertical-align: middle;
			}
			@keyframes advcb-spin {
				to { transform: rotate(360deg); }
			}
		</style>
		<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
		<?php if ( ! empty( $script_url ) ) : ?>
			<script src="<?php echo esc_url( $script_url ); ?>" async defer></script>
		<?php endif; ?>
	</head>
	<body>
		<div class="advcb-captcha-container">
			<div class="advcb-captcha-icon">🔐</div>
			<h1 class="advcb-captcha-title"><?php echo esc_html( $title ); ?></h1>
			<p class="advcb-captcha-message">
				<?php
				printf(
					/* translators: %s: country code */
					esc_html__( 'Your location (%s) is not currently allowed to access this site. Please complete the verification below to continue.', 'advcb' ),
					esc_html( $country_code )
				);
				?>
			</p>

			<div class="advcb-captcha-error" id="advcb-error"></div>

			<div class="advcb-captcha-widget">
				<?php if ( 'recaptcha_v2' === $provider ) : ?>
					<div class="g-recaptcha" data-sitekey="<?php echo esc_attr( $site_key ); ?>"></div>
				<?php elseif ( 'recaptcha_v3' === $provider ) : ?>
					<input type="hidden" id="advcb-recaptcha-response" name="recaptcha_response">
				<?php elseif ( 'hcaptcha' === $provider ) : ?>
					<div class="h-captcha" data-sitekey="<?php echo esc_attr( $site_key ); ?>"></div>
				<?php elseif ( 'turnstile' === $provider ) : ?>
					<div class="cf-turnstile" data-sitekey="<?php echo esc_attr( $site_key ); ?>"></div>
				<?php endif; ?>
			</div>

			<button type="button" class="advcb-captcha-button" id="advcb-verify-btn">
				<span><?php esc_html_e( 'Verify & Continue', 'advcb' ); ?></span>
			</button>
		</div>

		<script>
		jQuery(document).ready(function($) {
			var provider = '<?php echo esc_js( $provider ); ?>';
			var responseToken = '';

			// For reCAPTCHA v3, execute on load
			if (provider === 'recaptcha_v3') {
				grecaptcha.ready(function() {
					grecaptcha.execute('<?php echo esc_js( $site_key ); ?>', {action: 'verify'})
						.then(function(token) {
							responseToken = token;
						});
				});
			}

			$('#advcb-verify-btn').on('click', function() {
				var $btn = $(this);
				var $error = $('#advcb-error');

				// Get token based on provider
				if (provider === 'recaptcha_v3') {
					// Token already loaded
					if (!responseToken) {
						grecaptcha.execute('<?php echo esc_js( $site_key ); ?>', {action: 'verify'})
							.then(function(token) {
								responseToken = token;
								verifyCaptcha(responseToken);
							});
						return;
					}
				} else if (provider === 'recaptcha_v2') {
					responseToken = $('.g-recaptcha-response').val() || '';
				} else if (provider === 'hcaptcha') {
					responseToken = $('[name="h-captcha-response"]').val() || '';
				} else if (provider === 'turnstile') {
					responseToken = $('[name="cf-turnstile-response"]').val() || '';
				}

				if (!responseToken) {
					$error.text('<?php esc_html_e( 'Please complete the CAPTCHA first.', 'advcb' ); ?>').show();
					return;
				}

				verifyCaptcha(responseToken);
			});

			function verifyCaptcha(token) {
				var $btn = $('#advcb-verify-btn');
				var $error = $('#advcb-error');

				$btn.prop('disabled', true).find('span').html('<?php esc_html_e( 'Verifying...', 'advcb' ); ?>');
				$error.hide();

				$.ajax({
					url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
					type: 'POST',
					data: {
						action: 'advcb_verify_captcha',
						nonce: '<?php echo wp_create_nonce( 'advcb_captcha_nonce' ); ?>',
						captcha_response: token
					},
					success: function(response) {
						if (response.success) {
							$btn.find('span').html('<?php esc_html_e( 'Success!', 'advcb' ); ?>');
							setTimeout(function() {
								window.location.href = response.data.redirect;
							}, 500);
						} else {
							$error.text(response.data.message || '<?php esc_html_e( 'Verification failed. Please try again.', 'advcb' ); ?>').show();
							$btn.prop('disabled', false).find('span').html('<?php esc_html_e( 'Verify & Continue', 'advcb' ); ?>');
							// Reset CAPTCHA
							if (provider === 'recaptcha_v2' && typeof grecaptcha !== 'undefined') {
								grecaptcha.reset();
							} else if (provider === 'hcaptcha' && typeof hcaptcha !== 'undefined') {
								hcaptcha.reset();
							} else if (provider === 'turnstile' && typeof turnstile !== 'undefined') {
								turnstile.reset();
							}
						}
					},
					error: function() {
						$error.text('<?php esc_html_e( 'Network error. Please try again.', 'advcb' ); ?>').show();
						$btn.prop('disabled', false).find('span').html('<?php esc_html_e( 'Verify & Continue', 'advcb' ); ?>');
					}
				});
			}
		});
		</script>
	</body>
	</html>
	<?php
	exit;
}


/**
 * ANALYTICS DASHBOARD
 */

/**
 * AJAX handler for getting dashboard stats.
 */
function advcb_get_dashboard_stats() {
	check_ajax_referer( 'advcb_analytics_nonce', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => 'Permission denied' ) );
	}

	global $wpdb;
	$table_name = $wpdb->prefix . 'advcb_block_logs';
	$range = isset( $_POST['range'] ) ? sanitize_text_field( wp_unslash( $_POST['range'] ) ) : '7';

	// Calculate date range based on selection.
	$intervals = array(
		'1'   => DAY_IN_SECONDS,
		'7'   => 7 * DAY_IN_SECONDS,
		'30'  => 30 * DAY_IN_SECONDS,
		'90'  => 90 * DAY_IN_SECONDS,
		'365' => 365 * DAY_IN_SECONDS,
	);

	$interval = isset( $intervals[ $range ] ) ? $intervals[ $range ] : 7 * DAY_IN_SECONDS;
	$start_date = gmdate( 'Y-m-d H:i:s', time() - $interval );

	// Get total blocks in range.
	$total_blocks = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(*) FROM $table_name WHERE blocked_time >= %s",
			$start_date
		)
	);

	// Get unique IPs blocked.
	$unique_ips = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(DISTINCT ip) FROM $table_name WHERE blocked_time >= %s",
			$start_date
		)
	);

	// Get unique countries.
	$unique_countries = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(DISTINCT country_code) FROM $table_name WHERE blocked_time >= %s",
			$start_date
		)
	);

	// Get blocks per day for the chart.
	$group_by = '1' === $range ? 'HOUR' : 'DAY';
	$date_format = '1' === $range ? '%Y-%m-%d %H:00' : '%Y-%m-%d';

	$time_series = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT DATE_FORMAT(blocked_time, %s) as period, COUNT(*) as count
			FROM $table_name
			WHERE blocked_time >= %s
			GROUP BY period
			ORDER BY period ASC",
			$date_format,
			$start_date
		),
		ARRAY_A
	);

	// Get top countries.
	$top_countries = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT country_code, COUNT(*) as count
			FROM $table_name
			WHERE blocked_time >= %s AND country_code != ''
			GROUP BY country_code
			ORDER BY count DESC
			LIMIT 10",
			$start_date
		),
		ARRAY_A
	);

	// Get block reasons breakdown.
	$block_reasons = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT reason, COUNT(*) as count
			FROM $table_name
			WHERE blocked_time >= %s
			GROUP BY reason
			ORDER BY count DESC",
			$start_date
		),
		ARRAY_A
	);

	// Sanitize block reasons for safe JavaScript output.
	foreach ( $block_reasons as &$reason ) {
		$reason['reason'] = sanitize_text_field( $reason['reason'] );
	}
	unset( $reason );

	// Get top blocked IPs.
	$top_ips = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT ip, country_code, COUNT(*) as count, MAX(blocked_time) as last_seen
			FROM $table_name
			WHERE blocked_time >= %s
			GROUP BY ip, country_code
			ORDER BY count DESC
			LIMIT 20",
			$start_date
		),
		ARRAY_A
	);

	// Get all-time stats.
	$all_time_total = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );
	$all_time_unique = $wpdb->get_var( "SELECT COUNT(DISTINCT ip) FROM $table_name" );

	wp_send_json_success( array(
		'total_blocks'     => (int) $total_blocks,
		'unique_ips'       => (int) $unique_ips,
		'unique_countries' => (int) $unique_countries,
		'time_series'      => $time_series,
		'top_countries'    => $top_countries,
		'block_reasons'    => $block_reasons,
		'top_ips'          => $top_ips,
		'all_time_total'   => (int) $all_time_total,
		'all_time_unique'  => (int) $all_time_unique,
	) );
}
add_action( 'wp_ajax_advcb_get_dashboard_stats', 'advcb_get_dashboard_stats' );

/**
 * Render the analytics dashboard page.
 */
function advcb_analytics_dashboard_page() {
	// Get available countries for flag display.
	$countries = advcb_get_countries();
	?>
	<div class="wrap advcb-analytics-wrap">
		<h1><?php esc_html_e( 'Analytics Dashboard', 'advcb' ); ?></h1>

		<div class="advcb-date-range-selector">
			<button type="button" class="button advcb-range-btn" data-range="1"><?php esc_html_e( '24 Hours', 'advcb' ); ?></button>
			<button type="button" class="button advcb-range-btn active" data-range="7"><?php esc_html_e( '7 Days', 'advcb' ); ?></button>
			<button type="button" class="button advcb-range-btn" data-range="30"><?php esc_html_e( '30 Days', 'advcb' ); ?></button>
			<button type="button" class="button advcb-range-btn" data-range="90"><?php esc_html_e( '90 Days', 'advcb' ); ?></button>
			<button type="button" class="button advcb-range-btn" data-range="365"><?php esc_html_e( '1 Year', 'advcb' ); ?></button>
		</div>

		<div id="advcb-dashboard-content">
			<div class="advcb-loading">
				<span class="spinner is-active"></span>
				<?php esc_html_e( 'Loading analytics...', 'advcb' ); ?>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
	<script>
	jQuery(document).ready(function($) {
		var currentRange = '7';
		var charts = {};

		function loadDashboard() {
			$('#advcb-dashboard-content').html('<div class="advcb-loading"><span class="spinner is-active"></span><?php esc_html_e( 'Loading analytics...', 'advcb' ); ?></div>');

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'advcb_get_dashboard_stats',
					nonce: '<?php echo wp_create_nonce( 'advcb_analytics_nonce' ); ?>',
					range: currentRange
				},
				success: function(response) {
					if (response.success) {
						renderDashboard(response.data);
					} else {
						$('#advcb-dashboard-content').html('<div class="notice notice-error"><p>' + response.data.message + '</p></div>');
					}
				},
				error: function() {
					$('#advcb-dashboard-content').html('<div class="notice notice-error"><p><?php esc_html_e( 'Failed to load analytics data.', 'advcb' ); ?></p></div>');
				}
			});
		}

		function renderDashboard(data) {
			// Destroy existing charts.
			Object.keys(charts).forEach(function(key) {
				if (charts[key]) {
					charts[key].destroy();
				}
			});
			charts = {};

			var html = '';

			// Summary cards.
			html += '<div class="advcb-summary-cards">';
			html += '<div class="advcb-card advcb-card-primary">';
			html += '<div class="advcb-card-icon">🛡️</div>';
			html += '<div class="advcb-card-content">';
			html += '<div class="advcb-card-value">' + formatNumber(data.total_blocks) + '</div>';
			html += '<div class="advcb-card-label"><?php esc_html_e( 'Blocked Attempts', 'advcb' ); ?></div>';
			html += '</div></div>';

			html += '<div class="advcb-card advcb-card-secondary">';
			html += '<div class="advcb-card-icon">🌐</div>';
			html += '<div class="advcb-card-content">';
			html += '<div class="advcb-card-value">' + formatNumber(data.unique_ips) + '</div>';
			html += '<div class="advcb-card-label"><?php esc_html_e( 'Unique IPs', 'advcb' ); ?></div>';
			html += '</div></div>';

			html += '<div class="advcb-card advcb-card-tertiary">';
			html += '<div class="advcb-card-icon">🌍</div>';
			html += '<div class="advcb-card-content">';
			html += '<div class="advcb-card-value">' + data.unique_countries + '</div>';
			html += '<div class="advcb-card-label"><?php esc_html_e( 'Countries', 'advcb' ); ?></div>';
			html += '</div></div>';

			html += '<div class="advcb-card advcb-card-quaternary">';
			html += '<div class="advcb-card-icon">📊</div>';
			html += '<div class="advcb-card-content">';
			html += '<div class="advcb-card-value">' + formatNumber(data.all_time_total) + '</div>';
			html += '<div class="advcb-card-label"><?php esc_html_e( 'All Time Blocks', 'advcb' ); ?></div>';
			html += '</div></div>';
			html += '</div>';

			// Charts row.
			html += '<div class="advcb-charts-row">';

			// Time series chart.
			html += '<div class="advcb-chart-container advcb-chart-large">';
			html += '<h3><?php esc_html_e( 'Blocked Attempts Over Time', 'advcb' ); ?></h3>';
			html += '<canvas id="advcb-time-chart"></canvas>';
			html += '</div>';

			// Top countries pie chart.
			html += '<div class="advcb-chart-container">';
			html += '<h3><?php esc_html_e( 'Top Blocked Countries', 'advcb' ); ?></h3>';
			html += '<canvas id="advcb-countries-chart"></canvas>';
			html += '</div>';
			html += '</div>';

			// Second charts row.
			html += '<div class="advcb-charts-row">';

			// Block reasons chart.
			html += '<div class="advcb-chart-container">';
			html += '<h3><?php esc_html_e( 'Block Reasons', 'advcb' ); ?></h3>';
			html += '<canvas id="advcb-reasons-chart"></canvas>';
			html += '</div>';

			// Top IPs table.
			html += '<div class="advchart-table-container">';
			html += '<h3><?php esc_html_e( 'Top Blocked IPs', 'advcb' ); ?></h3>';
			html += '<table class="wp-list-table widefat fixed striped">';
			html += '<thead><tr><th><?php esc_html_e( 'IP Address', 'advcb' ); ?></th><th><?php esc_html_e( 'Country', 'advcb' ); ?></th><th><?php esc_html_e( 'Blocks', 'advcb' ); ?></th><th><?php esc_html_e( 'Last Seen', 'advcb' ); ?></th></tr></thead>';
			html += '<tbody>';

			if (data.top_ips && data.top_ips.length > 0) {
				data.top_ips.slice(0, 10).forEach(function(row) {
					html += '<tr>';
					html += '<td><code>' + esc_html(row.ip) + '</code></td>';
					html += '<td>' + (row.country_code || '-') + '</td>';
					html += '<td>' + row.count + '</td>';
					html += '<td>' + row.last_seen + '</td>';
					html += '</tr>';
				});
			} else {
				html += '<tr><td colspan="4"><?php esc_html_e( 'No data available', 'advcb' ); ?></td></tr>';
			}

			html += '</tbody></table>';
			html += '</div>';
			html += '</div>';

			$('#advcb-dashboard-content').html(html);

			// Initialize Time Series Chart.
			if (data.time_series && data.time_series.length > 0) {
				var timeCtx = document.getElementById('advcb-time-chart').getContext('2d');
				charts.time = new Chart(timeCtx, {
					type: 'line',
					data: {
						labels: data.time_series.map(function(d) { return d.period; }),
						datasets: [{
							label: '<?php esc_html_e( 'Blocked Attempts', 'advcb' ); ?>',
							data: data.time_series.map(function(d) { return parseInt(d.count); }),
							borderColor: '#2271b1',
							backgroundColor: 'rgba(34, 113, 177, 0.1)',
							fill: true,
							tension: 0.3
						}]
					},
					options: {
						responsive: true,
						maintainAspectRatio: false,
						plugins: {
							legend: { display: false }
						},
						scales: {
							y: { beginAtZero: true }
						}
					}
				});
			}

			// Initialize Countries Chart.
			if (data.top_countries && data.top_countries.length > 0) {
				var countryCtx = document.getElementById('advcb-countries-chart').getContext('2d');
				var countryColors = [
					'#e63946', '#f4a261', '#e9c46a', '#2a9d8f', '#264653',
					 '#457b9d', '#1d3557', '#fb6f92', '#c9ada7', '#a8dadc'
				];

				charts.countries = new Chart(countryCtx, {
					type: 'doughnut',
					data: {
						labels: data.top_countries.map(function(d) {
							var name = d.country_code;
							<?php
							// Inline country names mapping for JS.
							$js_countries = array_slice(advcb_get_countries(), 1, null, true);
							echo 'var countryMap = ' . json_encode( $js_countries ) . ';';
							?>
							if (countryMap[d.country_code]) {
								name = countryMap[d.country_code];
							}
							return name + ' (' + d.count + ')';
						}),
						datasets: [{
							data: data.top_countries.map(function(d) { return parseInt(d.count); }),
							backgroundColor: countryColors
						}]
					},
					options: {
						responsive: true,
						maintainAspectRatio: false,
						plugins: {
							legend: {
								position: 'right',
								labels: { boxWidth: 12 }
							}
						}
					}
				});
			}

			// Initialize Reasons Chart.
			if (data.block_reasons && data.block_reasons.length > 0) {
				var reasonsCtx = document.getElementById('advcb-reasons-chart').getContext('2d');
				var reasonColors = ['#e63946', '#f4a261', '#e9c46a'];

				charts.reasons = new Chart(reasonsCtx, {
					type: 'pie',
					data: {
						labels: data.block_reasons.map(function(d) { return d.reason + ' (' + d.count + ')'; }),
						datasets: [{
							data: data.block_reasons.map(function(d) { return parseInt(d.count); }),
							backgroundColor: reasonColors
						}]
					},
					options: {
						responsive: true,
						maintainAspectRatio: false,
						plugins: {
							legend: {
								position: 'right',
								labels: { boxWidth: 12 }
							}
						}
					}
				});
			}
		}

		function formatNumber(num) {
			if (num >= 1000000) {
				return (num / 1000000).toFixed(1) + 'M';
			}
			if (num >= 1000) {
				return (num / 1000).toFixed(1) + 'K';
			}
			return num.toString();
		}

		function esc_html(str) {
			return $('<div/>').text(str).html();
		}

		// Range selector click handler.
		$('.advcb-range-btn').on('click', function() {
			$('.advcb-range-btn').removeClass('active');
			$(this).addClass('active');
			currentRange = $(this).data('range');
			loadDashboard();
		});

		// Initial load.
		loadDashboard();
	});
	</script>

	<style>
		.advcb-analytics-wrap {
			max-width: 1400px;
		}
		.advcb-date-range-selector {
			margin: 20px 0;
			padding: 15px;
			background: #fff;
			border: 1px solid #c3c4c7;
			border-radius: 4px;
		}
		.advcb-date-range-selector .button {
			margin-right: 5px;
		}
		.advcb-date-range-selector .button.active {
			background: #2271b1;
			color: #fff;
			border-color: #2271b1;
		}
		.advcb-loading {
			text-align: center;
			padding: 40px;
			color: #646970;
		}
		.advcb-loading .spinner {
			float: none;
			margin: 0 auto 10px;
		}
		.advcb-summary-cards {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
			gap: 15px;
			margin-bottom: 20px;
		}
		.advcb-card {
			background: #fff;
			border: 1px solid #c3c4c7;
			border-radius: 4px;
			padding: 20px;
			display: flex;
			align-items: center;
			transition: box-shadow 0.2s;
		}
		.advcb-card:hover {
			box-shadow: 0 2px 8px rgba(0,0,0,0.1);
		}
		.advcb-card-icon {
			font-size: 2.5em;
			margin-right: 15px;
			opacity: 0.8;
		}
		.advcb-card-content {
			flex: 1;
		}
		.advcb-card-value {
			font-size: 2em;
			font-weight: 600;
			color: #1d2327;
			line-height: 1.2;
		}
		.advcb-card-label {
			color: #646970;
			font-size: 0.9em;
			margin-top: 5px;
		}
		.advcb-card-primary { border-left: 4px solid #2271b1; }
		.advcb-card-secondary { border-left: 4px solid #d63638; }
		.advcb-card-tertiary { border-left: 4px solid #56a53d; }
		.advcb-card-quaternary { border-left: 4px solid #9b59b6; }

		.advcb-charts-row {
			display: grid;
			grid-template-columns: 2fr 1fr;
			gap: 15px;
			margin-bottom: 15px;
		}
		.advcb-chart-container {
			background: #fff;
			border: 1px solid #c3c4c7;
			border-radius: 4px;
			padding: 20px;
		}
		.advcb-chart-large {
			grid-column: 1 / -1;
		}
		.advcb-chart-container h3 {
			margin-top: 0;
			padding-bottom: 10px;
			border-bottom: 1px solid #eee;
		}
		.advcb-chart-container canvas {
			max-height: 300px;
		}
		.advchart-table-container {
			background: #fff;
			border: 1px solid #c3c4c7;
			border-radius: 4px;
			padding: 20px;
		}
		.advchart-table-container h3 {
			margin-top: 0;
			padding-bottom: 10px;
			border-bottom: 1px solid #eee;
		}
		.advchart-table-container table {
			margin-top: 10px;
		}
		.advchart-table-container code {
			background: #f0f0f1;
			padding: 2px 6px;
			border-radius: 3px;
		}

		@media (max-width: 1200px) {
			.advcb-charts-row {
				grid-template-columns: 1fr;
			}
		}
	</style>
	<?php
}


/**
 * ADMIN HELPERS FOR MANAGING THE GEOIP DATABASE
 */
function advcb_geoip_notice_key() {
        $user_id = get_current_user_id();

        return 'advcb_geoip_notice_' . ( $user_id ? $user_id : '0' );
}

function advcb_add_geoip_notice( $message, $type = 'success' ) {
        $allowed_types = array( 'success', 'error', 'warning', 'info' );

        if ( ! in_array( $type, $allowed_types, true ) ) {
                $type = 'success';
        }

        set_transient(
                advcb_geoip_notice_key(),
                array(
                        'type'    => $type,
                        'message' => $message,
                ),
                MINUTE_IN_SECONDS
        );
}

function advcb_geoip_admin_notices() {
        if ( ! current_user_can( 'manage_options' ) ) {
                return;
        }

        $notice = get_transient( advcb_geoip_notice_key() );

        if ( false === $notice ) {
                return;
        }

        $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

        if ( $screen && ! in_array( $screen->id, array( 'toplevel_page_advcb_settings', 'country-blocker_page_advcb_block_logs', 'country-blocker_page_advcb_analytics' ), true ) ) {
                return;
        }

        delete_transient( advcb_geoip_notice_key() );

        $type  = isset( $notice['type'] ) ? $notice['type'] : 'success';
        $class = 'notice notice-' . sanitize_html_class( $type );

        echo '<div class="' . esc_attr( $class ) . '"><p>' . esc_html( $notice['message'] ) . '</p></div>';
}
add_action( 'admin_notices', 'advcb_geoip_admin_notices' );

function advcb_geoip_redirect_to_settings() {
        $redirect = admin_url( 'admin.php?page=advcb_settings' );
        wp_safe_redirect( $redirect );
        exit;
}

function advcb_save_geoip_database_content( $content, $filename = 'GeoLite2-Country.mmdb' ) {
        if ( ! function_exists( 'wp_mkdir_p' ) ) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        $storage_dir = advcb_get_geoip_storage_dir();

        if ( ! $storage_dir ) {
                return new WP_Error( 'advcb_geoip_storage', __( 'Unable to determine the GeoIP storage directory.', 'advcb' ) );
        }

        if ( ! wp_mkdir_p( $storage_dir ) ) {
                return new WP_Error( 'advcb_geoip_storage', __( 'Unable to create the GeoIP storage directory.', 'advcb' ) );
        }

        $filename = sanitize_file_name( $filename );

        if ( '' === $filename ) {
                $filename = 'GeoLite2-Country.mmdb';
        }

        if ( 'mmdb' !== strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) ) ) {
                $filename .= '.mmdb';
        }

        $destination = trailingslashit( $storage_dir ) . $filename;
        $written     = file_put_contents( $destination, $content );

        if ( false === $written ) {
                return new WP_Error( 'advcb_geoip_write', __( 'Failed to save the GeoIP database file.', 'advcb' ) );
        }

        @chmod( $destination, 0644 );

        update_option( 'advcb_geoip_db_path', $filename );

        return array(
                'path'     => $destination,
                'filename' => $filename,
                'bytes'    => $written,
        );
}

function advcb_handle_geoip_download() {
        if ( ! current_user_can( 'manage_options' ) ) {
                wp_die( esc_html__( 'You do not have permission to perform this action.', 'advcb' ) );
        }

        check_admin_referer( 'advcb_geoip_download' );

        $url = isset( $_POST['advcb_geoip_url'] ) ? wp_unslash( $_POST['advcb_geoip_url'] ) : '';
        $url = esc_url_raw( trim( $url ) );

        if ( empty( $url ) ) {
                advcb_add_geoip_notice( __( 'Please provide a valid download URL.', 'advcb' ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        // Validate URL and prevent SSRF attacks - only allow HTTPS from trusted domains.
        $parsed_url = wp_parse_url( $url );

        if ( ! $parsed_url || empty( $parsed_url['host'] ) ) {
                advcb_add_geoip_notice( __( 'Invalid URL provided.', 'advcb' ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        // Only allow HTTPS URLs (prevent internal network access).
        if ( isset( $parsed_url['scheme'] ) && 'https' !== $parsed_url['scheme'] ) {
                advcb_add_geoip_notice( __( 'Only HTTPS URLs are allowed for security reasons.', 'advcb' ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        // Block private/reserved IP addresses in the host (prevent SSRF).
        $host = strtolower( $parsed_url['host'] );
        if ( preg_match( '/^(127\.|10\.|172\.(1[6-9]|2[0-9]|3[01])\.|192\.168\.|localhost|::1|fe80::|fc00::)/i', $host ) ) {
                advcb_add_geoip_notice( __( 'Access to internal/private network addresses is not allowed.', 'advcb' ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        // Additional allowed domains filter.
        $allowed_domains = apply_filters( 'advcb_allowed_geoip_download_domains', array(
                'github.com',
                'githubusercontent.com',
                'git.io',
                'p3terx.com',
        ) );

        $host_domain = $host;
        // Remove 'www.' prefix for comparison.
        $host_domain = preg_replace( '/^www\./', '', $host_domain );

        $is_allowed_domain = false;
        foreach ( $allowed_domains as $allowed_domain ) {
                if ( $host_domain === $allowed_domain || str_ends_with( $host_domain, '.' . $allowed_domain ) ) {
                        $is_allowed_domain = true;
                        break;
                }
        }

        if ( ! $is_allowed_domain ) {
                advcb_add_geoip_notice( __( 'Downloads are only allowed from trusted GeoIP database sources.', 'advcb' ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        $response = wp_safe_remote_get(
                $url,
                array(
                        'timeout' => 60,
                        'stream'  => false,
                )
        );

        if ( is_wp_error( $response ) ) {
                advcb_add_geoip_notice( sprintf( __( 'Download failed: %s', 'advcb' ), $response->get_error_message() ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        $status_code = (int) wp_remote_retrieve_response_code( $response );

        if ( 200 !== $status_code ) {
                advcb_add_geoip_notice( sprintf( __( 'Download failed with status code %d.', 'advcb' ), $status_code ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        $body = wp_remote_retrieve_body( $response );

        // Enforce maximum file size limit (100MB) to prevent DoS.
        $max_file_size = apply_filters( 'advcb_max_geoip_file_size', 100 * MB_IN_BYTES );

        if ( strlen( $body ) > $max_file_size ) {
                advcb_add_geoip_notice( sprintf( __( 'The downloaded file is too large. Maximum size is %s.', 'advcb' ), size_format( $max_file_size ) ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        if ( empty( $body ) ) {
                advcb_add_geoip_notice( __( 'The downloaded file appears to be empty.', 'advcb' ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        // Validate MaxMind database header (must start with the MaxMind database signature).
        if ( strlen( $body ) < 2 || substr( $body, 0, 2 ) !== "\x00\x00" ) {
                // Try to detect if this is a gzip file.
                if ( strlen( $body ) < 2 || substr( $body, 0, 2 ) !== "\x1f\x8b" ) {
                        advcb_add_geoip_notice( __( 'The downloaded file does not appear to be a valid MaxMind database file.', 'advcb' ), 'error' );
                        advcb_geoip_redirect_to_settings();
                }
        }

        $filename = isset( $parsed_url['path'] ) ? basename( $parsed_url['path'] ) : 'GeoLite2-Country.mmdb';
        // Sanitize filename to prevent any path traversal or malicious filenames.
        $filename = sanitize_file_name( $filename );
        if ( empty( $filename ) || '.mmdb' !== strtolower( substr( $filename, -5 ) ) ) {
                $filename = 'GeoLite2-Country.mmdb';
        }

        $result = advcb_save_geoip_database_content( $body, $filename );

        if ( is_wp_error( $result ) ) {
                advcb_add_geoip_notice( $result->get_error_message(), 'error' );
        } else {
                advcb_add_geoip_notice( sprintf( __( 'GeoIP database saved as %s.', 'advcb' ), $result['filename'] ), 'success' );
        }

        advcb_geoip_redirect_to_settings();
}
add_action( 'admin_post_advcb_geoip_download', 'advcb_handle_geoip_download' );

function advcb_handle_geoip_upload() {
        if ( ! current_user_can( 'manage_options' ) ) {
                wp_die( esc_html__( 'You do not have permission to perform this action.', 'advcb' ) );
        }

        check_admin_referer( 'advcb_geoip_upload' );

        if ( empty( $_FILES['advcb_geoip_file'] ) || ! is_array( $_FILES['advcb_geoip_file'] ) ) {
                advcb_add_geoip_notice( __( 'No file was uploaded.', 'advcb' ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        $file = $_FILES['advcb_geoip_file'];

        if ( ! empty( $file['error'] ) && UPLOAD_ERR_OK !== (int) $file['error'] ) {
                advcb_add_geoip_notice( __( 'There was an error uploading the file.', 'advcb' ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        $filename = isset( $file['name'] ) ? sanitize_file_name( wp_unslash( $file['name'] ) ) : '';

        if ( '' === $filename ) {
                $filename = 'GeoLite2-Country.mmdb';
        }

        if ( ! function_exists( 'wp_check_filetype_and_ext' ) ) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        $file_type = wp_check_filetype_and_ext( $file['tmp_name'], $filename, array( 'mmdb' => 'application/octet-stream' ) );

        if ( isset( $file_type['ext'] ) && 'mmdb' !== $file_type['ext'] ) {
                advcb_add_geoip_notice( __( 'Please upload a valid .mmdb database file.', 'advcb' ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        if ( ! is_uploaded_file( $file['tmp_name'] ) ) {
                advcb_add_geoip_notice( __( 'The uploaded file could not be validated.', 'advcb' ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        // Enforce maximum file size limit (100MB) to prevent DoS.
        $max_file_size = apply_filters( 'advcb_max_geoip_file_size', 100 * MB_IN_BYTES );
        $file_size = filesize( $file['tmp_name'] );
        if ( false === $file_size || $file_size > $max_file_size ) {
                @unlink( $file['tmp_name'] );
                advcb_add_geoip_notice( sprintf( __( 'The uploaded file is too large. Maximum size is %s.', 'advcb' ), size_format( $max_file_size ) ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        $storage_dir = advcb_get_geoip_storage_dir();

        if ( ! $storage_dir ) {
                advcb_add_geoip_notice( __( 'Unable to determine the GeoIP storage directory.', 'advcb' ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        if ( ! function_exists( 'wp_mkdir_p' ) ) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        if ( ! wp_mkdir_p( $storage_dir ) ) {
                advcb_add_geoip_notice( __( 'Unable to create the GeoIP storage directory.', 'advcb' ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        if ( 'mmdb' !== strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) ) ) {
                $filename .= '.mmdb';
        }

        $destination = trailingslashit( $storage_dir ) . $filename;

        // Read file content to validate it's a valid MaxMind database.
        $file_content = file_get_contents( $file['tmp_name'] );
        if ( false === $file_content ) {
                @unlink( $file['tmp_name'] );
                advcb_add_geoip_notice( __( 'Failed to read the uploaded file.', 'advcb' ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        // Validate MaxMind database header (must start with the MaxMind database signature).
        if ( strlen( $file_content ) < 4 || substr( $file_content, 0, 4 ) !== "\x00\x00\x00\x00" ) {
                // Try to detect if this is a gzip file.
                if ( strlen( $file_content ) < 2 || substr( $file_content, 0, 2 ) !== "\x1f\x8b" ) {
                        @unlink( $file['tmp_name'] );
                        advcb_add_geoip_notice( __( 'The uploaded file does not appear to be a valid MaxMind database file.', 'advcb' ), 'error' );
                        advcb_geoip_redirect_to_settings();
                }
        }

        // Use move_uploaded_file only - do not fall back to copy() for security reasons.
        if ( ! move_uploaded_file( $file['tmp_name'], $destination ) ) {
                advcb_add_geoip_notice( __( 'Failed to move the uploaded file.', 'advcb' ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        // Set secure file permissions.
        @chmod( $destination, 0644 );

        update_option( 'advcb_geoip_db_path', $filename );

        advcb_add_geoip_notice( sprintf( __( 'GeoIP database uploaded as %s.', 'advcb' ), $filename ), 'success' );

        advcb_geoip_redirect_to_settings();
}
add_action( 'admin_post_advcb_geoip_upload', 'advcb_handle_geoip_upload' );


/**
 * SANITIZE INPUTS
 */
function advcb_sanitize_allowed_countries( $input ) {
	$countries = is_array( $input ) ? $input : explode( ',', $input );
	return array_map( 'sanitize_text_field', array_map( 'trim', $countries ) );
}

function advcb_sanitize_blacklisted_ips( $input ) {
        $ips = is_array( $input ) ? $input : explode( ',', $input );
        return array_map( 'sanitize_text_field', array_map( 'trim', $ips ) );
}

function advcb_sanitize_boolean( $input ) {
        return (bool) $input;
}

function advcb_sanitize_mode( $input ) {
        return ( $input === 'block' ) ? 'block' : 'allow';
}

function advcb_sanitize_textarea( $input ) {
        return wp_kses_post( $input );
}

function advcb_sanitize_http_status( $input ) {
        $allowed = array( 403, 410, 451 );
        $input   = (int) $input;
        return in_array( $input, $allowed, true ) ? $input : 403;
}

function advcb_sanitize_url( $input ) {
        return esc_url_raw( trim( $input ) );
}

function advcb_sanitize_redirect_status( $input ) {
        $allowed = array( 301, 302, 307, 308 );
        $input   = (int) $input;
        return in_array( $input, $allowed, true ) ? $input : 302;
}

function advcb_sanitize_positive_int( $input ) {
        return absint( $input );
}

function advcb_sanitize_file_path( $input ) {
        if ( is_array( $input ) ) {
                return '';
        }

        $input = is_string( $input ) ? wp_unslash( $input ) : '';
        $input = trim( $input );

        if ( '' === $input ) {
                return '';
        }

        $normalized = wp_normalize_path( $input );

        if ( function_exists( 'path_is_absolute' ) && path_is_absolute( $normalized ) ) {
                return sanitize_text_field( $normalized );
        }

        if ( preg_match( '#^[a-zA-Z]:/#', $normalized ) ) {
                return sanitize_text_field( $normalized );
        }

        return sanitize_file_name( wp_basename( $normalized ) );
}

function advcb_sanitize_geoip_source( $input ) {
        if ( ! is_string( $input ) ) {
                return 'api';
        }

        $input = strtolower( trim( $input ) );
        $allowed = array( 'api', 'database' );

        return in_array( $input, $allowed, true ) ? $input : 'api';
}

function advcb_sanitize_captcha_provider( $input ) {
        if ( ! is_string( $input ) ) {
                return 'recaptcha_v2';
        }

        $input = strtolower( trim( $input ) );
        $allowed = array( 'recaptcha_v2', 'recaptcha_v3', 'hcaptcha', 'turnstile' );

        return in_array( $input, $allowed, true ) ? $input : 'recaptcha_v2';
}

function advcb_sanitize_captcha_threshold( $input ) {
        $threshold = floatval( $input );
        if ( $threshold < 0 ) {
                return 0.1;
        }
        if ( $threshold > 1 ) {
                return 1.0;
        }
        return round( $threshold, 2 );
}

/**
 * Sanitize CAPTCHA secret key - keep existing value if masked placeholder is sent.
 */
function advcb_sanitize_captcha_secret_key( $input ) {
        // If the input is the masked placeholder, keep the existing value.
        if ( '••••••••••••••••' === $input || strspn( $input, '••••••••••' ) === strlen( $input ) ) {
                return get_option( 'advcb_captcha_secret_key', '' );
        }
        return sanitize_text_field( $input );
}

function advcb_replace_placeholders( $message, $context = array() ) {
        $placeholders = array(
                '{ip}'           => isset( $context['ip'] ) ? $context['ip'] : '',
                '{country_code}' => isset( $context['country_code'] ) ? $context['country_code'] : '',
                '{reason}'       => isset( $context['reason'] ) ? $context['reason'] : '',
        );

        foreach ( $placeholders as $placeholder => $value ) {
                $message = str_replace( $placeholder, esc_html( $value ), $message );
        }

        return $message;
}
?>

<?php

/*
 * Plugin Name: WP Contact Form 7 Log
 * Plugin URI: https://github.com/miconda/wp-fail2ban-addon-cf7log
 * Description: Contact Form 7 - log form submission actions.
 * Version: 1.0.0
 * Author: Daniel-Constantin Mierla
 * Author URI: https://github.com/miconda
 * License: GPLv2
 * Requires PHP: 5.3
 */
/*
 *  Copyright 2020 Daniel-Constantin Mierla  (email : miconda@gmail.org)
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License, version 2, as
 *  published by the Free Software Foundation.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program; if not, write to the Free Software
 *	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

function wpcf7log_filter_spam( $spam )
{
	openlog('wpcf7log', LOG_PID, LOG_DAEMON);
	syslog(LOG_NOTICE, "contact form 7 submission from {$_SERVER['REMOTE_ADDR']}");
	closelog();

    return $spam;
}

add_filter( 'wpcf7_spam', wpcf7log_filter_spam, 100, 1);


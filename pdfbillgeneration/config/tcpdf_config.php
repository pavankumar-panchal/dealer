<?php
//============================================================+
// File name   : tcpdf_config.php
// Begin       : 2004-06-11
// Last Update : 2014-12-11
//
// Description : Configuration file for TCPDF.
// Author      : Nicola Asuni - Tecnick.com LTD - www.tecnick.com - info@tecnick.com
// License     : GNU-LGPL v3 (http://www.gnu.org/copyleft/lesser.html)
// -------------------------------------------------------------------
// Copyright (C) 2004-2014  Nicola Asuni - Tecnick.com LTD
//
// This file is part of TCPDF software library.
//
// TCPDF is free software: you can redistribute it and/or modify it
// under the terms of the GNU Lesser General Public License as
// published by the Free Software Foundation, either version 3 of the
// License, or (at your option) any later version.
//
// TCPDF is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// See the GNU Lesser General Public License for more details.
//
// You should have received a copy of the GNU Lesser General Public License
// along with TCPDF.  If not, see <http://www.gnu.org/licenses/>.
//
// See LICENSE.TXT file for more information.
//============================================================+

/**
 * Configuration file for TCPDF.
 * @author Nicola Asuni
 * @package com.tecnick.tcpdf
 * @version 4.9.005
 * @since 2004-10-27
 */

// IMPORTANT:
// If you define the constant K_TCPDF_EXTERNAL_CONFIG, all the following settings will be ignored.
// If you use the tcpdf_autoconfig.php, then you can overwrite some values here.


/**
 * Installation path (/var/www/tcpdf/).
 * By default it is automatically calculated but you can also set it as a fixed string to improve performances.
 */
//define ('K_PATH_MAIN', '');

/**
 * URL path to tcpdf installation folder (http://localhost/tcpdf/).
 * By default it is automatically set but you can also set it as a fixed string to improve performances.
 */
//define ('K_PATH_URL', '');

/**
 * Path for PDF fonts.
 * By default it is automatically set but you can also set it as a fixed string to improve performances.
 */
//define ('K_PATH_FONTS', K_PATH_MAIN.'fonts/');

/**
 * Default images directory.
 * By default it is automatically set but you can also set it as a fixed string to improve performances.
 */
//define ('K_PATH_IMAGES', '');

/**
 * Deafult image logo used be the default Header() method.
 * Please set here your own logo or an empty string to disable it.
 */
//define ('PDF_HEADER_LOGO', '');

/**
 * Header logo image width in user units.
 */
//define ('PDF_HEADER_LOGO_WIDTH', 0);

/**
 * Cache directory for temporary files (full path).
 */
//define ('K_PATH_CACHE', '/tmp/');

/**
 * Generic name for a blank image.
 */
if (!defined('K_TCPDF_EXTERNAL_CONFIG')) {
	
	// DOCUMENT_ROOT fix for IIS Webserver
	if ((!isset($_SERVER['DOCUMENT_ROOT'])) OR (empty($_SERVER['DOCUMENT_ROOT']))) {
		if(isset($_SERVER['SCRIPT_FILENAME'])) {
			$_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0-strlen($_SERVER['PHP_SELF'])));
		} elseif(isset($_SERVER['PATH_TRANSLATED'])) {
			$_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0-strlen($_SERVER['PHP_SELF'])));
		}	else {
			// define here your DOCUMENT_ROOT path if the previous fails
			$_SERVER['DOCUMENT_ROOT'] = '/var/www';
		}
	}
	
	// Automatic calculation for the following K_PATH_MAIN constant
	$k_path_main = str_replace( '\\', '/', realpath(substr(dirname(__FILE__), 0, 0-strlen('config'))));
	if (substr($k_path_main, -1) != '/') {
		$k_path_main .= '/';
	}
	
	/**
	 * Installation path (/var/www/tcpdf/).
	 * By default it is automatically calculated but you can also set it as a fixed string to improve performances.
	 */
	define ('K_PATH_MAIN', $k_path_main);
	
	// Automatic calculation for the following K_PATH_URL constant
	if (isset($_SERVER['HTTP_HOST']) AND (!empty($_SERVER['HTTP_HOST']))) {
		if(isset($_SERVER['HTTPS']) AND (!empty($_SERVER['HTTPS'])) AND strtolower($_SERVER['HTTPS'])!='off') {
			$k_path_url = 'https://';
		} else {
			$k_path_url = 'http://';
		}
		$k_path_url .= $_SERVER['HTTP_HOST'];
		$k_path_url .= str_replace( '\\', '/', substr($_SERVER['PHP_SELF'], 0, -24));
	}
	
	/**
	 * URL path to tcpdf installation folder (http://localhost/tcpdf/).
	 * By default it is automatically calculated but you can also set it as a fixed string to improve performances.
	 */
	define ('K_PATH_URL', $k_path_url);
	
	/**
	 * path for PDF fonts
	 * use K_PATH_MAIN.'fonts/old/' for old non-UTF8 fonts
	 */
	define ('K_PATH_FONTS', K_PATH_MAIN.'fonts/');
	
	/**
	 * cache directory for temporary files (full path)
	 */
	define ('K_PATH_CACHE', K_PATH_MAIN.'cache/');
	
	/**
	 * cache directory for temporary files (url path)
	 */
	define ('K_PATH_URL_CACHE', K_PATH_URL.'cache/');
	
	/**
	 *images directory
	 */
	define ('K_PATH_IMAGES', K_PATH_MAIN.'images/');
	
	/**
	 * blank image
	 */
	define ('K_BLANK_IMAGE', K_PATH_IMAGES.'_blank.png');
	
	/**
	 * page format
	 */
	define ('PDF_PAGE_FORMAT', 'A4');
	
	/**
	 * page orientation (P=portrait, L=landscape)
	 */
	define ('PDF_PAGE_ORIENTATION', 'P');
	
	/**
	 * document creator
	 */
	define ('PDF_CREATOR', 'TCPDF');
	
	/**
	 * document author
	 */
	define ('PDF_AUTHOR', '');
	
	/**
	 * header title
	 */
	define ('PDF_HEADER_TITLE', 'RELYON SOFTECH LTD');
	
	/**
	 * header description string
	 */
	define ('PDF_HEADER_STRING', "#73, Shreelekha Complex, WOC Road
Bangalore, Karnataka, India. Pin: 560086");
	
	/**
	 * image logo
	 */
	define ('PDF_HEADER_LOGO','relyon-logo.jpg');
	
	/**
	 * header logo image width [mm]
	 */
	define ('PDF_HEADER_LOGO_WIDTH', 30);
	
	/**
	 *  document unit of measure [pt=point, mm=millimeter, cm=centimeter, in=inch]
	 */
	define ('PDF_UNIT', 'mm');
	
	/**
	 * header margin
	 */
	define ('PDF_MARGIN_HEADER', 0);
	
	/**
	 * footer margin
	 */
	define ('PDF_MARGIN_FOOTER', 10);
	
	/**
	 * top margin
	 */
	define ('PDF_MARGIN_TOP', 5);
	
	/**
	 * bottom margin
	 */
	define ('PDF_MARGIN_BOTTOM', 5);
	
	/**
	 * left margin
	 */
	define ('PDF_MARGIN_LEFT', 10);
	
	/**
	 * right margin
	 */
	define ('PDF_MARGIN_RIGHT', 10);
	
	/**
	 * default main font name
	 */
	define ('PDF_FONT_NAME_MAIN', 'helvetica');
	
	/**
	 * default main font size
	 */
	define ('PDF_FONT_SIZE_MAIN', 10);
	
	/**
	 * default data font name
	 */
	define ('PDF_FONT_NAME_DATA', 'helvetica');
	
	/**
	 * default data font size
	 */
	define ('PDF_FONT_SIZE_DATA', 8);
	
	/**
	 * default monospaced font name
	 */
	define ('PDF_FONT_MONOSPACED', 'courier');
	
	/**
	 * ratio used to adjust the conversion of pixels to user units
	 */
	define ('PDF_IMAGE_SCALE_RATIO', 1);
	
	/**
	 * magnification factor for titles
	 */
	define('HEAD_MAGNIFICATION', 1.1);
	
	/**
	 * height of cell repect font height
	 */
	define('K_CELL_HEIGHT_RATIO', 1.25);
	
	/**
	 * title magnification respect main font size
	 */
	define('K_TITLE_MAGNIFICATION', 1.3);
	
	/**
	 * reduction factor for small font
	 */
	define('K_SMALL_RATIO', 2/3);
}

//============================================================+
// END OF FILE                                                 
//============================================================+
?>
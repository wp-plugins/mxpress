<?php

/*
  Plugin Name: mxPress
  Plugin URI: http://www.mxpress.co.za
  Description: mxPress automatically transforms your WordPress website into a Mxit App, visible on Mxit without affecting your normal theme or urls. Find settings under <b>Apearance &raquo; <a href="themes.php?page=mxpress_plugin">Mxit Options</a></b>. Requires a Mxit Service URL (register one at <a href="http://code.mxit.com" target="_new">code.mxit.com</a>)
  Author: Kazazoom - Andre Clements & Eric Clements
  Version: 1.0.3
  Author URI: http://www.kazazoom.com
 */

require_once dirname(__FILE__) . '/config.php';

include_once dirname(__FILE__) . '/admin.php';

function mxpress_render() {
    include_once dirname(__FILE__) . '/mxpress_index.php';
}

function mxpress_get_version() {
    $plugin_data = get_plugin_data(__FILE__);
    
    $plugin_version = $plugin_data['Version'];
    return $plugin_version;
}

/*
 * todo:
 * *fix placement of titles via conditional options
 * *image uploader - affected via native media library as of ver 0.0.16
 * *GAtrack external via redirect
 * *refactor
 * 
 * coming soon:
 * *make content pagination markup sensitive
 * *more template tags, eg. list categories, tags under posts]
 * *comment via message
 * *plugin creates editable theme template
 * *internal db tracking/log
 * 
 * recent changes:
 * 2012/11/05
 * version 0.0.11
 * comments listing and submit functionality
 * fixed bugs with titles, category and content rendering logic
 * improved logic for Menu | Back link in footer
 * some refactoring code clean-up and more...
 * 
 * 2012/10
 * version 0.0.10
 * * Changed default options 
 * * Inserted reset defaults for options
 * * Admin section descriptions somewhat refined
 * 
 * 2012/07/13
 * *plugin list details partially updated
 * *disable certain setting fields with coming soon notice
 * *rename plugin, version update to 0.0.9
 * *load defaults at plugin activation, if no settings found
 * *fix bullets
 * *Google analytics
 */
?>
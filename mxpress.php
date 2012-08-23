<?php

/**
 * @package Kaza_Mxit
 * @version 0.1
 */
/*
  Plugin Name: mxPress
  Plugin URI: http://www.mxpress.co.za
  Description: mxPress automatically transforms your WordPress website into a Mxit App, visible on Mxit without affecting your normal theme or urls. Find settings under <b>Apearance &raquo; <a href="themes.php?page=kzmx_plugin">Mxit Options</a></b>. Requires a Mxit Service URL (register one at <a href="http://code.mxit.com" target="_new">code.mxit.com</a>)
  Author: Kazazoom - Andre Clements & Eric Clements
  Version: 0.0.9
  Author URI: http://kazazoom.com
 */ 

require_once dirname(__FILE__) . '/config.php';

add_action('admin_menu', 'kzmx_add_admin_page'); // see admin.php
register_activation_hook( __FILE__, 'kzmx_activate' );
add_action('wp', 'kzmx_render'); // does the translation

function kzmx_render() {
    include_once dirname(__FILE__) . '/index.php';
}

/*  
 * todo:
 * *fix placement of titles via conditional options
 * *image uploader
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
 * 2012/07/13
 * *plugin list details partially updated
 * *disable certain setting fields with coming soon notice
 * *rename plugin, version update to 0.0.9
 * *load defaults at plugin activation, if no settings found
 * *fix bullets
 * *Google analytics
 */

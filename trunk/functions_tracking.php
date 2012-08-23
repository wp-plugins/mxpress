<?php

function kzmx_googleAnalyticsGetImageUrl() {
    global $mx_options;
    if (($mx_options['doGA']) && ($mx_options['setGA_ACCOUNT'])) {
        $GA_ACCOUNT = $mx_options['setGA_ACCOUNT'];
        $GA_PIXEL = plugins_url() . '/mxpress/lib/ga.php';

        $url = "";
        $url .= $GA_PIXEL . "?";
        $url .= "utmac=" . $GA_ACCOUNT;
        $url .= "&utmn=" . rand(0, 0x7fffffff);
        $referer = $_SERVER["HTTP_REFERER"];
        $query = $_SERVER["QUERY_STRING"];
        $path = $_SERVER["REQUEST_URI"];
        if (empty($referer)) {
            $referer = "-";
        }
        $url .= "&utmr=" . urlencode($referer);
        if (!empty($path)) {
            $url .= "&utmp=" . urlencode($path);
        }
        $url .= "&guid=ON";
        return str_replace("&", "&amp;", $url);
    } else {
        return false;
    }
}

function kzmx_GA_trackbypx() {
    global $mx_options;
    if ($mx_options['doGA']) {
        $googleAnalyticsImageUrl = kzmx_googleAnalyticsGetImageUrl();
        echo '<img src="' . $googleAnalyticsImageUrl . '" />';
    } 
    return;
}

?>

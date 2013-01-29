<?php

function mxpress_googleAnalyticsGetImageUrl() {
    global $mxpress_options;
    if (($mxpress_options['doGA']) && ($mxpress_options['setGA_ACCOUNT'])) {
        $GA_ACCOUNT = $mxpress_options['setGA_ACCOUNT'];
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

function mxpress_GA_trackbypx() {
    global $mxpress_options;
    if ($mxpress_options['doGA']) {
        $googleAnalyticsImageUrl = mxpress_googleAnalyticsGetImageUrl();
        echo '<img src="' . $googleAnalyticsImageUrl . '" />';
    }
    return;
}

function track_user() {
// to do: integrate
    /*
      $isPersistUser=true;
      $isUpdateUser=true;

      $mDB = 0;//the wordpress database?;
      $DB_NAME = 0;//the wordpress database name?;

      if (!$mDB) {
      die('Could not connect: ' . mysql_error());
      }
     */
    mysql_select_db($DB_NAME); //Might not be necessary if we use wordpress DB connection variable.

    if ($isUpdateUser || $isPersistUser) {

        extract($_SERVER);

        // Log Mxit user rows in DB 
        if ($HTTP_X_MXIT_USERID_R) {

            // <editor-fold defaultstate="collapsed" desc="Prep Mxit headers">
            $HTTP_X_MXIT_LOCATION_ar = explode(',', $HTTP_X_MXIT_LOCATION);
            $HTTP_X_MXIT_PROFILE_ar = explode(',', $HTTP_X_MXIT_PROFILE);
            $HTTP_UA_PIXELS_ar = explode('x', $HTTP_UA_PIXELS);

            //gender
            if ($HTTP_X_MXIT_PROFILE_ar[3] == 'Male')
                $HTTP_X_MXIT_PROFILE_ar[3] = 1;
            if ($HTTP_X_MXIT_PROFILE_ar[3] == 'Female')
                $HTTP_X_MXIT_PROFILE_ar[3] = 0;

            // limits
            $CLEAN_NICK = urldecode($HTTP_X_MXIT_NICK);
            $HTTP_X_MXIT_NICK = substr($CLEAN_NICK, 0, 64);
            $HTTP_X_MXIT_PROFILE_ar[0] = substr($HTTP_X_MXIT_PROFILE_ar[0], 0, 7);
            $HTTP_X_DEVICE_USER_AGENT = substr($HTTP_X_DEVICE_USER_AGENT, 0, 300);
            // </editor-fold>

            $query = "SELECT UserOID_Usr FROM wp_users_mxit_info WHERE MxitUserID_Usr = '$HTTP_X_MXIT_USERID_R' LIMIT 0,1;";
            $result = mysql_query($query, $mDB);

            if ((mysql_num_rows($result) < 1) && ($isPersistUser)) {

                $query = "INSERT INTO wp_users_mxit_info
                (UserOID_Usr, MXitUserID_Usr, UserInfo_MxitDisplayName_Usr,UserInfo_CurrentCountryCode_Usr,UserInfo_CurrentRegionCode_Usr,UserInfo_CurrentCityCode_Usr,UserInfo_CurrentCityName_Usr, UserInfo_CurrentMNOCode_Usr, UserInfo_CurrentMNOCellID_Usr, UserInfo_LanguageCode_Usr, UserInfo_RegisteredCountry_Usr, UserInfo_DateOfBirth_Usr, UserInfo_Gender_Usr,UserInfo_MxitTarrifPlan_Usr,UserInfo_UserAgentString_Usr,UserInfo_DeviceIP,UserInfo_MobileNumber,DeviceInfo_Features_Usr, DeviceInfo_DisplayWidth_Usr, DeviceInfo_DisplayHeight_Usr, Datetime_Registered_Usr, Datetime_LastAccess_Usr) 
                VALUES 
                (NULL, '$HTTP_X_MXIT_USERID_R','$HTTP_X_MXIT_NICK','{$HTTP_X_MXIT_LOCATION_ar[0]}','{$HTTP_X_MXIT_LOCATION_ar[2]}','{$HTTP_X_MXIT_LOCATION_ar[4]}','{$HTTP_X_MXIT_LOCATION_ar[5]}','{$HTTP_X_MXIT_LOCATION_ar[6]}','{$HTTP_X_MXIT_LOCATION_ar[7]}','{$HTTP_X_MXIT_PROFILE_ar[0]}','{$HTTP_X_MXIT_PROFILE_ar[1]}','{$HTTP_X_MXIT_PROFILE_ar[2]}','{$HTTP_X_MXIT_PROFILE_ar[3]}','{$HTTP_X_MXIT_PROFILE_ar[4]}','$HTTP_X_DEVICE_USER_AGENT','$HTTP_X_FORWARDED_FOR',''/*future REST use*/,'{$HTTP_X_MXIT_LOCATION_ar[8]}','{$HTTP_UA_PIXELS_ar[0]}','{$HTTP_UA_PIXELS_ar[1]}','" . date('Y-m-d H:i:s') . "','" . date('Y-m-d H:i:s') . "');";
                $isPersistUser_result = mysql_query($query, $mDB);

                $UserOID_Usr = mysql_insert_id($mDB);
            } else {

                $row = mysql_fetch_assoc($result);
                $UserOID_Usr = $row['UserOID_Usr'];
                if (($UserOID_Usr) && ($isUpdateUser)) {
                    //update if fresher than 15 min
                    $query = "UPDATE wp_users_mxit_info SET 
                    Datetime_LastAccess_Usr=now(),
                        UserInfo_MxitDisplayName_Usr = '$HTTP_X_MXIT_NICK',
                        UserInfo_CurrentCountryCode_Usr = '{$HTTP_X_MXIT_LOCATION_ar[0]}',
                        UserInfo_CurrentRegionCode_Usr = '{$HTTP_X_MXIT_LOCATION_ar[2]}',
                        UserInfo_CurrentCityCode_Usr = '{$HTTP_X_MXIT_LOCATION_ar[4]}',
                        UserInfo_CurrentCityName_Usr = '{$HTTP_X_MXIT_LOCATION_ar[5]}', 
                        UserInfo_CurrentMNOCode_Usr = '{$HTTP_X_MXIT_LOCATION_ar[6]}', 
                        UserInfo_CurrentMNOCellID_Usr = '{$HTTP_X_MXIT_LOCATION_ar[7]}', 
                        UserInfo_LanguageCode_Usr = '{$HTTP_X_MXIT_PROFILE_ar[0]}', 
                        UserInfo_RegisteredCountry_Usr = '{$HTTP_X_MXIT_PROFILE_ar[1]}', 
                        UserInfo_DateOfBirth_Usr = '{$HTTP_X_MXIT_PROFILE_ar[2]}', 
                        UserInfo_Gender_Usr = '{$HTTP_X_MXIT_PROFILE_ar[3]}',
                        UserInfo_MxitTarrifPlan_Usr = '{$HTTP_X_MXIT_PROFILE_ar[4]}',
                        UserInfo_UserAgentString_Usr = '$HTTP_X_DEVICE_USER_AGENT',
                        UserInfo_DeviceIP = '$HTTP_X_FORWARDED_FOR',
                        /* UserInfo_MobileNumber = '',future REST use*/
                        DeviceInfo_Features_Usr = '{$HTTP_X_MXIT_LOCATION_ar[8]}', 
                        DeviceInfo_DisplayWidth_Usr = '{$HTTP_UA_PIXELS_ar[0]}', 
                        DeviceInfo_DisplayHeight_Usr = '{$HTTP_UA_PIXELS_ar[1]}'
                        WHERE UserOID_Usr='$UserOID_Usr'
                        AND Datetime_LastAccess_Usr < ADDDATE(NOW(), INTERVAL -15 MINUTE)
                        ;";
                    $isPersistUser_result = mysql_query($query, $mDB);
                }
            }
        }
    }

    mysql_close($mDB);
}

?>
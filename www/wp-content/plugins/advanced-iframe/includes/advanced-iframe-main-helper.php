<?php
defined('_VALID_AI') or die('Direct Access to this location is not allowed.');

class AdvancedIframeHelper {

      static function scale_value($value, $iframe_zoom)
        {
            if (strpos($value, '%') === false) {
                return (intval($value) * floatval($iframe_zoom)) . 'px';
            } else {
                $value = substr($value, 0, -1);
                return (intval($value) * floatval($iframe_zoom)) . '%';
            }
        }

        /**
         * Replace placeholders in the url and fill them with proper values.
         */
        static function ai_replace_placeholders($str_input, $enable_replace, $aip_standalone) {
          // wordpress does encode ' by default which does kill urls that contain this char
          $str_input = str_replace('&#8242;', '%27', $str_input);
          $str_input = str_replace('&#8217;', '%27', $str_input);

          if ($enable_replace) {
              $str_input = str_replace('{host}', $_SERVER['HTTP_HOST'], $str_input);
              $str_input = str_replace('{port}', $_SERVER['SERVER_PORT'], $str_input);
              
              // the random number can be used to avoid caching
              $str_input = str_replace('{timestamp}', time() , $str_input);
              
              if (!isset($aip_standalone)) {
                  $str_input = str_replace('{site}', site_url(), $str_input);
                  $str_input = AdvancedIframeHelper::replace_user_data($str_input);

                  $admin_email = get_option( 'admin_email' );
                  $str_input = str_replace('{adminemail}', urlencode($admin_email), $str_input);

                  $str_input = AdvancedIframeHelper::replace_url_path_data($str_input);
                  $str_input = AdvancedIframeHelper::replace_full_url_data($str_input);
                  $str_input = AdvancedIframeHelper::replace_query_data($str_input);

                // evaluate shortcodes for the parameter 
                $str_input = str_replace('{{', "[", $str_input);
                $str_input = str_replace('}}', "]", $str_input);
                $str_input = do_shortcode($str_input);
              }
              
              // we replace all leftover placeholder 
             $regex = '/{(.*?)}/';
             $result = preg_match_all( $regex, $str_input, $match);  
             if ($result) {
               foreach ($match[1] as $key) {
                 $str_input = str_replace('{'.$key.'}', '' , $str_input); 
               } 
             }   
              
              
          }
          return $str_input;
      }

      // key, user_key,  value, $str_input
      
    //  'userid','ID',
      
      
      /**
       *  replaces one on the main user keys and also checks if a default 
       *  is set. The default is used when the result is empty.
       *  
       *  
       */
    static function replace_key_with_default($key, $value, $str_input, $current_user) {
        if (strpos($str_input,'{' . $key) !== false) {
            $regex = '/{('.$key.'.*?)}/';
            preg_match_all( $regex, $str_input, $match);
            foreach ($match[1] as $result_key) {
                // we check if we have a default value
                $userinfo_elements = explode(",", $result_key);
                $result_value =  ($value == '') ? '' :  $current_user->$value;
                if (count($userinfo_elements) == 2 && empty($result_value)) {
                    $result_value = trim($userinfo_elements[1]);
                }
                $str_input = str_replace('{'.$result_key.'}', urlencode($result_value), $str_input);
            }
        }
        return  $str_input;
    }


     static function replace_user_data($str_input) {
            $current_user = wp_get_current_user();
            
            $str_input = AdvancedIframeHelper::replace_key_with_default('userid', 'ID', $str_input,$current_user);
            // $str_input = str_replace('{userid}', urlencode($current_user->ID), $str_input);
            if (empty($current_user->ID)) {
                $str_input = AdvancedIframeHelper::replace_key_with_default('username', '', $str_input,$current_user);
                // $str_input = str_replace('{username}', '', $str_input);
                $str_input = AdvancedIframeHelper::replace_key_with_default('useremail', '', $str_input,$current_user);
                // $str_input = str_replace('{useremail}', '', $str_input);
            } else {
                $str_input = AdvancedIframeHelper::replace_key_with_default('username', 'user_login', $str_input,$current_user);
                // $str_input = str_replace('{username}', urlencode($current_user->user_login), $str_input);
                $str_input = AdvancedIframeHelper::replace_key_with_default('useremail', 'user_email', $str_input,$current_user);
                // $str_input = str_replace('{useremail}', urlencode($current_user->user_email), $str_input);
                
                // dynamic $propertyName = 'id'; print($phpObject->{$propertyName});
                if (strpos($str_input,'{userinfo') !== false) {
                    $regex = '/{(userinfo.*?)}/';
                    $result = preg_match_all( $regex, $str_input, $match);
                    if ($result) {
                        foreach ($match[1] as $hits) {
                            $key = substr($hits, 9);
                            // we check if we have a default value
                            $userinfo_elements = explode(",", $key);
                            if (count($userinfo_elements) == 2) {
                                $value = $current_user->trim($userinfo_elements[0]);
                                if (empty($value)) {
                                    $value = trim($userinfo_elements[1]);
                                }
                            } else {
                                $value = $current_user->trim($key);
                            }
                            $str_input = str_replace('{'.$hits.'}', urlencode($value), $str_input);
                        }
                    }
                }
                // postmeta! https://codex.wordpress.org/Custom_Fields
                if (strpos($str_input,'{usermeta') !== false) {
                    $regex = '/{(usermeta.*?)}/';
                    $result = preg_match_all( $regex, $str_input, $match);
                    if ($result) {
                        foreach ($match[1] as $hits) {
                            $key = substr($hits, 9);    
                             // we check if we have a default value
                            $usermeta_elements = explode(",", $key);
                            if (count($userinfo_elements) == 2) {
                                $value = get_user_meta( $current_user->ID, trim($usermeta_elements[0]), true );
                                if (empty($value)) {         
                                    $value = trim($usermeta_elements[1]);
                                }
                            } else {
                                $value = get_user_meta( $current_user->ID, trim($key), true );
                            }
                            
                            $str_input = str_replace('{'.$hits.'}', urlencode($value), $str_input);
                        }
                    }
                }
            }
            return $str_input;
        }

       static function replace_full_url_data($str_input) {
            if (strpos($str_input,'{href}') !== false) {
                $location = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
                if ($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443" ) {
                    $location .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
                } else {
                    $location .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
                }
                $str_input = str_replace('{href}', urlencode($location), $str_input);
            }
            return $str_input;
        }

        static function replace_query_data($str_input) {
            if (strpos($str_input,'{query') !== false) {
                $regex = '/{(query.*?)}/';
                $result = preg_match_all( $regex, $str_input, $match);
                if ($result) {
                    foreach ($match[1] as $hits) {
                       $key = substr($hits, 6);
                       $query_elements = explode(",", $key);
                          if (count($query_elements) == 2) {
                               $value = advancediFrame::param(trim($query_elements[0]));
                              if (empty($value)) {
                                  $value = trim($query_elements[1]);
                              }
                          } else {
                              $value = advancediFrame::param(trim($key));
                          }
                      $str_input = str_replace('{'.$hits.'}', $value , $str_input);
                    }
                }
            }
            return $str_input;
        }


    static function replace_url_path_data($str_input) {     
        if (strpos($str_input,'{urlpath') !== false) {
            // part of the url are extracted {urlpath1} = first path element
            $path_elements = explode("/", trim($_SERVER['REQUEST_URI'], "/"));
            $count = 1;
            foreach($path_elements as $path_element){
                $str_input = str_replace('{urlpath'.$count.'}', urlencode($path_element), $str_input);
                $count++;
            }
            // part of the url counting from the end {urlpath-1} = last path element
            reset($path_elements);
            $rpath_elements = array_reverse($path_elements);
            $count = 1;
            foreach($rpath_elements as $path_element){
                $str_input = str_replace('{urlpath-'.$count.'}', urlencode($path_element), $str_input);
                $count++;
            }
        }
        return $str_input;
    }
}

?>
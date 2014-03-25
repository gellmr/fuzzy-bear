
<!-- ==================================== -->
<!-- (HTML_Fragment) View: Store (Header) -->

<?php

// WINDOWS STYLE PATHNAMES USE A BACKSLASH
//include_once getcwd() . '\application\libraries\util.php'; // not sure if this is a good way to include my util class.

// UNIX STYLE PATHNAMES USE A FORWARD SLASH
include_once getcwd() . '/application/libraries/util.php'; // not sure if this is a good way to include my util class.

?>



<!DOCTYPE html>

<html lang = "en" xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <meta name = "description"  content = "Fuzzy Bear Electronics" />
    <meta name = "keywords"   content = "HTML, CSS, XML, JavaScript" />
    <meta name = "author"   content = "Michael Gell, December 2012" />
    <meta charset = "UTF-8" />

    <?php
    // link to CSS files.
    if (isset($css_array) && count($css_array) > 0) {
      foreach ($css_array as $css_file) {
        echo link_tag('public/css/'.$css_file);
      }
    }
    // link to JavaScript files.
    if (isset($js_array) && count($js_array) > 0) {
      foreach ($js_array as $js_file) {
        echo '<script src="' . base_url() . 'public/js/' . $js_file . '"></script>';
      }
    }

    // bootstrap fonts
    echo link_tag('public/fonts/glyphicons-halflings-regular.eot');
    echo link_tag('public/fonts/glyphicons-halflings-regular.svg');
    echo link_tag('public/fonts/glyphicons-halflings-regular.ttf');
    echo link_tag('public/fonts/glyphicons-halflings-regular.woff');

    ?>
    <title>Fuzzy Bear Electronics</title>
  </head>

  <body>

    <div class="container">

      <div class="row">
      
        <div class="col-xs-8">
          <div class="row">
            <div class="col-xs-12">
              <h3>Fuzzy Bear Electronics</h3>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div id='disclaimer'>
                This is not a real site.<br />I'm just demonstrating my amazing web application skills.
              </div>
            </div>
          </div>
        </div> <!-- col 8 -->

        <div class="col-xs-4">
          <div class="row" id="loginStatus" class="small_Text">
            <div class="col-xs-12">
              <?php
              // Thin grey strip across the top of the login panel (Top right of page)
              // CURRENTLY LOGGED IN AS BLAH
              if (
                ( isset($currentUser) && $currentUser != $NO_USER )
                &&
                ( isset($login_status) && $login_status == $LOGGED_IN )
              )
              {
                // Logged in as blah.
                echo "Currently logged in as $currentUser";
              }
              else
              {
                // NOT LOGGED IN.
                echo "Not currently logged in.";
              }
              //echo " $page_title";
              ?>
            </div> <!-- col 12 -->
          </div> <!-- row -->
          
          <div class="row">
            <div class="col-xs-12">
              <?php
              // TODO: remove all controller logic from templates.
              function echo_anchor($controller, $text, $attribs, $current_page, $css_class, $divId=''){
                if ($current_page == $text){
                  // No anchor. Already on this page.
                  echo util::bright_PNG_anchor($text, $css_class, $divId);
                }else{
                  // Yes anchor. We are not on this page.
                  echo util::dark_PNG_anchor($controller, $text, $attribs, $css_class, $divId);
                }
              }
              // Display all the items in the login panel (top right of screen)
              //
              if (isset($login_panel_contents)) {
                foreach ($login_panel_contents as $index => $value) {
                  // -------------------------------------------------
                  if ($value == 'forgot_pw') {
                    // Link - I forgot my password
                    // THIS IS DISPLAYED IF WE ARE LOGGED OUT.
                    echo anchor(
                      "forgotPassword",
                      "I forgot my password",
                      'class="btn btn-primary forgot_button" title="I forgot my password"'
                    );
                  }
                  elseif ($value == 'register')
                  {
                    // Link - Register
                    // THIS IS DISPLAYED IF WE ARE LOGGED OUT.
                    echo anchor(
                      "register",
                      "Register",
                      'class="btn btn-primary register_button" title="Register"'
                    );
                  }
                  elseif ($value == 'login')
                  {
                    // Link - Login
                    // THIS IS DISPLAYED IF WE ARE LOGGED OUT.
                    echo anchor(
                      "login",
                      "Login",
                      'class="btn btn-primary login_button" title="Login"'
                    );
                    // -------------------------------------------------
                  }
                  elseif ($value == 'my_account')
                  {
                    // Link - My Account
                    // ONLY DISPLAYED IF WE ARE LOGGED IN.
                    echo anchor(
                      "myAccount",
                      "My Account",
                      'id="myAccount_button" class="btn btn-primary" title="My Account"'
                    );
                  }
                  elseif ($value == 'logout')
                  {
                    // Link - Logout
                    // ONLY DISPLAYED IF WE ARE LOGGED IN.
                    echo anchor(
                      "logout",
                      "Logout",
                      'class="btn btn-primary login_button" title="Logout"'
                    );
                    // -------------------------------------------------
                  }
                }
              }
              ?>
            </div> <!-- col 12 -->
          </div> <!-- row -->
        </div> <!-- col 4 -->
        
        <?php
        if($showDebug && $globalShowDebug) {
          ?>
          <div id="debugPanel">
            <?php
            if(isset($debugText)) {
              echo $debugText;
            }else{
              echo "debug panel";
            }
            ?>
          </div>
          <?php
        } ?>
      </div> <!-- row -->

      <!-- (END HTML_Fragment) View: Store (Header) -->
<?php
/*
Plugin Name: Retweet 
Plugin URI: http://www.retweet.com
Description: The official Retweet button to put on your website or weblog.
Version: 1.0
Author: Mesiab Labs
Author URI: http://mesiablabs.com/
*/

/*  Copyright 2009  Mesiab Labs  (email : retweet@mesiablabs.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//Initialize the admin panel
if (!function_exists("retweet_admin")) {
        function retweet_admin() {
                        add_options_page('Retweet Options', 'retweet', 8, basename(__FILE__), 'retweet_options');
        }
}

//Options page
function retweet_options() {


    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( $_POST["retweet_submit" ] == 'Y' ) {
        // Read their posted value
        $opt_val = $_POST[ $data_field_name ];

        // Save the posted value in the database
        // Save Settings
        if($_POST['retweet_home'] == "on") update_option('retweet_home', "checked");
        else update_option('retweet_home', "off");
        if($_POST['retweet_post'] == "on") update_option('retweet_post', "checked");
        else update_option('retweet_post', "off");
        if($_POST['retweet_page'] == "on") update_option('retweet_page', "checked");
        else update_option('retweet_page', "off");

        if($_POST['retweet_addthis'] == "on") update_option('retweet_addthis', "checked");
        else update_option('retweet_addthis', "");
        
        update_option( "retweet_style", $_POST['retweet_style'] );
        update_option( "retweet_location", $_POST['retweet_location'] );
        update_option( "retweet_size", $_POST['retweet_size'] );
        update_option( "retweet_username", $_POST['retweet_username'] );

        // Put an options updated message on the screen

?>
<div class="updated"><p><strong>Options saved</strong></p></div>
<?php

    }
    // Read in existing option value from database
    
    $retweet_home = get_option( "retweet_home" );
    $retweet_post = get_option( "retweet_post" );
    $retweet_page = get_option( "retweet_page" );
    $retweet_location = get_option('retweet_location');
    $retweet_size = get_option('retweet_size');
    $retweet_username = get_option('retweet_username');
    $retweet_style = get_option('retweet_style');
    $retweet_addthis = get_option('retweet_addthis');
    if(!$retweet_style)
      $retweet_style="float:right;margin-left: 10px;";
    
    if($retweet_size!="small")
    {
        $addthis_button="disabled";
        $retweet_addthis="";
    }
    // Now display the options editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>Retweet Plugin Options</h2>";

    // options form
    
    ?>
<script >
function buttonCheck(){
  size=document.getElementById("retweet_size").value;
  addthis=document.getElementById("retweet_addthis");
  
  if(size=="small")
  {
    addthis.disabled=false;
    document.getElementById("rt-preview").innerHTML='<img src="http://retweet.com/static/images/button_small_X.png">';
  }
  else
  {
    addthis.disabled=true;
    addthis.checked=false;  
    document.getElementById("rt-preview").innerHTML='<img src="http://retweet.com/static/images/button_large_X.png">';
    
  }
  
}
</script>
<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<input type="hidden" name="retweet_submit" value="Y">

<table width="90%">
<tr>
<th scope="row"  style="text-align:right;padding:10px;padding:10px;">
Display Button on
</th><td colspan=2>

  <input type="checkbox" name="retweet_home" <?php _e(get_option('retweet_home')=="off" ? "":"checked"); ?> /> Home Page<br />
  <input type="checkbox" name="retweet_post" <?php _e(get_option('retweet_post')=="off" ? "":"checked"); ?> /> Posts<br />
  <input type="checkbox" name="retweet_page" <?php _e(get_option('retweet_page')=="off" ? "":"checked"); ?> /> Pages<br />
</td>

</tr>
<tr>
<th scope="row" style="text-align:right;padding:10px;">
Button Position
</th><td width="200px">
  <select id="location" name="retweet_location" style="width:150px;">
          <option value="" <?php _e($retweet_location=="" ? "selected" : ""); ?>>Top of post</option>
          <option value="bottom" <?php _e($retweet_location=="bottom" ? "selected" : ""); ?>>Bottom of post</option>
  </select>
  
</td>
<td rowspan=2>
  <div id='rt-preview'>
    <?php if ($retweet_size=="small"): ?>
      <img src="http://retweet.com/static/images/button_small_X.png">  
    <?php else: ?>  
      <img src="http://retweet.com/static/images/button_large_X.png">
    <?php endif ?>  
    
  </div>
</td>  
</tr>
<tr>
<th scope="row"  style="text-align:right;padding:10px;">
Size
</th><td>
  <select id="retweet_size" name="retweet_size"  style="width:100px;" onchange="javascript:buttonCheck();">
          <option value="" <?php _e($retweet_size=="" ? "selected" : ""); ?>>Big</option>
          <option value="small" <?php _e($retweet_size=="small" ? "selected" : ""); ?>>Small</option>
  </select> 
</td>
  
</tr>
<tr>
<th scope="row"  style="text-align:right;padding:10px;padding:10px;">
AddThis widget 
</th><td  colspan=2>

  <input type="checkbox" id="retweet_addthis" name="retweet_addthis" <?php _e($retweet_addthis=="checked" ? "checked":""); ?> <?php echo $addthis_button ?>/> Also include the AddThis widget (This lets users share to Facebook, Digg, etc.) <b>Small button only</b><br />
</td></tr>
<tr>
<th scope="row"  style="text-align:right;padding:10px;">
Different Username
</th><td  colspan=2>RT <input type="text" name="retweet_username" value="<?php echo $retweet_username; ?>"> (By specifying the ‘username’ parameter retweets will be in format "RT @yourname [title] [link]")
</td></tr>
<th scope="row"  style="text-align:right;padding:10px;">
Style 
</th><td  colspan=2><input type="text" name="retweet_style" value="<?php echo $retweet_style; ?>"> (To be applied on the button div)
</td></tr>

</table>

<p class="submit">
<input type="submit" name="Submit" value="Update Options" />
</p>

</form>

<?php
$recent_posts=get_posts();
if(count($recent_posts)>5)
  $recent_posts=array_splice($recent_posts,0,5);

function getCount($link)
{
    $content=file("http://www.retweet.com/story/api/$link");
    $json=json_decode($content[0]);
    return $json->tweets;
}
?>

<h2>Retweet Statistics</h2>

<table width="90%">
<tr>
<td>Post
</td>
<td>Retweets
</td>
<td>Details
</td>  
</tr>

<?php foreach ($recent_posts as $post): 
  $link=get_permalink($post->ID);
  $rtlink=str_replace("?", "*",$link);
?>
  
  <tr>
  <td><a href="<?php echo $link; ?>"><?php echo $post->post_title; ?></a></td>
  <td><a href="http://www.retweet.com/story/tweets/<?php echo $rtlink; ?>/0"><?php echo getCount($rtlink) ?></a></td>
  <td><a href="http://www.retweet.com/story/tweets/<?php echo $rtlink; ?>/0">More</a></td>
  </tr>
<?php endforeach ?>

</table>  


</div>

<?php
 
}


//Show button
if (!function_exists("retweet_button")) {
        function retweet_button($content) {
                if(is_home() && get_option('retweet_home') == "off") return $content;
                if(is_single() && get_option('retweet_post') == "off") return $content;
                if(is_page() && get_option('retweet_page') == "off") return $content;
                if(!(is_home() || is_single() || is_page()))
                   return $content;
                
                $retweet_location = get_option('retweet_location');
                $retweet_size = get_option('retweet_size');
                $retweet_username = get_option('retweet_username');
                $retweet_style = get_option('retweet_style');
                $retweet_addthis = get_option('retweet_addthis');

                if(!$retweet_style)
                  $retweet_style="float:right;margin-left: 10px;";

                $button_code="<div id='retweet_button' style='$retweet_style'>";
                if($retweet_size=="small" && $retweet_addthis=="checked")
                   $button_code.='<a class="addthis_button" addthis:url="'.get_permalink().'" href="http://www.addthis.com/bookmark.php?v=250&amp;pub=xa-4a9a4e0e5333be83"><img src="http://s7.addthis.com/static/btn/sm-share-en.gif" width="83" height="16" alt="Bookmark and Share" style="border:0;margin-bottom: 4px; margin-right: 8px;"/></a><script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js?pub=xa-4a9a4e0e5333be83"></script>';
                
                $button_code.="<script type=\"text/javascript\">url='".get_permalink()."';";
                if($retweet_size=="small")
                  $button_code.="size='small';";
                if($retweet_username)
                  $button_code.="username='$retweet_username';";                
                  
                $button_code.='</script><script type="text/javascript" src="http://www.retweet.com/static/retweets.js"></script>';
                
                
                $button_code.='</div>'; 
                
                if($retweet_location=="bottom")
                   return $content.$button_code;
                else   
                   return $button_code.$content;
       }
}

add_filter('the_content', 'retweet_button');
add_action('admin_menu', 'retweet_admin');

?>

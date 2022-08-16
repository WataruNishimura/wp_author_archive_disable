<?php

/**
 * Plugin Name:     Disable Author Archive
 * Description:     Disable selected author's page. 
 * Author:          WATARU NISHIMURA
 * Author URI:      https://kraftsman.jp
 * Text Domain:     disable-author-archive
 * Version:         0.1.0
 * License: GPL2
 *
 * @package         Disable_Author_Archive
 */

/*  Copyright 2022 Wataru Nishimura (email : wataru.nishimura@kraftsman.jp)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


function daa_disable_user_settings($user)
{
?>
  <h3>ライター画面管理 | Author Archive Disable</h3>
  <table class="form-table">
    <tr>
      <th><label for="disable_author_page">ライター画面の無効化</label></th>
      <td>
        <?php $selected = get_the_author_meta('disable_author_page', $user->ID); ?>
        <select name="disable_author_page" id="disable_author_page">
          <option value="yes" <?php echo ($selected == "yes") ?  'selected="selected"' : '' ?>>無効化しない</option>
          <option value="no" <?php echo ($selected != "yes") ?  'selected="selected"' : '' ?>>無効化する</option>
        </select>
      </td>
    </tr>
  </table>
<?php
}

function daa_save_user_settings($user_id) {
  // only editable user update user meta
  if(!current_user_can("edit_user", $user_id)) {
    return;
  }

  update_user_meta($user_id, "disable_author_page", $_POST["disable_author_page"]);
}

function daa_redirect_not_found() {
  if(is_author()) {
    // retrive is_disable from user's meta
    $is_disable = get_the_author_meta("disable_author_page", get_query_var("author"));
    if($is_disable != "yes" ) { 
      global $wp_query;
      $wp_query->set_404();
      status_header( 404 );
      nocache_headers();
      return;
      exit();
    }
  }
} 

add_action("wp", "daa_redirect_not_found");

add_action("edit_user_profile", "daa_disable_user_settings");
add_action("show_user_profile", "daa_disable_user_settings");

add_action('profile_update', 'daa_save_user_settings');
add_action('user_register', 'daa_save_user_settings'); 

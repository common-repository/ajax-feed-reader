<?php
/*
Plugin Name: Ajax Feed Reader
Plugin URI: http://firstelement.jp
Description: Feed reader plug-in.
Version: 1.1 beta
Author: Takumi Kumagai
Author URI: http://takumin.ddo.jp/
License: GPL2
*/

/*Copyright 2012 Takumi Kumagai (email : kumagai.t at firstelement.jp)

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
?>
<?php 
require_once('simplepie/simplepie.inc');


function AFR_return_json(){
	if($_GET['AFRurl']){
		$url_list = explode(',',$_GET['AFRurl']);
		$return = array();
		
		$feed = new SimplePie();
		$feed->set_feed_url($url_list);
		
		if($_GET['AFRlimit']){
			$feed->set_item_limit(intval($_GET['AFRlimit']));
		}
		
		//キャッシュ
		if(is_writable(WP_CONTENT_DIR)){
			if(!is_dir(WP_CONTENT_DIR.'/cache/ajax_feed')){
				$old = umask(0);
				
				if(!is_dir(WP_CONTENT_DIR.'/cache')){
					$AFRflag = @mkdir(WP_CONTENT_DIR.'/cache',0755);
				}else{$AFRflag = true;} //cacheは既に有る
				if($AFRflag){ $AFRflag = @mkdir(WP_CONTENT_DIR.'/cache/ajax_feed',0755);}
				if($AFRflag){
						$feed->set_cache_location(WP_CONTENT_DIR.'/cache/ajax_feed');
				}else{ //キャッシュディレクトリを作れなかった。。
					$feed->enable_cache(false);
				}
				
				umask($old);
				
			}else if(is_writable(WP_CONTENT_DIR.'/cache/ajax_feed')){
				$feed->set_cache_location(WP_CONTENT_DIR.'/cache/ajax_feed');
			}else{ //何故かajax_feedのキャッシュディレクトリが書き込めないのでキャッシュを諦める
				$feed->enable_cache(false);
			}
		}else{ //wp_contentに書き込みできなければキャッシュを作らない
			$feed->enable_cache(false);
		}
		
		$feed->init();
		
		if($_GET['AFRlimit']){
			$i = 1;
			foreach($feed->get_items() as $item){
				$return[] = array(
				'link' => $item->get_permalink(),
				'title' => $item->get_title()
				);
				$i++;
				if($i > intval($_GET['AFRlimit'])){ break;}
			}
		}else{
			foreach($feed->get_items() as $item){
				$return[] = array(
				'link' => $item->get_permalink(),
				'title' => $item->get_title()
				);
			}
		}
		@header('Content-Type: application/json; charset='. get_bloginfo('charset'));
		echo json_encode($return);
		exit;
	}
}
add_action( 'init', 'AFR_return_json' );

/****************************************************************************/
/*ショートコード
/****************************************************************************/
function AFR_shortcode($atts){
	extract(
		shortcode_atts(
			array(
				'url' => '',
				'limit' => '',
			), $atts));
	if($url!=''){
		$divid = 'afr'.rand(100,999);
		
		$url = get_bloginfo('url').'/?AFRurl='.ereg_replace("\r|\n","",strip_tags($url));
		if($limit){
			$url .= '&AFRlimit='.$limit;
		}
		$return = <<<END
<div class="AFR" id="$divid">フィード読み込み中</div>
<script>
jQuery(function(){
	jQuery.getJSON( '$url' , function(json){
		if(json){
			
			var source = '<ul>';
			jQuery.each(json,function(){
				//console.log(this.link +':'+this.title);
				
				source += '<li>';
				
				if(this.link){
					source += '<a href="'+this.link+'">';
				}
				source += this.title;
				if(this.link){
					source += '</a>';
				}
				source += '</li>';
			});
			jQuery('#$divid').html(source);
		}else{
			//jQuery('#$divid').remove();
			jQuery('#$divid').html('接続エラー');
		}
	});
});
</script>
END;
		//json_encode($return);
		return $return;
	}
}
add_shortcode('AFR', 'AFR_shortcode');
/****************************************************************************/
/*管理ページにメニューつける
/****************************************************************************/
function AFR_Management_page(){
	/*(ページタイトル, 付け加えるオプション名,ユーザーレベル, 実行ファイル,関数)*/
	add_menu_page('Ajax Feed Reader', 'Ajax Feed Reader', 3, __FILE__, 'AFR_show_Management_page');
	//add_submenu_page(__FILE__, 'fe-photogallery', '画像アップロード', 0, 'flashuplord', 'flash_uplord_picture_management');
	//add_submenu_page(__FILE__, 'fe-photogallery', '画像削除', 0, 'flashdelete', 'flash_delete_picture_management');
}
//add_action('admin_menu', 'AFR_Management_page');
/****************************************************************************/
/*設定ページ
/****************************************************************************/
function AFR_show_Management_page(){
	echo 'Hellow World';
}
?>
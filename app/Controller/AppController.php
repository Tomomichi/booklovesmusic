<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * This is a placeholder class.
 * Create the same file in app/Controller/AppController.php
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       Cake.Controller
 * @link http://book.cakephp.org/view/957/The-App-Controller
 */
class AppController extends Controller {
	var $ext = '.html';
	var $helpers = array ('Html','Form','Js', 'Session');
	var $components = array ('Cookie', 'Session');
	var $uses = array('Playlist', 'Account', 'Userlog');	//複数データベース使用の設定
	
	
	//共通事前処理
	function beforeFilter(){
		//スマホ判定
	    $useragents = array(
	      'iPhone', // Apple iPhone
	      'iPod',   // Apple iPod touch
	      'Android' // Android
	    ); 
	    $pattern = '/'.implode('|', $useragents).'/i';
	    if ( preg_match($pattern, $_SERVER['HTTP_USER_AGENT']) ) {
			App::build( array( 'views' => ROOT . DS . APP_DIR . DS . 'View'. DS . 'Smartphone' . DS ) );
	    }

		//ログイン状況チェック
		$user['Account']['name'] = "ゲスト"; //初期設定
		$login_flg = false;
		if($this->Cookie->read('blm_id')){
			$cookie = $this->Cookie->read('blm_id');
			$user = $this->Userlog->getLoginUser($cookie);
			$login_flg = true;
		}
		$this->set('user', $user);
		$this->set('login_flg', $login_flg);
		
		//最新PlayListの取得。
		$p_num = 5; //取得件数。
		$new_lists = $this->Playlist->getNewList($p_num);
		$this->set('new_lists', $new_lists);
		
		//投稿数の多いuserを取得。
		$dj_num = 5;
		$popDJs = $this->Playlist->getTopDJs($dj_num);
		$this->set("popDJs", $popDJs);
	}
}
define('AWS_HOST', 'ecs.amazonaws.jp');


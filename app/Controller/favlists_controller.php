<?php

	class FavlistsController extends AppController {
		var $name = 'Favlists';
		var $uses = array('Favlist', 'Account', 'Userlog', 'Playlist');	//複数データベース使用の設定
		
		
		function index(){
			//まだとくに処理なし
		}
		
		function addfav() {
			$user_id = h($this->params['url']['user_id']);
			$pl_id = h($this->params['url']['pl_id']);
		
			$this->Favlist->addFavs($user_id, $pl_id);
		}

		function chkfav() {
			$user_id = h($this->params['url']['user_id']);
			$pl_id = h($this->params['url']['pl_id']);
		
			$chk_flg = $this->Favlist->chkFavs($user_id, $pl_id);
			return $chk_flg;
		}
	}

?>

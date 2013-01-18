<?php

	class ContactsController extends AppController {
		var $name = 'Contacts';
		var $uses = array('Contact', 'Account', 'Userlog', 'Playlist');	//複数データベース使用の設定
		
		
		function index(){
			//まだとくに処理なし
		}
		
		function submit_contact() {
			$text = h($this->params['url']['text']);
			
			//作成したプレイリストをDBに登録
			$data = array(
				'text' => $text,
			);
			$this->Contact->save($data);
		}

	}

?>

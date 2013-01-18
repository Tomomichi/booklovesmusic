<?php

	App::import("Vendor", "Zend/Gdata/ClientLogin");
	App::import("Vendor", "Zend/Gdata/YouTube");

	class MypagesController extends AppController {
		var $name = 'Mypages';
		var $uses = array('Account', 'Userlog', 'Playlist', 'Favlist');	//複数データベース使用の設定
		
		function index($author_id){
			//本人確認
			$user_flg = false;
			if($this->Cookie->read('blm_id')){
				$cookie = $this->Cookie->read('blm_id');
				$u_log = $this->Userlog->find("first", array(
					'conditions'=>array( 'cookie' => $cookie ) 
				) );
				if($u_log['Userlog']['user_id'] == $author_id){
					$user_flg = true;
				}
			}
			$this->set("user_flg", $user_flg);
			
			//対象ユーザーの情報を取得
			$params = array(
				'conditions' => array('id' => $author_id),
			);
			$account = $this->Account->find("first", $params);
			$this->set("account", $account);

			//該当ユーザーの作成したリストを取得
			$this->paginate = array(
				'Playlist'=>array(
						'conditions' => array('Playlist.user_id'=>$author_id),
						'limit' => 5,
						'order' => array('Playlist.created' => 'desc'),
				)
			);
			$this->set('playlists', $this->paginate('Playlist') );
			
			//お気に入りプレイリストの取得
			$favIDs = $this->Favlist->getFavIDs($author_id);
			$favlists =  $fav_videos = $favDJs = array();
			if($favIDs){
				$favlists = $this->Playlist->getFavlists($favIDs);
			}
			$this->set("favlists" , $favlists );
		}


		function edit_list($author_id, $pl_id) {
			//対象ユーザーの情報を取得
			$params = array(
				'conditions' => array('id' => $author_id),
			);
			$account = $this->Account->find("first", $params);
			$this->set("account", $account);

			//編集するプレイリストの情報取得
			$list = $this->Playlist->find('first', array(
				"conditions"=>array("Playlist.id"=>$pl_id)
			));
			$this->set("list", $list);
			
			//Amazonから本の情報取得
			$am_xml = $this->Playlist->getAmazonData($list['Playlist']['amazon_id']);
			$this->set("xml", $am_xml);
			
			//YouTube再生リストの情報取得
			$yt_xml = $this->Playlist->getYtPlaylist($list['Playlist']['youtube_id']);
			$this->set("yt_xml",$yt_xml);
			$videoIDs = $this->Playlist->getVideoID($yt_xml);
			$this->set("videoIDs",$videoIDs);
		}


		function edit_complete($author_id, $pl_id) {
			//作成したリストのデータを取得。
			$video_data = $_POST['data']['video_data'];
			
			//編集するプレイリストの情報取得
			$list = $this->Playlist->find('first', array(
				"conditions"=>array("Playlist.id"=>$pl_id)
			));
			$this->set("list", $list);

			//Youtube再生リストを空にして、新しいビデオを追加
			$this->Playlist->clearYtPlayList($list['Playlist']['youtube_id']);
			foreach($video_data as $video_id){
				$this->Playlist->addVideoToYTPlayList($list['Playlist']['youtube_id'], $video_id);
			}

			//完了メッセージの表示。
			$this->Session->setFlash("PlayListを更新しました", "default", array("class" => "flash-notice"));
			$this->redirect(array('controller'=>'mypages', 'action'=>'index', $author_id));
		}


		function delete_list($user_id, $pl_id) {
			//対象ユーザーの情報を取得
			$params = array(
				'conditions' => array('id' => $user_id),
			);
			$account = $this->Account->find("first", $params);
			$this->set("account", $account);
			
			//編集するプレイリストの情報取得
			$list = $this->Playlist->find('first', array(
				"conditions"=>array("Playlist.id"=>$pl_id)
			));
			$this->set("list", $list);

			//Youtube再生リストを削除
			$this->Playlist->deleteYtPlayList($list['Playlist']['youtube_id']);

			//DBからPlayListを削除
			$this->Playlist->delete($pl_id);
			
			//完了メッセージの表示。
			$this->Session->setFlash("PlayListを削除しました", "default", array("class" => "flash-notice"));
			$this->redirect(array('controller'=>'mypages', 'action'=>'index', $author['Account']['id']));
		}

	}

?>

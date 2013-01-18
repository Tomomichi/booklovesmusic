<?php

	App::import("Vendor", "Zend/Gdata/ClientLogin");
	App::import("Vendor", "Zend/Gdata/YouTube");

	class AddlistsController extends AppController {
		var $name = 'Addlists';
		var $uses = array('Account', 'Userlog', 'Playlist');	//複数データベース使用の設定
		
		//ログインステータスの確認
		function beforeFilter(){
			//ログインしてなかったらリダイレクト
			if(!$this->Cookie->read('blm_id')){
				$this->Session->setFlash("PlayListを登録するにはログインが必要です。", "default", array("class" => "flash-error"));
				$this->redirect(array('controller'=>'accounts', 'action'=>'index'));
			}

			parent::beforeFilter();  
		}

		
		function index(){
			//まだとくに処理なし
		}


		function book_search(){

			//検索ワードの取得
			$a_key = urlencode($_POST['keyword']);

			//キーワードでAmazonから本を検索
			$am_xml = $this->Playlist->getAmazonSearchResult($a_key);
			$this->set("xml", $am_xml);
		}


		function make_list($a_key){

			//Amazonから本の情報取得
			$am_xml = $this->Playlist->getAmazonData($a_key);
			$this->set("a_key", $a_key);
			$this->set("xml", $am_xml);

			//本にひもづくプレイリストの表示。
			$playlists = $this->Playlist->getPlaylist($a_key);
			$this->set("playlists" , $playlists );

		}


		function search_video($y_key, $num){
			//モーダルウィンドウ用なのでdefault.ctpは使わない
			$this->layout="default_modal";

			//何番目の動画かをセット
			$this->set("num", $num);
			
			//キーワードからYoutubeの動画を検索
			$yt_videos = $this->Playlist->searchYtVideos($y_key);
			$this->set("videos", $yt_videos);
		}


		function add_complete($a_key, $y_key=null){
			
			//Amazonから本の情報取得
			$am_xml = $this->Playlist->getAmazonData($a_key);
			$this->set("xml", $am_xml);

			$exceptionMessage = null;

			//じぶんで作成した場合、まずYoutubeの再生リストとして登録
			if(!$y_key){
				//作成したリストのデータを取得。
				$list_data = $_POST['data']['list_data'];
				$video_data = $_POST['data']['video_data'];
				
				//再生リストを作成して、再生リストのIDを取得。
				$y_key = $this->Playlist->createYTPlayList($list_data, $video_data);
				$this->set("a_key", $a_key);
				$this->set("y_key", $y_key);
			}
			
			//ユーザー情報の取得
			if($this->Cookie->read('blm_id')){
				$cookie = $this->Cookie->read('blm_id');
				$u_log = $this->Userlog->find("first", array(
					'conditions'=>array( 'cookie' => $cookie ) 
				) );
				
				$user = $this->Account->find("first", array(
					'conditions'=>array( 'id' => $u_log['Userlog']['user_id'] ) 
				) );
				$this->set("author", $user);
			}
			
			//作成したプレイリストをDBに登録
			$data = array(
				'user_id' => $user['Account']['id'],
				'amazon_id' => $a_key,
				'amazon_img' => $am_xml->Items->Item->MediumImage->URL,
				'youtube_id' => $y_key,
				'youtube_img' => "http://img.youtube.com/vi/".$video_data['video_01']."/1.jpg",
				'youtube_name' => $list_data['list_title']
			);
			$this->Playlist->save($data);
			
			//完了メッセージの表示。
			$this->Session->setFlash("PlayListを登録しました", "default", array("class" => "flash-notice"));
			
			//YouTube再生リストの情報取得
			$yt_xml = $this->Playlist->getYtPlaylist($y_key);
			$this->set("yt_xml",$yt_xml);
			$this->set("video_id", $y_key);
		}
	}

?>

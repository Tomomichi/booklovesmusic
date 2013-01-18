<?php

	class PlaylistsController extends AppController {
		var $name = 'Playlists';
		var $uses = array('Playlist', 'Account', 'Userlog', 'Favlist');	//複数データベース使用の設定


		function index(){
			//本の一覧取得
			$books = $this->Playlist->getAllBooks();
			$this->set('books', $books);
		}
		
		
		function book_search(){

			//検索ワードの取得
			$a_key = urlencode($_POST['keyword']);

			//キーワードでAmazonから本を検索
			$am_xml = $this->Playlist->getAmazonSearchResult($a_key);
			$this->set("xml", $am_xml);
		}


		function list_search($a_key){

			//Amazonから本の情報取得
			$am_xml = $this->Playlist->getAmazonData($a_key);
			$this->set("xml", $am_xml);
			
			//本にひもづくプレイリストの表示。
			$playlists = $this->Playlist->getPlaylist($a_key);
			$this->set("playlists" , $playlists );

			//プレイリスト作者の名前取得
			$DJs = $this->Account->getDJs($playlists);
			$this->set("DJs", $DJs);
			
			//各プレイリストに登録されてる動画情報の取得。 （ $videos[プレイリストID（blm定義）][プレイリスト内の順番(0～)] = 個別の動画 ）
			$videos = $this->Playlist->getVideos($playlists);
			$this->set('videos', $videos);
		}


		function video_play($a_key,$y_key){

			$this->set("a_key", $a_key);
			$this->set("y_key", $y_key);

			//Amazonから本の情報取得
			$am_xml = $this->Playlist->getAmazonData($a_key);
			$this->set("xml", $am_xml);

			//YouTube再生リストの情報取得
			$yt_xml = $this->Playlist->getYtPlaylist($y_key);
			$this->set("yt_xml",$yt_xml);
			$this->set("video_id", $y_key);
			
			//プレイリスト情報の取得
			$params = array(
				'conditions' => array('youtube_id' => $y_key),
			);
			$pl = $this->Playlist->find("first", $params);
			$this->set("playlist", $pl);

			//おなじ作成者のリスト一覧
			$this->paginate = array(
				'Playlist'=>array(
						'conditions' => array('Playlist.user_id'=>$pl['Playlist']['user_id']),
						'limit' => 5,
						'order' => array('Playlist.created' => 'desc'),
				)
			);
			$this->set('other_lists', $this->paginate('Playlist') );
			
			//お気に入りしてるかどうかチェック（ログインしてるときだけ）
			$fav_flg = false;
			if($this->Cookie->read('blm_id')){
				$cookie = $this->Cookie->read('blm_id');
				$user = $this->Userlog->getLoginUser($cookie);
				$fav_flg = $this->Favlist->chkFavs($user['Account']['id'], $pl['Playlist']['id']);
			}
			$this->set("fav_flg", $fav_flg);
		}

	}
?>

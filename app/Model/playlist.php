<?php
class Playlist extends AppModel
{
	var $name = 'Playlist';

	var $validate = array(
		'title' => array('rule' => 'notEmpty'),
		'body' => array('rule' => 'notEmpty')
	);

	var $belongsTo = array('Account'=>
		array(
			'className' => 'Account',
			'foreignKey' => 'user_id'
	));


	const EMAIL = $_ENV['BLM_GOOGLE_EMAIL'];
	const PASSWD = $_ENV['BLM_GOOGLE_PASS'];
	const SERVICE = 'youtube';
	const DEVKEY = $_ENV['BLM_GOOGLE_DEVKEY'];

	//キーワードからamazonで本を検索
	function getAmazonSearchResult($a_key) {

		//amazonAPIの利用
		require_once("../Lib/amazon_api.php");
		$type = "ItemSearch";
		$request = make_Request($type, $a_key);
		$request   = canonical_Str( $request );
		$signature = get_Signature( $request, AWS_HOST );
		$request = "http://".AWS_HOST."/onca/xml?".$request."&Signature=". $signature;
		
		//検索結果の取得
		$response = file_get_contents($request);
		$parsed_xml = simplexml_load_string($response);

		return $parsed_xml;

	}

	//ISBNからamazonの本情報を取得
	function getAmazonData($ISBN) {

		//amazonAPIの利用
		$type = "ItemLookup";
		require_once("../Lib/amazon_api.php");
		$request = make_Request($type, $ISBN);
		$request   = canonical_Str( $request );
		$signature = get_Signature( $request, AWS_HOST );
		$request = "http://".AWS_HOST."/onca/xml?".$request."&Signature=". $signature;

		//検索結果の取得
		$response = file_get_contents($request);
		$parsed_xml = simplexml_load_string($response);
		
		return $parsed_xml;
	}

	//本にひもづいているPlaylistを取得。
	function getPlaylist($a_key) {
		$params = array(
			'conditions' => array('amazon_id' => $a_key),
		);
		$playlists = $this->find('all',$params);

		return $playlists;
	}

	//ユーザーがつくったPlaylistをすべて取得。
	function getUserPlaylist($user_id) {
		$params = array(
			'conditions' => array('user_id' => $user_id),
		);
		$playlists = $this->find('all',$params);

		return $playlists;
	}

	//ユーザーのfavListをすべて取得。
	function getFavlists($favIDs) {
		$IDs = array();
		foreach($favIDs as $favID) {
			$IDs[] = $favID['Favlist']['pl_id'];
		}

		$params = array(
			'conditions'=>array(
				'Playlist.id'=>$IDs,
			),
		);
		$playlists = $this->find('all',$params);

		return $playlists;
	}

	//PlaylistのIDからYoutubeの情報を取得。
	function getYtPlaylist($pl_code) {
		$request = "http://gdata.youtube.com/feeds/api/playlists/".$pl_code;
		$response = file_get_contents($request);
		$xml = simplexml_load_string($response);
		return $xml;
	}


	//本にひもづくplaylistの一覧から、それぞれの動画情報を取得。
	function getVideos($playlists) {
		$videos = array();
		foreach($playlists as $pl) {
			//プレイリストの集合体を取得。
			$pl = $pl['Playlist'];
			$yt_playlist = $this->getYtPlaylist($pl['youtube_id']);
			$yt_playlist = $yt_playlist->entry;
			
			//各プレイリストのビデオ情報を配列に格納。
			$i = 0;
			foreach($yt_playlist as $v) {
				$videos[$pl['id']][$i] = $v;
				$i++;
			}
		}
		return $videos;
	}


	//キーワードからYoutubeの動画を検索。
	function searchYtVideos($y_key) {
		$request = "http://gdata.youtube.com/feeds/api/videos?vq=".$y_key;
		$response = file_get_contents($request);
		$xml = simplexml_load_string($response);
		$yt_videos = $xml->entry;

		return $yt_videos;
	}


	//キーワードからYoutubeの再生リストを検索。
	function searchYtPlaylists($y_key) {
		$request = "http://gdata.youtube.com/feeds/api/playlists/snippets?v=2&q=".$y_key;
		$response = file_get_contents($request);
		$xml = simplexml_load_string($response);
		$yt_playlists = $xml->entry;

		return $yt_playlists;
	}


	//再生リストの最初の動画のサムネイルを取得。
	function getThumImage($pl_code, $size=0) {
		$xml = $this->getYtPlaylist($pl_code);
		$v_url = $xml->entry->link[0]->attributes()->href;
		$v_id = substr($v_url,31,11);
		$thum_src = "http://img.youtube.com/vi/".$v_id."/".$size.".jpg";

		return $thum_src;
	}


	//YouTubeに再生リストを登録。
	function createYTPlayList($data, $video_data) {
		$cl = new Zend_Gdata_ClientLogin();
		$client = $cl->getHttpClient(self::EMAIL, self::PASSWD, self::SERVICE);
		$yt = new Zend_Gdata_YouTube($client, null, null, self::DEVKEY);

		//空の再生リストを登録
		$newPlaylist = $yt->newPlaylistListEntry();
		$newPlaylist->description = $yt->newDescription()->setText($data['caption']);
		$newPlaylist->title = $yt->newTitle()->setText($data['list_title']);
		
		$postLocation = 'http://gdata.youtube.com/feeds/users/default/playlists';
		try {
		  $postedEntry = $yt->insertEntry($newPlaylist, $postLocation);
		} catch (Zend_Gdata_App_Exception $e) {
		  echo $e->getMessage();
		}
		$pl_link = $postedEntry->getLink();
		$y_key = substr($pl_link[1]->getHref(), -32);

		//再生リストに動画を追加。
		$postUrl = "http://gdata.youtube.com/feeds/api/playlists/".$y_key;
		foreach($video_data as $v){
			$videoEntryToAdd = $yt->getVideoEntry($v);
			$newPlaylistListEntry = $yt->newPlaylistListEntry($videoEntryToAdd->getDOM());
			try {
			  $yt->insertEntry($newPlaylistListEntry, $postUrl);
			} catch (Zend_App_Exception $e) {
			  print $e->getMessage();
			}
		}
		return $y_key;
	}


	//投稿数の多いbookDJを取得
	function getTopDJs($num) {
		$fields = array();
		App::import("Model", "Account");
		$account = new Account();
		$schema = $account->schema();
		foreach ($schema as $field => $properties) {
		  $fields[] = "{$account->alias}.{$field}";
		}
		$params = array(
			'fields' => array_merge($fields, array('count(Playlist.id) as EntryCount')),
			'group' => array('Account.id'),
	        'order' => array('EntryCount' => 'desc'),
			'limit' => $num,
		);
		return $this->find("all", $params);
	}


	//ユーザー別最新PlayListの取得
	function getNewList($num) {
		$sql = " SELECT *";
		$sql .= " FROM playlists as Playlist INNER JOIN accounts as Account ON Playlist.user_id = Account.id";
		$sql .= " WHERE Playlist.created IN (SELECT MAX(Playlist.created) FROM playlists as Playlist GROUP BY Playlist.user_id)";
		$sql .= " GROUP BY Playlist.user_id";
		$sql .= " ORDER BY Playlist.created DESC";
		$sql .= " LIMIT ".$num.";";

		return $this->query($sql);
	}

	//再生リストの中の各動画のＩＤを取得
	function getVideoID($xml) {
		$videoIDs = array();
		$num = count($xml->entry);
		for($i=0;$i<$num;$i++){
			$v_url = $xml->entry[$i]->link[0]->attributes()->href;
			$videoIDs[$i] = substr($v_url,31,11);
		}

		return $videoIDs;
	}

	//YouTubeの再生リストを更新
	function clearYTPlayList($y_key) {
		$cl = new Zend_Gdata_ClientLogin();
		$client = $cl->getHttpClient(self::EMAIL, self::PASSWD, self::SERVICE);
		$yt = new Zend_Gdata_YouTube($client, null, null, self::DEVKEY);

		//なかの動画を削除（むりやり）
		for($i=0;$i<3;$i++){
			if($yt->getPlaylistListEntry($y_key)){
				$currentList = $yt->getPlaylistListEntry($y_key);
				$newPlaylistListEntry = $yt->newPlaylistListEntry($currentList->getDOM());
				$newPlaylistListEntry->delete();
			}
		}
	}

	//YouTubeの再生リストに動画を追加
	function addVideoToYTPlaylist($y_key, $video_id){
		$cl = new Zend_Gdata_ClientLogin();
		$client = $cl->getHttpClient(self::EMAIL, self::PASSWD, self::SERVICE);
		$yt = new Zend_Gdata_YouTube($client, null, null, self::DEVKEY);
		$postUrl = "http://gdata.youtube.com/feeds/api/playlists/".$y_key;

		$videoEntryToAdd = $yt->getVideoEntry($video_id);
		$newPlaylistListEntry = $yt->newPlaylistListEntry($videoEntryToAdd->getDOM());
		$yt->insertEntry($newPlaylistListEntry, $postUrl);
	}

	//登録されてる本情報を取得
	function getAllBooks(){
		$params = array(
			'group' => array('Playlist.amazon_id'),
	        'order' => array('Playlist.created' => 'desc'),
			'fields' => array('Playlist.id', 'Playlist.amazon_id', 'Playlist.amazon_img', 'created')
		);
		return $this->find('all',$params);
	}

}
?>

<?php
class Favlist extends AppModel
{
	var $name = 'Favlist';


	//お気に入りに追加。
	function addFavs($user_id, $pl_id) {
		//すでに追加してるかどうかチェック
		$params = array(
			'conditions' => array(
				'user_id' => $user_id,
				'pl_id' => $pl_id,
			),
		);
		$fav_flg = $this->find('count',$params);

		//まだなかったら追加。
		if(!$fav_flg) {
			$this->create();
			$data['Favlist']['user_id'] = $user_id;
			$data['Favlist']['pl_id'] = $pl_id;

			$this->save($data);
		}
	}
	
	//お気に入りしてるかどうかチェック。
	function chkFavs($user_id, $pl_id) {
		$params = array(
			'conditions' => array(
				'user_id' => $user_id,
				'pl_id' => $pl_id,
			),
		);
		$fav_flg = $this->find('count',$params);
		
		return $fav_flg;
	}
	
	
	//お気に入りに追加しているリストの一覧取得
	function getFavIDs($user_id) {

		$params = array(
			'conditions' => array('user_id' => $user_id),
		);
		$favIDs = $this->find('all',$params);
		
		return $favIDs;
	}
	
}
?>
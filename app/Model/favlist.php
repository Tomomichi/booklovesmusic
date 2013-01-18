<?php
class Favlist extends AppModel
{
	var $name = 'Favlist';


	//���C�ɓ���ɒǉ��B
	function addFavs($user_id, $pl_id) {
		//���łɒǉ����Ă邩�ǂ����`�F�b�N
		$params = array(
			'conditions' => array(
				'user_id' => $user_id,
				'pl_id' => $pl_id,
			),
		);
		$fav_flg = $this->find('count',$params);

		//�܂��Ȃ�������ǉ��B
		if(!$fav_flg) {
			$this->create();
			$data['Favlist']['user_id'] = $user_id;
			$data['Favlist']['pl_id'] = $pl_id;

			$this->save($data);
		}
	}
	
	//���C�ɓ��肵�Ă邩�ǂ����`�F�b�N�B
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
	
	
	//���C�ɓ���ɒǉ����Ă��郊�X�g�̈ꗗ�擾
	function getFavIDs($user_id) {

		$params = array(
			'conditions' => array('user_id' => $user_id),
		);
		$favIDs = $this->find('all',$params);
		
		return $favIDs;
	}
	
}
?>
<?php
class Account extends AppModel
{
	var $name = 'Account';
	
	var $hasMany = array('Playlist'=>array(
		'className' => 'Playlist',
		'foreignKey' => 'user_id',
	));

	//�v���C���X�g�쐬�҂̖��O���擾
	function getDJs($playlists) {
		$DJs = array();
		foreach($playlists as $pl) {
			$user_id = $pl['Playlist']['user_id'];
			
			$params = array(
				'conditions' => array('id' => $user_id),
			);
			$user = $this->find('first',$params);
			$DJs[$pl['Playlist']['id']] = $user['Account']['name'];
		}
		
		return $DJs;
		
	}
	
	
}
?>
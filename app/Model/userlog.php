<?php
class Userlog extends AppModel
{
	var $name = 'Userlog';

	var $belongsTo = array('Account'=>
		array(
			'className' => 'Account',
			'foreignKey' => 'user_id'
	));

	
	function getLoginUser($cookie) {
			$conditions = array('cookie'=> $cookie);
			$order = array('Userlog.created');
			$uid_log = $this->find('first', array( 'conditions'=>$conditions, 'order'=>$order) );
			$user = $uid_log;
			
			return $user;
	}
	
}
?>
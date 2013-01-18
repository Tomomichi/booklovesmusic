<?php
	require_once("../webroot/hybridauth/Hybrid/Auth.php" );

	class AccountsController extends AppController {
		var $name = 'Accounts';
		var $uses = array('Account', 'Userlog', 'Playlist');	//複数データベース使用の設定
		
		
		function index(){
			//まだとくに処理なし
		}
		
		function login($sns){
			$config = "hybridauth/config.php";
			try{
				$hybridauth = new Hybrid_Auth($config);

				//hybridauthで認証
				$auth = $hybridauth->authenticate( $sns );
				$user_profile = $auth->getUserProfile();
				$this->set("user_profile", $user_profile);
				
				//SNSのユーザー情報を配列にセット
				$data = array();
				$data['name'] = $user_profile->displayName;
				$data['provider'] = $sns;
				$data['provider_uid'] = $user_profile->identifier;
				
				//DBに登録されてなければ、ユーザー情報を登録
				if($data){
					//同じSNS・SNS_IDのデータがないか検索
					$reg_flg = $this->Account->find("count", array(
						'conditions'=>array(
							'Account.provider_uid' => $data['provider_uid'], 
							'Account.provider' => $sns
					) ));
					
					//なければ登録
					if(!$reg_flg){
						$this->Account->save($data);
					}
				}

				//DBからユーザー情報を取得
				$user = $this->Account->find("first", array(
					'conditions'=>array(
						'Account.provider_uid' => $data['provider_uid'], 
						'Account.provider' => $sns
				) ));
				
				//クッキーになければ、セッションIDを付与してクッキーに保存
				if(!$this->Cookie->read('blm_id')){
					$this->Cookie->path = '/';
					
					$SID = $this->Session->id();
					$this->Cookie->write('blm_id', $SID, false);
				
					//SessionIDとユーザーIDの照合をDBに保存
					$this->Userlog->save(array(
						'cookie'=> $SID,
						'user_id'=> $user['Account']['id'],
					));
				}
			}
			catch( Exception $e ){
				echo "Ooophs, we got an error: " . $e->getMessage();
			}
			
			//ユーザー情報の読み込み
			$user['Account']['name'] = "ゲスト"; //初期設定
			if($this->Cookie->read('blm_id')){
				$cookie = $this->Cookie->read('blm_id');
				$conditions = array('cookie'=> $cookie);
				$order = array('Userlog.created');
				$uid_log = $this->Userlog->find('first', array( 'conditions'=>$conditions, 'order'=>$order) );
				$uid = $uid_log['Userlog']['user_id'];
				$user = $this->Account->find('first', array('conditions'=> array('id'=>$uid)) );
			}

			$this->set('user', $user);
			$this->render("/Pages/home");
		}
		
		
		function logout(){
			//DBからcookie照合情報を消去
			$SID = $_COOKIE['PHPSESSID'];
			$deleteCondition = array("cookie"=>$SID);
			$this->Userlog->deleteAll($deleteCondition);
				
			//cookieの消去
			$this->Cookie->delete('blm_id');
			
			//user情報の設定
			$user['Account']['name'] = "ゲスト"; //初期設定
			$this->set('user', $user);
			
			$this->render("/Pages/home");
		}

	}

?>

<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    private $_id, $permissions;
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate1()
	{
		$users=array(
			// username => password
			'demo'=>'demo',
			'admin'=>'admin',
		);
		if(!isset($users[$this->username]))
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif($users[$this->username]!==$this->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
			$this->errorCode=self::ERROR_NONE;
		return !$this->errorCode;
	}
        
//        public function authenticate()
//	{
//		$users=array(
//			// username => password
//			'democtp'=>'democtp',
//			//'admin'=>'admin',
//		);
//		if(!isset($users[$this->username]) || !isset($users[$this->password])){
//                    $this->errorCode=self::ERROR_USERNAME_INVALID;
//                }
//		elseif($users[$this->username]!==$this->password)
//			$this->errorCode=self::ERROR_PASSWORD_INVALID;
//		else
//			$this->errorCode=self::ERROR_NONE;
//                
//		return !$this->errorCode;
//	}
        
        
        public function authenticate()
        {
            Yii::import('application.models.SystemAdmin');
            
            $user = SystemAdmin::model()->findByAttributes(array('username'=>$this->username));
                        
            if($user===null || $user->password !== $user->hashPassword($this->password))
                $this->errorCode=self::ERROR_PASSWORD_INVALID;
            else
            {
                //echo 'authenticated'; exit;
                $this->_id = $user->admin_id;
                
                $this->setState('roleid', $user->role_id);
                $this->setState('permissions', json_decode($user->role->permissions,true)); //true makes it decode as array
                
                $this->errorCode=self::ERROR_NONE;
            }
            return !$this->errorCode;
        }

        /*
         * this method is called automatically when u call 
         * Yii::app()->user->id
         */
        public function getId()
        {
            return $this->_id;
        }
        
       
        
//        /*
//         * this method is called automatically when u call 
//         * Yii::app()->user->name
//         */
//        public function getName(){
//            
//        }
}
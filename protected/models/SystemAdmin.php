<?php

/**
 * This is the model class for table "{{system_admin}}".
 *
 * The followings are the available columns in table '{{system_admin}}':
 * @property integer $admin_id
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property string $firstname
 * @property string $middlename
 * @property string $lastname
 * @property string $gender
 * @property string $email
 * @property string $phone
 * @property integer $role_id
 * @property integer $state_id
 * @property integer $lga_id
 *
 * The followings are the available model relations:
 * @property Roles $role
 * @property TrainingModule[] $trainingModules
 */
class SystemAdmin extends CActiveRecord
{
    //public $permissionArray = array();

    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{system_admin}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, firstname, lastname, gender, email, phone, role_id, state_id, lga_id', 'required'),
			array('role_id, state_id, lga_id', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>30),
                        //array('username', 'unique', 'message'=>'User name already in use.'),
			array('password', 'length', 'max'=>32),
			array('firstname, middlename, lastname', 'length', 'max'=>35),
			array('gender', 'length', 'max'=>6),
			array('email', 'length', 'max'=>255),
			array('phone', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('admin_id, username, password, salt, firstname, middlename, lastname, gender, email, phone, role_id, state_id, lga_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'role' => array(self::BELONGS_TO, 'Roles', 'role_id'),
			'trainingModules' => array(self::HAS_MANY, 'TrainingModule', 'admin_id'),
                        'state' => array(self::BELONGS_TO, 'State', 'state_id'),
                        'lga' => array(self::BELONGS_TO, 'Lga', 'lga_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'admin_id' => 'Admin',
			'username' => 'User Name',
			'password' => 'Password',
			'firstname' => 'First Name',
			'middlename' => 'Middle Name',
			'lastname' => 'Last Name',
			'gender' => 'Gender',
			'email' => 'Email',
			'phone' => 'Phone',
			'role_id' => 'Role',
                        'salt' => 'Salt',
                        'state_id' => 'State',
                        'lga_id' => 'LGA',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('admin_id',$this->admin_id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('firstname',$this->firstname,true);
		$criteria->compare('middlename',$this->middlename,true);
		$criteria->compare('lastname',$this->lastname,true);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('role_id',$this->role_id);
                $criteria->compare('state_id',$this->role_id);
                $criteria->compare('lga_id',$this->role_id);
                $criteria->compare('salt',$this->salt);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

        public static function getRoleLevel($userid){
            return SystemAdmin::model()->findByPk($userid)->role->level;
        }

        /**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SystemAdmin the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        
        public function validatePassword($password)
        {
            return $this->hasPassword($password) == $this->password;
        }

        public function hashPassword($password)
        {    
            return md5(md5($password).$this->salt);
        }
        
        public function beforeSave()
        {
            if(parent::beforeSave()){
                if($this->getIsNewRecord()) 
                {
                    if(!isset($this->salt)) {
                        $this->salt = substr(uniqid(rand()), -6);
                    }
                    if (isset($this->password)) {
                        $this->password = $this->hashPassword($this->password);
                    }
                }
            }      
            
           return parent::beforeSave();
            
        }
}
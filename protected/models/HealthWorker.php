<?php

/**
 * This is the model class for table "{{health_worker}}".
 *
 * The followings are the available columns in table '{{health_worker}}':
 * @property string $worker_id
 * @property string $remote_id
 * @property string $title
 * @property string $username
 * @property string $password
 * @property string $firstname
 * @property string $middlename
 * @property string $lastname
 * @property string $gender
 * @property string $email
 * @property string $phone
 * @property string $qualification
 * @property integer $supervisor
 * @property integer $cadre_id
 * @property integer $facility_id
 * @property integer $facility_id
 * @property string $date_created
 * @property integer $channel_id
 *
 * The followings are the available model relations:
 * @property HealthFacility $facility
 * @property JobAid[] $cthxJobAs
 * @property TestSession[] $testSessions
 * @property TrainingSession[] $trainingSessions
 */
class HealthWorker extends CActiveRecord
{
    const BATCH_FILE_MIME = 'application/vnd.ms-excel';
    const BATCH_FILE_SIZE = 25600000;
    //const BATCH_FILE

    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{health_worker}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('remote_id, firstname, lastname, gender, phone, supervisor, cadre_id, facility_id', 'required'),
			array('remote_id, supervisor, cadre_id, facility_id, channel_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>20),
			array('username', 'length', 'max'=>30),
			array('password', 'length', 'max'=>32),
			array('firstname, middlename, lastname', 'length', 'max'=>35),
			array('gender', 'length', 'max'=>6),
			array('email', 'length', 'max'=>255),
			array('phone', 'length', 'max'=>15),
			array('qualification', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('worker_id, title, username, password, firstname, middlename, lastname, gender, email, phone, qualification, supervisor, cadre_id, facility_id, date_created, channel_id', 'safe', 'on'=>'search'),
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
			'facility' => array(self::BELONGS_TO, 'HealthFacility', 'facility_id'),
                        'cadre' => array(self::BELONGS_TO, 'Cadre', 'cadre_id'),
			'cthxJobAs' => array(self::MANY_MANY, 'JobAid', '{{job_aid_worker}}(worker_id, aid_id)'),
			'testSessions' => array(self::HAS_MANY, 'TestSession', 'worker_id'),
			'trainingSessions' => array(self::HAS_MANY, 'TrainingSession', 'worker_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'worker_id' => 'Worker',
                        'remote_id' => 'Remote ID',
			'title' => 'Title',
			'username' => 'Username',
			'password' => 'Password',
			'firstname' => 'Firstname',
			'middlename' => 'Middlename',
			'lastname' => 'Lastname',
			'gender' => 'Gender',
			'email' => 'Email',
			'phone' => 'Phone',
			'qualification' => 'Qualification',
			'supervisor' => 'Supervisor',
			'cadre_id' => 'Cadre',
			'facility_id' => 'Facility',
                        'date_created' => 'Date Created',
                        'channel_id' => ' Channel'
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

		$criteria->compare('worker_id',$this->worker_id,true);
                $criteria->compare('remote_id',$this->remote_id,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('firstname',$this->firstname,true);
		$criteria->compare('middlename',$this->middlename,true);
		$criteria->compare('lastname',$this->lastname,true);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('qualification',$this->qualification,true);
		$criteria->compare('supervisor',$this->supervisor);
		$criteria->compare('cadre_id',$this->cadre_id);
		$criteria->compare('facility_id',$this->facility_id);
                $criteria->compare('date_created',$this->date_created);
                $criteria->compare('channel_id',$this->channel_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HealthWorker the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

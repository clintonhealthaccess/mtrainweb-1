<?php

/**
 * This is the model class for table "{{health_facility}}".
 *
 * The followings are the available columns in table '{{health_facility}}':
 * @property integer $facility_id
 * @property string $facility_address
 * @property string $facility_name
 * @property integer $state_id
 * @property integer $lga_id
 *
 * The followings are the available model relations:
 * @property Lga $lga
 * @property State $state
 * @property HealthWorker[] $healthWorkers
 * @property TestSession[] $testSessions
 * @property TrainingSession[] $trainingSessions
 */
class HealthFacility extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{health_facility}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('facility_address, facility_name, state_id, lga_id', 'required'),
			array('state_id, lga_id', 'numerical', 'integerOnly'=>true),
                        array('state_id, lga_id', 'numerical', 'min'=>1, 'tooSmall'=>'Please select {attribute}'),
			array('facility_address', 'length', 'max'=>255),
			array('facility_name', 'length', 'max'=>150),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('facility_id, facility_address, facility_name, state_id, lga_id', 'safe', 'on'=>'search'),
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
			'lga' => array(self::BELONGS_TO, 'Lga', 'lga_id'),
			'state' => array(self::BELONGS_TO, 'State', 'state_id'),
			'healthWorkers' => array(self::HAS_MANY, 'HealthWorker', 'facility_id'),
			'testSessions' => array(self::HAS_MANY, 'TestSession', 'facility_id'),
			'trainingSessions' => array(self::HAS_MANY, 'TrainingSession', 'facility_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'facility_id' => 'Facility ID.',
			'facility_address' => 'Facility Address',
			'facility_name' => 'Facility Name',
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

		$criteria->compare('facility_id',$this->facility_id);
		$criteria->compare('facility_address',$this->facility_address,true);
		$criteria->compare('facility_name',$this->facility_name,true);
		$criteria->compare('state_id',$this->state_id);
		$criteria->compare('lga_id',$this->lga_id);
                
                //$criteria->with = array('state');

                //$f = Yii::app()->request->getParam('state_id' , null);
                //var_dump($f); exit;
                
                //if(!empty($f))
                  // $criteria->compare('state.state_id' , $f , true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HealthFacility the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

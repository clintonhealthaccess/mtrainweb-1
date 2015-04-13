<?php

/**
 * This is the model class for table "{{training_session}}".
 *
 * The followings are the available columns in table '{{training_session}}':
 * @property string $session_id
 * @property string $start_time
 * @property string $end_time
 * @property integer $status
 * @property integer $session_type
 * @property integer $material_type
 * @property string $worker_id
 * @property string $module_id
 * @property string $training_id
 * @property integer $facility_id
 * @property integer $channel_id
 *
 * The followings are the available model relations:
 * @property Training $training
 * @property HealthWorker $worker
 * @property TrainingModule $module
 * @property HealthFacility $facility
 */
class TrainingSession extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{training_session}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('session_type, material_type, worker_id, module_id, training_id, facility_id, channel_id', 'required'),
			array('status, session_type, material_type, facility_id', 'numerical', 'integerOnly'=>true),
			array('worker_id, module_id, training_id', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('session_id, start_time, end_time, status, session_type, material_type, worker_id, module_id, training_id, facility_id, channel_id', 'safe', 'on'=>'search'),
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
			'training' => array(self::BELONGS_TO, 'Training', 'training_id'),
			'worker' => array(self::BELONGS_TO, 'HealthWorker', 'worker_id'),
			'module' => array(self::BELONGS_TO, 'TrainingModule', 'module_id'),
			'facility' => array(self::BELONGS_TO, 'HealthFacility', 'facility_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'session_id' => 'Session',
			'start_time' => 'Start Time',
			'end_time' => 'End Time',
			'status' => 'Status',
			'session_type' => 'Session Type',
			'material_type' => 'Material Type',
			'worker_id' => 'Worker',
			'module_id' => 'Module',
			'training_id' => 'Training',
			'facility_id' => 'Facility',
                        'channel_id' => 'Channel',
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

		$criteria->compare('session_id',$this->session_id,true);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('session_type',$this->session_type);
		$criteria->compare('material_type',$this->material_type);
		$criteria->compare('worker_id',$this->worker_id,true);
		$criteria->compare('module_id',$this->module_id,true);
		$criteria->compare('training_id',$this->training_id,true);
		$criteria->compare('facility_id',$this->facility_id);
                $criteria->compare('channel_id',$this->channel_id);
                
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TrainingSession the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

<?php

/**
 * This is the model class for table "{{test_session}}".
 *
 * The followings are the available columns in table '{{test_session}}':
 * @property string $session_id
 * @property string $date_taken
 * @property integer $score
 * @property integer $total
 * @property integer $improvement
 * @property string $test_id
 * @property string $worker_id
 * @property integer $facility_id
 * @property integer $channel_id
 *
 * The followings are the available model relations:
 * @property Test $test
 * @property HealthWorker $worker
 * @property HealthFacility $facility
 */
class TestSession extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{test_session}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date_taken, score, total, improvement, test_id, worker_id, facility_id, channel_id', 'required'),
			array('score, total, improvement, facility_id', 'numerical', 'integerOnly'=>true),
			array('test_id, worker_id', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('session_id, date_taken, score, total, improvement, test_id, worker_id, facility_id, channel_id', 'safe', 'on'=>'search'),
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
			'test' => array(self::BELONGS_TO, 'Test', 'test_id'),
			'worker' => array(self::BELONGS_TO, 'HealthWorker', 'worker_id'),
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
			'date_taken' => 'Date Taken',
			'score' => 'Score',
			'total' => 'Total',
                        'improvement' => 'Improvement',
			'test_id' => 'Test',
			'worker_id' => 'Worker',
			'facility_id' => 'Facility',
                        'channel_id' => 'Channel'
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
		$criteria->compare('date_taken',$this->date_taken,true);
		$criteria->compare('score',$this->score);
		$criteria->compare('total',$this->total);
		$criteria->compare('test_id',$this->test_id,true);
		$criteria->compare('worker_id',$this->worker_id,true);
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
	 * @return TestSession the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
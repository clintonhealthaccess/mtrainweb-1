<?php

/**
 * This is the model class for table "{{aids_session}}".
 *
 * The followings are the available columns in table '{{aids_session}}':
 * @property string $session_id
 * @property string $date_viewed
 * @property integer $aid_id
 * @property integer $aid_type
 * @property integer $facility_id
 * @property integer $channel_id
 * 
 * The followings are the available model relations:
 * @property HealthFacility $facility
 */
class AidsSession extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{aids_session}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date_viewed, aid_id, aid_type, facility_id, channel_id', 'required'),
			array('aid_id, aid_type, facility_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('session_id, date_viewed, aid_id, aid_type, facility_id, channel_id', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'session_id' => 'Session',
			'date_viewed' => 'Date Viewed',
			'aid_id' => 'Aid',
			'aid_type' => 'Aid Type',
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
		$criteria->compare('date_viewed',$this->date_viewed,true);
		$criteria->compare('aid_id',$this->aid_id);
		$criteria->compare('aid_type',$this->aid_type);
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
	 * @return AidsSession the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
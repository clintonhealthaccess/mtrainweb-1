<?php

/**
 * This is the model class for table "{{lga}}".
 *
 * The followings are the available columns in table '{{lga}}':
 * @property integer $lga_id
 * @property string $lga_name
 * @property integer $state_id
 *
 * The followings are the available model relations:
 * @property HealthFacility[] $healthFacilities
 * @property State $state
 */
class Lga extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{lga}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lga_name, state_id', 'required'),
			array('state_id', 'numerical', 'integerOnly'=>true),
			array('lga_name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lga_id, lga_name, state_id', 'safe', 'on'=>'search'),
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
			'healthFacilities' => array(self::HAS_MANY, 'HealthFacility', 'lga_id'),
			'state' => array(self::BELONGS_TO, 'State', 'state_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'lga_id' => 'Lga',
			'lga_name' => 'Lga Name',
			'state_id' => 'State',
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

		$criteria->compare('lga_id',$this->lga_id);
		$criteria->compare('lga_name',$this->lga_name,true);
		$criteria->compare('state_id',$this->state_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Lga the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

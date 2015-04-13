<?php

/**
 * This is the model class for table "{{app_modules}}".
 *
 * The followings are the available columns in table '{{app_modules}}':
 * @property integer $id
 * @property string $module_name
 * @property integer $weight
 *
 * The followings are the available model relations:
 * @property Actions[] $actions
 */
class AppModules extends CActiveRecord
{
    const USER_MODULE_NAME = 'Users';
    const REPORTS_MODULE_NAME = 'Reports';
    const SETTINGS_MODULE_NAME = 'Settings';
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{app_modules}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('module_name, weight', 'required'),
			array('weight', 'numerical', 'integerOnly'=>true),
			array('module_name', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, module_name, weight', 'safe', 'on'=>'search'),
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
			'actions' => array(self::HAS_MANY, 'Actions', 'app_module_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'module_name' => 'Module Name',
			'weight' => 'Weight',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('module_name',$this->module_name,true);
		$criteria->compare('weight',$this->weight);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AppModules the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

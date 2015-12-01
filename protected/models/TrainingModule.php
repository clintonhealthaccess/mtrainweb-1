<?php

/**
 * This is the model class for table "{{training_module}}".
 *
 * The followings are the available columns in table '{{training_module}}':
 * @property string $module_id
 * @property string $module_title
 * * @property string $module_abbr
 * @property string $guide_file
 * @property string $remarks
 * @property integer $admin_id
 * @property integer $category_id
 *
 * The followings are the available model relations:
 * @property JobAid[] $cthxJobAs
 * @property Test[] $tests
 * @property SystemAdmin $admin
 * @property Category $category
 * @property TrainingSession[] $trainingSessions
 * @property Training[] $cthxTrainings
 */
class TrainingModule extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{training_module}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('module_title, category_id', 'required'),
			array('category_id', 'numerical', 'integerOnly'=>true),
			array('module_title, guide_file', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('module_id, module_title, guide_file, remarks, category_id', 'safe', 'on'=>'search'),
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
			'JobAsMM' => array(self::MANY_MANY, 'JobAid', '{{jobaid_to_module}}(module_id, aid_id)'),
			'tests' => array(self::HAS_MANY, 'Test', 'module_id'),
			'admin' => array(self::BELONGS_TO, 'SystemAdmin', 'admin_id'),
			'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
			'trainingSessions' => array(self::HAS_MANY, 'TrainingSession', 'module_id'),
			'trainingsMM' => array(self::MANY_MANY, 'Training', '{{training_to_module}}(module_id, training_id)'),
                        'trainings' => array(self::HAS_MANY, 'Training', 'training_id'),
                        //'jobaids' => array(self::HAS_MANY, 'JobAid', 'aid_id'),
                    
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'module_id' => 'Module',
			'module_title' => 'Module Title',
                        'module_abbr' => 'Module Abbr.',
			'guide_file' => 'Guide File',
			'remarks' => 'Remarks',
			'admin_id' => 'Admin',
			'category_id' => 'Category',
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

		$criteria->compare('module_id',$this->module_id,true);
		$criteria->compare('module_title',$this->module_title,true);
                $criteria->compare('module_abbr',$this->module_abbr,true);
		$criteria->compare('guide_file',$this->guide_file,true);
		$criteria->compare('remarks',$this->remarks,true);
		$criteria->compare('admin_id',$this->admin_id);
		$criteria->compare('category_id',$this->category_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TrainingModule the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

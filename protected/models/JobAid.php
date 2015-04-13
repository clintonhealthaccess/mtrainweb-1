<?php

/**
 * This is the model class for table "{{job_aid}}".
 *
 * The followings are the available columns in table '{{job_aid}}':
 * @property string $aid_id
 * @property string $title
 * @property string $aid_file
 *
 * The followings are the available model relations:
 * @property HealthWorker[] $cthxHealthWorkers
 * @property TrainingModule[] $cthxTrainingModules
 */
class JobAid extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{job_aid}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, aid_file', 'required'),
			array('title, aid_file', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('aid_id, title, aid_file', 'safe', 'on'=>'search'),
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
			'cthxHealthWorkers' => array(self::MANY_MANY, 'HealthWorker', '{{job_aid_worker}}(aid_id, worker_id)'),
			'cthxTrainingModules' => array(self::MANY_MANY, 'TrainingModule', '{{jobaid_to_module}}(aid_id, module_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'aid_id' => 'Aid',
			'title' => 'Title',
			'aid_file' => 'Aid File',
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

		$criteria->compare('aid_id',$this->aid_id,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('aid_file',$this->aid_file,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return JobAid the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

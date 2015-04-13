<?php

/**
 * This is the model class for table "{{training}}".
 *
 * The followings are the available columns in table '{{training}}':
 * @property string $training_id
 * @property string $training_title
 * @property string $video_file
 *
 * The followings are the available model relations:
 * @property TrainingSession[] $trainingSessions
 * @property TrainingModule[] $cthxTrainingModules
 */
class Training extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{training}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('training_title, video_file', 'required'),
			array('training_title, video_file', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('training_id, training_title, video_file', 'safe', 'on'=>'search'),
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
			'trainingSessions' => array(self::HAS_MANY, 'TrainingSession', 'training_id'),
			'cthxTrainingModules' => array(self::MANY_MANY, 'TrainingModule', '{{training_to_module}}(training_id, module_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'training_id' => 'Training',
			'training_title' => 'Training Title',
			'video_file' => 'Video File',
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

		$criteria->compare('training_id',$this->training_id,true);
		$criteria->compare('training_title',$this->training_title,true);
		$criteria->compare('video_file',$this->video_file,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Training the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

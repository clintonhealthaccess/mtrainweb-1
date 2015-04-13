<?php

/**
 * This is the model class for table "{{test_question}}".
 *
 * The followings are the available columns in table '{{test_question}}':
 * @property string $question_id
 * @property string $question
 * @property string $options
 * @property string $correct_option
 * @property string $test_id
 * @property string $tiptext
 *
 * The followings are the available model relations:
 * @property Test $test
 */
class TestQuestion extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{test_question}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('question, options, correct_option, test_id, tiptext', 'required'),
			array('correct_option', 'length', 'max'=>255),
			array('test_id', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('question_id, question, options, correct_option, test_id, tiptext', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'question_id' => 'Question',
			'question' => 'Question',
			'options' => 'Options',
			'correct_option' => 'Correct Option',
			'test_id' => 'Test',
			'tiptext' => 'Tiptext',
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

		$criteria->compare('question_id',$this->question_id,true);
		$criteria->compare('question',$this->question,true);
		$criteria->compare('options',$this->options,true);
		$criteria->compare('correct_option',$this->correct_option,true);
		$criteria->compare('test_id',$this->test_id,true);
		$criteria->compare('tiptext',$this->tiptext,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TestQuestion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

<?php

/**
 * This is the model class for table "{{cadre}}".
 *
 * The followings are the available columns in table '{{cadre}}':
 * @property integer $cadre_id
 * @property string $cadre_title
 *
 * The followings are the available model relations:
 * @property HealthWorker[] $healthWorkers
 */
class Cadre extends CActiveRecord
{
        const NURSES = 1,
              MIDWIFE = 2,
              CHEW = 3;
        
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{cadre}}';
	}

        /**
        * @return array validation rules for model attributes.
        */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cadre_title', 'required'),
			array('cadre_title', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cadre_id, cadre_title', 'safe', 'on'=>'search'),
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
			'healthWorkers' => array(self::HAS_MANY, 'HealthWorker', 'cadre_id'),
		);
	}
        
        public function scopes(){
            return array(
                'sortvyidasc' => array('order'=>'id')
            );
        }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cadre_id' => 'Cadre ID',
			'cadre_title' => 'Cadre Name',
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

		$criteria->compare('cadre_id',$this->cadre_id);
		$criteria->compare('cadre_title',$this->cadre_title,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Cadre the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

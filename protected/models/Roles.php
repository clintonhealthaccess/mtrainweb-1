<?php

/**
 * This is the model class for table "{{roles}}".
 *
 * The followings are the available columns in table '{{roles}}':
 * @property integer $role_id
 * @property string $role_title
 * @property string $level
 * @property string $permissions
 *
 * The followings are the available model relations:
 * @property SystemAdmin[] $systemAdmins
 */
class Roles extends CActiveRecord
{
    const ADMIN_ROLE_ID = 4;
    
    const LG_LEVEL = 1;
    const STATE_LEVEL = 2;
    const FMOH_LEVEL = 3;
    const ADMIN_LEVEL = 4;
    

    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{roles}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('role_title, level, permissions', 'required'),
			array('role_title', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('role_id, role_title, level, permissions', 'safe', 'on'=>'search'),
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
			'systemAdmins' => array(self::HAS_MANY, 'SystemAdmin', 'role_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'role_id' => 'Role',
			'role_title' => 'Role Title',
			'permissions' => 'Permissions',
                        'level' => 'Level',
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

		$criteria->compare('role_id',$this->role_id);
		$criteria->compare('role_title',$this->role_title,true);
		$criteria->compare('permissions',$this->permissions,true);
                $criteria->compare('level',$this->level,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Roles the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        
}
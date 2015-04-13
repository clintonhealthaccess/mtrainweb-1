<?php

/**
 * This is the model class for table "{{actions}}".
 *
 * The followings are the available columns in table '{{actions}}':
 * @property integer $action_id
 * @property string $action_name
 * @property string $label
 * @property string $module
 */
class Actions extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{actions}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('action_name, label, module', 'required'),
			array('action_name, module', 'length', 'max'=>50),
			array('label', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('action_id, action_name, label, module', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'action_id' => 'Action',
			'action_name' => 'Action Name',
			'label' => 'Label',
			'module' => 'Module',
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

		$criteria->compare('action_id',$this->action_id);
		$criteria->compare('action_name',$this->action_name,true);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('module',$this->module,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Actions the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        
        /*
         * This method creates an array of actions for a module
         * Args: name of the module as saved in the database. 
         * The argument is also made a constant in the AppModules class
         */
        public static function getPermittedActions($moduleName){
            $permittedActions = array();
            $dataReader = Yii::app()->db->createCommand()
                            ->select('action_name')
                            ->from('cthx_actions')
                            ->where('app_module_id=:id', array(':id'=>AppModules::model()->findByAttributes(array('module_name'=>$moduleName))->id))
                            ->query();
            
            foreach($dataReader as $row){ 
                $permittedActions[] = $row['action_name'];
            }
            
            return $permittedActions;
        }
        
        public static function getDisplay($action){
            //return in_array($action, $permissions) ? 'block' : 'hidden';
            return Yii::app()->user->checkAccess(array($action)) ? 'block' : 'hidden';
        }
}

<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class Content extends CModel
{
	private $trainings;
	private $aids;
	private $assessments;
        private $ivr;

        public function __construct(){
            $this->setTrainingContent();
            $this->setAidsContent();
            $this->setIVRContent();
        }


        /**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
	
	}
        
        /**
	 * Declares attribute labels.
	 */
	public function attributeNames()
	{
	
	}

	private function setTrainingContent(){
            $training = array('categories' => array(
                    'rh' => array(
                                 'name'=> 'Reproductive Health',
                                 'modules' => array(
                                     'fp' => array(
                                         'name'=> 'Family Planning',
                                         'topics' => array(
                                             'Equipment and materials for contraceptive implants',
                                             'Follow-up Counselling',
                                             'Insertion/Removal of Contraceptive Implant Capsules 1',
                                             'Insertion/Removal of Contraceptive Implant Capsules 2',
                                             'Insertion/Removal of Contraceptive Implant Capsules 3',
                                             'Insertion/Removal of Contraceptive Implant Capsules 4',
                                         )
                                      ),
                                 )
                        ),
                        
                    'mh' => array(
                                'name' => 'Maternal Health',
                                'modules' => array(
                                    'mcpd' => array(
                                        'name' => 'Management of Complications in Pregnancy & Delivery',
                                        'topics' => array(
                                            'Bleeding after childbirth(postpartum haemorrhage',
                                            'Pre-eclampsia and Eclampsia',
                                            'Bleeding in early pregnancy (Unsafe Abortion)',
                                            'Bleeding in Late Pregnancy',
                                            'Admitting a woman in Labour and Partograph',
                                            'Social support in Labour',
                                            'Prolonged obstructed labour',
                                            'Other indirect causes of maternal and newborn mortality',
                                            'Prevention and Management of Sepsis',
                                        )
                                    )
                                )
                    ),
                
                
                    'nch' => array(
                                'name' => 'Newborn & Child Health',
                                'modules' => array(
                                    'mnc' => array(
                                        'name' => 'Management of Newborn Complications',
                                        'topics' => array(
                                            'Examination of the newborn baby',
                                            'Care of the newborn baby until discharge',
                                            'Neonatal Sepsis',
                                            'Communicate and Counsel',
                                            'Special situations',
                                            'Preterm birth complications',
                                        )
                                    ),
                                    'mcci' => array(
                                        'name' => 'Management of Common Childhood Illnesses',
                                        'topics' => array(
                                            'Assess and classify; Identify treatment; Treat the sick child or young infant',
                                        )
                                    )
                                )
                    ),    
                )
            );
            
            $this->trainings = $training;
        }
        
        public function getTrainingContent(){
            return $this->trainings;
        }
        
        private function setAidsContent(){
            $aids = array('categories' => array(
                    'rh' => array(
                                 'name'=> 'Reproductive Health',
                                 'modules' => array(
                                     'fp' => array(
                                         'name'=> 'Family Planning',
                                         'topics' => array(
                                             'Insertion of Jadelle Contraceptive Implants',
                                             'Removal of Jadelle Contraceptive Implants',
                                         )
                                      ),
                                 )
                        ),
                        
                    'mh' => array(
                                'name' => 'Maternal Health',
                                'modules' => array(
                                    'mcpd' => array(
                                        'name' => 'Management of Complications in Pregnancy & Delivery',
                                        'topics' => array(
                                            'Management of Postpartum Haemorrhage(PHC)',
                                            'Protocol for the Management of PPH(Hospitals)',
                                            'Application and Removal of NASG',
                                            'Care and Cleaning of the Non-Pneumatic Antishock Garment (NASG)',
                                            'Prevention of PPH using Misoprostol',
                                            'Treatment of PPH using Misoprostol',
                                            'Management of Eclampsia',
                                            'Protocol for the Management of Eclampsia(Hospitals)',
                                            'Administering Magnesium Sulphate',
                                            'Loading Dose of MgSO4',
                                            'Maintenance Dose of MgSO4',
                                        )
                                    )
                                )
                    ),
                
                
                    'nch' => array(
                                'name' => 'Newborn & Child Health',
                                'modules' => array(
                                    'mnc' => array(
                                        'name' => 'Management of Newborn Complications',
                                        'topics' => array(
                                            'Care of a Baby at Time of Birth',
                                            'Chart for Post-Natal Care of the Newborn',
                                            'Newborn Resuscitation',
                                            'Chlorhexidine Gel for Cord Care in Newborn',
                                            'Identification and Management of Preterm Complications',
                                        )
                                    ),
                                    'mcci' => array(
                                        'name' => 'Management of Common Childhood Illnesses',
                                        'topics' => array(
                                            'Pneumonia: Prevention, Diagnosis and Management',
                                            'Pneumonia: Prevention and Management for Doctors',
                                        )
                                    )
                                )
                    ),
                     
                )
            );
            
            $this->aids = $aids;
        }
        
        public function getAidsContent(){
            return $this->aids;
        }
        
        
        private function setIVRContent(){
            $ivr = array('categories' => array(
                    'rh' => array(
                                 'name'=> 'Reproductive Health',
                                 'modules' => array(
                                     'fp' => array(
                                         'name'=> 'Family Planning',
                                         'topics' => array(
                                             'Dual protection contraception against HIV transmission and unintended pregnancy',
                                             'Reproductive health',
                                             'Avoid pregnancy following unprotected intercourse',
                                         )
                                      ),
                                 )
                        ),
                        
                    'mh' => array(
                                'name' => 'Maternal Health',
                                'modules' => array(
                                    'mcpd' => array(
                                        'name' => 'Management of Complications in Pregnancy & Delivery',
                                        'topics' => array(
                                            'Prevention or treatment of postpartum hemorrhage',
                                            'Prevention and treatment of eclampsia',
                                        )
                                    )
                                )
                    ),
                
                
                    'nch' => array(
                                'name' => 'Newborn & Child Health',
                                'modules' => array(
                                    'mnc' => array(
                                        'name' => 'Management of Newborn Complications',
                                        'topics' => array(
                                            'Resuscitation of Newborn',
                                            'Neonatal sepsis, first-line, second-line',
                                            'Newborn umbilical cord care/cleansing',
                                        )
                                    ),
                                    'mcci' => array(
                                        'name' => 'Management of Common Childhood Illnesses',
                                        'topics' => array(
                                            'Treatment of Malaria',
                                            'Treatment of Diarrhea',
                                            'Treatment of simple pneumonia',
                                        )
                                    )
                                )
                    ),    
                )
            );
            
            $this->ivr = $ivr;
        }
        
        public function getIVRContent(){
            return $this->ivr;
        }
        
        
//        public static function model($className=__CLASS__)
//	{
//		return parent::model($className);
//	}
        
}
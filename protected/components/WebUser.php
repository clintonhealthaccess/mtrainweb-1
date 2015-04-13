<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WebUser
 *
 * @author Swedge
 */
class WebUser extends CWebUser{
    
    
    
    public function checkAccess($operations, $params=array())
    {
        
        if (empty($this->id)) {
            // Not identified => no rights
            return false;
        }
        
        //these two lines represent 2 ways how data can be retrieved 
        //when the variable was pushed into session with setState in the 
        //Identity (extending CUserIdentity) class
        $roleid = $this->getState("roleid");
        $permissions = Yii::app()->user->permissions;
        
        foreach ($operations as $operation){
            if(array_key_exists($operation, $permissions))
                    return true;  //one is enough
        }
        
        return false; //no operation found allowed
        
        // allow access if the operation request is the current user's role
        //return ($operation === $role);
    }
    
    
//     function setPermissions($p){
//        $this->permissions = $p;
//     }
//     
//     function getPermissions(){
//         return $this->permissions;
//     }
}

?>

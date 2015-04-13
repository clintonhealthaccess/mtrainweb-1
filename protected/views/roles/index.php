<?php
/* @var $this RolesController */
/* @var $roles Roles */
 /* @var $actions Actions */
 /* @var $appModules AppModules */
?>

<div class="row">
    <div class="col-md-12">
        
    
    <!--pagetitle-->
    <div class="row">
        <div class="col-md-10 col-md-offset-1 marginbottom20 ">
            <h3 class="arialtitlebold">Users</h3>
        </div>
    </div>
    </div>
    
    
    
    <!--form-->
    <div class="row marginbottom20">
        <div class="col-md-10 col-md-offset-1">
            <header class="containerheader"><h6>Admin Roles & Permissions</h6></header>
            <section class="container">
                <form method="POST" action="<?php echo Yii::app()->createUrl('/roles/update'); ?>">
                <article>
                    <div class="row noborder margintop20">
                    <div class="col-md-10 col-md-offset-1">
                        <?php if(Yii::app()->user->getFlash('updated')=='success'){ ?>
                                    <div class="alert alert-success" role="alert">
                                        <button type="button" class="close" data-dismiss="alert">
                                            <span aria-hidden="true">&times;</span>
                                            <span class="sr-only">Close</span>
                                        </button>
                                        <a href="#" class="alert-link">
                                            <span class="glyphicon glyphicon-ok"></span>
                                            Permissions updated successfully!
                                        </a>
                                    </div>
                       <?php } ?>
                                
                        <table class="smallerfont tableborder">
                            <tbody>
                            <tr>
                                <th>Action</th>
                                <th>Administrator</th>
                                <th>FMOH Officer</th>
                                <th>State Officer</th>
                                <th>LG Officer</th>
                            </tr>
                            
                            <?php
                                $html=''; $moduleTitle = '';
                                
                                
                                
                                foreach($appModules as $appModule){
                                    //the module header
                                    $html .= '<tr><td class="moduletitle" colspan="5"><strong>' . $appModule->module_name . '</strong></td></tr>';
                                    
                                    //get the actions for the current module ordered by their weights
                                    $actions = Actions::model()->findAll(array(
                                                    'condition' => 'app_module_id=' . $appModule->id . ' AND status=1',
                                                    'order' => 'weight'
                                                ));
                                    
                                        foreach($actions as $action){
                                            $html .= '<tr>';
                                            $html .=    '<td>' . $action->label . '</td>';
                                            foreach ($roles as $role){
                                                $rolePermissions = json_decode($role->permissions);
                                                $checkedText = ''; $disabled ='';
                                                if(is_object($rolePermissions)) {
                                                    $checkedText = property_exists($rolePermissions,$action->action_name) ? 'checked' : ''; //mind the trailing spaces
                                                    $disabled = $role->role_id==Roles::ADMIN_ROLE_ID ? 'disabled' : '';
                                                }
                                                $html .=    '<td><input name="Roles[' . $role->role_id .'][' . $action->action_name . ']" ' .
                                                            'id="' . $action->action_name . '" ' . $checkedText . " $disabled " .
                                                            'type="checkbox"/></td>';

                                            }
                                            $html .= '</tr>';
                                            
                                        }//emd of action loop
                                }//end module loop
                                
//                                for($i=0; $i<count($actions); $i++){
//                                    $action = $actions[$i];
//                                    if(strtoupper($moduleTitle) != strtoupper($action->module)){
//                                        $html .= '<tr><td class="moduletitle" colspan="5"><strong>' . $action->module . '</strong></td></tr>';
//                                        $html .= '<tr>';
//                                        $html .=    '<td>' . $action->label . '</td>';
//                                        foreach ($roles as $role){
//                                            $rolePermissions = json_decode($role->permissions);
//                                            $checkedText = ''; $disabled ='';
//                                            if(is_object($rolePermissions)) {
//                                                $checkedText = property_exists($rolePermissions,$action->action_name) ? 'checked ' : ' '; //mind the trailing spaces
//                                                $disabled = $role->role_id==Roles::ADMIN_ROLE_ID ? 'disabled' : '';
//                                            }
//                                            $html .=    '<td><input name="Roles[' . $role->role_id .'][' . $action->action_name . ']" ' .
//                                                        'id="' . $action->action_name . '" ' . $checkedText . " $disabled " .
//                                                        'type="checkbox"/></td>';
//                                            
//                                        }
//                                        $html .= '</tr>';
//                                        
//                                        $moduleTitle = $action->module;
//                                    }
//                                    else{
//                                        $html .= '<tr>';
//                                        $html .=    '<td>' . $action->label . '</td>';
//                                        foreach ($roles as $role){
//                                            $rolePermissions = json_decode($role->permissions);
//                                            $checkedText = '';
//                                            if(is_object($rolePermissions)) {
//                                                $checkedText = property_exists($rolePermissions,$action->action_name) ? 'checked ' : ' '; //mind the trailing spaces
//                                                $disabled = $role->role_id== Roles::ADMIN_ROLE_ID ? 'disabled' : '';
//                                            }
//                                            $html .=    '<td><input name="Roles[' . $role->role_id .'][' . $action->action_name . ']" ' .
//                                                        'id="' . $action->action_name . '" ' . $checkedText . " $disabled " .
//                                                        'type="checkbox"/></td>';
//                                        }
//                                        $html .= '</tr>';
//                                    }
//                                }
                                
                                echo $html;
                            ?>

                            </tbody>
                        </table>
                      </div>

                        <div class="row noborder">
                          <!--<div class="col-md-3 col-md-offset-1"></div>-->

                            <div class="col-md-2 col-md-offset-9">
                                    <!--<a href="#" id="savebtn" class="btn btn-primary  bluehover ">Save Changes</a>-->
                                    <input class="btn btn-primary  bluehover " type="submit" name="save" id="save" value="Save Changes">
                            </div>
                        </div>
                    </div>
                </article>
                </form>
            </section>
        </div>
      </div>
    
</div>


<?php
//    Yii::app()->clientScript->registerScript('helloscript',"
//        alert('hello');
//        $('#FacilityTableContainer').jtable('load');
//    ",CClientScript::POS_END);
?>



<?php
///* @var $this RolesController */
///* @var $dataProvider CActiveDataProvider */
//
//$this->breadcrumbs=array(
//	'Roles',
//);
//
//$this->menu=array(
//	array('label'=>'Create Roles', 'url'=>array('create')),
//	array('label'=>'Manage Roles', 'url'=>array('admin')),
//);
//?>

<!--<h1>Roles</h1>-->

<?php //$this->widget('zii.widgets.CListView', array(
//	'dataProvider'=>$dataProvider,
//	'itemView'=>'_view',
//)); ?>

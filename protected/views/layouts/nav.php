<div class="container header-bg-m-br noborder"> 
    
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-left ">
                <li class="marginright25"><a href="<?php echo Yii::app()->getBaseUrl(true); ?>" >Dashboard</a></li>
                <li class="marginright25"><a href="<?php echo $this->baseUrl;?>/site/content" >Content</a></li>
                <!--<li class="marginright25"><a href="#" >Assessment</a></li>
                <li class="marginright25"><a href="#" >Job Aids</a></li>
                <li class="marginright25"><a href="#" >Standing Orders</a></li>-->
                
                
                <?php 
                    
                    $modulePermittedActions = Actions::getPermittedActions(AppModules::USER_MODULE_NAME);                    
                    if( $this->user->checkAccess($modulePermittedActions) ) :
                ?>
                    <li class="dropdown marginright25">
                        <a href="#" id="usersDropdown" class="dropdown-toggle " data-toggle="dropdown">Users <b><span class="caret"></span></b></a>
                        <ul class="dropdown-menu  nomargin" role="menu" aria-labelledby="usersDropdown">
                            <?php if($this->user->checkAccess(array('upload_user_list'))): ?>
                                <!--<li><a href="<?php echo $this->baseUrl;?>/healthWorker/batchReg">Batch Registration</a></li>-->
                            <?php endif; ?>

                            <?php if($this->user->checkAccess(array('access_admin_users'))): ?>
                                <li role="menuitem" class="dropdown-submenu">
                                        <a tabindex="-1" href="#">Administrators</a>
                                          <ul class="dropdown-menu">
                                              <?php if($this->user->checkAccess(array('access_admin_users'))): ?>
                                                <li><a href="<?php echo $this->baseUrl;?>/systemAdmin">Users</a></li>
                                              <?php endif; ?>
                                              
                                              <?php if($this->user->checkAccess(array('manage_roles_permissions'))): ?>
                                                <li><a href="<?php echo $this->baseUrl;?>/roles">Roles & Permissions</a></li>
                                              <?php endif; ?>
                                          </ul>
                                </li>
                            <?php endif; ?>
                                
                        </ul>
                    </li>
                <?php endif; //end if users ?>
                
                
                <?php 
                    $modulePermittedActions = Actions::getPermittedActions(AppModules::SETTINGS_MODULE_NAME);                    
                    if( $this->user->checkAccess($modulePermittedActions) ) :
                ?> 
                    <li class="dropdown marginright25">
                        <a href="#" id="settingsDropdown" class="dropdown-toggle" data-toggle="dropdown" >Settings <b><span class="caret"></span></b></a>
                        <ul class="dropdown-menu  nomargin" role="menu" aria-labelledby="settingsDropdown">
                            <?php if($this->user->checkAccess(array('access_cadres'))): ?>
                                <li role="menuitem"><a  href="<?php echo $this->baseUrl;?>/cadre">Cadre</a></li>
                            <?php endif; ?>
                             
                             <?php if($this->user->checkAccess(array('access_facilities'))): ?>
                                <li role="menuitem"><a href="<?php echo $this->baseUrl;?>/healthFacility">Facility</a></li>
                             <?php endif; ?>
                                
                        </ul>
                    </li>
                <?php endif; //end if settings ?>
                
                
                <?php 
                    $modulePermittedActions = Actions::getPermittedActions(AppModules::REPORTS_MODULE_NAME);                    
                    if( $this->user->checkAccess($modulePermittedActions) ) :
                ?>
                        <li class="dropdown">
                            <a href="#" id="reportsDropdown" class="dropdown-toggle" data-toggle="dropdown">Reports <b><span class="caret"></span></b></a>
                            <ul class="dropdown-menu  nomargin" role="menu" aria-labelledby="reportsDropdown">
                                 <?php if($this->user->checkAccess(array('access_hcw_report'))): ?>
                                    <li role="menuitem"><a href="<?php echo $this->baseUrl;?>/HealthWorker">Health Workers</a></li>
                                 <?php endif; ?>
                                    
                                 <?php if($this->user->checkAccess(array('access_usage_report'))): ?>
                                    <li role="menuitem"><a href="<?php echo $this->baseUrl;?>/UsageMetrics">Usage Metrics</a></li>
                                 <?php endif; ?>
                                    
                                 <?php if($this->user->checkAccess(array('access_assessment_report'))): ?>
                                    <li role="menuitem"><a href="<?php echo $this->baseUrl;?>/AssessmentMetrics">Assessment Metrics</a></li>
                                 <?php endif; ?>
                                    
                                 <?php if($this->user->checkAccess(array('access_aids_report'))): ?>
                                    <li role="menuitem"><a href="<?php echo $this->baseUrl;?>/AidsSession">Job Aids & Standing Order Views</a></li>
                                 <?php endif; ?>
                                    
                            </ul>
                        </li>
                <?php endif; //end if settings ?>
                
                <li ><a href="index.php" class="smallerfont mini-signout">Sign Out</a></li>
            </ul>
        </div>
</div>
<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */
?>



<div class="container-fluid" >
<div class="row  marginbottom20 ">
  <div class="col-md-4 col-md-offset-4">
      <section class="container loginwrapper">
          <article>
          <div class="row noborder">
                  <div class="col-md-7 marginbottom15">
                  <img src="<?php echo $this->baseUrl; ?>/img/logo.png" class="img-responsive" />
                  </div>
          </div>
              <div class="row noborder">
                <div class="col-md-11  marginleft25 whiteframe">
                    
                    <!--<form class="form-horizontal padd10" id="loginform" method="post" action="login-check.php" autocomplete="on">-->
                    <?php $form=$this->beginWidget('CActiveForm', array(
                            'id'=>'login-form',
                            'enableClientValidation'=>true,
                            'clientOptions'=>array(
                                    'validateOnSubmit'=>true,
                            ),
                            'htmlOptions' => array('class'=>'form-horizontal')
                    )); ?>


                      <div class="form-group margintop10" >
                        <!--<label for="username" class="col-sm-4 control-label">Username </label>-->
                        <?php echo $form->labelEx($model,'username',array('class'=>'col-sm-4 control-label')); ?>
                        
                        <div class="col-sm-8">
                            <?php echo $form->textField($model,'username',array('class'=>'form-control','placeholder'=>"Enter Username")); ?>
                          <!--<input type="text" class="form-control" id="username" name="username" placeholder="Enter Username"/>-->
                        </div>
                        <?php //echo $form->error($model,'username'); ?>
                      </div>
                    
                      <div class="form-group">
                          <!--<label for="password" class="col-sm-3 control-label">Password</label>-->
                          <?php echo $form->labelEx($model,'password',array('class'=>'col-sm-4 control-label')); ?>
                        
		
                        <div class="col-sm-8">
                            <?php echo $form->passwordField($model,'password',array('class'=>'form-control','placeholder'=>"Enter Password")); ?>
                          <!--<input type="password" class="form-control" id="password" name="password" placeholder="Enter Password"/>-->
                        </div>
                          <?php //echo $form->error($model,'password'); ?>
                      </div>
                      <div class="form-group nomargin ">
                        <div class="col-sm-4 col-sm-offset-5 margintop10 aligncenter">
                            <input type="submit" class="btn btn-primary width70px" id="active" name ="submit" value="Login" />
                        </div>
                      </div>

                    <?php $this->endWidget(); ?>
                    <!--</form>-->
                </div>
                  
             </div><!-- .row -->
             
             <div class="row noborder <?php echo !$validated ? 'block' : 'hidden'; ?>">
                 <div class="col-md-12 text-center smallerfont">
                     <p class="error bold"><span class="glyphicon glyphicon-warning-sign"></span>&nbsp; Invalid username or password.</p>
                 </div>
             </div><!-- .row -->
             
            </article>
      </section>
  </div>
    
</div>
    
 </div><!-- .container -->
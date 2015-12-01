<?php
/* @var $this SystemAdminController */
/* @var $model SystemAdmin */
?>
<div class="row">
    <div class="col-md-12">
        
    
    <!--pagetitle-->
    <div class="row">
        <div class="col-md-10 col-md-offset-1 marginbottom20 ">
            <h3 class="arialtitlebold"><?php echo ucfirst($model->firstname) . ' ' . ucfirst($model->lastname); ?></h3>
        </div>
    </div>
    </div>
    
    
    <div class="row marginbottom20">
        <div class="col-md-5 col-md-offset-1">
            <header class="containerheader"><h6>Edit Profile</h6></header>
            <section class="container">
                <form method="POST" action="<?php echo Yii::app()->createUrl('/systemAdmin/myProfile'); ?>" 
                      onsubmit="return checkPasswordMatch('password', 'cpassword');">
                <article>
                    <div class="row noborder margintop20">
                    <div class="col-md-10 ">
                        <?php if(Yii::app()->user->getFlash('updated')=='success'){ ?>
                                    <div class="alert alert-success" role="alert">
                                        <button type="button" class="close" data-dismiss="alert">
                                            <span aria-hidden="true">&times;</span>
                                            <span class="sr-only">Close</span>
                                        </button>
                                        <a href="#" class="alert-link">
                                            <span class="glyphicon glyphicon-ok"></span>
                                            Your profile has been updated successfully!
                                        </a>
                                    </div>
                       <?php } ?>
                                
                        <!--form controls-->
                        <!--<div class="form">-->

                            <?php $form=$this->beginWidget('CActiveForm', array(
                                    'id'=>'system-admin-form',
                                    // Please note: When you enable ajax validation, make sure the corresponding
                                    // controller action is handling ajax validation correctly.
                                    // There is a call to performAjaxValidation() commented in generated controller code.
                                    // See class documentation of CActiveForm for details on this.
                                    'enableAjaxValidation'=>false,
                            )); ?>

                                    <p class="note">Fields with <span class="required">*</span> are required.</p>

                                    <?php echo $form->errorSummary($model); ?>

                                    <div class="form-group">
                                            <?php echo $form->labelEx($model,'firstname'); ?>
                                            <?php echo $form->textField($model,'firstname',array('maxlength'=>35, 'class'=>'form-control')); ?>
                                            <?php echo $form->error($model,'firstname'); ?>
                                    </div>

                                    <div class="form-group">
                                            <?php echo $form->labelEx($model,'middlename'); ?>
                                            <?php echo $form->textField($model,'middlename',array('maxlength'=>35, 'class'=>'form-control')); ?>
                                            <?php echo $form->error($model,'middlename'); ?>
                                    </div>

                                    <div class="form-group">
                                            <?php echo $form->labelEx($model,'lastname'); ?>
                                            <?php echo $form->textField($model,'lastname',array('maxlength'=>35, 'class'=>'form-control')); ?>
                                            <?php echo $form->error($model,'lastname'); ?>
                                    </div>

                                    <div class="form-group">
                                            <?php echo $form->labelEx($model,'gender'); ?>
                                            <?php echo $form->textField($model,'gender',array('maxlength'=>6, 'class'=>'form-control')); ?>
                                            <?php echo $form->error($model,'gender'); ?>
                                    </div>

                                    <div class="form-group">
                                            <?php echo $form->labelEx($model,'email'); ?>
                                            <?php echo $form->textField($model,'email',array('maxlength'=>255, 'class'=>'form-control')); ?>
                                            <?php echo $form->error($model,'email'); ?>
                                    </div>

                                    <div class="form-group">
                                            <?php echo $form->labelEx($model,'phone'); ?>
                                            <?php echo $form->textField($model,'phone',array('maxlength'=>15, 'class'=>'form-control')); ?>
                                            <?php echo $form->error($model,'phone'); ?>
                                    </div>
                                    
                                    
                                    <div class="row" style="margin:40px 0 10px; ">
                                        <div class="col-md-11" style="padding-left: 0;">
                                            <strong>Change Password <br>
                                                    <small><em>(Leave blank if you are not changing your password)</em></small>
                                            </strong>
                                        </div>
                                    </div>
                                        
                                    <div class="form-group">
                                            <?php echo $form->labelEx($model,'password'); ?>
                                            <?php echo $form->passwordField(new SystemAdmin(),'password',array('maxlength'=>32, 'id'=>'password', 'class'=>'form-control')); ?>
                                            <?php echo $form->error($model,'password'); ?>
                                    </div>

                                    <div class="form-group" style="margin-bottom: 35px;">
                                        <label for="cpassword">Confirm Password</label>
                                        <input type="password" class="form-control" id="cpassword" name="SystemAdmin[cpassword]" maxlength="32">
                                    </div>

                                    <!--<div class="row ">-->
                                            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class'=>'btn btn-primary  bluehover')); ?>
                                    <!--</div>-->

                            <?php $this->endWidget(); ?>

                        <!--</div>-->
                      </div>

                        
                    </div>
                    
                    <!--<div id="dialog" title="mTrain Report Engine">
                        <p></p>
                    </div>-->
                </article>
                </form>
            </section>
        </div>
      </div>
   
</div>
<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>

<h1>Login</h1>

<p>Please fill out the following form with your login credentials:</p>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username'); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
		<p class="hint">
			Hint: You may login with <kbd>demo</kbd>/<kbd>demo</kbd> or <kbd>admin</kbd>/<kbd>admin</kbd>.
		</p>
	</div>

	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Login'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->


div class="container-fluid  margintop100  col-md-offset-1" >
<div class="row  marginbottom20 ">
  <div class="col-md-5 col-md-offset-3">
      <section class="container borderradius7px">
          <article>
          <div class="row noborder">
                  <div class="col-md-7 marginbottom15">
                  <img src="img/logo.png" class="img-responsive" />
                  </div>
          </div>
              <div class="row noborder">
                <div class="col-md-11  marginleft25 whiteframe">
                    <form class="form-horizontal padd10" id="loginform" method="post" action="login-check.php" autocomplete="on">


                      <div class="form-group margintop10" >
                        <label for="username" class="col-sm-3 control-label">Username </label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username"/>
                        </div>
                      </div>
                      <div class="form-group">
                          <label for="password" class="col-sm-3 control-label">Password</label>
                        <div class="col-sm-9">
                          <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password"/>
                        </div>
                      </div>
                      <div class="form-group nomargin ">
                        <div class="col-sm-4 col-sm-offset-5 margintop10 aligncenter">
                            <input type="submit" class="btn btn-primary width70px" id="active" name ="submit" value="Login" />
                        </div>
                      </div>

                    </form>
                </div>
                  
             </div><!-- .row -->
             
             <div class="row noborder hideblock">
                 <div class="col-md-9 col-md-offset-3 smallerfont">
                     <p class="colorred"><span class="glyphicon glyphicon-warning-sign"></span>&nbsp; Invalid username/password, try again later.</p>
                 </div>
             </div><!-- .row -->
             
             <div class="row margintop20 noborder">
                 <div class="col-md-7 col-md-offset-4 top15 bluetextcolor smallestfont">
                     <span><strong>Copyright mTrain &COPY; 2014</strong></span>
                 </div>
            </div><!-- .row -->
            </article>
      </section>
  </div>
    
</div>
    
 </div><!-- .container -->
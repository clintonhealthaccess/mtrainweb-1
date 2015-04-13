<?php 
    //$this->baseUrl = Yii::app()->request->baseUrl;
    //$assetsPath = $baseUrl . '/pc_assets';
?>

<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title><?php echo Yii::app()->name; ?> | <?php echo CHtml::encode($this->pageTitle); ?></title>
        
        <!-- Bootstrap -->
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.css" media="screen, projection" type="text/css" />
        <!-- Native css -->
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery-ui.min.css" media="screen, projection" />
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/datepicker.css" media="screen, projection" />
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/jTable/themes/redmond/jquery-ui-1.11.2.custom.css" />
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/jtable/themes/metro/blue/jtable.min.css" media="screen, projection" />
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/datepicker.css" media="screen, projection" />
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/app.css" media="screen, projection" />
        
	<!--<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />-->
	<!--<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />-->
	
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        
        <?php Yii::app( )->clientScript->registerCoreScript( 'jquery' );?>
        
        <?php //Yii::app()->clientScript->registerScriptFile( $this->baseUrl . "/jtable/scripts/jquery-1.6.4.min.js", CClientScript::POS_HEAD ); ?>
        <?php Yii::app()->clientScript->registerScriptFile( $this->baseUrl . "/js/jquery-1.11.1.min.js", CClientScript::POS_HEAD ); ?>
        <?php Yii::app()->clientScript->registerScriptFile( $this->baseUrl . "/js/jquery-ui.min.js", CClientScript::POS_HEAD ); ?>
        <?php Yii::app()->clientScript->registerScriptFile( $this->baseUrl . "/js/bootstrap-datepicker.js", CClientScript::POS_HEAD ); ?>
        <?php Yii::app()->clientScript->registerScriptFile( $this->baseUrl . "/jtable/jquery.jtable.js", CClientScript::POS_BEGIN ); ?>
        <?php Yii::app()->clientScript->registerScriptFile( $this->baseUrl . "/js/jquery.form.js", CClientScript::POS_HEAD ); ?>
        <?php Yii::app()->clientScript->registerScriptFile( $this->baseUrl . "/js/functions.js", CClientScript::POS_HEAD ); ?>
        <?php //Yii::app()->clientScript->registerScriptFile( $this->baseUrl . "/js/facilityjtable.js", CClientScript::POS_END ); ?>
        
        
          
        
        
</head>

<body>
    
    
<div class="container-fluid">      

 <div class="row header-bg">
         <div class="col-md-3 padd15">
             <a href="<?php echo $this->absUrl; ?>">
                 <img src="<?php echo Yii::app()->request->baseUrl; ?>/img/adm-logo.png" class="img-responsive" alt="mTrain Logo" />
             </a>
         </div>
   
<!--         <div class="col-md-1 col-md-offset-6 paddtop30 alignright dropdown">
             <a href="" id="adminDropdown" role="button" data-toggle="dropdown" data-target="#" class="linkcolor smallerfont">admin<span class="caret"></span></a>
             <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="adminDropdown">
                 <li role="menuitem"><a href="<?php //echo $this->baseUrl;?>/site/logout">Sign Out</a></li>
             </ul>
         </div>-->

         <div class="col-md-1  margintop20">
            <div class="dropdown floatright">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
                    <?php echo Yii::app()->user->name; ?> &nbsp;&nbsp;<span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right whitebg" role="menu" aria-labelledby="dropdownMenu1">
                  <li role="presentation"><a role="menuitem" tabindex="0" href="<?php echo $this->baseUrl;?>/site/logout">Log Out</a></li>
                </ul>
            </div>
        </div>

 </div><!-- .row -->
 
 <!--MENU-->
 <div class="row header-bg-m-br bluebg" style="height: 50px;">           
    <?php require_once 'nav.php'; ?>
</div>
        
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

                
	<?php echo $content; ?>

                
	<div class="clear"></div>

	
        
        <div class="row noborder ">
            <div class="col-md-12 top15 text-center bold">
                <span>&copy; Copyright mTrain 2014</span>
            </div>
       </div>

</div><!-- page -->

<!--    <script src="<?php echo $this->baseUrl; ?>/js/jquery-1.11.1.min.js"></script>-->
<!--    <script src="<?php echo $this->baseUrl; ?>/js/bootstrap-datepicker.js"></script>-->
    <script src="<?php echo $this->baseUrl; ?>/js/bootstrap.min.js"></script>
    
    
</body>
</html>
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
        
        <link rel="icon" type="image/png" href="<?php echo Yii::app()->request->baseUrl; ?>/img/favicon.png" />
            
        <!-- Bootstrap -->
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.css" media="screen, projection" type="text/css" />
        <!-- Native css -->
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery-ui.min.css" media="screen, projection" />
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/datepicker.css" media="screen, projection" />
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/jtable/themes/metro/blue/jtable.min.css" media="screen, projection" />
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/datepicker.css" media="screen, projection" />
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/app.css" media="screen, projection" />
	
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        
        <?php Yii::app( )->clientScript->registerCoreScript( 'jquery' );?>
        
        <?php Yii::app()->clientScript->registerScriptFile( $this->baseUrl . "/js/jquery-1.11.1.min.js", CClientScript::POS_HEAD ); ?>
        <?php Yii::app()->clientScript->registerScriptFile( $this->baseUrl . "/js/jquery-ui.min.js", CClientScript::POS_HEAD ); ?>
        <?php Yii::app()->clientScript->registerScriptFile( $this->baseUrl . "/js/bootstrap-datepicker.js", CClientScript::POS_HEAD ); ?>
        <?php Yii::app()->clientScript->registerScriptFile( $this->baseUrl . "/jtable/jquery.jtable.js", CClientScript::POS_BEGIN ); ?>
        <?php Yii::app()->clientScript->registerScriptFile( $this->baseUrl . "/js/jquery.form.js", CClientScript::POS_HEAD ); ?>
        <?php Yii::app()->clientScript->registerScriptFile( $this->baseUrl . "/js/functions.js", CClientScript::POS_HEAD ); ?>
        
</head>

<body class="offwhiteframe">
    
    
<div class="container-fluid">      
	<?php echo $content; ?>

                
	<div class="clear"></div>

	
        
        <div class="row noborder ">
            <div class="col-md-12 text-center bold margintop10 marginbottom20">
                <span>Supported by Federal Ministry of Health, Nigeria</span>
            </div>
       </div>

</div><!-- page -->

    <script src="<?php echo $this->baseUrl; ?>/js/bootstrap.min.js"></script>
    
    
</body>
</html>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>mTrain Administration | Users</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <!-- Native css -->
    <link rel="stylesheet" href="css/app.css"/>
  </head>
  <body class="offwhiteframe">

<div class="container-fluid  margintop100  col-md-offset-1" >
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
             
             <div class="row noborder hideblock <?php if($_GET['failed'] =='true' ){ echo 'displayblock';} ?> ">
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
 
 
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
   
  </body>
</html>
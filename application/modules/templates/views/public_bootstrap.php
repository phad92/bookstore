<!DOCTYPE html>
<html lang="en" <?php if(isset($use_angularjs) && $use_angularjs == true){echo 'ng-app="myApp"';}?>>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?php echo base_url('public/bootstrap')?>/favicon.ico">

    <title>Jumbotron Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url('public/bootstrap')?>/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Animate core CSS -->
    <link href="<?php echo base_url('public/libs')?>/animate.css/animate.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="<?php echo base_url('public/libs')?>/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- Owl Slider -->
    <link href="<?php echo base_url('public/libs')?>/owl.carousel/dist/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="<?php echo base_url('public/bootstrap')?>/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url('public/assets/css')?>/jumbotron.css" rel="stylesheet">

    <!-- Custom styles for Top Nav -->
    <!-- <link href="<?php //echo base_url('public/assets/css')?>/headerandfooter.css" rel="stylesheet"> -->
    <?php if(isset($use_featherlight)):?>
    <!-- Custom styles for Feather Light -->
    <link href="<?php echo base_url('public/assets/css')?>/featherlight.min.css" type="text/css" rel="stylesheet" />
    <?php endif;?>
    <?php if(isset($use_angularjs)):?>
    <!-- Custom styles for Feather Light -->
    <script src="https://code.angularjs.org/1.4.9/angular.min.js"></script>
    <?php endif;?>
    <!-- JQUERY  -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
    <script src="<?php echo base_url('public/libs')?>/jquery/dist/jquery.min.js"></script>
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="<?php echo base_url('public/bootstrap')?>/assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="<?php echo base_url('public/bootstrap')?>/assets/js/ie-emulation-modes-warning.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
      
      .navbar-inverse{
        /* border: 2px solid red; */
        height: 15px !important;
        /* height:  */
      }
</style>
      
    </style>
  </head>

  <body>
    <!-- <div class="container-fluid patop">
      <div class="container" style="height: 110px;">
        <div class="row">
          <?php //echo Modules::run('templates/_draw_page_top')?>
        </div>
      </div>

    </div> -->
  <!-- END OF NAVIGATION BAR -->
  <!-- <nav class="navbar navbar-inverse navbar-fixed-top"> -->
   <!-- <nav class="navbar navbar-inverse remove-border-radius">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo base_url()?>">Home</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
        <?php //echo modules::run('store_book_categories/_draw_top_nav'); ?>
        </div>/.navbar-collapse -->
      <!-- </div>
    </nav> -->
  <?php echo modules::run('store_book_categories/_draw_top_nav'); ?>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <!-- <div class="container" style="min-height: 650px;margin-top: 12px !important;"> -->
      <!-- <div class="panel panel-default">
        <div class="panel-body"> -->
          <?php

          if(isset($page_content)){
            // echo nl2br($page_content);
            if(!isset($page_url)){
              $page_url = 'homepage';
            }
            echo '<div class="container" style="min-height: 650px;margin-top: 12px !important;">';
            echo Modules::run('sliders/_attempt_draw_slider');
            echo Modules::run('homepage_blocks/_draw_cta_3'); 
            
            if($page_url == ""){
              require_once('content_homepage.php');
            }else{
              echo modules::run('contactus/_draw_form');
            }
            echo '</div>';
          }elseif(isset($view_file)){
                $this->load->view($view_module.'/'.$view_file);
          }?>
        <!-- </div>
      </div> -->
      	

    <!-- </div> container -->
        <?php include('footer.php')?>
    <div class="container">
      <?php //echo Modules::run('btm_nav/_draw_btm_nav')?>
      <!-- <p>&copy; 2016 Company, Inc.</p>  -->
    </div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script>window.jQuery || document.write('<script src="<?php echo base_url('public/bootstrap')?>/assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="<?php echo base_url('public/bootstrap')?>/dist/js/bootstrap.min.js"></script>
    <!-- handlebars js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.11/handlebars.js"></script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?php echo base_url('public/bootstrap')?>/assets/js/ie10-viewport-bug-workaround.js"></script>
    <?php if(isset($use_featherlight)):?>
    <!-- Javascript for feather light -->
    <script src="<?php echo base_url('public/assets/js')?>/featherlight.min.js" type="text/javascript" charset="utf-8"></script>
      <?php endif?>
      
    <!-- Owl Carousel -->
    <script src="<?php echo base_url('public/libs')?>/owl.carousel/dist/owl.carousel.min.js" type="text/javascript" charset="utf-8"></script>
  </body>
</html>

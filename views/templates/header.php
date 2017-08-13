<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PEHMOI</title>

    <link href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">  
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/icon.png" type="image/x-icon">    
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/datatables/css/dataTables.bootstrap.css">    
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/ckeditor/ckeditor.js"></script>
    <script src="<?php echo base_url(); ?>assets/jquery/jquery-3.1.0.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.14.0/jquery.validate.min.js"></script>
    <script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>
   
    
  </head>
  <body>
  <br>
  
  <div class="container">
  <nav class="navbar navbar-inverse" style="font-size: 12px;">
    <div class="container-fluid">

        <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span> 
      </button>
          <!--a class="navbar-brand" href="<?php echo base_url(); ?>">PEHMOI</a-->
        </div>

        <div class="collapse navbar-collapse" id="myNavbar">
          
          <ul class="nav navbar-nav">
             <li><a href="<?php echo base_url(); ?>posts">News, Posts and Updates</a></li>
             <li><a href="<?php echo base_url(); ?>members">Members List</a></li>
             <?php if(($this->session->userdata('logged_in') and !($this->session->userdata('access_lvl') == "Member"))) : ?>
              <li><a href="<?php echo base_url(); ?>users">Users List</a></li>                    
            <?php endif; ?> 
             <?php if($this->session->userdata('logged_in')) : ?>
              <li><a href="<?php echo base_url(); ?>ledger">General Ledger</a></li>
              <li><a href="<?php echo base_url(); ?>cashbook">Cashbook</a></li>                      
            <?php endif; ?>

            <!--li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Separated link</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">One more separated link</a></li>
          </ul>
        </li-->

          </ul>


          <ul class="nav navbar-nav navbar-right" style="margin-right:0px">

          <?php if(!$this->session->userdata('logged_in')) : ?>
            <li><a href="<?php echo base_url(); ?>users/login"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>                      
          <?php endif; ?>
          <?php if($this->session->userdata('logged_in')) : ?>
            <li><a href="<?php echo base_url(); ?>users/logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>                      
          <?php endif; ?>
          </ul>

        </div>

      </div>
    </nav>

     <div class="row" style="font-size: 12px;">

        <div class="col-sm-8">
            <p><span class="glyphicon glyphicon-th-list"></span><?php echo anchor('categories', (' Categories'))?>

                 <?php if($this->session->userdata('logged_in')) : ?>
                    <?php if(($this->session->userdata('access_lvl') == "Super User") or ($this->session->userdata('access_lvl') == "Administrator")): ?> 
                        | <span class="glyphicon glyphicon-bookmark"></span><?php echo anchor('categories/create', (' Create Category'))?>
                    <?php endif; ?>                     
                <?php endif; ?>             
                
             <?php if($this->session->userdata('logged_in')) : ?>
                        | <span class="glyphicon glyphicon-pencil"></span><?php echo anchor('posts/create', (' Create Post'))?>
                    <?php if(($this->session->userdata('access_lvl') == "Super User") or ($this->session->userdata('access_lvl') == "Administrator")): ?>   
                        
                        | <span class="glyphicon glyphicon-plus"></span><?php echo anchor('members/register', (' Add Member'))?>
                    <?php endif; ?>            
                    <?php if($this->session->userdata('access_lvl') == "Super User"): ?>              
                        | <span class="glyphicon glyphicon-user"></span><?php echo anchor('users/register', (' Register User'))?>
                    <?php endif; ?>
                <?php endif; ?>
             </p>
        </div>

        <div class="col-sm-4">
        <p style="text-align: right;"><span class="glyphicon glyphicon-home"></span><?php echo anchor('', (' Home'))?> | <span class="glyphicon glyphicon-star"></span><?php echo anchor('about', (' About'))?> | 
     
          <span class="glyphicon glyphicon-user"></span><?php if($this->session->userdata('logged_in')) 
             {echo " Hello ".ucfirst(strtolower($this->session->userdata('username')));}
             else {echo " Hello Guest";}
          ?>
           <?php if($this->session->userdata('logged_in') and $this->session->userdata('access_lvl') == "Member") : ?>
              <?php echo " | ".anchor('users', (' My Account'))?>
           <?php endif; ?></p>
        </div>

    </div> 
    
    

      <!-- Flash messages -->
      <div id="alert">
        <?php if($this->session->flashdata('user_registered')): ?>
          <?php echo '<p class="alert alert-success">'.$this->session->flashdata('user_registered').'</p>'; ?>
        <?php endif; ?>

        <?php if($this->session->flashdata('post_created')): ?>
          <?php echo '<p class="alert alert-success">'.$this->session->flashdata('post_created').'</p>'; ?>
        <?php endif; ?>

        <?php if($this->session->flashdata('post_updated')): ?>
          <?php echo '<p class="alert alert-success">'.$this->session->flashdata('post_updated').'</p>'; ?>
        <?php endif; ?>

        <?php if($this->session->flashdata('category_created')): ?>
          <?php echo '<p class="alert alert-success">'.$this->session->flashdata('category_created').'</p>'; ?>
        <?php endif; ?>

        <?php if($this->session->flashdata('post_deleted')): ?>
          <?php echo '<p class="alert alert-success">'.$this->session->flashdata('post_deleted').'</p>'; ?>
        <?php endif; ?>

        <?php if($this->session->flashdata('login_failed')): ?>
          <?php echo '<p class="alert alert-danger">'.$this->session->flashdata('login_failed').'</p>'; ?>
        <?php endif; ?>

        <?php if($this->session->flashdata('user_loggedin')): ?>
          <?php echo '<p class="alert alert-success">'.$this->session->flashdata('user_loggedin').'</p>'; ?>
        <?php endif; ?>
          
         <?php if($this->session->flashdata('user_loggedout')): ?>
          <?php echo '<p class="alert alert-success">'.$this->session->flashdata('user_loggedout').'</p>'; ?>
        <?php endif; ?>
          
        <?php if($this->session->flashdata('category_deleted')): ?>
          <?php echo '<p class="alert alert-success">'.$this->session->flashdata('category_deleted').'</p>'; ?>
        <?php endif; ?>

        <?php if($this->session->flashdata('comment_deleted')): ?>
          <?php echo '<p class="alert alert-success">'.$this->session->flashdata('comment_deleted').'</p>'; ?>
        <?php endif; ?>

        <?php if($this->session->flashdata('comment_posted')): ?>
          <?php echo '<p class="alert alert-success">'.$this->session->flashdata('comment_posted').'</p>'; ?>
        <?php endif; ?>

        <?php if($this->session->flashdata('member_added')): ?>
          <?php echo '<p class="alert alert-success">'.$this->session->flashdata('member_added').'</p>'; ?>
        <?php endif; ?>

        <?php if($this->session->flashdata('member_deleted')): ?>
          <?php echo '<p class="alert alert-success">'.$this->session->flashdata('member_deleted').'</p>'; ?>
        <?php endif; ?>

        <?php if($this->session->flashdata('member_updated')): ?>
          <?php echo '<p class="alert alert-success">'.$this->session->flashdata('member_updated').'</p>'; ?>
        <?php endif; ?>

        <?php if($this->session->flashdata('member_registered')): ?>
          <?php echo '<p class="alert alert-success">'.$this->session->flashdata('member_registered').'</p>'; ?>
        <?php endif; ?>

        <?php if($this->session->flashdata('user_updated')): ?>
          <?php echo '<p class="alert alert-success">'.$this->session->flashdata('user_updated').'</p>'; ?>
        <?php endif; ?>

        <?php if($this->session->flashdata('user_deleted')): ?>
          <?php echo '<p class="alert alert-success">'.$this->session->flashdata('user_deleted').'</p>'; ?>
        <?php endif; ?>

        <?php if($this->session->flashdata('entry_added')): ?>
          <?php echo '<p class="alert alert-success">'.$this->session->flashdata('entry_added').'</p>'; ?>
        <?php endif; ?>
      </div>

      <script type="text/javascript">
       setTimeout(function() {
            $("#alert").fadeOut('fast');
            }, 3000);
      </script>
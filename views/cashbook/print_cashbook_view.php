<!DOCTYPE html>
<html>
<head>

<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Cashbook</title>
  <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/icon.png" type="image/x-icon">  
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/export/site-examples.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/export/jquery.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/export/buttons.dataTables.min.css">
	<link href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<style type="text/css" class="init">
	</style>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/datatables/css/dataTables.bootstrap.css"> 
<script src="<?php echo base_url(); ?>assets/export/site.js"></script>	
<script src="<?php echo base_url(); ?>assets/export/jquery-1.12.4.js"></script>
<script src="<?php echo base_url(); ?>assets/export/dataTables.buttons.min.js"></script>
 <script src="<?php echo base_url(); ?>assets/export/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/export/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/export/buttons.flash.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/export/jszip.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/export/pdfmake.min.jss"></script>
    <script src="<?php echo base_url(); ?>assets/export/vfs_fonts.js"></script>
    <script src="<?php echo base_url(); ?>assets/export/buttons.html5.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/export/buttons.print.min.js"></script>	
	<script src="<?php echo base_url(); ?>assets/bootstrap.min.js"></script>

	
	<script type="text/javascript" class="init">
		$(document).ready(function() {
			$('#table').DataTable( {
				"dom": 'Bfrtip',
				"buttons": [
					'copy', 'csv', 'excel', 'print',
				],
                //"tableTools": {
        //"sSwfPath" : "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls.swf ",  // set swf path
    //},

				 "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('exportcashbook/ajax_list')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],
			} );
			
			
			
		} );
	</script>
</head>

<body>
<br><br>
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
                    <?php if(($this->session->userdata('access_lvl') == "Super User") or ($this->session->userdata('access_lvl') == "Administrator")): ?>   
                        | <span class="glyphicon glyphicon-pencil"></span><?php echo anchor('posts/create', (' Create Post'))?>
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

    <br>
    <img src="<?php echo base_url(); ?>assets/images/pioneerLogo200x45.png" alt="" />
    <h4><strong>PIONEER ENTREPRENEURS OF HILLTOP MARKET ORG. INC.</strong></h4>
    <h4>General Ledger</h4>
    <br />

<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size: 12px;">
        <thead>
                <tr>
                   <th>Date</th>
                    <th>Title</th>
                    <th>Details</th>
                    <th>Amount</th>
                    <th>Last edit by</th>
                   
                    
                </tr>
            </thead>
            <tbody>
            </tbody>

            <tfoot>
            <tr>
                 <th>Date</th>
                    <th>Title</th>
                    <th>Details</th>
                    <th>Amount</th>
                    <th>Last edit by</th>
            </tr>
            </tfoot>
           
    </table>
	<div align="center">
                  <a href="<?php echo base_url();?>members" class="btn btn-danger">Back</a>
                </div>
    
	
	</div>
    
</body>

</html>
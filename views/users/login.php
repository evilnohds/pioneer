<?php echo form_open('users/login'); ?>
	<div class="row" style="margin-top: 120px;">
		<div class="col-md-4 col-md-offset-4">	
			<img src="<?php echo base_url(); ?>assets/images/pioneerLogo200x45.png" alt="" style="display: block;margin: auto;"/>	
			<h3 class="text-center"><?php echo $title; ?></h3>
			<div class="form-group">			
				<input type="text" name="username" class="form-control" placeholder="Enter Username" required autofocus>
			</div>
			<div class="form-group">
				<input type="password" name="password" class="form-control" placeholder="Enter Password" required autofocus>
			</div>
			<button type="submit" class="btn btn-primary btn-block">Login</button>
		</div>
	</div>
<?php echo form_close(); ?>
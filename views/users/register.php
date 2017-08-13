<div class="row">
		<div class="col-md-4 col-md-offset-4">
<?php echo validation_errors(); ?>

<?php echo form_open('users/register'); ?>
			<h4 class="text-center"><?= $title; ?></h4>
			<div class="form-group">
				<label>Name</label>
				<input type="text" class="form-control" name="name" placeholder="Name">
			</div>
			<div class="form-group">
			  <label for="sel1">Access Level</label>
			  <select class="form-control" name="access_lvl" id="sel1">
			    <option>Member</option>
			    <option>Administrator</option>
			    <option>Super User</option>
			  </select>
			</div>
			<div class="form-group">
				<label>Email</label>
				<input type="email" class="form-control" name="email" placeholder="Email">
			</div>
			<div class="form-group">
				<label>Username</label>
				<input type="text" class="form-control" name="username" placeholder="Username">
			</div>
			<div class="form-group">
				<label>Stall No. (if applicable)</label>
				<input type="text" class="form-control" name="stall_no" placeholder="Stall No.">
			</div>
			<div class="form-group">
				<label>Password</label>
				<input type="password" class="form-control" name="password" placeholder="Password">
			</div>
			<div class="form-group">
				<label>Confirm Password</label>
				<input type="password" class="form-control" name="password2" placeholder="Confirm Password">
			</div>
			<button type="submit" class="btn btn-primary btn-block">Submit</button>
		</div>
	</div>
<?php echo form_close(); ?>

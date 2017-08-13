
<div class="row">
		<div class="col-md-4 col-md-offset-4">
<?php echo validation_errors(); ?>

<?php echo form_open('members/register'); ?>
			<h4 class="text-center"><?= $title; ?></h4>
			<div class="form-group">
                            <label>Stall No.</label>
                            <input name="member_stallno" placeholder="Stall No." class="form-control" type="text">
                        </div>

                        <div class="form-group">
                            <label>First Name</label>
                            <input name="member_first_name" placeholder="First Name" class="form-control" type="text">
                        </div>

                        <div class="form-group">
                            <label>Surname</label>
                            <input name="member_last_name" placeholder="Last Name" class="form-control" type="text">
                        </div>

                        <div class="form-group">
                            <label">Section</label>
                            <select name="member_section" class="form-control">
                                    <option value="">--Select Section--</option>
                                    <option value="Groceries">Groceries and Dry Goods</option>
                                    <option value="Wagwagan">Wagwagan</option>
                                    <option value="Fruits">Fruits</option>
                                    <option value="Vegetables">Vegetables</option>
                                    <option value="Fish and Meat">Fish and Meat</option>
                                </select>
                        </div>

                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="member_address" placeholder="Address" class="form-control"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Age</label>
                            <input name="member_age" placeholder="Age" class="form-control" type="number" min="15" max="100">
                        </div>

                        <div class="form-group">
                            <label>Gender</label>
                            <select name="member_sex" class="form-control">
                                    <option value="">--Select Gender--</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Civil Status</label>
                            <select name="member_cstatus" class="form-control">
                                    <option value="">--Select Status--</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Living Common Law">Living Common Law</option>
                                    <option value="Widowed">Widowed</option>
                                    <option value="Separated">Separated</option>
                                    <option value="Divorced">Divorced</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Date of Birth</label>
                            <input name="member_birthdate" placeholder="yyyy-mm-dd" class="form-control datepicker" type="text">
                        </div>

                        <div class="form-group">
                            <label>Contact No.</label>
                            <input name="member_contactno" placeholder="Mobile or Home phone" class="form-control" type="text">
                        </div>

			<button type="submit" class="btn btn-primary btn-block">Submit</button>
		</div>
	</div>

    <script>
    $(document).ready(function(){
      var date_input=$('input[name="member_birthdate"]'); //our date input has the name "date"
      var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
      var options={
        format: 'yy-mm-dd',
        container: container,
        todayHighlight: true,
        autoclose: true,
      };
      date_input.datepicker(options);
    })
</script>

<?php echo form_close(); ?>


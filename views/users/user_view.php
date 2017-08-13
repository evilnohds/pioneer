
<br>
    <img src="<?php echo base_url(); ?>assets/images/pioneerLogo200x45.png" alt="" />
    <h4><strong>PIONEER ENTREPRENEURS OF HILLTOP MARKET ORG. INC.</strong></h4>
    <h4>Users</h4>
    <br />
    <?php if($this->session->userdata('logged_in')) : ?>
        <?php if($this->session->userdata('access_lvl') == "Super User"): ?>
            <button class="btn btn-success" onclick="add_person()"><i class="glyphicon glyphicon-plus"></i> Add User</button>
        <?php endif; ?>
    <?php endif; ?>
        <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
        <br />
        <br />
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size: 12px;">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Stall No.</th>
                    <th>Access Level</th>
                    <th>Username</th>
                    <th>Email</th> 
                   <th>Action</th>
                    
                    
                </tr>
            </thead>
            <tbody>
            </tbody>

            <tfoot>
            <tr>
               <th>Name</th>
                    <th>Stall No.</th>
                    <th>Access Level</th>
                    <th>Username</th>
                    <th>Email</th>                
                    <th>Action</th>
            </tr>
            </tfoot>
        </table>

        
        <!--div align="center">
                <form method="post" action="<?php echo base_url(); ?>users/action">
                    <input type="submit" name="export" class="btn btn-warning" value="Export Users List PDF" />
                </form>
                <form method="post" action="<?php echo base_url(); ?>users/word">
                    <input type="submit" name="export" class="btn btn-danger" value="Export Users List Word" />
                </form>
            </div-->
            <?php if($this->session->userdata('logged_in') and !($this->session->userdata('access_lvl') == "Member")): ?>
                <br><br>
                <div align="center">
                  <a href="<?php echo base_url();?>users/word" class="btn btn-warning">Export Full List in Word</a>&nbsp;&nbsp;
                  <a href="<?php echo base_url();?>users/excel" class="btn btn-danger">Export Full List in Excel</a>&nbsp;&nbsp;
                  <a href="<?php echo base_url();?>exportusers" class="btn btn-success">Export Options</a>
                </div>
            <?php endif; ?>
            
            <br><br>
    </div>



<script src="<?php echo base_url('assets/jquery/jquery-3.1.0.min.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>


<script type="text/javascript">

var save_method; //for save method string
var table;

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('users/ajax_list')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],

    });

    //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,  
    });

    //set input/textarea/select event when change value, remove class error and remove text help block 
    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });

});



function add_person()
{
    save_method = 'add';
    $('#form2')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form2').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add a User'); // Set Title to Bootstrap modal title
}

function disable_person(id)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('users/ajax_disable')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_table();
                alert('Delete data successful');
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}

function edit_person(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('users/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
            $('[name="name"]').val(data.name);
            $('[name="stall_no"]').val(data.stall_no);
            $('[name="access_lvl"]').val(data.access_lvl);
            $('[name="email"]').val(data.email);
            $('[name="username"]').val(data.username);
            $('[name="register_date"]').datepicker('update',data.register_date);
            $('[name="password"]').val(data.password);            
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit User'); // Set title to Bootstrap modal title
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function edit_my_person(id)
{
    save_method = 'update';
    $('#form3')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('users/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
            $('[name="name"]').val(data.name);
            $('[name="email"]').val(data.email);
            $('[name="username"]').val(data.username);
            $('[name="register_date"]').datepicker('update',data.register_date);
            $('[name="password"]').val(data.password);            
            $('#modal_form3').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('My Account'); // Set title to Bootstrap modal title
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('users/ajax_add')?>";
        $.ajax({
        url : url,
        type: "POST",
        data: $('#form2').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form2').modal('hide');
                
                reload_table();
                alert('Save data successful');
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 
            alert('Save data successful');
        }
    });
    } else {
        url = "<?php echo site_url('users/ajax_update')?>";
        $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form').modal('hide');
                
                reload_table();
                alert('Update data successful');
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 
            

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    });
    }   // ajax adding data to database
    
}

function save_my_account()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    url = "<?php echo site_url('users/ajax_update_my_account')?>";
        $.ajax({
        url : url,
        type: "POST",
        data: $('#form3').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form3').modal('hide');
                
                reload_table();
                alert('Update data successful');
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 
            

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    }); 
}

function delete_person(id)
{
    if(confirm('Are you sure to delete to this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('users/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                
                reload_table();
                alert('Delete data successful');
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}

</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Users Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">

                        <div class="form-group">
                            <label class="control-label col-md-3">Name</label>
                            <div class="col-md-9">
                                <input name="name" placeholder="Full Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Stall No.</label>
                            <div class="col-md-9">
                                <input name="stall_no" placeholder="Stall No." class="form-control" type="number" min="1" max="999">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-3">Access Level</label>
                            <div class="col-md-9">
                                <select name="access_lvl" class="form-control">
                                    <option value="">--Select Access Level--</option>
                                    <option value="Super User">Super User</option>
                                    <option value="Administrator">Administrator</option>
                                    <option value="Member">Member</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Email</label>
                            <div class="col-md-9">
                                <input name="email" placeholder="Email" class="form-control" type="email">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Username</label>
                            <div class="col-md-9">
                                <input name="username" placeholder="Username" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                                <label class="control-label col-md-3">Change Password?</label>
                                <div class="col-md-9">
                                    <select name="changepw" class="form-control">
                                        <option value="False">No</option>
                                        <option value="True">Yes</option>
                                    </select>
                                </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Password</label>
                            <div class="col-md-9">
                                <input name="password" placeholder="Password" class="form-control" type="password">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Confirm Password</label>
                            <div class="col-md-9">
                                <input name="cpassword" placeholder="Confirm Password" class="form-control" type="password">
                                <span class="help-block"></span>
                            </div>
                        </div>                        

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
<div class="modal fade" id="modal_form2" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Users Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form2" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">

                        <div class="form-group">
                            <label class="control-label col-md-3">Name</label>
                            <div class="col-md-9">
                                <input name="name" placeholder="Full Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Stall No.</label>
                            <div class="col-md-9">
                                <input name="stall_no" placeholder="Stall No." class="form-control" type="number" min="1" max="999">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Access Level</label>
                            <div class="col-md-9">
                                <select name="access_lvl" class="form-control">
                                    <option value="">--Select Access Level--</option>
                                    <option value="Super User">Super User</option>
                                    <option value="Administrator">Administrator</option>
                                    <option value="Member">Member</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Email</label>
                            <div class="col-md-9">
                                <input name="email" placeholder="Email" class="form-control" type="email">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Username</label>
                            <div class="col-md-9">
                                <input name="username" placeholder="Username" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        
                        <div class="form-group">
                            <label class="control-label col-md-3">Password</label>
                            <div class="col-md-9">
                                <input name="password" placeholder="Password" class="form-control" type="password">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Confirm Password</label>
                            <div class="col-md-9">
                                <input name="cpassword" placeholder="Confirm Password" class="form-control" type="password">
                                <span class="help-block"></span>
                            </div>
                        </div>                        

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
<div class="modal fade" id="modal_form3" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">My Account</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form3" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">

                        <div class="form-group">
                            <label class="control-label col-md-3">Name</label>
                            <div class="col-md-9">
                                <input name="name" placeholder="Full Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Email</label>
                            <div class="col-md-9">
                                <input name="email" placeholder="Email" class="form-control" type="email">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Username</label>
                            <div class="col-md-9">
                                <input name="username" placeholder="Username" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                                <label class="control-label col-md-3">Change Password?</label>
                                <div class="col-md-9">
                                    <select name="changepw" class="form-control">
                                        <option value="False">No</option>
                                        <option value="True">Yes</option>
                                    </select>
                                </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Password</label>
                            <div class="col-md-9">
                                <input name="password" placeholder="Password" class="form-control" type="password">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Confirm Password</label>
                            <div class="col-md-9">
                                <input name="cpassword" placeholder="Confirm Password" class="form-control" type="password">
                                <span class="help-block"></span>
                            </div>
                        </div>                        

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save_my_account()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</body>
</html>
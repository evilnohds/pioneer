<br>
    <img src="<?php echo base_url(); ?>assets/images/pioneerLogo200x45.png" alt="" />
    <h4><strong>PIONEER ENTREPRENEURS OF HILLTOP MARKET ORG. INC.</strong></h4>
    <h4>Members</h4>
    <br />
    <?php if($this->session->userdata('logged_in')) : ?>
        <?php if(!($this->session->userdata('access_lvl') == "Member")): ?>
            <button class="btn btn-success" onclick="add_person()"><i class="glyphicon glyphicon-plus"></i> Add member</button>
        <?php endif; ?>
        <?php if($this->session->userdata('access_lvl') == "Member"): ?>
            <button class="btn btn-success" onclick="edit_my_data()"><i class="glyphicon glyphicon-tag"></i> Edit My Data</button>
        <?php endif; ?>
    <?php endif; ?>
        <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
        <br />
        <br />
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size: 12px;">
            <thead>
                <tr>
                    <th>Stall No.</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Section</th>
                    <?php if($this->session->userdata('logged_in')) : ?>
                        <th>Address</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Civil Status</th>
                        <th>Date of Birth</th>
                    <?php endif; ?>
                    
                    <th>Contact No.</th>
                    <?php if($this->session->userdata('logged_in')) : ?>
                    <?php if($this->session->userdata('access_lvl') == "Super User" or $this->session->userdata('access_lvl') == "Administrator") : ?>
                        <th>Action</th>
                    <?php endif; ?>
                    <?php endif; ?>
                    
                </tr>
            </thead>
            <tbody>
            </tbody>

            <tfoot>
            <tr>
                <th>Stall No.</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Section</th>
                   <?php if($this->session->userdata('logged_in')) : ?>
                        <th>Address</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Civil Status</th>
                        <th>Date of Birth</th>
                    <?php endif; ?>
                    <th>Contact No.</th>
                <?php if($this->session->userdata('logged_in')) : ?>
                    <?php if($this->session->userdata('access_lvl') == "Super User" or $this->session->userdata('access_lvl') == "Administrator") : ?>
                    <th>Action</th>
                <?php endif; ?>
                <?php endif; ?>
            </tr>
            </tfoot>
        </table>

          <?php if($this->session->userdata('logged_in') and !($this->session->userdata('access_lvl') == "Member")): ?>
                <br><br>
                <div align="center">
                  <a href="<?php echo base_url();?>members/word" class="btn btn-warning">Export as Word</a>&nbsp;&nbsp;
                  <a href="<?php echo base_url();?>members/excel" class="btn btn-danger">Export as Excel</a>&nbsp;&nbsp;
                  <a href="<?php echo base_url();?>exportmembers" class="btn btn-success">Print Options</a>
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
            "url": "<?php echo site_url('members/ajax_list')?>",
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
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add a Member'); // Set Title to Bootstrap modal title
}

function edit_person(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('members/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="member_id"]').val(data.member_id);
            $('[name="member_stallno"]').val(data.member_stallno);
            $('[name="member_last_name"]').val(data.member_last_name);
            $('[name="member_first_name"]').val(data.member_first_name);
            $('[name="member_section"]').val(data.member_section);
            $('[name="member_address"]').val(data.member_address);
            $('[name="member_age"]').val(data.member_age);
            $('[name="member_sex"]').val(data.member_sex);
            $('[name="member_cstatus"]').val(data.member_cstatus);
            $('[name="member_birthdate"]').datepicker('update',data.member_birthdate);
            $('[name="member_contactno"]').val(data.member_contactno);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Member'); // Set title to Bootstrap modal title
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function edit_my_data()
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('members/ajax_edit_my_data/')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="member_id"]').val(data.member_id);
            $('[name="member_stallno"]').val(data.member_stallno);
            $('[name="member_last_name"]').val(data.member_last_name);
            $('[name="member_first_name"]').val(data.member_first_name);
            $('[name="member_section"]').val(data.member_section);
            $('[name="member_address"]').val(data.member_address);
            $('[name="member_age"]').val(data.member_age);
            $('[name="member_sex"]').val(data.member_sex);
            $('[name="member_cstatus"]').val(data.member_cstatus);
            $('[name="member_birthdate"]').datepicker('update',data.member_birthdate);
            $('[name="member_contactno"]').val(data.member_contactno);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Member'); // Set title to Bootstrap modal title
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
        url = "<?php echo site_url('members/ajax_add')?>";
    } else {
        url = "<?php echo site_url('members/ajax_update')?>";
    }

    // ajax adding data to database
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
                alert('Save/update data successful');
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
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('members/ajax_delete')?>/"+id,
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

function disable_person(id)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('members/ajax_disable')?>/"+id,
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
                <h3 class="modal-title">Members Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="member_id"/> 
                    <div class="form-body">

                        <div class="form-group">
                            <label class="control-label col-md-3">Stall No.</label>
                            <div class="col-md-9">
                                <input name="member_stallno" placeholder="Stall No." class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">First Name</label>
                            <div class="col-md-9">
                                <input name="member_first_name" placeholder="First Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Last Name</label>
                            <div class="col-md-9">
                                <input name="member_last_name" placeholder="Last Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Section</label>
                            <div class="col-md-9">
                                <select name="member_section" class="form-control">
                                    <option value="">--Select Section--</option>
                                    <option value="Groceries">Groceries and Dry Goods</option>
                                    <option value="Wagwagan">Wagwagan</option>
                                    <option value="Fruits">Fruits</option>
                                    <option value="Vegetables">Vegetables</option>
                                    <option value="Fish and Meat">Fish and Meat</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Address</label>
                            <div class="col-md-9">
                                <textarea name="member_address" placeholder="Address" class="form-control"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Age</label>
                            <div class="col-md-9">
                                <input name="member_age" placeholder="Age" class="form-control" type="number" min="15" max="100">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Gender</label>
                            <div class="col-md-9">
                                <select name="member_sex" class="form-control">
                                    <option value="">--Select Gender--</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Civil Status</label>
                            <div class="col-md-9">
                                <select name="member_cstatus" class="form-control">
                                    <option value="">--Select Status--</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Living Common Law">Living Common Law</option>
                                    <option value="Widowed">Widowed</option>
                                    <option value="Separated">Separated</option>
                                    <option value="Divorced">Divorced</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Date of Birth</label>
                            <div class="col-md-9">
                                <input name="member_birthdate" placeholder="yyyy-mm-dd" class="form-control datepicker" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Contact No.</label>
                            <div class="col-md-9">
                                <input name="member_contactno" placeholder="Mobile or Home phone" class="form-control" type="text">
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
</body>
</html>
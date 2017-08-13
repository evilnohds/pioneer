<br>
    <img src="<?php echo base_url(); ?>assets/images/pioneerLogo200x45.png" alt="" />
    <h4><strong>PIONEER ENTREPRENEURS OF HILLTOP MARKET ORG. INC.</strong></h4>
    <h4>Cashbook</h4>
    <p>Financial entries that contains all cash receipts and payments, including bank deposits and withdrawals. Entries in the cash book are then posted and balanced in the general ledger. </p>
    <br />
    <?php if($this->session->userdata('logged_in')) : ?>
        <?php if($this->session->userdata('access_lvl') == "Super User"): ?>
            <button class="btn btn-success" onclick="add_entry()"><i class="glyphicon glyphicon-plus"></i> Add Entry</button>
        <?php endif; ?>
        
    <?php endif; ?>
        <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
        <br />
        <br />
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size: 12px;">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Title</th>
                    <th>Details</th>
                    <th>Amount</th>
                    <th>Last edit by</th>
                    
                    <?php if($this->session->userdata('logged_in') and $this->session->userdata('access_lvl') == "Super User") : ?>
                        <th>Action</th>
                    <?php endif; ?>
                    
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
                    <?php if($this->session->userdata('logged_in') and $this->session->userdata('access_lvl') == "Super User") : ?>
                        <th>Action</th>
                    <?php endif; ?>
            </tr>
            </tfoot>
        </table>

          <?php if($this->session->userdata('logged_in') and !($this->session->userdata('access_lvl') == "Member")): ?>
                <br><br>
                <div align="center">
                  <a href="<?php echo base_url();?>cashbook/word" class="btn btn-warning">Export Full List in Word</a>&nbsp;&nbsp;
                  <a href="<?php echo base_url();?>cashbook/excel" class="btn btn-danger">Export Full List in Excel</a>&nbsp;&nbsp;
                  <a href="<?php echo base_url();?>exportcashbook" class="btn btn-success">More Export Options</a>
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
            "url": "<?php echo site_url('cashbook/ajax_list')?>",
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



function add_entry()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add an Entry'); // Set Title to Bootstrap modal title
}

function edit_entry(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('cashbook/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id"]').val(data.id);
            $('[name="date"]').val(data.date);
            $('[name="title"]').val(data.title);
            $('[name="details"]').val(data.details);
            $('[name="amount"]').val(data.amount);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Entry'); // Set title to Bootstrap modal title
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
        url = "<?php echo site_url('cashbook/ajax_add')?>";
    } else {
        url = "<?php echo site_url('cashbook/ajax_update')?>";
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

function delete_entry(id)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('cashbook/ajax_delete')?>/"+id,
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

function disable_entry(id)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('cashbook/ajax_disable')?>/"+id,
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
                <h3 class="modal-title">Cashbook Entry</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">

                        <div class="form-group">
                            <label class="control-label col-md-3">Date</label>
                            <div class="col-md-9">
                                <input name="date" placeholder="yyyy-mm-dd" class="form-control datepicker" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Title</label>
                            <div class="col-md-9">
                                <select name="title" class="form-control">
                                    <option value="">--Select Options--</option>
                                    <option value="Cash Collection">Cash Collection</option>
                                    <option value="Salary Expense">Salary Expense</option>
                                    <option value="Others">Others</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>

                       <div class="form-group">
                            <label class="control-label col-md-3">Details</label>
                            <div class="col-md-9">
                                <textarea name="details" placeholder="Details" class="form-control"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>

                       
                        <div class="form-group">
                            <label class="control-label col-md-3">Amount</label>
                            <div class="col-md-9">
                                <input name="amount" placeholder="Amount" class="form-control" type="number" min="0" max="999999">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Entry</label>
                            <div class="col-md-9">
                                <select name="entrybalance" class="form-control">
                                    <option value="Debit">--Select Options--</option>
                                    <option value="Credit">Credit</option>
                                    <option value="Debit">Debit</option>
                                </select>
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
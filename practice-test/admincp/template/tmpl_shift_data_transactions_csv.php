<div><? echo $msgg ?></div>
<!--<div><? echo print_r($checking) ?></div>-->

<div class="tab-content" style='overflow-x: auto;'>
    <div class="tab-pane fade in active">
        <script src="https://cdn.ckeditor.com/4.13.1/standard-all/ckeditor.js"></script>
        <h5><b>Add Question From The CSV File Of Transactions Table</b></h5>
        <p><br></p>
        
        <form method="post" enctype="multipart/form-data">
            
            <div class='row form-group'>
                <div class='col-md-3'><label class='form-control-label'>CSV File (Student)</label></div>
                <div class='col-md-9'><input type="file" name="csv_file" accept=".csv" class='form-control'></div>
            </div>
            
            <div class='row form-group'>
                <div class='col-md-3'><label class='form-control-label'>Click to Update The Subscription Table </label></div>
                <div class='col-md-5'>
                    <button type="submit" class="btn btn-success" name='update_subs_btn'>Update According to User Table</button>
                </div>
            </div>
        </form>
        
        <br><hr><br>
        
        <form method="post" enctype="multipart/form-data">
           
            <div class='row form-group'>
                <div class='col-md-3'>
                    <label class='form-control-label'>Attributes Serial in CSV File</label>
                </div>
                <div class='col-md-9'>
                    <label class='form-control-label'><b style="color:darkred">user_id, amount,	currency, date, test_id, txn_id, subscription</b></label>
                </div>
            </div>
            
            <div class='row form-group'>
                <div class='col-md-3'><label class='form-control-label'>CSV File</label></div>
                <div class='col-md-9'><input type="file" name="csv_file" accept=".csv" class='form-control'></div>
            </div>

            <div class='row form-group'>
                <div class='col-md-3'></div>
                <div class='col-md-9'>
                    <button type="submit" class="btn btn-success" name='upload_btn'>Upload</button>
                </div>
            </div>
        </form>

    </div>
</div>

<script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/latest.js?config=TeX-AMS_HTML' async></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.ckeditor.com/4.13.1/standard-all/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>



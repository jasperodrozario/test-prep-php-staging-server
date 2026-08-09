<div id='response'><? echo $msg ?></div>

<div class="tab-content" style='overflow-x: auto;'>
    <div class="tab-pane fade in active">
        <script src="https://cdn.ckeditor.com/4.13.1/standard-all/ckeditor.js"></script>
        <h5><b>Add Question From A CSV File</b></h5>
        <p><br></p>
        <div id="form-response"></div>

        <form method="post" enctype="multipart/form-data">
           
            <div class='row form-group'>
                <div class='col-md-3'><label class='form-control-label'>Insert Into</label></div>
                <div class='col-md-9'>
                    <select name='table_name' class='form-control'>
                        <option value=''>Select Table</option>
                        <option value='question'>Question Table</option>
                        <option value='story'>Story Table</option>
                        <option value='study'>Study Table</option>
                    </select>
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



<div class="container mt-5 form-container" style="margin-top: 10%; margin-bottom: 10%;">
    <h2 class="mb-4 text-center">Enter Student's Email Address</h2>
    <br><br>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter student's email" required>
            </div>
        </div>
        
        <div class="form-group row">
            <label for="platform" class="col-sm-2 col-form-label">Platform</label>
            <div class="col-sm-10">
                <select name='platform' class='form-control' required>
<!--
                    <option value=''>Select Platform</option>
                    <option value='app'>App</option>
-->
                    <option value='web'>Web</option>
                </select>
            </div>
        </div>
        
        <div class="form-group row">
            <label class="col-sm-2 col-form-label"></label>
            <div class="col-sm-10">
                <button type="submit" class="btn btn-primary">Go to Progress Report</button>
            </div>
        </div>
        
    </form>
</div>


<style>
    .form-container {
        background-color: #f8f9fa; /* Light gray background */
        padding: 30px; /* Padding around the form */
        border-radius: 10px; /* Rounded corners */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
    }
</style>

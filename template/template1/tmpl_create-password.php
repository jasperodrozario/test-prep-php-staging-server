<main class="password-page">

    <div class="password-card">

        <div class="text-center mb-4">
            <div class="icon-circle">
                <i class="fas fa-lock"></i>
            </div>

            <h2>Create New Password</h2>

            <p class="text-muted">
                Choose a strong password to keep your account secure.
            </p>
        </div>

        <div id="response-success"></div>
        <div id="response-error"></div>

        <form id="form111" method="post" enctype="multipart/form-data">

            <input type="hidden" name="action" value="create-password">
            <input type="hidden" name="user_id" value="<?= $_GET['user_id']; ?>">

            <div class="form-group password-container mb-3">
                <input
                    type="password"
                    class="form-control modern-input"
                    id="password"
                    name="password"
                    placeholder="New Password"
                    required>

                <i class="fas fa-eye" onclick="togglePasswordVisibility(this)"></i>
            </div>

            <div class="form-group password-container mb-4">
                <input
                    type="password"
                    class="form-control modern-input"
                    id="pass"
                    name="conf_password"
                    placeholder="Confirm Password"
                    required>

                <i class="fas fa-eye" onclick="togglePasswordVisibility(this)"></i>
            </div>

            <button type="submit" class="btn btn-password">
                Create Password
            </button>

        </form>

    </div>

</main>

<style>
body{
    background:#f4f7fb;
    font-family:'Inter',sans-serif;
}

.password-page{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:40px 20px;
}

.password-card{

    width:100%;
    max-width:470px;

    background:#fff;

    border-radius:20px;

    padding:45px;

    box-shadow:
    0 10px 40px rgba(0,0,0,.08);
}

.icon-circle{

    width:80px;
    height:80px;

    margin:auto;

    background:#fff7e9;

    border-radius:50%;

    display:flex;
    justify-content:center;
    align-items:center;

    margin-bottom:20px;
}

.icon-circle i{

    font-size:34px;

    color:#FDB44B;
}

.password-card h2{

    font-weight:700;
    margin-bottom:8px;
}

.password-card p{

    color:#6c757d;
    margin-bottom:30px;
}

.password-container{

    position:relative;
}

.password-container i{

    position:absolute;

    top:50%;

    right:18px;

    transform:translateY(-50%);

    color:#999;

    cursor:pointer;

    transition:.25s;
}

.password-container i:hover{

    color:#FDB44B;
}

.modern-input{

    height:55px;

    border-radius:12px;

    border:1px solid #dee2e6;

    padding-right:50px;

    font-size:15px;

    box-shadow:none;

    transition:.25s;
}

.modern-input:focus{

    border-color:#FDB44B;

    box-shadow:0 0 0 .2rem rgba(253,180,75,.18);
}

.btn-password{

    width:100%;

    height:55px;

    border-radius:12px;

    border:none;

    background:#FDB44B;

    color:#fff;

    font-weight:600;

    font-size:16px;

    transition:.25s;
}

.btn-password:hover{

    background:#f39b00;

    color:#fff;

    transform:translateY(-2px);
}

#response-success{

    display:none;

    background:#d1fae5;

    color:#065f46;

    border-radius:10px;

    padding:14px;

    margin-bottom:20px;

    font-weight:600;

    text-align:center;
}

#response-error{

    margin-bottom:20px;
}

@media(max-width:576px){

.password-card{

padding:30px 22px;

border-radius:16px;

}
}
</style>


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script>
$('#form111').submit(function(event){
        event.preventDefault();
         
        $.ajax({
            type: 'POST',
            dataType : "json",
            url: "<?=$configs['vpath']?>ajax-home", 
            data: new FormData(this),
    		contentType: false,       // The content type used when sending data to the server.
			cache: false,             // To unable request pages to be cached
			processData:false   
        })
        .done(function(data)
        {
        	//alert(data);
            if(data.error) 
            {
            	 $('#response-error').html(data.error);
            	 $('#response-success').html("");
            }
            if(data.success) 
            {
            	 $('#form111').hide();
                 $('#response-success').show();
            	 $('#response-success').html(data.success);
            }		
			if(data.url)
			{
				window.location=data.url;
         
			}
        
        })
        .fail(function() 
        {
         
            // just in case posting your form failed
            alert("Posting failed.");
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });	
</script>

<script>
    
    function togglePasswordVisibility(eyeIcon) {
        const passwordInput = eyeIcon.previousElementSibling; // Get the input element before the clicked icon

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        }
    }
</script>
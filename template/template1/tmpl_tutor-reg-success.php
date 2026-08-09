<main>

<br><br>
<section id="page-content">
    <div class="inner-body">
        <div class="container">
            <div class="row">
                <div class="midle-conarea mr-t88" style="min-height: 400px;">
                    <div class="col-md-12 text-center">
                        <h2>Thank You for Submitting the Tutor Information Form!</h2>
                        <p>Congratulations, your form has been submitted successfully.</p>
                        <p>You can now view student progress reports by logging in at 
                            <a href="<?=$configs['vpath'];?>login" target="_new" class="login-link">
                                <b><u>staging-test-prep.asvab-tutoring.com</u></b>
                            </a>
                        </p>
                        <p>Thank you for your time and effort!</p>
                        <p><b><?=ucwords($configs['dotcom'])?></b></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<br>
</main>
<style>
    .inner-body{
        margin-top: 120px;
    }
    
    .login-link {
        color: #0056b3; /* Set the default link color */
        text-decoration: underline;
    }

    .login-link:hover {
        color: #0056b3; /* Set the hover color to a visible one */
        text-decoration: underline;
    }

    .login-link b u {
        text-decoration: none; /* Remove the underline from the bold and underlined text */
    }
</style>
<main>
<br><br>
<section class="user-area-style ptb-100">
    <div class="container">
        <div class="log-in-area">
            <div class="section-title">
                <h2>Choose Your Payment Method</h2>
            </div>
            
            <div class="contact-form-action">

                <h3>Review Your Information</h3>
                <p><strong>Name:</strong> <?= htmlspecialchars($_POST['f_name'] ." ".$_POST['l_name'], ENT_QUOTES) ?></p>
                <p><strong>Subscription Plan:</strong> <?= htmlspecialchars($_POST['duration'], ENT_QUOTES) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($_POST['email'], ENT_QUOTES) ?></p>
                <p><strong>Mobile:</strong> <?= htmlspecialchars($_POST['mobile'], ENT_QUOTES) ?></p>
                <p><strong>Course:</strong><?= htmlspecialchars($_POST['item_name'], ENT_QUOTES) ?></p>
                <p><strong>Total Amount:</strong> $<?= $_POST['amount'] ?></p>
                
                <script src="https://www.paypal.com/sdk/js?client-id=AUKaUC4G2i39Af_KA_EYVK4a0x1pXBP-zt5yEf3PcxeUbyJqm4EQICfZkyKR23_dzh5uhCyL7P1TB8HF&vault=true&intent=subscription"></script>

                <div id="paypal-button-container"></div>
            </div>
        </div>
    </div>
</section>
        
<br><br>
</main>
<script>
    $(document).ready(function(){

        // Declare planId outside of paypal.Buttons
        var planId = '<?= $plan_id ?>'; // Embed the plan ID from PHP

        paypal.Buttons({
            createSubscription: function(data, actions) {
                return actions.subscription.create({
                    'plan_id': planId // Use the embedded plan ID here
                });
            },
            onApprove: function(data, actions) {
                actions.redirect();
                // Prepare form data
                var formData = {
                    test_id: '<?=$_POST['test_id']?>',
                    duration: '<?=$_POST['duration']?>',
                    f_name: '<?=$_POST['f_name']?>',
                    l_name: '<?=$_POST['l_name']?>',
                    email: '<?=$_POST['email']?>',
                    password: '<?=$_POST['password']?>',
                    mobile: '<?=$_POST['mobile']?>',
                    user_id: '<?=$_POST['user_id']?>',
                    referral_id: '<?=$_POST['referral_id']?>',
                    price: '<?=$_POST['price']?>',
                    course: '<?=$_POST['item_name']?>',
                    currency_code: '<?=$_POST['currency_code']?>',
                    subscriptionID: data.subscriptionID 
                };

                // Send form data to your backend
                $.ajax({
                    url: '<?=$configs['vpath']?>notify', // Your backend endpoint
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Handle success response from your backend
//                        alert('Subscription created successfully!');
//                        console.log(response);
                        window.location.href = '<?=$configs['vpath']?>thank-you';
                        
                        // Optionally redirect or update UI based on response
                    },
                    error: function(xhr, status, error) {
                        // Handle error response from your backend
                        alert('An error occurred while processing your subscription.');
                        console.error(error);
                    }
                });
            }
        }).render('#paypal-button-container');
        
    });
</script>


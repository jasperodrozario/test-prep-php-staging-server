<main>
<div class="page-title-area bg-11 ">
    <div class="container">
        <div class="page-title-content">


            <?
            if ($_GET['account'] == 'free') {
            ?>
                <h2>Thank you for registration</h2>
            <?
            } else {
            ?>
                <h2>Thank you for payment</h2>
            <?
            }
            ?>

        </div>
    </div>
</div>


<section class="privacy-policy-area ptb-100">
    <div class="container">
        <div class="privacy-policy-wrap">

            <?
            if ($_GET['account'] == 'free') {
            ?>

                <p>Congratulations, your registration has been done successfully. Your account will be activated by your tutor. </p>

                <p>After activation, you will be able to <a href='<?= $configs['vpath'] . "login" ?>'><b><u>log in</u></b></a> and start the online practice test. We will inform you when your account will be activated by your tutor.</p>

                <p><br><br> Thanks Again.<br><b><?= ucwords($configs['dotcom']) ?> </p>
            <?
            } else {
            ?>
                <p>Congratulations, your payment has been done successfully. Now you can <a href='<?= $configs['vpath'] . "login" ?>'><b><u>log in</u></b></a> and start the online practice test.</p>

                <p> <br><br> Thanks Again.<br><b><?= ucwords($configs['dotcom']) ?> </p>
            <?
            }
            ?>
        </div>
    </div>
</section>
</main>
<main>
<div class="page-title-area bg-11 ">
   <div class="container">
      <div class="page-title-content">
         <h2><?= $db_value['title'] ?></h2>

      </div>
   </div>
</div>


<section class="privacy-policy-area ptb-100">
   <div class="container">
      <div class="privacy-policy-wrap">

         <?= html_entity_decode(html_entity_decode(html_entity_decode($db_value['contents'])));
         if ($_GET['seo_url'] == "logout") {

            echo "<script>setTimeout(\"location.href = '" . $configs['vpath'] . "';\",6000);</script>";
         }
         ?>


      </div>
   </div>
</section>
</main>
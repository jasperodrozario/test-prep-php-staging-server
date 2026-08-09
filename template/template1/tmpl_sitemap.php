


<div class="page-title-area bg-11 mt-110">
<div class="container">
<div class="page-title-content">
<h2>Sitemap</h2>

</div>
</div>
</div>


<section class="privacy-policy-area ptb-100">
<div class="container">
 
	
<div class="row">
		<?
		
		foreach($courses as $d)
		{
			for($i=0;$i<count($city);$i++)
			{
				echo"<div class='col-sm-3'>";
			    echo"<a href='https://ocs.asvab-tutoring.com/" .seo_url($city[$i]) ."/course/"  .seo_url($d['seo_url'])."'>".ucwords(str_replace("-"," ",$d['name'])) ." in ". $city[$i] ."</a>";
				echo"</div>";
			}
			
        }
		?>
</div> 	
 
</div>
</section>


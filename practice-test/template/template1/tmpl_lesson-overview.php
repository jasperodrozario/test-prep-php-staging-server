
<h3><?=$db_value['name']?> overview</h3>
<div class='clearfix'><br></div>
<?=html_entity_decode(html_entity_decode(str_replace("&#92;","\\",$db_value['details'])));?>

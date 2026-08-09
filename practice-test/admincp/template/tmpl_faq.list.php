<table id="table-1" width="100%" class="table table-bordered table-stripped" border="0" align="center" cellspacing="1" cellpadding="4"  >
<thead>
<tr >
<td width="5%" align="left"><b>Id </b></td>
<td width="25%" align="left"><b>Question</b></td>

<td width="13%"  align=center><b>Order</b></td>
<td width="13%"  align=center><b>Status</b></td>
<td width="13%"  align=center ><b>Action</b></td></tr>
</thead><tbody>
<?php

foreach($faqs as $d)
{
 
	echo"<tr><td width=5%>" . $d['id'] . "</td><td>" . $d['question'] . "</td><td align='center'>" . $d['ord'] . "</td><td align=center>" . $d['status'] . "</td><td align='center'><i class='fa fa-pencil-square-o'> </i> <a   href='faq.entryform?id=" . $d['id'] . "'>Edit</a> | <i class='fa fa-times'></i><a   href=\"javascript:if(confirm('Are you sure you want to delete?')){window.location='" . basename($_SERVER['PHP_SELF'],".php") . "?id=" . $d['id'] . "';}\">Delete</a></td></tr>";
  	/*echo"<tr><td>" . $d['id'] . "</td><td>" . $d['question'] . "</td><td align='center'>" . $d['ord'] . "</td><td align=center>" . $d['status'] . "</td><td align='center'><i class='fa fa-pencil-square-o'> </i> <a   href='faq.entryform?id=" . $d['id'] . "'>Edit</a> | <i class='fa fa-times'></i><a   href=\"javascript:if(confirm('Are you sure you want to delete?')){window.location='" . basename($_SERVER['PHP_SELF'],".php") . "?id=" . $d['id'] . "&del=1&limit=".$_REQUEST['limit']."';}\">Delete</a></td></tr>";*/
}
?> 
</tbody>
</table>
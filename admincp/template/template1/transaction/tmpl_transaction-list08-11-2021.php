<form method='post' id='form' enctype="multipart/form-data">

       <table class='table table-striped'>
      <thead  >

         <tr >
        <td ><b>Id </b></td>
        <td ><b>Trans. Date</b></td>

        <td ><b>Name</b></td>
        <td ><b>Amount</b></td>
        <td ><b>Details</b></td>
        <td ><b>Payer Email</b></td>
        <td ><b>Trans Id</b></td>
        <td ><b>Status</b></td>
        <td   align=center ><b>Gateway</b></td></tr>
        </thead>


       
        <tbody>
      <?
      
      if($total)
      {  
        foreach($transactions as $d)
        {

         if($d['status']=='Y'){
          $d['status']="Active";
          }
          else{
            $d['status']="Pending";


          }

          //$name=getFldNameById("login","user_name",array("user_id"=>$d['user_id']));       
         
        echo"<tr><td>" . $d['id'] ."</td><td>" . showdate($d['date']) ."</td><td>" . $name ."</td><td >" . $d['amount'] . "".$d['currency']."</td><td >" . $d['details'] . "</td><td align='center'>" . $d['payer_email'] . "</td>          
          <td >" . $d['trans_id'] . "</td><td align=center>" . $d['status'] . "</td><td align=center>" . $d['payment_mode'] . "</td>          

          
          </tr>";
      }
    } 
    else
    {
      echo"<tr><td colspan=7 align='center'>No Transactions Found.</td></tr>";
    }
      ?>
      </tbody>  


      </table>

    </form>
      <?
      
      if($total>20)
      {
      echo'<ul class="pagination pagination-primary m-b-0">';
      echo paging($total,"","faq-list");
      echo'</ul>';
    }
      
  

?>



 
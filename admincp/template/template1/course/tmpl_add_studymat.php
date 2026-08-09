<!--  <div id='response'></div> -->
 <form method='post' id='form' enctype="multipart/form-data">
<input type="hidden"   name='action'  value="file">

<input type='hidden' name="course_id" value="<?=$_GET['course_id'];?>">

<input type='hidden' name="chapter_id" value="<?=$_GET['chapter_id'];?>">

<input type='hidden' name="file_type" value="p">

<input type='hidden' name="id" value="<?=$_GET['id'];?>">





    <table border=0 cellpadding=4 cellspacing=0 width=100% class='table table-bordered '>
       <tr><td width="15%">Studymat Title</td><td><input type="text" name="title" class='form-control' required value="<?=$db_value['title'] ?>"></td><td width="15%">Upload File [pdf]</td><td>
        <?php if($db_value['file']){
          echo "<a target='_blank' href='".$configs['vpath'].$db_value['file']."'>".$db_value['file']."</a><br>";
        } ?> 

         

          <input id="file" type="file" name="file" class="form-control" accept=".pdf"></td></tr>

 

       

      <tr><td width="15%">Display Order</td><td><input type="text" name="pos" class='form-control' required value="<?=$db_value['pos'] ?>"></td><td>Availability </td><td><input type="radio" value='0' name="paid"  <?if($db_value['paid']=="0"){echo"checked";}?>>Free <input type='radio' name=paid value='1' <?if($db_value['paid']=="1"){echo"checked";}?>>Paid</td></tr>






    <tr ><td></td><td colspan="3"><input type='submit' class='btn btn-success' name='submit' value='Update'  > <input type='button' class='btn btn-danger'  value='Cancel' onclick="javascript:history.back();"></td></tr>
</table>
 </form>


<h3 class="box-title">Video List of <?=$course_name ?> : <?=$cahpter_name ?></h3>
         <table border=0 cellpadding=4 cellspacing=0 width=100% class='table table-bordered '>
      <thead  >

          <tr style='text-transform: capitalize!important'>
            <th width=5>Id</th>
           
            <th>Title</th>
              <th>File</th>

            <th width="11%" align="center">Display Order </th>

         
           
            <th width=20%>Action</th>
           
          </tr>
        </thead>
       
      <tbody>
      <?
      
      if($total)
      { 

        foreach($db_list as $d)
        {

         
          if($d['status']=='Y')
        {
           $d['status']="<img src='../img/on.png'>"; 
        }
        else
        {
          $d['status']="<img src='../img/off.png'>"; 
        }
    

        

        echo"<tr><td>" . $d['id'] ."</td>
         
          <td>" . $d['title'] ."</td>
         

          <td align='left'><a target='_blank' href='".$configs['vpath'].$d['file']."'>".$d['file']."</a></td>

         <td align='center'>" . $d['pos'] ."</td>

         
          <td> <a href='".$configs['admin_vpath'] ."course/add-studymat?course_id=" . encrypt($d['course_id']) . "&chapter_id=" . encrypt($d['chapter_id'])."&id=".encrypt($d['id'])."' class=''>Edit</a> |
                    <a href=\"javascript:if(confirm('Are sure to delete?')){window.location='".$configs['admin_vpath'] ."course/add-studymat?del_id=" . encrypt($d['id'])."';}\" class=''>Delete</a>"; 


                    

                    


                   
          echo"</td>
          </tr>";
      }
    } 
    else
    {
      echo"<tr align='center'><td colspan=5>No Studymat Found.</td></tr>";
    }
      ?>
      </tbody>  
      </table>

 
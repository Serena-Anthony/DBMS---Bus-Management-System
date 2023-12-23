<?php 
ob_start();
session_start();
if(isset($_SESSION['admin_session_token']) && !empty($_SESSION['admin_session_token']))
{
    include("connect.php");
    ?>
<!DOCTYPE html>
<html>
  <head>
    <title>View Complaints</title>
    <link rel="stylesheet" type="text/css" href="admin_mangage_routes.css" />
    <link rel="stylesheet" type="text/css" href="usermenu.css" />
    <!-- Link to your external CSS file -->
  </head>
  <body >

  <?php include("adminmenu.php"); ?>
  <?php 
 
       $per_page_record = 10;  // Number of entries to show in a page.   
       // Look for a GET variable page if not found default is 1.        
       if (isset($_GET["page"])) {    
           $page  = $_GET["page"];    
       }    
       else {    
         $page=1;    
       }    
   
       $start_from = ($page-1) * $per_page_record;     
   
       $query = "SELECT * FROM complaints LIMIT $start_from, $per_page_record";     
       $rs_result = mysqli_query ($con, $query);       
  ?>

    <div class="form-container">
      <h1>View Complaints</h1>
     
      <table id="routes">   
          <thead>   
            <tr>
            <th>S.No</th>
              <th>Service Number</th> 
              <th>User ID</th>    
              <th>Passenger Details</th>   
              <th>Complaint/Issue</th>   
              <th>Created At</th>  
            </tr>   
          </thead>   
          <tbody>   
        <?php     
        $i = 1;
            while ($row = mysqli_fetch_array($rs_result)) {    
                  // Display each field of the records.    
            ?>     
            <tr>    
                <td><?php echo $row['id']; ?></td> 
                <td><?php echo $row["busnum"]; ?></td>   
                <td><?php echo $row["userid"]; ?></td>  
                <td><?php echo $row["passenger_details"]; ?></td>   
                <td><?php echo $row["description"]; ?></td>   
                <td><?php echo $row["created_at"]; ?></td>                                         
            </tr>     
            <?php    
            $i++; 
                };    
            ?>     
          </tbody>   
        </table>   
     
       
        <!--Pagination-->
        <div class="pagination">    
      <?php  
        $query = "SELECT COUNT(*) FROM complaints";     
        $rs_result1 = mysqli_query($con, $query);     
        $row = mysqli_fetch_row($rs_result1);     
        $total_records = $row[0];     
          
    echo "</br>";     
        // Number of pages required.   
        $total_pages = ceil($total_records / $per_page_record);     
        $pagLink = "";       
      
        if($page>=2){   
            echo "<a href='admin_view_complaints.php?page=".($page-1)."'>  Prev </a>";   
        }       
                   
        for ($i=1; $i<=$total_pages; $i++) {   
          if ($i == $page) {   
              $pagLink .= "<a class = 'active' href='admin_view_complaints.php?page="  
                                                .$i."'>".$i." </a>";   
          }               
          else  {   
              $pagLink .= "<a href='admin_view_complaints.php?page=".$i."'>   
                                                ".$i." </a>";     
          }   
        };     
        echo $pagLink;   
  
        if($page<$total_pages){   
            echo "<a href='admin_view_complaints.php?page=".($page+1)."'>  Next </a>";   
        }   
  
      ?>    
      </div>  

      <div class="inline">   
      <input id="page" type="number" min="1" max="<?php echo $total_pages?>"   
      placeholder="<?php echo $page."/".$total_pages; ?>" required>   
      <button class="btn" onClick="go2Page();">Go</button>   

     
    </div>
    
     
    <script>   
    function go2Page()   
    {   
        var page = document.getElementById("page").value;   
        page = ((page><?php echo $total_pages; ?>)?<?php echo $total_pages; ?>:((page<1)?1:page));   
        window.location.href = 'admin_view_complaints.php?page='+page;   
    }   

  </script>  
  </body>
</html>
<?php 
mysqli_close($con);
}
else
{
    header("location: login.php");
}
ob_end_flush();
?>
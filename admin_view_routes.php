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
    <title>View & Manage Services</title>
    <link rel="stylesheet" type="text/css" href="admin_mangage_routes.css" />
    <link rel="stylesheet" type="text/css" href="usermenu.css" />
    <!-- Link to your external CSS file -->
  </head>
  <body >

  <?php include("adminmenu.php");?>
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
   
       $query = "SELECT * FROM routes LIMIT $start_from, $per_page_record";     
       $rs_result = mysqli_query ($con, $query);       
  ?>

    <div class="form-container">
      <h1>View Routes</h1>

      <?php 
        if(isset($_COOKIE['success']))
        {
          echo "<p class='success'>".$_COOKIE['success']."</p>";
        }
        if(isset($_COOKIE['error']))
        {
          echo "<p class='error'>".$_COOKIE['error']."</p>";
        }
      ?>
     
      <table id="routes">   
          <thead>   
            <tr>
            
              <th>Bus Number</th>   
              <th>Name</th>   
              <th>Source</th>   
              <th>Destination</th>   
              <th width="10%">Start Time</th>   
              <th width="10%">End Time</th>   
              <th>Seating Capacity</th>   
              <th>Seat Fare</th>   
              <th width="20%">Action</th>   
            </tr>   
          </thead>   
          <tbody>   
        <?php     
        $i = 1;
            while ($row = mysqli_fetch_array($rs_result)) {    
                  // Display each field of the records.    
            ?>     
            <tr>    
               
             <td><?php echo $row["busnum"]; ?></td>     
            <td><?php echo $row["source"]; ?></td>   
            <td><?php echo $row["destination"]; ?></td>   
            <td><?php echo $row["start_time"]; ?></td>   
            <td><?php echo $row["end_time"]; ?></td>   
            <td><?php echo $row["total_seats"]; ?></td>   
            <td><?php echo $row["charge"]; ?></td>   
            <td><?php echo $row["status"]; ?></td>      
            <td>
              <a class="edit-link" href="admin_edit_route.php?bid=<?php echo $row['id']; ?>">Edit</a>
              |
              <a class="delete-link" href="javascript:void(0)" onclick="deleteRoute(<?php echo $row['busnum']; ?>)">Delete</a>
            </td>                                     
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
        $query = "SELECT COUNT(*) FROM routes";     
        $rs_result1 = mysqli_query($con, $query);     
        $row = mysqli_fetch_row($rs_result1);     
        $total_records = $row[0];     
          
    echo "</br>";     
        // Number of pages required.   
        $total_pages = ceil($total_records / $per_page_record);     
        $pagLink = "";       
      
        if($page>=2){   
            echo "<a href='admin_view_routes.php?page=".($page-1)."'>  Prev </a>";   
        }       
                   
        for ($i=1; $i<=$total_pages; $i++) {   
          if ($i == $page) {   
              $pagLink .= "<a class = 'active' href='admin_view_routes.php?page="  
                                                .$i."'>".$i." </a>";   
          }               
          else  {   
              $pagLink .= "<a href='admin_view_routes.php?page=".$i."'>   
                                                ".$i." </a>";     
          }   
        };     
        echo $pagLink;   
  
        if($page<$total_pages){   
            echo "<a href='admin_view_routes.php?page=".($page+1)."'>  Next </a>";   
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
        window.location.href = 'admin_view_routes.php?page='+page;   
    }   

    function deleteRoute(id)
    {
      var c = confirm("Do you want to delete bus route?");
      if(c === true)
      {
        window.location = "admin_delete_route.php?bid="+id;
      }
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
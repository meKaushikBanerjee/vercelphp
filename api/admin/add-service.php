<?php 
include('includes/mainheader.php');
if(isset($_POST['create']))
{
  function random_id_strings($length_of_string)
  {
    $str_id = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return substr(str_shuffle($str_id),0,$length_of_string);
  }
  $randid = random_id_strings(6);
  $sql="SELECT * from services";
  $query = $dbh->prepare($sql);
  $query->execute();
  $row=$query->rowCount();
  if($row>=0)
  {
    $row += 1;
    $id="SERV_".$row.$randid;
  }
  $aid=$_POST['id'];
  $name=$_POST['name'];
  $value=$_POST['value'];
  $sql="INSERT into services(adminid,serviceid,servicename,servicedetails) VALUES(:aid,:id,:name,:value)";
  $query = $dbh->prepare($sql);        
  $query->bindParam(':id',$id,PDO::PARAM_STR);
  $query->bindParam(':name',$name,PDO::PARAM_STR);  
  $query->bindParam(':aid',$aid,PDO::PARAM_STR);
  $query->bindParam(':value',$value,PDO::PARAM_STR);
  if($query->execute())
  {
    $_SESSION['msg']="Service added Successfully!!";         
  }
  else
  {
    echo "<script>alert('Service could not be added!');</script>";
  }
}
?>

<body>
  <script>
    function deleteservice(val) 
    {
      var request = new XMLHttpRequest();
      request.onreadystatechange = function() {
        if(this.readyState === 4 && this.status === 200){
          alert("Service deleted successfully!");
          location.reload();
          event.preventDefault(); 
        }
        if(this.readyState === 4 && this.status === 204){
          alert("Service could not be deleted!");
          location.reload(); 
          event.preventDefault();
        }
      };
      request.open("GET", "deleteservice.php?seid="+val , true);
      request.send();
    }
  </script>

<?php include("includes/header.php");?>  
  <div class="ts-main-content">
<?php include('includes/sidebar.php'); ?>

    <div class="content-wrapper">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">          
            <h2 class="page-title">Add Service</h2>
            <div class="row">
              <div class="col-md-12">
                <div class="panel panel-primary">
                  <div class="panel-heading">Add Service</div>
                    <div class="panel-body">
                      <?php 
                        if(isset($_POST['create']))
                        { 
                      ?>
                        <div class="alert alert-error" style="color: green;">
                          <button type="button" class="close" data-dismiss="alert">×</button>
                            <?php echo htmlentities($_SESSION['msg']); ?>
                            <?php echo htmlentities($_SESSION['msg']=""); ?>
                        </div>
                      <?php 
                        }
                        $logid=$_SESSION['login'];
                      ?>
                      <form class="form-horizontal row-fluid" method="POST">
                        <div class="form-group">
                          <div class="col-sm-8">                            
                            <input type="hidden" name="id" value="<?php echo $logid; ?>" class="form-control">
                          </div>
                        </div>
                                              
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Service Name</label>
                          <div class="col-sm-8">
                            <input type="text" name="name" id="name" class="form-control" required>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-sm-3 control-label">Service Details</label>
                          <div class="col-sm-8">
                            <textarea name="value" id="value" cols="20" rows="10" class="form-control" required></textarea>
                          </div>
                        </div>

                        <div class="col-sm-8 col-sm-offset-5">
                          <input class="btn btn-primary" type="submit" name="create" value="Create">
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>  
      </div>
    </div>
  </div>
  <div class="ts-main-content">
    <div class="content-wrapper">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <h2 class="page-title">Manage Designation</h2>
            <div class="table-responsive">
              <table id="zctb" class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <th scope="col">Service Id</th>
                    <th scope="col">Service Name</th>
                    <th scope="col">Service Details</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $sql="SELECT * from services";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                    if($query->rowCount() > 0)
                    {
                      foreach($results as $result)
                      {
                  ?>
                        <tr>
                          <td scope="row" width="100"><?php echo $result->serviceid; ?></td>
                          <td scope="row" width="100"><?php echo $result->servicename; ?></td>
                          <td scope="row" width="100"><?php echo $result->servicedetails; ?></td>
                          <td width="20">
                            <a href="" onclick="deleteservice('<?php echo $result->serviceid; ?>');" style="color: red;"><i class="fa fa-close"></i>Delete</a>
                          </td>
                        </tr>
                  <?php
                      }
                    } 
                  ?>                                      
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php include('includes/footer.php'); ?>
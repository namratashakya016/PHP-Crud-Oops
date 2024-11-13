
<?php
// include function file
include_once("function.php");
$updatedata=new database();
if(isset($_POST['update']))
{
// Get the userid
$userid=intval($_GET['id']);

$whereClause = 'id = ?';  
$whereParams = [$userid];  
$data = [
  'FirstName' => $_POST['firstname'],
  'LastName' => $_POST['lastname'],
  'EmailId' => $_POST['emailid'],
  'ContactNumber'=>$_POST['ContactNumber'],
  'Address'=>$_POST['address']
];
$sql=$updatedata->update('users',$data, $whereClause, $whereParams);
if($sql)
{
echo "<script>alert('Record Updated successfully');</script>";
echo "<script>window.location.href='index.php'</script>";
}
else
{
echo "<script>alert('Something went wrong. Please try again');</script>";
echo "<script>window.location.href='index.php'</script>";
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>PHP CURD Operation using  PHP OOP </title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">

<div class="row">
<div class="col-md-12">
<h3>Update Record | PHP CRUD Operations using  PHP OOP</h3>
<hr />
</div>
</div>

<?php
// Get the userid
$userid=intval($_GET['id']);
$onerecord=new database();
$row=$onerecord->fetchonerecord($userid,'users');
// $cnt=1;
// while($row=mysqli_fetch_array($sql))
  // {
  ?>
<form name="insertrecord" method="post">
<div class="row">
<div class="col-md-4"><b>First Name</b>
<input type="text" name="firstname" value="<?php echo htmlentities($row['FirstName']);?>" class="form-control" required>
</div>
<div class="col-md-4"><b>Last Name</b>
<input type="text" name="lastname" value="<?php echo htmlentities($row['LastName']);?>" class="form-control" required>
</div>
</div>
<div class="row">
<div class="col-md-4"><b>Email id</b>
<input type="email" name="emailid" value="<?php echo htmlentities($row['EmailId']);?>" class="form-control" required>
</div>
<div class="col-md-4"><b>Contact Number</b>
<input type="text" name="ContactNumber" value="<?php echo htmlentities($row['ContactNumber']);?>" class="form-control" maxlength="10" required>
</div>
</div>
<div class="row">
<div class="col-md-8"><b>Address</b>
<textarea class="form-control" name="address" required><?php echo htmlentities($row['Address']);?></textarea>
</div>
</div>
<?php //} ?>
<div class="row" style="margin-top:1%">
<div class="col-md-8">
<input type="submit" name="update" value="Update">
</div>
</div>
     </form>
            
      
	</div>
</div>

</body>
</htm
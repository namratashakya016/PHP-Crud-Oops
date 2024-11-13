<?php
require_once'function.php';
// Object creation
$insertdata=new database();
if(isset($_POST['insert']))
{
$data = [
    'FirstName' => $_POST['firstname'],
    'LastName' => $_POST['lastname'],
    'EmailId' => $_POST['emailid'],
    'ContactNumber'=>$_POST['ContactNumber'],
    'Address'=>$_POST['address']
];

//Function Calling
$sql=$insertdata->insert('users', $data);
if($sql)
{
// Message for successfull insertion
echo "<script>alert('Record inserted successfully');</script>";
echo "<script>window.location.href='index.php'</script>";
}
else
{
// Message for unsuccessfull insertion
echo "<script>alert('Something went wrong. Please try again');</script>";
echo "<script>window.location.href='index.php'</script>";
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>PHP CURD Operation using  PHP OOP</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">

<div class="row">
<div class="col-md-12">
<h3>Insert Record | PHP CRUD Operations using  PHP OOP</h3>
<hr />
</div>
</div>


<form name="insertrecord" method="post">
<div class="row">
<div class="col-md-4"><b>First Name</b>
<input type="text" name="firstname" class="form-control" required>
</div>
<div class="col-md-4"><b>Last Name</b>
<input type="text" name="lastname" class="form-control" required>
</div>
</div>

<div class="row">
<div class="col-md-4"><b>Email id</b>
<input type="email" name="emailid" class="form-control" required>
</div>
<div class="col-md-4"><b>Contactno</b>
<input type="text" name="ContactNumber" class="form-control" maxlength="10" required>
</div>
</div>  



<div class="row">
<div class="col-md-8"><b>Address</b>
<textarea class="form-control" name="address" required></textarea>
</div>
</div>  

<div class="row" style="margin-top:1%">
<div class="col-md-8">
<input type="submit" name="insert" value="Submit">
</div>
</div> 
     
         

</form>           
</div>
</div>
</body>
</html>
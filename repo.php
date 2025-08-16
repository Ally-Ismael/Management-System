<?php 
session_start();
$db = mysqli_connect('localhost', 'root', '', 'iyaloo');
$name= "";
$surname= "";
$make = "";
$serial_no = "";
$asset_no = "";
$department = "";

if (isset($_POST['save_btn'])){
	save();
}
function btn(){
	// call these variables with the global keyword to make them available in function
	global $db, $name, $surname, $make, $serial_no, $asset_no, $department;
	$name =$_POST['name'];
	$surname=$_POST['surname'];
	$make =$_POST['make'];
	$serial_no =$_POST['serial_no'];
	$asset_no =$_POST['asset_no'];
	$department =$_POST['department'];
 
if (empty($name)){
		array_push($errors,"name is required");
	}
	if (empty($surname)){
		array_push($errors,"surname is required");
	}
	if (empty($make)){
		array_push($errors,"make is required");
	}
	if (empty($serial_no)){
		array_push($errors,"serial number is required");	
	}
	if (empty($asset_no)){
		array_push($errors,"asset number is required");	
	}
	if (empty($department)){
		array_push($errors,"department is required");	
	}
	$db= mysqli_connect('localhost','root','','iyaloo');
	//if no errors, save in iyaloo database
		$mysql = "INSERT INTO record (name,surname,make,serial_no,asset_no,department) 
		VALUES('$name','$surname','$make','$serial_no,'$asset_no','$department')";
		 mysqli_query($db,$mysql);

		  $_SESSION['name']= $name;
		  $_SESSION['success']="Your details was added successful.";
		 header('location:admin.php');
}
?>
<?

// fetch cnoList argument. 
// note that cnoList is an array.
$cnoList = $_POST["usernameList"];
// count the number of courses passed by multi.php
$numOfCno = count($usernameList);
// display the corresponding course numbers
// note that at this point, you can go on and do some more 
// complicated operations.
for($n=0; $n<$numOfCno; $n++){
 echo "$cnoList[$n]<br>";
}
?>
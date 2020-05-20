<?php

?>
<!DOCTYPE html>
<html>
<head>
	<title>XLSx</title>
</head>
<body>
<form action="#" method="POST" enctype="multipart/form-data">
	<input type="file" name="excel">
	<input type="submit" name="submit">
</form>
<?php
if(isset($_FILES['excel']['name'])){
	$con=mysqli_connect("localhost","root","","youtube");
	include "xlsx.php";
	if($con){
		$excel=SimpleXLSX::parse($_FILES['excel']['tmp_name']);
		echo "<pre>";	
		// print_r($excel->rows(1));
		print_r($excel->dimension(2));
		print_r($excel->sheetNames());
        for ($sheet=0; $sheet < sizeof($excel->sheetNames()) ; $sheet++) { 
        $rowcol=$excel->dimension($sheet);
        $i=0;
        if($rowcol[0]!=1 &&$rowcol[1]!=1){
		foreach ($excel->rows($sheet) as $key => $row) {
			//print_r($row);
			$q="";
			foreach ($row as $key => $cell) {
				//print_r($cell);echo "<br>";
				if($i==0){
					$q.=$cell. " varchar(50),";
				}else{
					$q.="'".$cell. "',";
				}
			}
			if($i==0){
				$query="CREATE table ".$excel->sheetName($sheet)." (".rtrim($q,",").");";
			}else{
				$query="INSERT INTO ".$excel->sheetName($sheet)." values (".rtrim($q,",").");";
			}
			echo $query;
			if(mysqli_query($con,$query))
			{
				echo "true";
			}
			echo "<br>";
			$i++;
		}
	}
		}
	}
}

?>
</body>
</html>
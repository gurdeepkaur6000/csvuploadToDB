<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// include mysql database configuration file
include_once 'db.php';

 //ini_set('upload_max_filesize', '1000000000M');
if (isset($_POST['submit']))
{
 
    // Allowed mime types
    $fileMimes = array(
        'text/x-comma-separated-values',
        'text/comma-separated-values',
        'application/octet-stream',
        'application/vnd.ms-excel',
        'application/x-csv',
        'text/x-csv',
        'text/csv',
        'application/csv',
        'application/excel',
        'application/vnd.msexcel',
        'text/plain'
    );
 
    // Validate whether selected file is a CSV file
    if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $fileMimes))
    {

            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
 
            // Skip the first line
            fgetcsv($csvFile);
 
            // Parse data from CSV file line by line
             // Parse data from CSV file line by line
			 $row = 0;
            while (($getData = fgetcsv($csvFile, 10000, ",")) !== FALSE)
            {
				$row++;
				if ($row>5)//rows 2,3,4,5
				{
					
					$getData[26] = sanitize($getData[26]); 
					mysqli_query($conn, "INSERT INTO erptablefull set
						`idsheet`='$getData[0]',	
						`dateadded`='$getData[1]',			
						`yearadded`='$getData[2]',				
						`sessiontype`='$getData[3]',
						`category`='$getData[4]',
						`vouchertype`='$getData[5]',
						`voucherno`='$getData[6]',
						`rollno`='$getData[7]',
						`uniqueid`='$getData[8]',
						`status`='$getData[9]',
						`feecategory`='$getData[10]',
						`faculty`='$getData[11]',
						`program`='$getData[12]',
						`department`='$getData[13]',
						`badge`='$getData[14]',
						`receiptno`='$getData[15]',
						`feehead`='$getData[16]',
						`dueamount`='$getData[17]',
						`paidamount`='$getData[18]',
						`concession`='$getData[19]',
						`scholarship`='$getData[20]',
						`reverseconcession`='$getData[21]',
						`writeoff`='$getData[22]',
						`adjustedamount`='$getData[23]',
						`refunded`='$getData[24]',
						`fund`='$getData[25]',
						`remark`='$getData[26]'");
				}
                // Get row data
                //$name = $getData[0];
               /** $branch = $getData[11];
                $feeCategory = $getData[10];
                $feeTypeName = $getData[16];
				
				mysqli_query($conn, "INSERT INTO datatable (`name`) VALUES ('" . $branch . "')");
				
 
                // If user already exists in the database with the same email
                $query = "SELECT * FROM branches WHERE branchName = '" . $branch. "'";
                $check = mysqli_query($conn, $query);
 
                if ($check->num_rows == 0)
                {
					mysqli_query($conn, "INSERT INTO branches (`branchName`) VALUES ('" . $branch . "')");
					$br_id = mysqli_insert_id($conn);	
					
					
					$feeCategoryId='';
					$querycat = "SELECT * FROM feecategory WHERE feeCategory = '" . $feeCategory. "' and br_id='$br_id'";
					$checkcat = mysqli_query($conn, $querycat);
					if ($checkcat->num_rows == 0)
					{
						mysqli_query($conn, "INSERT INTO feecategory (`feeCategory`,`br_id`) VALUES ('" . $feeCategory . "','" . $br_id . "')");
						$feeCategoryId = mysqli_insert_id($conn);
					}
					
					
					
                    //mysqli_query($conn, "UPDATE branches SET branchName = '" . $branch."' WHERE branch = '" . $branch . "'");
                }
                else
                { 
					$fetchone = mysqli_fetch_array($check);
					$br_id = $fetchone['id'];
					
					$feeCategoryId='';
					$querycat = "SELECT * FROM feecategory WHERE feeCategory = '" . $feeCategory. "' and br_id='$br_id'";
					$checkcat = mysqli_query($conn, $querycat);
					if ($checkcat->num_rows == 0)
					{
						mysqli_query($conn, "INSERT INTO feecategory (`feeCategory`,`br_id`) VALUES ('" . $feeCategory . "','" . $br_id . "')");
						$feeCategoryId = mysqli_insert_id($conn);
					}
                }
				
				mysqli_query($conn, "INSERT INTO feecollectiontype (`feeCollectionHead`,`feeCollectionDesc`,`br_id`) VALUES ('Academic','Academic','" . $br_id . "')");
				$fc1 = mysqli_insert_id($conn);
				
				mysqli_query($conn, "INSERT INTO feecollectiontype (`feeCollectionHead`,`feeCollectionDesc`,`br_id`) VALUES ('AcademicMisc','AcademicMisc','" . $br_id . "')");
				$fc2 = mysqli_insert_id($conn);
				
				mysqli_query($conn, "INSERT INTO feecollectiontype (`feeCollectionHead`,`feeCollectionDesc`,`br_id`) VALUES ('Hotel','Hotel','" . $br_id . "')");
				$fc3 = mysqli_insert_id($conn);
				
				mysqli_query($conn, "INSERT INTO feecollectiontype (`feeCollectionHead`,`feeCollectionDesc`,`br_id`) VALUES ('HotelMisc','HotelMisc','" . $br_id . "')");
				$fc4 = mysqli_insert_id($conn);
				
				mysqli_query($conn, "INSERT INTO feecollectiontype (`feeCollectionHead`,`feeCollectionDesc`,`br_id`) VALUES ('Transport','Transport','" . $br_id . "')");
				$fc5 = mysqli_insert_id($conn);
				
				mysqli_query($conn, "INSERT INTO feecollectiontype (`feeCollectionHead`,`feeCollectionDesc`,`br_id`) VALUES ('TransportMisc','TransportMisc','" . $br_id . "')");
				$fc6 = mysqli_insert_id($conn);
				
				if(!empty($feeCategoryId))
				{					
					mysqli_query($conn, "INSERT INTO feetypes (`feeTypeName`,`feeCategoryId`,`feeCollectionId`,`br_id`) VALUES ('" . $feeTypeName . "','" . $feeCategoryId . "','" . $fc1 . "','" . $br_id . "')");
					
					mysqli_query($conn, "INSERT INTO feetypes (`feeTypeName`,`feeCategoryId`,`feeCollectionId`,`br_id`) VALUES ('" . $feeTypeName . "','" . $feeCategoryId . "','" . $fc2 . "','" . $br_id . "')");
					
					mysqli_query($conn, "INSERT INTO feetypes (`feeTypeName`,`feeCategoryId`,`feeCollectionId`,`br_id`) VALUES ('" . $feeTypeName . "','" . $feeCategoryId . "','" . $fc3 . "','" . $br_id . "')");
					
					mysqli_query($conn, "INSERT INTO feetypes (`feeTypeName`,`feeCategoryId`,`feeCollectionId`,`br_id`) VALUES ('" . $feeTypeName . "','" . $feeCategoryId . "','" . $fc4 . "','" . $br_id . "')");
					
					mysqli_query($conn, "INSERT INTO feetypes (`feeTypeName`,`feeCategoryId`,`feeCollectionId`,`br_id`) VALUES ('" . $feeTypeName . "','" . $feeCategoryId . "','" . $fc5 . "','" . $br_id . "')");
					
					mysqli_query($conn, "INSERT INTO feetypes (`feeTypeName`,`feeCategoryId`,`feeCollectionId`,`br_id`) VALUES ('" . $feeTypeName . "','" . $feeCategoryId . "','" . $fc6 . "','" . $br_id . "')");
				} **/
				
					
					
            } 
 
            // Close opened CSV file
            fclose($csvFile);
 
            header("Location: index.php");
         
    }
    else
    {
        echo "Please select valid file";
    }
}

function sanitize($line){
    $line = str_replace('"',' ',$line);
	$line = str_replace("'",' ',$line);
	return $line;
}
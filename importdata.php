<?php
if(!session_id()){ 
    session_start(); 
} 

include 'database.php';

$res_status = $res_msg = ''; 
if (isset($_POST['importSubmit'])) {

    if(isset($_SESSION['email'])){
        $_SESSION['administrator'] = $_SESSION['email'];
    }
    if (is_uploaded_file($_FILES['file']['tmp_name'])) {
        $csvFile = $_FILES['file']['tmp_name'];
        $csvMimes = array('text/x-csv', 'text/csv', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-comma-separated-values', 'text/comma-separated-values', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)) {
            $csvData = fopen($csvFile, 'r');
            fgetcsv($csvData); 

            while (($line = fgetcsv($csvData)) !== FALSE) {
                $filteredline = array_filter($line); // Filter out empty values
                if(!empty($filteredline)){
                $firstname = $line[0];
                $lastname = $line[1];
                $email = $line[2];
                $personal_email = $line[3];
                $password = $line[4];
                $barangay = $line[5];
                $city_municipality = $line[6];
                $accessrole = $line[7];
                $organization = $line[8];
                $is_verified = $line[9];
                $import_date = date('m-d-Y'); // Current date uploaded
                $imported_by = $_SESSION["administrator"]; // Assuming $administrator is defined somewhere in your code

                // Prepare and execute the SQL query to insert data
                $stmt = $connection->prepare("INSERT INTO tempaccstbl (firstname, lastname, email, personal_email, password, barangay, city_municipality, accessrole, organization, is_verified, import_date, imported_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssssssss",$firstname, $lastname, $email, $personal_email, $password, $barangay, $city_municipality, $accessrole, $organization, $is_verified, $import_date, $imported_by);

                if (!$stmt->execute()) {
                    $error = true;
                    break;
                }
            }
            }
            fclose($csvData);

            $res_status = 'success'; 
            $res_msg = 'Members data has been imported successfully.'; 
        }else{ 
            $res_status = 'danger'; 
            $res_msg = 'Please select a valid CSV file.'; 
        } 
    }else{ 
        $res_status = 'danger'; 
        $res_msg = 'Something went wrong, please try again.'; 
    } 
 
    // Store status in SESSION 
    $_SESSION['response'] = array( 
        'status' => $res_status, 
        'msg' => $res_msg 
    ); 
} 
 
// Redirect to the listing page 
header("Location: adminpage.php"); 
exit(); 
 
?>
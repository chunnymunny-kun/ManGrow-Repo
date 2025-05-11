<?php
session_start();
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create event_images directory if it doesn't exist
    $upload_dir = 'uploads/event_images/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Get form data
    $program_type = $_POST['program_type'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $organization = $_POST['organization'];
    $venue = isset($_POST['venue']) ? $_POST['venue'] : null;
    $barangay = isset($_POST['barangay']) ? $_POST['barangay'] : null;
    $city_municipality = isset($_POST['city']) ? $_POST['city'] : null;
    $area_no = isset($_POST['area_no']) ? $_POST['area_no'] : null;
    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
    $eco_points = isset($_POST['bounty']) ? $_POST['bounty'] : 0;
    $participants = 0;
    $created_at = date('Y-m-d H:i:s');
    if($progam_type == 'Announcement'){
        $is_approved = 'Approved';
    }else if($progam_type != 'Announcement' && $_SESSION['accessrole'] == 'Barangay Official'){
        $is_approved = 'Approved';
    }else{
        $is_approved = 'Pending';
    }
    $qr_url = $_POST['qrtext']; 
    $qr_image = '';

    $thumbnail_name = $_FILES['thumbnail']['name'];
    $thumbnail_tmp = $_FILES['thumbnail']['tmp_name'];
    $thumbnail_error = $_FILES['thumbnail']['error'];
    
    $file_extension = pathinfo($thumbnail_name, PATHINFO_EXTENSION);
    $unique_filename = uniqid() . '.' . $file_extension;
    $thumbnail_path = $upload_dir . $unique_filename;

    $thumbnail_data = null;
    if ($thumbnail_error === UPLOAD_ERR_OK) {
        // Move the uploaded file
        if (move_uploaded_file($thumbnail_tmp, $thumbnail_path)) {
            $image_data = file_get_contents($thumbnail_path);
            $mime_type = mime_content_type($thumbnail_path);
            $thumbnail_data = 'data:' . $mime_type . ';base64,' . base64_encode($image_data);
        } else {
            $_SESSION['response'] = [
                'status' => 'error',
                'msg' => 'Error moving uploaded file'
            ];
            header("Location: events.php");
            exit();
        }
    }

    // Get the QR image data from the hidden input (added in JavaScript below)
    if (isset($_POST['qr_image_data'])) {
        $qr_image = $_POST['qr_image_data'];
    }
    if(isset($_SESSION['name'])){
        $author = $_SESSION['name'];
    }

    // Insert into database
    $sql = "INSERT INTO eventstbl (
            program_type, title, description, organization, 
            thumbnail, thumbnail_data, venue, barangay, 
            city_municipality, area_no, start_date, end_date, 
            participants, created_at, is_approved, eco_points,
            qr_url, qr_image, author
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $connection->prepare($sql);
    $stmt->bind_param(
        "ssssssssssssissssss",
        $program_type, $title, $description, $organization,
        $thumbnail_path,
        $thumbnail_data, 
        $venue, $barangay,
        $city_municipality, $area_no, $start_date, $end_date,
        $participants, $created_at, $is_approved, $eco_points,
        $qr_url, $qr_image, $author
    );

    if ($stmt->execute()) {
        $_SESSION['response'] = [
            'status' => 'success',
            'msg' => 'Event created successfully! Awaiting approval.'
        ];
    } else {
        if (file_exists($thumbnail_path)) {
            unlink($thumbnail_path);
        }
        $_SESSION['response'] = [
            'status' => 'error',
            'msg' => 'Error creating event: ' . $connection->error
        ];
    }

    $stmt->close();
    $connection->close();
    
    header("Location: events.php");
    exit();
}
?>
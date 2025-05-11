<?php
session_start();
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = $_POST['event_id'];
    $approval_status = $_POST['approval_status'];
    if(isset($_SESSION['name'])){
    $admin_name = $_SESSION['name'];
    }
    $disapproval_note = null;
    $notification_message = "Your event has been " . strtolower($approval_status);

    if ($approval_status == 'Disapproved' && !empty($_POST['disapproval_reason'])) {
        $disapproval_note = $_POST['disapproval_reason'];
    }

    // Validate inputs
    if (!empty($event_id) && in_array($approval_status, ['Approved', 'Disapproved'])) {
        $query = "UPDATE eventstbl SET is_approved = ?, disapproval_note = ?, approved_by = ?, approval_date = NOW() WHERE event_id = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("sssi", $approval_status, $disapproval_note, $admin_name, $event_id);
        
        if ($stmt->execute()) {
            $_SESSION['response'] = [
                'status' => 'success',
                'msg' => 'Event status updated successfully'
            ];
        } else {
            $_SESSION['response'] = [
                'status' => 'error',
                'msg' => 'Error updating event status'
            ];
        }
        $stmt->close();
    } else {
        $_SESSION['response'] = [
            'status' => 'error',
            'msg' => 'Invalid data submitted'
        ];
    }
    
    header("Location: ".$_SERVER['HTTP_REFERER']);
    exit();
}
?>
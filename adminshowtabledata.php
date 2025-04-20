<?php
header('Content-Type: application/json');

function filtertable($city = null, $barangay = null) {
    include 'database.php';
    
    try {
        $query = "SELECT * FROM tempaccstbl WHERE 1=1";
        // Add city filter only if provided and not empty
        if (!empty($city)) {
            $citymunicipality = mysqli_real_escape_string($connection, $city);
            $query .= " AND city_municipality = '$citymunicipality'";
        }
        // Add barangay filter only if provided and not empty
        if (!empty($barangay)) {
            $cmbarangay = mysqli_real_escape_string($connection, $barangay);
            $query .= " AND barangay = '$cmbarangay'";
        }
        $result = mysqli_query($connection, $query);
        
        if (!$result) {
            throw new Exception("Database error: " . mysqli_error($connection));
        }
        
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        
        return $data;
    } catch (Exception $e) {
        http_response_code(500);
        return ['error' => $e->getMessage()];
    }
}

// Handle AJAX request
if (isset($_GET['action']) && $_GET['action'] === 'filter') {
    $city = $_GET['city'] ?? null;
    $barangay = $_GET['barangay'] ?? null;
    
    // Convert empty strings to null
    $city = $city === '' ? null : $city;
    $barangay = $barangay === '' ? null : $barangay;
    
    $filteredData = filtertable($city, $barangay);
    echo json_encode($filteredData);
    exit;
}
?>
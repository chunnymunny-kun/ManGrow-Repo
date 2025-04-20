<?php
    function getcitymunicipality(){
        include 'database.php';
        $query = "SELECT * FROM citymunicipalitytbl";
        $result = mysqli_query($connection,$query);
        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }
        return $data;
    }

    if(isset($_GET["city"])){
       echo getBarangays($_GET["city"]);
    }


    function getBarangays($city){
        include 'database.php';
    
        // Sanitize the input
        $citymunicipality = mysqli_real_escape_string($connection, $city);
        
        $query = "SELECT * FROM barangaytbl WHERE city_municipality = '$citymunicipality'";
        $result = mysqli_query($connection, $query);
        
        if(!$result) {
            return json_encode(["error" => mysqli_error($connection)]);
        }
        
        $data = [];
        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }
        
        return json_encode($data);
    }

?>
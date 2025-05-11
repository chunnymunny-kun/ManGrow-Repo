<?php
    session_start();
    include 'database.php';


    if(isset($_SESSION["accessrole"])){
        // Check if role is NOT in allowed list
        if($_SESSION["accessrole"] != 'Barangay Official' && 
           $_SESSION["accessrole"] != 'Administrator' && 
           $_SESSION["accessrole"] != 'Representative') {
            $_SESSION['response'] = [
                'status' => 'error',
                'msg' => 'This account is not authorized'
            ];
            header("Location: index.php");
            exit();
        }
        
        // Set variables if logged in with proper role
        if(isset($_SESSION["fullname"])){
            $email = $_SESSION["fullname"];
        }
        if(isset($_SESSION["accessrole"])){
            $accessrole = $_SESSION["accessrole"];
        }
    } else {
        // No accessrole set at all - redirect to login
        $_SESSION['response'] = [
            'status' => 'error',
            'msg' => 'Please login first'
        ];
        header("Location: index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mangrove Map</title>
    <link rel="stylesheet" href="adminpage.css">
    <link rel="stylesheet" href="adminmappage.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="bataanbarangay.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <script type ="text/javascript" src ="app.js" defer></script>

</head>
<body>
    <header>
        <div class="header-logo"><span class="logo"><i class='bx bxs-leaf'></i>ManGrow</span></div>
        <nav class = "navbar">
            <ul class = "nav-list">
                <li><a href="adminpage.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M240-200h120v-200q0-17 11.5-28.5T400-440h160q17 0 28.5 11.5T600-400v200h120v-360L480-740 240-560v360Zm-80 0v-360q0-19 8.5-36t23.5-28l240-180q21-16 48-16t48 16l240 180q15 11 23.5 28t8.5 36v360q0 33-23.5 56.5T720-120H560q-17 0-28.5-11.5T520-160v-200h-80v200q0 17-11.5 28.5T400-120H240q-33 0-56.5-23.5T160-200Zm320-270Z"/></svg></a></li>
                <li><a href="adminaccspage.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M600-120v-120H440v-400h-80v120H80v-320h280v120h240v-120h280v320H600v-120h-80v320h80v-120h280v320H600ZM160-760v160-160Zm520 400v160-160Zm0-400v160-160Zm0 160h120v-160H680v160Zm0 400h120v-160H680v160ZM160-600h120v-160H160v160Z"/></svg></a></li>
                <li class="active"><a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q152 0 263.5 98T876-538q-20-10-41.5-15.5T790-560q-19-73-68.5-130T600-776v16q0 33-23.5 56.5T520-680h-80v80q0 17-11.5 28.5T400-560h-80v80h240q11 0 20.5 5.5T595-459q-17 27-26 57t-9 62q0 63 32.5 117T659-122q-41 20-86 31t-93 11Zm-40-82v-78q-33 0-56.5-23.5T360-320v-40L168-552q-3 18-5.5 36t-2.5 36q0 121 79.5 212T440-162Zm340 82q-7 0-12-4t-7-10q-11-35-31-65t-43-59q-21-26-34-57t-13-65q0-58 41-99t99-41q58 0 99 41t41 99q0 34-13.5 64.5T873-218q-23 29-43 59t-31 65q-2 6-7 10t-12 4Zm0-113q10-17 22-31.5t23-29.5q14-19 24.5-40.5T860-340q0-33-23.5-56.5T780-420q-33 0-56.5 23.5T700-340q0 24 10.5 45.5T735-254q12 15 23.5 29.5T780-193Zm0-97q-21 0-35.5-14.5T730-340q0-21 14.5-35.5T780-390q21 0 35.5 14.5T830-340q0 21-14.5 35.5T780-290Z"/></svg></a></li>
                <li><a href="adminreportpage.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M320-600q17 0 28.5-11.5T360-640q0-17-11.5-28.5T320-680q-17 0-28.5 11.5T280-640q0 17 11.5 28.5T320-600Zm0 160q17 0 28.5-11.5T360-480q0-17-11.5-28.5T320-520q-17 0-28.5 11.5T280-480q0 17 11.5 28.5T320-440Zm0 160q17 0 28.5-11.5T360-320q0-17-11.5-28.5T320-360q-17 0-28.5 11.5T280-320q0 17 11.5 28.5T320-280ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h440l200 200v440q0 33-23.5 56.5T760-120H200Zm0-80h560v-400H600v-160H200v560Zm0-560v160-160 560-560Z"/></svg></a></li>
            </ul>
        </nav>
        
        <?php    
        if (isset($_SESSION["email"])) {
            $loggeduser = $_SESSION["email"];
            echo "<div class='userbox'><a href='#' id='login' onclick='LoginToggle()'>$loggeduser</a></div>"; // Display the name with a link to profile.php
        }else{
            ?> <div class ="admin-user"></div> <?php
        }
        ?>
    </header>
    <main class="map-container">
        <div class="map-box">
<?php 
    $statusType = '';
    $statusMsg = '';
    //<!-- Flash Message Display -->
    if(!empty($_SESSION['response'])): ?>
    <div class="flash-container">
        <div class="flash-message flash-<?= $_SESSION['response']['status'] ?>">
            <?= $_SESSION['response']['msg'] ?>
        </div>
    </div>
    <?php 
    unset($_SESSION['response']); 
    endif; 
    ?>
    <!-- Display status message -->
    <?php if(!empty($statusMsg)){ ?>
    <div class="col-xs-12">
        <div class="alert alert-<?php echo $status; ?>"><?php echo $statusMsg; ?></div>
    </div>
    <?php } ?>
    <div class="profile-details close" id="profile-details">
            <div class ="details-box">
            <h2><?php 
            if(isset($_SESSION["name"])){
                $loggeduser = $_SESSION["name"];
                echo $loggeduser; 
            }else{
                echo "";
            }
            ?></h2>
            <p><?php
             if(isset($_SESSION["email"])){
                $email = $_SESSION["email"];
                echo $email;
            }else{
                echo "";
            }
             ?></p>
            <p><?php
            if(isset($_SESSION["accessrole"])){
                $accessrole = $_SESSION["accessrole"];
                echo $accessrole;
            }else{
                echo "";
            }
            ?></p>
            <p><?php
            if(isset($_SESSION["organization"])){
                $accessrole = $_SESSION["organization"];
                echo $accessrole;
            }else{
                echo "";
            }
            ?></p>
            <button type="button" name="logoutbtn" onclick="window.location.href='adminlogout.php';">Log Out <i class="fa fa-sign-out" aria-hidden="true"></i></button>
            <?php
                if(isset($_SESSION["accessrole"]) && $_SESSION["accessrole"] == "Barangay Official"){
                    ?><button type="button" name="returnbtn" onclick="window.location.href='index.php';">Back to Home <i class="fa fa-angle-double-right"></i></button><?php
                }
            ?>
            </div>
        </div>
        <!-- map for mangrove areas in Bataan -->
        <div id="map"></div>
        </div>
        <div class="adminmap-main">
            <h1 class="map-heading">Mangrove Monitoring</h1>
            <div class="admin-profile">
            <h3>User Profile</h3>
            <div class="profile-info">
                <p><strong>Name:</strong> <?= $_SESSION["name"] ?? "Guest" ?></p>
                <p><strong>Email:</strong> <?= $_SESSION["email"] ?? "Not logged in" ?></p>
                <p><strong>Role:</strong> <?= $_SESSION["accessrole"] ?? "Visitor" ?></p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h3>Quick Actions</h3>
            <button class="action-btn" onclick="window.location.href='adminreportpage.php'">
                <i class="fa fa-file-text"></i> View Submission Reports
            </button>
            <button class="action-btn" onclick="window.location.href='add_marker.php'" disabled>
                <i class="fa fa-map-marker"></i> Add New Marker (disabled)
            </button>
        </div>

        <!-- Statistics Dashboard -->
        <div class="stats-dashboard">
            <h3>Mangrove Statistics</h3>
            <div class="stat-cards">
                <div class="stat-card">
                    <h4>Total Area</h4>
                    <p id="total-area">Loading...</p>
                </div>
                <div class="stat-card">
                    <h4>Protected Zones</h4>
                    <p id="protected-zones">Loading...</p>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="recent-activity">
            <h3>Recent Changes</h3>
            <ul id="activity-feed">
                <li>User updated Abucay mangrove data</li>
                <li>New marker added in Balanga</li>
                <li>System backup completed</li>
            </ul>
        </div><div class="recent-activity">
            <h3>Recent Changes</h3>
            <ul id="activity-feed">
                <li>User updated Abucay mangrove data</li>
                <li>New marker added in Balanga</li>
                <li>System backup completed</li>
            </ul>
        </div>
        </div>
</main>
    <footer>
        <div id="right-footer">
            <h3>Follow us on</h3>
            <div id="social-media-footer">
                <ul>
                    <li>
                        <a href="#">
                            <i class="fab fa-facebook"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <p>This website is developed by ManGrow. All Rights Reserved.</p>
            <p class="credits">
                Data Sources: © Global Mangrove Watch, © OpenStreetMap contributors, © MapTiler
            </p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js" defer></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" defer></script>
    <script type = "text/javascript">
        function selectcitymunicipality(citymunicipality){
            console.log(Barangay);
        }
    </script>
    <script>
        //default map
        var map = L.map('map').setView([14.64852, 120.47318], 11.2);
        //attribution credits
        map.attributionControl.addAttribution('Mangrove data © <a href="https://www.globalmangrovewatch.org/" target="_blank">Global Mangrove Watch</a>');
        map.attributionControl.addAttribution('<a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>');
        map.attributionControl.addAttribution('&copy; Stadia Maps, Stamen Design, OpenMapTiles & OSM contributors');

        //map layering arrangement for mangrove areas
        map.createPane('cmPane').style.zIndex = 200;
        map.createPane('exmangrovePane').style.zIndex = 400;
        map.createPane('mangrovePane').style.zIndex = 500; 
        //registered city municipalities layer group 
        var cmlayer = L.layerGroup({pane:'cmPane'}).addTo(map);
        //toggles checkbox for mangrove areas
        var mangrovelayer = L.layerGroup({pane:'exmangrovePane'}).addTo(map);
        var extendedmangrovelayer = L.layerGroup({pane:'mangrovePane'}).addTo(map);
    // Base maps
    var osm = L.tileLayer('https://api.maptiler.com/maps/openstreetmap/{z}/{x}/{y}.jpg?key=w1gk7TVN9DDwIGdvJ31q', {
        attribution: '',
    }).addTo(map);

    //city municipality fetch data
    fetch('bataanbarangay.geojson')
            .then(response => response.json())
            .then(data => {
                L.geoJSON(data, {
                    style:function(feature){
                        return {
                            color: feature.properties.stroke || '#000000',
                            weight: feature.properties['stroke-width'] || 2,
                            opacity: feature.properties['stroke-opacity'] || 1,
                            fillColor: feature.properties.fill || '#da75e1',
                            fillOpacity: feature.properties['fill-opacity'] || 0.2
                        };
                    },
                    onEachFeature: (feature, layer) => {
                        layer.bindPopup(`<b>${feature.properties.city_municipality}</b>`);
                    }
                }).addTo(cmlayer);
            });
    //extended mangrove area fetch data
    fetch('extendedmangroveareas.json')
        .then(response => response.json())
        .then(data => {
            L.geoJSON(data, {
                onEachFeature: (feature, layer) => {
                    layer.bindPopup(`<b>${feature.properties.PXLVAL}</b>`);
                }
            }).addTo(extendedmangrovelayer);
        });    
    //mangrove area fetch data
    fetch('mangrove2020.json')
        .then(response => response.json())
        .then(data => {
            L.geoJSON(data, {
                style: function(feature){
                    return{
                        color:'#000000',
                        weight: 2,
                        opacity: 1,
                        fillColor: 'azure',
                        fillOpacity: 0.5
                    };
                },
                onEachFeature: (feature, layer) => {
                    layer.bindPopup(`<b>${feature.properties.Area}</b>`);
                    layer.on({
                        mouseover: function(e) {
                            this.openPopup();
                        },
                        mouseout: function(e) {
                            this.closePopup();
                        }
                    });
                }
            }).addTo(mangrovelayer);
        });   
             

   var SatelliteStreets = L.tileLayer('https://api.maptiler.com/maps/hybrid/{z}/{x}/{y}.jpg?key=w1gk7TVN9DDwIGdvJ31q', {
        attribution: '',
        maxZoom: 20
    });

    var StamenWatercolor = L.tileLayer('https://tiles.stadiamaps.com/tiles/stamen_watercolor/{z}/{x}/{y}.{ext}', {
        minZoom: 1,
        maxZoom: 16,
        attribution: '',
        ext: 'jpg'
    });

    var StamenTerrain = L.tileLayer('https://tiles.stadiamaps.com/tiles/stamen_terrain/{z}/{x}/{y}{r}.{ext}', {
        minZoom: 0,
        maxZoom: 16,
        attribution: '',
        ext: 'png'
    });

    // Layer control
    var baseMaps = {
        'Default': osm,
        'Satellite Streets': SatelliteStreets,
        'Water Color': StamenWatercolor,
        'Terrain': StamenTerrain
    };

    var overlayMaps = {
        'City/Municipality': cmlayer,
        'Mangrove Areas': mangrovelayer,
        'Extended Mangroves Areas': extendedmangrovelayer
    }

    // Add layer control
    var layerControl = L.control.layers(baseMaps,overlayMaps).addTo(map);

    // Add default layers
    osm.addTo(map);
    </script>
</body>
</html>
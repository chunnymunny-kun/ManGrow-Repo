<?php
    session_start();

    if(isset($_SESSION["name"])){
        $loggeduser = $_SESSION["name"];
    }else{
        header("Location: reportlogin.php");
        exit();
    }
    if(isset($_SESSION["email"])){
        $email = $_SESSION["email"];
    }
    if(isset($_SESSION["accessrole"])){
        $accessrole = $_SESSION["accessrole"];
    }
    // Get the event_id parameter from the URL
    $event_id = isset($_GET['event_id']) ? htmlspecialchars($_GET['event_id']) : '';
    $title = isset($_GET['title']) ? htmlspecialchars($_GET['title']) : '';
    $program_type = isset($_GET['programType']) ? htmlspecialchars($_GET['programType']) : '';
    $venue = isset($_GET['venue']) ? htmlspecialchars($_GET['venue']) : '';
    $area_no = isset($_GET['areaNo']) ? htmlspecialchars($_GET['areaNo']) : '';
    $barangay = isset($_GET['barangay']) ? htmlspecialchars($_GET['barangay']) : '';
    $city = isset($_GET['city']) ? htmlspecialchars($_GET['city']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Form</title>
    <link rel="stylesheet" href="reportform.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script type ="text/javascript" src ="app.js" defer></script>
</head>
<body>
    <header>
    <h1>Report Form</h1>
        <nav class = "navbar">
            <?php 
            if (isset($_SESSION["name"])) {
                // Show profile icon when logged in
                echo '<div class="userbox" onclick="toggleProfilePopup(event)">';
                if(isset($_SESSION['profile_image']) && !empty($_SESSION['profile_image'])) {
                    echo '<img src="'.$_SESSION['profile_image'].'" alt="Profile Image" class="profile-icon">';
                } else {
                    echo '<div class="default-profile-icon"><i class="fas fa-user"></i></div>';
                }
                echo '</div>';
            } else {
                // Show login link when not logged in
                echo '<a href="login.php" class="login-link">Login</a>';
            }
            ?>
            </nav>
        </header>
    <main>
        <?php if(!empty($_SESSION['response'])): ?>
        <div class="flash-container">
            <div class="flash-message flash-<?= $_SESSION['response']['status'] ?>">
                <?= $_SESSION['response']['msg'] ?>
            </div>
        </div>
        <?php 
        unset($_SESSION['response']); 
        endif; 
        ?>
            <!-- Profile Details Popup (positioned relative to header) -->
        <div class="profile-details close" id="profile-details">
            <div class="details-box">
                <?php
                if(isset($_SESSION['profile_image']) && !empty($_SESSION['profile_image'])) {
                        echo '<img src="'.$_SESSION['profile_image'].'" alt="Profile Image" class="big-profile-icon">';
                    } else {
                        echo '<div class="big-default-profile-icon"><i class="fas fa-user"></i></div>';
                    }
                ?>
                <h2><?= isset($_SESSION["name"]) ? $_SESSION["name"] : "" ?></h2>
                <p><?= isset($_SESSION["email"]) ? $_SESSION["email"] : "" ?></p>
                <p><?= isset($_SESSION["accessrole"]) ? $_SESSION["accessrole"] : "" ?></p>
                <p><?= isset($_SESSION["organization"]) ? $_SESSION["organization"] : "" ?></p>
                </div>
        <button type="button" name="logoutbtn" onclick="window.location.href='logout.php';">Log Out <i class="fa fa-sign-out" aria-hidden="true"></i></button>
        </div>
        <!-- reports page -->
        <div class="form-div">
            <?php
                if (isset($_GET['end_date'])) {
                    $end_date = htmlspecialchars($_GET['end_date']);
                    $current_date = date('Y-m-d');
                    if ($current_date > $end_date) {
                        ?><div class="form-content-strip">
                        <form action="" method="post" autocomplete="off" class="mangrove-form" enctype="multipart/form-data">
                        <div class="form-content">
                            <h3>The form is not accepting any responses.</h3><br>
                            <div class="form-group">
                                <div class="introduction">
                                <p style="font-size:18px; line-height:2rem;">Thank you for taking your time but unfortunately you cannot submit reports anymore as the event has already ended. </p>
                                </div>
                            </div>
                        </div>
                        </form>
                          </div>
                        <?php
                    }
                } 
                if(isset($_SESSION['accessrole']) && $_SESSION['accessrole'] == "Resident"){
                    //forms for residents
                    //event form will vary depending on what type of status report is detected
                    if ($program_type === 'Status Report') {
                    ?><div class="form-content-strip">
                    <form action="" method="post" autocomplete="off" class="mangrove-form" enctype="multipart/form-data">
                        <div class="form-content">
                            <div class="introduction">
                                <p>Thank you for participating in the event <strong><?php echo $title; ?></strong>.</p>
                                <br>
                                <p>This event compliance form is classified under <strong><?php echo $program_type; ?></strong>. Here are some of the location details from our event:</p>
                                <p>Venue: <strong><?php echo $venue; ?></strong></p>
                                <p>Barangay: <strong><?php echo $barangay; ?></strong></p>
                                <p>City/Municipality: <strong><?php echo $city; ?></strong></p>
                                <p>Area Number: <strong><?php echo $area_no; ?></strong>.</p>
                                <br>
                                <p>Please provide accurate and detailed information to ensure proper documentation and follow-up.</p>
                            </div>
                        </div>
                        <!-- Measurement Method Selection (Global for all trees) -->
                        <div class="form-content">
                            <h3>Measurement Method</h3>
                            <div class="form-group">
                                <label>Select Measurement Type*</label>
                                <div class="radio-group">
                                    <label><input type="radio" name="global_measure_method" value="visual_estimate" checked> Visual Estimate</label>
                                    <label><input type="radio" name="global_measure_method" value="height_pole"> Height Pole</label>
                                </div>
                            </div>
                        </div>
                
                        <!-- Tree Measurements Container -->
                        <div id="tree-measurements-container">
                            <!-- Default First Tree -->
                            <div class="form-content tree-measurement">
                                <h3>Tree #1</h3>
                                <div class="form-group">
                                    <label for="species_1">Species*</label>
                                    <select id="species_1" name="species[]" required>
                                        <option value="">Select species</option>
                                        <option value="rhizophora">Rhizophora</option>
                                        <option value="avicennia">Avicennia</option>
                                    </select>
                                </div>
                
                                <!-- Height Fields (Dynamically shown based on global method) -->
                                <div class="visual-estimate-fields">
                                    <div class="form-group">
                                        <label for="height_estimate_1">Height Estimate*</label>
                                        <select id="height_estimate_1" name="height_estimate[]" required>
                                            <option value="">Select range</option>
                                            <option value="0.25">0-0.5m (Knee height)</option>
                                            <option value="0.75">0.5-1m (Waist height)</option>
                                            <option value="1.5">1-2m (Chest height)</option>
                                            <option value="2.5">2-3m (Above head)</option>
                                            <option value="4">3-5m (1-story)</option>
                                        </select>
                                    </div>
                                </div>
                
                                <div class="height-pole-fields" style="display:none;">
                                    <div class="form-group">
                                        <label for="exact_height_1">Exact Height (meters)*</label>
                                        <input type="number" id="exact_height_1" name="exact_height[]" min="0.1" step="0.01" placeholder="1.5">
                                    </div>
                                </div>
                            </div>
                        </div>
                
                        <!-- Add tree button -->
                        <button type="button" id="add-tree-btn" class="secondary-btn">+ Add Another Tree</button>
                        <!-- Avg height Display -->
                        <div class="form-content avg-display">
                            <h3>Summary</h3>
                            <div class="form-group">
                                <label>Average Height:</label>
                                <div id="avg-height-value">No measurements yet</div>
                                <input type="hidden" name="avg_height" id="avg-height-input">
                            </div>
                        </div>

                        <!-- Environment Section -->
                        <div class="form-content">
                            <h3>Environment Details</h3>
                            
                            <div class="form-group">
                                <label for="soil">Soil Condition</label>
                                <select id="soil" name="soil">
                                    <option value="">Select condition</option>
                                    <option value="dry">Dry</option>
                                    <option value="moist">Moist</option>
                                    <option value="waterlogged">Waterlogged</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="water">Water Condition</label>
                                <select id="water" name="water">
                                    <option value="">Select condition</option>
                                    <option value="clear">Clear</option>
                                    <option value="murky">Murky</option>
                                    <option value="polluted">Polluted</option>
                                </select>
                            </div>
                        </div>
                
                        <!-- Photo Documentation -->
                        <div class="form-content">
                        <h3><i class="fa fa-camera"></i> Photo Evidence</h3>
    
                            <div class="photo-guidelines">
                                <p><strong>Please provide clear photos showing:</strong></p>
                                <ol>
                                    <li>The <strong>entire mangrove</strong> (showing its size and overall condition)</li>
                                    <li>A <strong>close-up</strong> of any damage/issues (if applicable)</li>
                                    <li>The <strong>surrounding area</strong> (to show environmental context)</li>
                                </ol>
                                <p><em>At least 2 photos required (full view + either close-up or surroundings).</em></p>
                            </div>

                            <div class="form-group">
                                <label for="photo-full">Full View of Mangrove*</label>
                                <input type="file" id="photo-full" name="photo_full" accept="image/*" capture="environment" required>
                                <small>Capture the entire tree from a few meters away</small>
                            </div>

                            <div class="form-group">
                                <label for="photo-detail">Close-up or Problem Area</label>
                                <input type="file" id="photo-detail" name="photo_detail" accept="image/*" capture="environment">
                                <small>Zoom in on damaged leaves, trunk issues, or healthy features</small>
                            </div>

                            <div class="form-group">
                                <label for="photo-context">Surrounding Area</label>
                                <input type="file" id="photo-context" name="photo_context" accept="image/*" capture="environment">
                                <small>Show nearby water, other plants, or human activities</small>
                            </div>
                        </div>
                
                        <!-- Additional Notes -->
                        <div class="form-content">
                            <h3>Additional Information</h3>
                            
                            <div class="form-group">
                                <label for="notes">Observations</label>
                                <textarea id="notes" name="notes" rows="3" placeholder="Any unusual changes or threats noticed..."></textarea>
                            </div>
                        </div>
                
                        <button type="submit" name="submit_report" class="submit-btn">Submit Report</button>
                    </form>
                </div>
                    <?php
                    }else if($program_type === 'Tree Planting'){
                        ?><div class="form-content-strip">
                        <form action="submit_planting.php" method="post" autocomplete="off" class="mangrove-form" enctype="multipart/form-data">
                            <div class="form-content">
                                    <div class="introduction">
                                    <p>Thank you for participating in the event <strong><?php echo $title; ?></strong>.</p>
                                    <br>
                                    <p>This event compliance form is classified under <strong><?php echo $program_type; ?></strong>. Here are some of the location details from our event:</p>
                                    <p>Venue: <strong><?php echo $venue; ?></strong></p>
                                    <p>Barangay: <strong><?php echo $barangay; ?></strong></p>
                                    <p>City/Municipality: <strong><?php echo $city; ?></strong></p>
                                    <p>Area Number: <strong><?php echo $area_no; ?></strong>.</p>
                                    <br>
                                    <p>Please provide accurate and detailed information to ensure proper documentation and follow-up.</p>
                                </div>
                            </div>

                            <!-- Participant Information -->
                            <div class="form-content">
                                <h3><i class="fas fa-user"></i> Participant Information</h3>
                                <div class="form-group">
                                    <label for="fullname">Full Name*</label>
                                    <input type="text" id="fullname" name="fullname" required>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="email">Email*</label>
                                        <input type="email" id="email" name="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Mobile Number*</label>
                                        <input type="tel" id="phone" name="phone" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Activity Details -->
                            <div class="form-content">
                                <h3><i class="fas fa-calendar-alt"></i> Activity Details</h3>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="planting_date">Date of Planting*</label>
                                        <input type="date" id="planting_date" name="planting_date" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="planting_time">Time of Activity*</label>
                                        <input type="time" id="planting_time" name="planting_time" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="tree_count">Number of Trees Planted*</label>
                                    <input type="number" id="tree_count" name="tree_count" min="1" required>
                                </div>
                            </div>

                            <!-- Location Information -->
                            <div class="form-content">
                                <h3><i class="fas fa-map-marker-alt"></i> Location Details</h3>
                                <div class="form-group">
                                    <label for="gps_coords">GPS Coordinates*</label>
                                    <div class="input-group">
                                        <input type="text" id="gps_coords" name="gps_coords" placeholder="Latitude, Longitude" required>
                                        <button type="button" class="secondary-btn" onclick="getLocation()">Get Current Location</button>
                                    </div>
                                    <small>Format: 12.3456, 123.4567 or click the button to auto-detect</small>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="barangay">Barangay*</label>
                                        <input type="text" id="barangay" name="barangay" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="site_name">Site Name*</label>
                                        <input type="text" id="site_name" name="site_name" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Mangrove Species -->
                            <div class="form-content">
                                <h3><i class="fas fa-tree"></i> Mangrove Species</h3>
                                <div id="species-container">
                                    <!-- Dynamic species fields will be added here -->
                                    <div class="species-entry">
                                        <div class="form-group">
                                            <label>Species #1*</label>
                                            <select name="species[]" required>
                                                <option value="">Select species</option>
                                                <option value="Rhizophora mucronata">Bakauan-babae (Rhizophora mucronata)</option>
                                                <option value="Rhizophora apiculata">Bakauan-lalaki (Rhizophora apiculata)</option>
                                                <option value="Avicennia marina">Bungalon (Avicennia marina)</option>
                                                <option value="Sonneratia alba">Pagatpat (Sonneratia alba)</option>
                                                <option value="other">Other (specify below)</option>
                                            </select>
                                        </div>
                                        <div class="form-group other-species" style="display:none;">
                                            <label>Specify Other Species</label>
                                            <input type="text" name="other_species[]">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="secondary-btn" id="add-species-btn">+ Add Another Species</button>
                            </div>

                            <!-- Photo Documentation -->
                            <div class="form-content">
                                <h3><i class="fas fa-camera"></i> Photo Evidence*</h3>
                                <div class="photo-guidelines">
                                    <p><strong>Upload clear photos showing:</strong></p>
                                    <ul>
                                        <li>Participants planting mangroves</li>
                                        <li>Close-up of planted seedlings</li>
                                        <li>Wide shot of planting area</li>
                                    </ul>
                                </div>
                                
                                <div class="form-group">
                                    <label for="planting_photo">Planting Activity Photo*</label>
                                    <input type="file" id="planting_photo" name="planting_photo" accept="image/*" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="seedling_photo">Seedling Close-up*</label>
                                    <input type="file" id="seedling_photo" name="seedling_photo" accept="image/*" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="site_photo">Site Overview Photo*</label>
                                    <input type="file" id="site_photo" name="site_photo" accept="image/*" required>
                                </div>
                            </div>

                            <!-- QR Code Verification -->
                            <div class="form-content">
                                <h3><i class="fas fa-qrcode"></i> LGU Verification</h3>
                                <div class="form-group">
                                    <label for="qr_code">QR Code from LGU*</label>
                                    <input type="text" id="qr_code" name="qr_code" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']); ?>" required>
                                    <small>Scan the QR code provided by your Local Government Unit</small>
                                </div>
                            </div>

                            <!-- Monitoring Information -->
                            <div class="form-content">
                                <h3><i class="fas fa-calendar-check"></i> Monitoring Schedule</h3>
                                <div class="form-group">
                                    <label for="monitoring_date">Follow-up Date*</label>
                                    <input type="date" id="monitoring_date" name="monitoring_date" required>
                                </div>
                            </div>

                            <!-- Additional Notes -->
                            <div class="form-content">
                                <h3><i class="fas fa-edit"></i> Additional Notes</h3>
                                <div class="form-group">
                                    <label for="remarks">Remarks/Observations</label>
                                    <textarea id="remarks" name="remarks" rows="3" placeholder="Any challenges faced, special conditions, or additional information..."></textarea>
                                </div>
                            </div>

                            <div class="form-footer">
                                <button type="submit" name="submit_report" class="submit-btn">Submit Participation Form</button>
                            </div>
                        </form>
                    </div>

                        <?php
                    }
                }
                else if(isset($_SESSION['accessrole']) && $_SESSION['accessrole'] == "Barangay Official"){
                    //forms for barangay officials
                    ?><div class="form-content-strip">
                    <form id="mangroveMonitoringForm" class="mangrove-form">
                        <div class="form-content">
                            <div class="introduction">
                            <p>Thank you for participating in the event <strong><?php echo $title; ?></strong>.</p>
                            <br>
                                <p>This event compliance form is classified under <strong><?php echo $program_type; ?></strong>. Here are some of the location details from our event:</p>
                                <p>Venue: <strong><?php echo $venue; ?></strong></p>
                                <p>Barangay: <strong><?php echo $barangay; ?></strong></p>
                                <p>City/Municipality: <strong><?php echo $city; ?></strong></p>
                                <p>Area Number: <strong><?php echo $area_no; ?></strong>.</p>
                                <br>
                                <p>Please provide accurate and detailed information to ensure proper documentation and follow-up.</p>
                            </div>
                        </div>
                        <!-- SECTION 1: TREE STATUS AND BASIC INFORMATION -->
                        <div class="form-content">
                            <h3><i class="fa fa-info-circle"></i> Tree Status and Basic Information</h3>
                            
                            <div class="form-group">
                                <label for="tree-status">Mangrove Status*</label>
                                <select id="tree-status" required>
                                    <option value="">Select status</option>
                                    <option value="alive">Alive</option>
                                    <option value="growing">Growing</option>
                                    <option value="damaged">Damaged</option>
                                    <option value="dead">Dead</option>
                                </select>
                            </div>
                
                            <div class="form-group">
                                <label for="growth-stage">Growth Stage*</label>
                                <select id="growth-stage" required>
                                    <option value="">Select growth stage</option>
                                    <option value="seedling">Seedling (0-1.5m)</option>
                                    <option value="sapling">Sapling (1.5-3m)</option>
                                    <option value="mature">Mature (>3m)</option>
                                </select>
                            </div>
                
                            <div class="form-group">
                                <label for="mangrove-species">Mangrove Species*</label>
                                <select id="mangrove-species" required>
                                    <option value="">Select species</option>
                                    <option value="rhizophora_apliculata">Bakawan Lalake (Rhizophora apliculata)</option>
                                    <option value="rhizophora_mucronata">Bakawan Babae (Rhizophora mucronata)</option>
                                    <option value="avicennia_marina">Bungalon (Avicennia marina)</option>
                                    <option value="sonneratia_alba">Palapat (Sonneratia alba)</option>
                                    <option value="other">Other (specify in notes)</option>
                                </select>
                            </div>
                        </div>
                
                        <!-- SECTION 2: LOCATION INFORMATION -->
                        <div class="form-content">
                            <h3><i class="fa fa-map-marker-alt"></i> Location Information</h3>
                            <div class="form-group">
                                <label>GPS Coordinates*</label>
                                <div class="gps-input">
                                    <input type="text" id="gps-lat" placeholder="Latitude" readonly required>
                                    <input type="text" id="gps-lon" placeholder="Longitude" readonly required>
                                    <button type="button" id="get-location" class="gps-btn">
                                        <i class="fa fa-crosshairs"></i> Get Current Location
                                    </button>
                                </div>
                            </div>
                
                            <div class="form-group">
                                <label for="tidal-zone">Tidal Information*</label>
                                <select id="tidal-zone" required>
                                    <option value="">Select tidal zone</option>
                                    <option value="high">High Tide Area</option>
                                    <option value="low">Low Tide Area</option>
                                    <option value="intertidal">Intertidal Zone</option>
                                </select>
                            </div>
                
                            <div class="form-group">
                                <label for="human-activities">Nearby Human Activities (Select all that apply)</label>
                                <div class="checkbox-group">
                                    <label><input type="checkbox" name="human-activities" value="fishing"> Fishing Activities</label>
                                    <label><input type="checkbox" name="human-activities" value="garbage"> Garbage Dumping</label>
                                    <label><input type="checkbox" name="human-activities" value="housing"> Housing Projects</label>
                                    <label><input type="checkbox" name="human-activities" value="agriculture"> Agriculture</label>
                                    <label><input type="checkbox" name="human-activities" value="tourism"> Tourism</label>
                                    <label><input type="checkbox" name="human-activities" value="none"> None</label>
                                </div>
                            </div>
                        </div>
                
                        <!-- SECTION 3: COMPLIANCE AND STATUS REPORTING -->
                        <div class="form-content">
                            <h3><i class="fa fa-clipboard-check"></i> Compliance and Status Reporting</h3>
                            
                            <div class="form-group">
                                <label for="compliance-status">Compliance Status*</label>
                                <select id="compliance-status" required>
                                    <option value="">Select compliance status</option>
                                    <option value="compliant">Compliant</option>
                                    <option value="non-compliant">Non-Compliant</option>
                                    <option value="partial">Partial Compliance</option>
                                    <option value="pending">Pending Verification</option>
                                </select>
                            </div>
                
                            <div class="form-group">
                                <label for="status-report">Status Reporting Details*</label>
                                <textarea id="status-report" placeholder="Provide details about the current status..." required></textarea>
                            </div>
                
                            <div class="form-group">
                                <label for="notes">Additional Notes</label>
                                <textarea id="notes" placeholder="Any additional observations or comments..."></textarea>
                            </div>
                        </div>
                
                        <!-- SECTION 4: PHOTO DOCUMENTATION -->
                        <div class="form-content">
                            <h3><i class="fa fa-camera"></i> Photo Documentation</h3>
                            
                            <div class="form-group">
                                <label for="tree-photo">Tree Photo* (showing overall condition)</label>
                                <input type="file" id="tree-photo" accept="image/*" capture="environment" required>
                            </div>
                
                            <div class="form-group">
                                <label for="location-photo">Location Photo (showing surrounding area)</label>
                                <input type="file" id="location-photo" accept="image/*" capture="environment">
                            </div>
                
                            <div class="form-group">
                                <label for="damage-photo">Damage Photo (if applicable)</label>
                                <input type="file" id="damage-photo" accept="image/*" capture="environment">
                            </div>
                        </div>
                
                        <button type="submit" class="submit-btn">
                            <i class="fa fa-paper-plane"></i> Submit Report
                        </button>
                    </form>
                </div>
                    <?php
                    }
                    else if(isset($_SESSION['accessrole']) && $_SESSION['accessrole'] == "Environmental Manager"){
                        ?><div class="form-content-strip">
                        <form id="environmentalManagerForm" class="mangrove-form">
                            <!-- SECTION 1: PLANTING ACTIVITY DATA -->
                             <div class="form-content">
                                <div class="introduction">
                                <p>Thank you for participating in the event <strong><?php echo $title; ?></strong>.</p>
                                <br>
                                    <p>This event compliance form is classified under <strong><?php echo $program_type; ?></strong>. Here are some of the location details from our event:</p>
                                    <p>Venue: <strong><?php echo $venue; ?></strong></p>
                                    <p>Barangay: <strong><?php echo $barangay; ?></strong></p>
                                    <p>City/Municipality: <strong><?php echo $city; ?></strong></p>
                                    <p>Area Number: <strong><?php echo $area_no; ?></strong>.</p>
                                    <br>
                                    <p>Please provide accurate and detailed information to ensure proper documentation and follow-up.</p>
                                </div>
                            </div>
                            <div class="form-content">
                                <h3><i class="fa fa-calendar-alt"></i> Planting Activity Data</h3>
                                
                                <div class="form-group">
                                    <label for="planting-date">Planting Date*</label>
                                    <input type="date" id="planting-date" required>
                                </div>
                    
                                <div class="form-group">
                                    <label for="number-planted">Number of Mangroves Planted*</label>
                                    <input type="number" id="number-planted" min="1" required>
                                </div>
                    
                                <div class="form-group">
                                    <label for="number-volunteers">Number of Volunteers*</label>
                                    <input type="number" id="number-volunteers" min="1" required>
                                </div>
                    
                                <div class="form-group">
                                    <label for="group-name">Group/Organization Name</label>
                                    <input type="text" id="group-name" placeholder="Name of organizing group">
                                </div>
                            </div>
                    
                            <!-- SECTION 2: LOCATION DATA -->
                            <div class="form-content">
                                <h3><i class="fa fa-map-marker-alt"></i> Location Data</h3>
                                
                                <div class="form-group">
                                    <label>GPS Coordinates*</label>
                                    <div class="gps-input">
                                        <input type="text" id="gps-lat" placeholder="Latitude" readonly required>
                                        <input type="text" id="gps-lon" placeholder="Longitude" readonly required>
                                        <button type="button" id="get-location" class="gps-btn">
                                            <i class="fa fa-crosshairs"></i> Get Current Location
                                        </button>
                                    </div>
                                </div>
                    
                                <div class="form-group">
                                    <label for="site-description">Site Description*</label>
                                    <textarea id="site-description" placeholder="Describe the planting site characteristics..." required></textarea>
                                </div>
                            </div>
                    
                            <!-- SECTION 3: ENVIRONMENTAL CONDITIONS -->
                            <div class="form-content">
                                <h3><i class="fa fa-flask"></i> Environmental Conditions</h3>
                                
                                <div class="form-group">
                                    <label for="soil-condition">Soil Condition*</label>
                                    <select id="soil-condition" required>
                                        <option value="">Select soil condition</option>
                                        <option value="excellent">Excellent (fertile, good texture)</option>
                                        <option value="good">Good</option>
                                        <option value="fair">Fair (some compaction)</option>
                                        <option value="poor">Poor (compacted, infertile)</option>
                                        <option value="contaminated">Contaminated</option>
                                    </select>
                                </div>
                    
                                <div class="form-group">
                                    <label for="water-condition">Water Condition*</label>
                                    <select id="water-condition" required>
                                        <option value="">Select water condition</option>
                                        <option value="excellent">Excellent (clean, proper salinity)</option>
                                        <option value="good">Good</option>
                                        <option value="fair">Fair (some pollution)</option>
                                        <option value="poor">Poor (polluted, improper salinity)</option>
                                    </select>
                                </div>
                    
                                <div class="form-group">
                                    <label for="environmental-observations">Environmental Observations*</label>
                                    <textarea id="environmental-observations" placeholder="Note any significant observations about the environment..." required></textarea>
                                </div>
                            </div>
                    
                            <!-- SECTION 4: PHOTO DOCUMENTATION -->
                            <div class="form-content">
                                <h3><i class="fa fa-images"></i> Photo Documentation</h3>
                                
                                <div class="form-group">
                                    <label for="before-photo">Before Photo* (pre-planting condition)</label>
                                    <input type="file" id="before-photo" accept="image/*" required>
                                    <input type="date" id="before-photo-date" placeholder="Date of before photo">
                                </div>
                    
                                <div class="form-group">
                                    <label for="after-photo">After Photo* (current condition)</label>
                                    <input type="file" id="after-photo" accept="image/*" required>
                                </div>
                    
                                <div class="form-group">
                                    <label for="additional-photos">Additional Photos (optional)</label>
                                    <input type="file" id="additional-photos" accept="image/*" multiple>
                                </div>
                            </div>
                    
                            <!-- SECTION 5: STATUS REPORTING -->
                            <div class="form-content">
                                <h3><i class="fa fa-clipboard-list"></i> Status Reporting</h3>
                                
                                <div class="form-group">
                                    <label for="survival-rate">Estimated Survival Rate (%)</label>
                                    <input type="number" id="survival-rate" min="0" max="100" placeholder="0-100">
                                </div>
                    
                                <div class="form-group">
                                    <label for="progress-report">Progress Report*</label>
                                    <textarea id="progress-report" placeholder="Detail the progress of the conservation program..." required></textarea>
                                </div>
                    
                                <div class="form-group">
                                    <label for="challenges">Challenges Encountered</label>
                                    <textarea id="challenges" placeholder="List any challenges faced..."></textarea>
                                </div>
                            </div>
                    
                            <button type="submit" class="submit-btn">
                                <i class="fa fa-paper-plane"></i> Submit Conservation<br>Report
                            </button>
                        </form>
                    </div>
                        <?php
                    }
            ?>
        </div>
    </main>
    <footer>
        <div id="right-footer">
            <p>This website is developed by ManGrow. All Rights Reserved.</p>
        </div>
    </footer>            
    <script type ="text/javascript" src ="reportform.js" defer></script>
</body>
</html>
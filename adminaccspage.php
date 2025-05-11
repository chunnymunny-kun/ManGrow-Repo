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
    <title>User Accounts</title>
    <link rel="stylesheet" href="adminpage.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <script type ="text/javascript" src ="adminusers.js" defer></script>
    <script type ="text/javascript" src ="app.js" defer></script>
</head>
<body>
    <header>
        <div class="header-logo"><span class="logo"><i class='bx bxs-leaf'></i>ManGrow</span></div>
        <nav class = "navbar">
            <ul class = "nav-list">
                <li><a href="adminpage.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M240-200h120v-200q0-17 11.5-28.5T400-440h160q17 0 28.5 11.5T600-400v200h120v-360L480-740 240-560v360Zm-80 0v-360q0-19 8.5-36t23.5-28l240-180q21-16 48-16t48 16l240 180q15 11 23.5 28t8.5 36v360q0 33-23.5 56.5T720-120H560q-17 0-28.5-11.5T520-160v-200h-80v200q0 17-11.5 28.5T400-120H240q-33 0-56.5-23.5T160-200Zm320-270Z"/></svg></a></li>
                <li class="active"><a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M600-120v-120H440v-400h-80v120H80v-320h280v120h240v-120h280v320H600v-120h-80v320h80v-120h280v320H600ZM160-760v160-160Zm520 400v160-160Zm0-400v160-160Zm0 160h120v-160H680v160Zm0 400h120v-160H680v160ZM160-600h120v-160H160v160Z"/></svg></a></li>
                <li><a href="adminmappage.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q152 0 263.5 98T876-538q-20-10-41.5-15.5T790-560q-19-73-68.5-130T600-776v16q0 33-23.5 56.5T520-680h-80v80q0 17-11.5 28.5T400-560h-80v80h240q11 0 20.5 5.5T595-459q-17 27-26 57t-9 62q0 63 32.5 117T659-122q-41 20-86 31t-93 11Zm-40-82v-78q-33 0-56.5-23.5T360-320v-40L168-552q-3 18-5.5 36t-2.5 36q0 121 79.5 212T440-162Zm340 82q-7 0-12-4t-7-10q-11-35-31-65t-43-59q-21-26-34-57t-13-65q0-58 41-99t99-41q58 0 99 41t41 99q0 34-13.5 64.5T873-218q-23 29-43 59t-31 65q-2 6-7 10t-12 4Zm0-113q10-17 22-31.5t23-29.5q14-19 24.5-40.5T860-340q0-33-23.5-56.5T780-420q-33 0-56.5 23.5T700-340q0 24 10.5 45.5T735-254q12 15 23.5 29.5T780-193Zm0-97q-21 0-35.5-14.5T730-340q0-21 14.5-35.5T780-390q21 0 35.5 14.5T830-340q0 21-14.5 35.5T780-290Z"/></svg></a></li>
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
    <main>
        <div class ="table-container">
<?php
$recordsPerPage = 15;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $recordsPerPage;

// For temporary accounts
$tempQuery = "SELECT * FROM tempaccstbl";
if (isset($_GET['search'])) {
    $filtervalues = $_GET['search'];
    $tempQuery .= " WHERE CONCAT(firstname, lastname, email, personal_email, barangay, city_municipality, accessrole, organization, is_verified, import_date, imported_by) LIKE '%$filtervalues%'";
}
$tempQuery .= " LIMIT $recordsPerPage OFFSET $offset";

// For verified accounts
$verifiedQuery = "SELECT account_id, fullname, email, personal_email, barangay, city_municipality, accessrole, organization, date_registered, bio FROM accountstbl";
if (isset($_GET['search'])) {
    $filtervalues = $_GET['search'];
    $verifiedQuery .= " WHERE CONCAT(fullname, email, personal_email, barangay, city_municipality, accessrole, organization, date_registered, bio) LIKE '%$filtervalues%'";
}
$verifiedQuery .= " LIMIT $recordsPerPage OFFSET $offset";
?>
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
        <div class="account-heading">
            <h1>User Accounts</h1>
        </div>

        <div class="button-box">    
            <div class="col-md-7">
                <form action="" method="GET">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="search" placeholder="Search user" value="<?php if(isset($_GET['search'])){echo $_GET['search'];}?>">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </form>
            </div>
            <div class="filter-box">
                <button class="btn btn-primary" id="add-user-btn" data-toggle="modal" data-target="#addUserModal">Import Users</button>
                <button class="btn btn-primary" id="add-user-btn" data-toggle="modal" data-target="#sendVerificationModal">Send Verification</button>
            </div>
        </div>

        <div class="account-section">
            <div class="head-filter-container">
                <h2>Temporary Accounts</h2>
                <form name="temp-account-filters" class="filter-form">
                    <label>Select Municipality
                        <select name="cmfilter" class="form-control city-filter" onchange="getBarangays(this, 'temp')">
                            <option value="">All</option>
                            <?php
                            require 'getdropdown.php';
                            $citymunicipalities = getcitymunicipality();
                            foreach($citymunicipalities as $citymunicipality){
                                echo '<option value="'.htmlspecialchars($citymunicipality['city']).'">'.htmlspecialchars($citymunicipality['city']).'</option>';
                            }
                            ?>
                        </select>
                    </label>
                    <label>Select Barangay
                        <select name="bfilter" class="form-control barangay-filter" onchange="filterTable('temp')" disabled>
                            <option value="">All</option>
                        </select>
                    </label>
                </form>
            </div>
        <div class="table-container">
            <table class="table table-hover table-bordered table-striped" id="temp-accs-table">
        <thead>
            <tr>
                <th>Temp Account ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Personal Email</th>
                <th>Barangay</th>
                <th>City/Municipality</th>
                <th>Access Role</th>
                <th>Organization</th>
                <th>Is Verified</th>
                <th>Date Uploaded</th>
                <th>Administrator</th>
            </tr>
        </thead>
        <tbody id="temp-accounts-body">
            <?php
            $query = "SELECT * FROM tempaccstbl";
            if (isset($_GET['search'])) {
                $filtervalues = $_GET['search'];
                $query = "SELECT * FROM tempaccstbl WHERE CONCAT(firstname, lastname, email, personal_email, barangay, city_municipality, accessrole, organization, is_verified, import_date, imported_by) LIKE '%$filtervalues%'";
            }
            $result = mysqli_query($connection, $query);
            if (mysqli_num_rows($result) > 0) {
                while($items = mysqli_fetch_assoc($result)) {
                    echo '<tr>
                        <td>'.$items['tempacc_id'].'</td>
                        <td>'.$items['firstname'].'</td>
                        <td>'.$items['lastname'].'</td>
                        <td>'.$items['email'].'</td>
                        <td>'.$items['personal_email'].'</td>
                        <td>'.$items['barangay'].'</td>
                        <td>'.$items['city_municipality'].'</td>
                        <td>'.$items['accessrole'].'</td>
                        <td>'.$items['organization'].'</td>
                        <td>'.$items['is_verified'].'</td>
                        <td>'.$items['import_date'].'</td>
                        <td>'.$items['imported_by'].'</td>
                    </tr>';
                }
            } else {
                echo '<tr><td colspan="12" class="text-center">No temporary accounts found</td></tr>';
            }
            ?>
        </tbody>
    </table>
    </div>
        <div class="pagination">
        <?php
        $totalTemp = mysqli_query($connection, "SELECT COUNT(*) as total FROM tempaccstbl");
        $totalTemp = mysqli_fetch_assoc($totalTemp)['total'];
        $totalTempPages = ceil($totalTemp / $recordsPerPage);
        
        if ($page > 1) {
            echo '<a href="?page='.($page - 1).(isset($_GET['search']) ? '&search='.urlencode($_GET['search']) : '').'">&laquo; Previous</a>';
        }
        
        for ($i = 1; $i <= $totalTempPages; $i++) {
            $active = $i == $page ? 'active' : '';
            echo '<a class="'.$active.'" href="?page='.$i.(isset($_GET['search']) ? '&search='.urlencode($_GET['search']) : '').'">'.$i.'</a>';
        }
        
        if ($page < $totalTempPages) {
            echo '<a href="?page='.($page + 1).(isset($_GET['search']) ? '&search='.urlencode($_GET['search']) : '').'">Next &raquo;</a>';
        }
        ?>
    </div>
</div>

        <div class="account-section">
        <div class="head-filter-container">
            <h2>Verified Accounts</h2>
            <form name="verified-account-filters" class="filter-form">
                <label>Select Municipality
                    <select name="cmfilter" class="form-control city-filter" onchange="getBarangays(this, 'verified')">
                        <option value="">All</option>
                        <?php
                        foreach($citymunicipalities as $citymunicipality){
                            echo '<option value="'.htmlspecialchars($citymunicipality['city']).'">'.htmlspecialchars($citymunicipality['city']).'</option>';
                        }
                        ?>
                    </select>
                </label>
                <label>Select Barangay
                    <select name="bfilter" class="form-control barangay-filter" onchange="filterTable('verified')" disabled>
                        <option value="">All</option>
                    </select>
                </label>
            </form>
        </div>
    <div class="table-container">
        <table class="table table-hover table-bordered table-striped" id="verified-accs-table">
        <thead>
            <tr>
                <th>Account ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Personal Email</th>
                <th>Barangay</th>
                <th>City/Municipality</th>
                <th>Access Role</th>
                <th>Organization</th>
                <th>Date Registered</th>
                <th>Bio</th>
            </tr>
        </thead>
        <tbody id="verified-accounts-body">
            <?php
            $query = "SELECT account_id, fullname, email, personal_email, barangay, city_municipality, accessrole, organization, date_registered, bio FROM accountstbl";
            if (isset($_GET['search'])) {
                $filtervalues = $_GET['search'];
                $query = "SELECT account_id, fullname, email, personal_email, barangay, city_municipality, accessrole, organization, date_registered, bio FROM accountstbl WHERE CONCAT(fullname, email, personal_email, barangay, city_municipality, accessrole, organization, date_registered, bio) LIKE '%$filtervalues%'";
            }
            $result = mysqli_query($connection, $query);
            if (mysqli_num_rows($result) > 0) {
                while($items = mysqli_fetch_assoc($result)) {
                    echo '<tr>
                        <td>'.$items['account_id'].'</td>
                        <td>'.$items['fullname'].'</td>
                        <td>'.$items['email'].'</td>
                        <td>'.$items['personal_email'].'</td>
                        <td>'.$items['barangay'].'</td>
                        <td>'.$items['city_municipality'].'</td>
                        <td>'.$items['accessrole'].'</td>
                        <td>'.$items['organization'].'</td>
                        <td>'.$items['date_registered'].'</td>
                        <td>'.substr($items['bio'], 0, 50).(strlen($items['bio']) > 50 ? '...' : '').'</td>
                    </tr>';
                }
            } else {
                echo '<tr><td colspan="10" class="text-center">No verified accounts found</td></tr>';
            }
            ?>
        </tbody>
    </table>
    </div>
        <div class="pagination">
        <?php
        $totalVerified = mysqli_query($connection, "SELECT COUNT(*) as total FROM accountstbl");
        $totalVerified = mysqli_fetch_assoc($totalVerified)['total'];
        $totalVerifiedPages = ceil($totalVerified / $recordsPerPage);
        
        if ($page > 1) {
            echo '<a href="?page='.($page - 1).(isset($_GET['search']) ? '&search='.urlencode($_GET['search']) : '').'">&laquo; Previous</a>';
        }
        
        for ($i = 1; $i <= $totalVerifiedPages; $i++) {
            $active = $i == $page ? 'active' : '';
            echo '<a class="'.$active.'" href="?page='.$i.(isset($_GET['search']) ? '&search='.urlencode($_GET['search']) : '').'">'.$i.'</a>';
        }
        
        if ($page < $totalVerifiedPages) {
            echo '<a href="?page='.($page + 1).(isset($_GET['search']) ? '&search='.urlencode($_GET['search']) : '').'">Next &raquo;</a>';
        }
        ?>
    </div>
</div>


<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add user accounts</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12" id="ImportForm">
                    <form action="importdata.php" method="post" enctype="multipart/form-data">
                        <input type="file" name="file" class="filefind"/>
                        <input type="submit" name="importSubmit" value="IMPORT" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="sendVerificationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Send Email Verification by Batch</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12" id="SendEmailForm">
                    <form action="sendemail.php" method="post" enctype="multipart/form-data">
                        <label for="date">Select date uploaded: <input type="date" name="date" class="dateInput"/></label>
                        <input type="submit" name="sendEmail" value="Submit" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>
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
</body>
</html>
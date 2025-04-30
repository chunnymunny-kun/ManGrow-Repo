<?php include 'adminheader.php'; ?>
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

if(!session_id()) {
    session_start();
}
include 'database.php';

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
            <button type="button" name="logoutbtn" onclick="window.location.href='adminlogout.php';">Log Out</button>
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
<?php include 'adminfooter.php'; ?>
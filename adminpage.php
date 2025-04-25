<?php include 'adminheader.php'; ?>
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
        <form action ="" method="GET">
            <div class ="input-group mb-3">
                <input type="text" class="form-control" name="search" placeholder="Search user" value="<?php if(isset($_GET['search'])){echo $_GET['search'];}?>">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>
    </div>
    <div class="filter-box">
    <form name="select-citymunicipality" action="" method="post">
    <label for="cmfilter">Select Municipality
        <select name="cmfilter" id="cmfilter" class="form-control" onchange="getBarangays()">
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
    <label for="bfilter">Select Barangay
        <select name="bfilter" id="bfilter" class="form-control" onchange="filtertable()" disabled>
            <option value="">All</option>
        </select>
    </label>
</form>
        <button class="btn btn-primary" id="add-user-btn" data-toggle="modal" data-target="#addUserModal">Import Users</button>
        <button class="btn btn-primary" id="add-user-btn" data-toggle="modal" data-target="#sendVerificationModal">Send Verification</button>
    </div>
</div>

<table class="table table-hover table-bordered table-striped" id="accs-table">
    <thead>
        <tr>
            <th>Temp Account ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Personal Email</th>
            <th>Password</th>
            <th>Barangay</th>
            <th>City/Municipality</th>
            <th>Access Role</th>
            <th>Organization</th>
            <th>Is Verified</th>
            <th>Date Uploaded</th>
            <th>Administrator</th>
        </tr>
    </thead>
    <tbody id="user-table-body"> <!-- Table body for displaying user data -->
        <?php
        $query = "SELECT * FROM tempaccstbl";
        if (isset($_GET['search'])) {
            $filtervalues = $_GET['search'];
            $query = "SELECT * FROM tempaccstbl where CONCAT(firstname, lastname, email, personal_email, password, barangay, city_municipality, accessrole, organization, is_verified, import_date, imported_by) LIKE '%$filtervalues%' ";
             // filter the table with the given query
        }
        $result = mysqli_query($connection, $query);
        if (!$result) {
            die("Query Failed: " . mysqli_error($connection));
        } else {
            if(mysqli_num_rows($result) > 0){
                while($items = mysqli_fetch_assoc($result)){
                    ?>
                    <tr>
                        <td><?php echo $items['tempacc_id']; ?></td>
                        <td><?php echo $items['firstname']; ?></td>
                        <td><?php echo $items['lastname']; ?></td>
                        <td><?php echo $items['email']; ?></td>
                        <td><?php echo $items['personal_email']; ?></td>
                        <td><?php echo $items['password']; ?></td>
                        <td><?php echo $items['barangay']; ?></td>
                        <td><?php echo $items['city_municipality']; ?></td>
                        <td><?php echo $items['accessrole']; ?></td>
                        <td><?php echo $items['organization']; ?></td>
                        <td><?php echo $items['is_verified']; ?></td>
                        <td><?php echo $items['import_date']; ?></td>
                        <td><?php echo $items['imported_by']; ?></td>
                    </tr>
                    <?php
                }
            }else{
                ?>
                <tr>
                    <td colspan="10" class="text-center">No Record Found</td>
                <?php
            }
        }
        ?>
    </tbody>
</table>

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
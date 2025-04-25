<!--
<?php
    session_start();

    if(isset($_SESSION["name"])){
        $loggeduser = $_SESSION["name"];
    }else{
        // Redirect to login or handle error when user is logged in or not
        header("Location: login.php");
        exit();
    }
    
    if(isset($_SESSION["email"])){
        $email = $_SESSION["email"];
    }
    if(isset($_SESSION["accessrole"])){
        $accessrole = $_SESSION["accessrole"];
    }
?>
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to ManGrow</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="profileform.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script type ="text/javascript" src ="app.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <form action="#" class="searchbar">
            <input type="text" placeholder="Search">
            <button type="submit"><i class='bx bx-search-alt-2'></i></button> 
        </form>
        <nav class = "navbar">
            <a href="#">About</a>
            <a href="#">Events</a>
            <a href="logout.php">Leaderboards</a>
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
    <aside id="sidebar" class="close">  
        <ul>
            <li>
                <span class="logo"><i class='bx bxs-leaf'></i>ManGrow</span>
                <button onclick= "SidebarToggle()"id="toggle-btn" class="rotate">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="m313-480 155 156q11 11 11.5 27.5T468-268q-11 11-28 11t-28-11L228-452q-6-6-8.5-13t-2.5-15q0-8 2.5-15t8.5-13l184-184q11-11 27.5-11.5T468-692q11 11 11 28t-11 28L313-480Zm264 0 155 156q11 11 11.5 27.5T732-268q-11 11-28 11t-28-11L492-452q-6-6-8.5-13t-2.5-15q0-8 2.5-15t8.5-13l184-184q11-11 27.5-11.5T732-692q11 11 11 28t-11 28L577-480Z"/></svg>
                </button>
            </li>
            <hr>
            <li class = "active">
                <a href="index.php" tabindex="-1">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M240-200h120v-200q0-17 11.5-28.5T400-440h160q17 0 28.5 11.5T600-400v200h120v-360L480-740 240-560v360Zm-80 0v-360q0-19 8.5-36t23.5-28l240-180q21-16 48-16t48 16l240 180q15 11 23.5 28t8.5 36v360q0 33-23.5 56.5T720-120H560q-17 0-28.5-11.5T520-160v-200h-80v200q0 17-11.5 28.5T400-120H240q-33 0-56.5-23.5T160-200Zm320-270Z"/></svg>
                    <span>Home</span>
                </a>
            </li>
            <li>
                <a href="dashboard.html" tabindex="-1">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M520-640v-160q0-17 11.5-28.5T560-840h240q17 0 28.5 11.5T840-800v160q0 17-11.5 28.5T800-600H560q-17 0-28.5-11.5T520-640ZM120-480v-320q0-17 11.5-28.5T160-840h240q17 0 28.5 11.5T440-800v320q0 17-11.5 28.5T400-440H160q-17 0-28.5-11.5T120-480Zm400 320v-320q0-17 11.5-28.5T560-520h240q17 0 28.5 11.5T840-480v320q0 17-11.5 28.5T800-120H560q-17 0-28.5-11.5T520-160Zm-400 0v-160q0-17 11.5-28.5T160-360h240q17 0 28.5 11.5T440-320v160q0 17-11.5 28.5T400-120H160q-17 0-28.5-11.5T120-160Zm80-360h160v-240H200v240Zm400 320h160v-240H600v240Zm0-480h160v-80H600v80ZM200-200h160v-80H200v80Zm160-320Zm240-160Zm0 240ZM360-280Z"/></svg>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <button onclick = "DropDownToggle(this)" class="dropdown-btn" tabindex="-1">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-240v-32q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v32q0 33-23.5 56.5T720-160H240q-33 0-56.5-23.5T160-240Zm80 0h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z"/></svg>
                    <span>Create</span>
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z"/></svg>                </button>
                <ul class="sub-menu" tabindex="-1">
                    <div>
                    <li><a href="#" tabindex="-1">Folder</a></li>
                    <li><a href="#" tabindex="-1">Document</a></li>
                    <li><a href="#" tabindex="-1">Project</a></li>
                    </div>
                </ul>
            </li>
            <li>
                <button onclick = "DropDownToggle(this)" class="dropdown-btn" tabindex="-1">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-240v-32q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v32q0 33-23.5 56.5T720-160H240q-33 0-56.5-23.5T160-240Zm80 0h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z"/></svg>
                    <span>To Do Lists</span>
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z"/></svg>                </button>
                <ul class="sub-menu">
                    <div>
                    <li><a href="#" tabindex="-1">Work</a></li>
                    <li><a href="#" tabindex="-1">Private</a></li>
                    <li><a href="#" tabindex="-1">Coding</a></li>
                    <li><a href="#" tabindex="-1">Gardening</a></li>
                    <li><a href="#" tabindex="-1">School</a></li>
                    </div>
                </ul>
            </li>
            <li>
                <a href="random.html" tabindex="-1">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-240v-32q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v32q0 33-23.5 56.5T720-160H240q-33 0-56.5-23.5T160-240Zm80 0h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z"/></svg>
                    <span>Random</span>
                </a>
            </li>
            <li>
                <a href="profile.html" tabindex="-1">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-240v-32q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v32q0 33-23.5 56.5T720-160H240q-33 0-56.5-23.5T160-240Zm80 0h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z"/></svg>
                    <span>Profile</span>
                </a>
            </li>
        </ul>
    </aside>
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
                <div class="profile-link-container">
                    <a href="profileform.php" class="profile-link">Go to Profile <i class="fa fa-angle-double-right"></i></a>
                </div>
        </div>
        <button type="button" name="logoutbtn" onclick="window.location.href='logout.php';">Log Out <i class="fa fa-sign-out" aria-hidden="true"></i></button>
        </div>
        <div class= "home-container">
        <div class="profile-form-strip">
        <div class="prof-img-sec">
            <?php
            $thumbnail = $_SESSION['profile_image'] ?? '';
            $fullImage = $_SESSION['profile_image'] ?? '';
            
            if ($thumbnail): ?>
                <img id="profile-icon" src="<?= htmlspecialchars($thumbnail) ?>" class="profile-icon-large" alt="Profile">
            <?php elseif ($fullImage && file_exists($fullImage)): ?>
                <img id="profile-icon" src="<?= $fullImage ?>" class="profile-icon-large" alt="Profile">
            <?php else: ?>
                <div id="default-profile-icon" class="default-profile-icon-large">
                    <i class="fas fa-user"></i>
                </div>
            <?php endif; ?>
            <!-- Profile Image Upload Form -->
            <form action="uploadprofile.php" method="post" enctype="multipart/form-data">
                <!-- Hidden file input -->
                <div class="form-group" style="margin-top: 15px;">
                    <label for="profile-image" class="file-upload-label">
                        <span>Change Profile Picture</span>
                        <input type="file" id="profile-image" name="profile-image" 
                            accept="image/jpeg,image/png,image/gif" style="display: none;" 
                            onchange="startCropper(this)">
                    </label>
                    <small class="form-text">Max 2MB (JPG or PNG)</small>
                </div>

                <!-- Cropping modal (hidden by default) -->
                <div id="crop-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000; padding-top: 50px;">
                    <div style="background: white; max-width: 800px; margin: 0 auto; padding: 20px; border-radius: 5px;">
                        <div id="cropper-container" style="max-height: 500px;">
                            <img id="image-to-crop" src="#" alt="Image to crop" style="max-width: 100%;">
                        </div>
                        <div style="margin-top: 20px; text-align: center;">
                            <button type="button" onclick="cancelCrop()" class="remove-btn" style="margin-right: 10px;">Cancel</button>
                            <button type="button" onclick="applyCrop()" class="upload-btn">Apply Crop</button>
                        </div>
                    </div>
                </div>

                <!-- Hidden inputs for cropped data -->
                <input type="hidden" id="x" name="x">
                <input type="hidden" id="y" name="y">
                <input type="hidden" id="width" name="width">
                <input type="hidden" id="height" name="height">

                <!-- Action buttons -->
                <div id="action-buttons" style="display: none; margin-top: 15px;">
                    <button type="submit" name="upload-image" class="upload-btn">Save Image</button>
                    <button type="button" onclick="cancelUpload()" class="remove-btn">Cancel</button>
                </div>

                <?php 
                if (isset($_SESSION['profile_image'])) { 
                    echo '<button type="submit" name="remove-image" class="remove-btn" style="margin-top: 15px;">Remove Image</button>';
                }
                ?>
            </form>
        </div>
    
    <div class="prof-form-sec">
        <!-- User Details Form -->
        <form action="updateprofile.php" method="post" class="profile-edit-form">
            <h1>Edit Profile Details</h1>
            
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" 
                       value="<?= isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : '' ?>" 
                       required disabled>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" 
                       value="<?= isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '' ?>" 
                       required disabled>
            </div>
            
            <div class="form-group password-group">
                <label for="password">Password</label>
                <div class="password-input-container">
                    <input type="password" id="password" name="password" placeholder="Enter new password">
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <small class="form-text">Your password must be 8-20 characters long, contain letters and numbers, and must not contain spaces, special characters, or emoji.</small>
            </div>

            <div class="form-group password-group">
                <label for="confirm-password">Confirm Password</label>
                <div class="password-input-container">
                    <input type="confirm-password" id="confirm-password" name="confirm-password" placeholder="Re-enter your new password">
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="form-group">
            <label for="barangay">Barangay</label>
            <input type="text" id="barangay" name="barangay" 
                   value="<?= isset($_SESSION['barangay']) ? htmlspecialchars($_SESSION['barangay']) : '' ?>" 
                   disabled>
            </div>

            <div class="form-group">
            <label for="citymun">City/Municipality</label>
            <input type="text" id="citymun" name="citymun" 
                   value="<?= isset($_SESSION['city_municipality']) ? htmlspecialchars($_SESSION['city_municipality']) : '' ?>" 
                   disabled>
            </div>

            <div class="form-group">
                <label for="accessrole">Role</label>
                <select id="accessrole" name="accessrole" required disabled>
                    <option value="Resident" <?= (isset($_SESSION['accessrole']) && $_SESSION['accessrole'] == 'Resident') ? 'selected' : '' ?>>Resident</option>
                    <option value="Barangay Official" <?= (isset($_SESSION['accessrole']) && $_SESSION['accessrole'] == 'Admin') ? 'selected' : '' ?>>Barangay Official</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="organization">Organization</label>
                <input type="text" id="organization" name="organization" 
                       value="<?= isset($_SESSION['organization']) ? htmlspecialchars($_SESSION['organization']) : '' ?>">
            </div>
            
            <div class="form-group">
                <label for="bio">Bio</label>
                <textarea id="bio" name="bio" rows="4"><?= isset($_SESSION['bio']) ? htmlspecialchars($_SESSION['bio']) : '' ?></textarea>
            </div>
            
            <div class="form-actions">
                <button type="submit" name="update_profile" class="save-btn">Save Changes</button>
                <button type="button" onclick="window.location.href='index.php';" class="cancel-btn">Cancel</button>
            </div>
        </form>
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
            <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

<script>
//image crop script
let cropper;
let originalState = {
    hasImage: <?php echo isset($_SESSION['profile_image']) ? 'true' : 'false'; ?>,
    imageSrc: '<?php echo isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : ''; ?>',
    hasThumbnail: <?php echo isset($_SESSION['profile_thumbnail']) ? 'true' : 'false'; ?>,
    thumbnailSrc: '<?php echo isset($_SESSION['profile_thumbnail']) ? $_SESSION['profile_thumbnail'] : ''; ?>'
};

function startCropper(input) {
    if (input.files && input.files[0]) {
        // Reset any existing cropper
        if (cropper) {
            cropper.destroy();
        }
        
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const imageElement = document.getElementById('image-to-crop');
            imageElement.src = e.target.result;
            
            // Initialize cropper only after image loads
            imageElement.onload = function() {
                document.getElementById('crop-modal').style.display = 'block';
                
                cropper = new Cropper(imageElement, {
                    aspectRatio: 1,
                    viewMode: 1,
                    autoCropArea: 0.8,
                    responsive: true,
                    guides: false,
                    ready: function() {
                        console.log('Cropper initialized successfully');
                    }
                });
            };
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

function applyCrop() {
    if (!cropper) {
        console.error('Cropper not initialized');
        alert('Please select an image first');
        return;
    }
    
    try {
        const croppedCanvas = cropper.getCroppedCanvas({
            width: 300,
            height: 300,
            fillColor: '#fff'
        });

        if (!croppedCanvas) {
            throw new Error('Failed to create cropped canvas');
        }

        // Update preview
        const previewUrl = croppedCanvas.toDataURL();
        const profileIcon = document.getElementById('profile-icon');
        const defaultIcon = document.getElementById('default-profile-icon');

        if (profileIcon) {
            profileIcon.src = previewUrl;
        } else {
            defaultIcon.style.display = 'none';
            const newImg = document.createElement('img');
            newImg.id = 'profile-icon';
            newImg.src = previewUrl;
            newImg.className = 'profile-icon-large';
            newImg.alt = 'Profile Image';
            defaultIcon.parentNode.insertBefore(newImg, defaultIcon.nextSibling);
        }
        
        // Store crop data
        const cropData = cropper.getData();
        document.getElementById('x').value = cropData.x;
        document.getElementById('y').value = cropData.y;
        document.getElementById('width').value = cropData.width;
        document.getElementById('height').value = cropData.height;
        
        // Update UI
        document.getElementById('crop-modal').style.display = 'none';
        document.getElementById('action-buttons').style.display = 'block';
        
    } catch (error) {
        console.error('Cropping failed:', error);
        alert('Cropping failed: ' + error.message);
    }
}

function cancelCrop() {
    document.getElementById('profile-image').value = '';
    document.getElementById('crop-modal').style.display = 'none';
    if (cropper) {
        cropper.destroy();
    }
    // Don't show action buttons since we canceled
    document.getElementById('action-buttons').style.display = 'none';
}

function cancelUpload() {
    document.getElementById('profile-image').value = '';
    document.getElementById('action-buttons').style.display = 'none';
    
    // Restore original state
    const profileIcon = document.getElementById('profile-icon');
    const defaultIcon = document.getElementById('default-profile-icon');
    
    if (originalState.hasImage) {
        // If there was originally an image, restore it
        if (profileIcon) {
            profileIcon.src = originalState.imageSrc;
        } else {
            // In case default icon was showing but we had an image (shouldn't happen)
            defaultIcon.style.display = 'none';
            const newImg = document.createElement('img');
            newImg.id = 'profile-icon';
            newImg.src = originalState.imageSrc;
            newImg.className = 'profile-icon-large';
            newImg.alt = 'Profile Image';
            defaultIcon.parentNode.insertBefore(newImg, defaultIcon.nextSibling);
        }
    } else {
        // If there was no image originally, show default icon
        if (profileIcon) {
            profileIcon.remove();
        }
        defaultIcon.style.display = 'flex';
    }
}
</script>
</body>
</html>
const ToggleButton = document.getElementById('toggle-btn')
const sidebar = document.getElementById('sidebar')
const loginbtn = document.getElementById('login')
const profileDetails = document.getElementById('profile-details');

function LoginToggle(){
    profileDetails.classList.toggle('close')
}

function SidebarToggle(){
    sidebar.classList.toggle('close')
    ToggleButton.classList.toggle('rotate')

    CloseAllSubMenus()                                                                                                                                
}

function DropDownToggle(button){
    if(!button.nextElementSibling.classList.contains('show')){

        CloseAllSubMenus()
    }

    button.nextElementSibling.classList.toggle('show')
    button.classList.toggle('rotate')

    if(sidebar.classList.contains('close')){
        sidebar.classList.toggle('close')
        ToggleButton.toggle('rotate')
    }
}

function CloseAllSubMenus(){
    Array.from(sidebar.getElementsByClassName('show')).forEach(ul => {
        ul.classList.remove('show')
        ul.previousElementSibling.classList.remove('rotate')
    })
}

function handleResize() {
    if (window.innerWidth <= 800) {
        if (sidebar.classList.contains('close')) {
            SidebarToggle();
        }
    }
}

handleResize();
window.addEventListener('resize', handleResize);

function toggleProfilePopup(e) {
    e.stopPropagation();
    const profileDetails = document.getElementById('profile-details');
    
    profileDetails.classList.toggle('close');
    
    if (!profileDetails.classList.contains('close')) {
        document.addEventListener('click', function closePopup(evt) {
            if (!profileDetails.contains(evt.target) && evt.target !== document.querySelector('.userbox')) {
                profileDetails.classList.add('close');
                document.removeEventListener('click', closePopup);
            }
        });
    }
}

function togglePasswordVisibility(inputId = 'password', iconClass = 'toggle-password') {
    const passwordInput = document.getElementById(inputId);
    const toggleBtn = document.querySelector(`.${iconClass} i`);
    
    if (!passwordInput || !toggleBtn) return;
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleBtn.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleBtn.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Verify elements exist
    const profileImageInput = document.getElementById('profile-image');
    if (profileImageInput) {
        profileImageInput.addEventListener('change', function() {
            startCropper(this);
        });
    }
    
    // Add similar checks for other interactive elements
});

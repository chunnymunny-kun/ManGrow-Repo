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

// JavaScript for Modal Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Sample content for modals (in real app, you might fetch this)
    const modalContents = {
        'modal-one': {
            title: 'Community News',
            content: '<p>Here are the latest updates from our community...</p> '+
            '<div class="modal-inner-container" id="mic-one"></div>'+
            '<ul><li>New member introductions</li><li>Upcoming policy changes</li><li>Recent achievements</li></ul>'
        },
        'modal-two': {
            title: 'Upcoming Events',
            content: '<p>Join us for these exciting events:</p>'+
            '<div class="modal-inner-container" id="mic-two"></div>'+
            '<ul><li>Monthly Meetup - June 15</li><li>Workshop: Advanced Techniques - June 22</li><li>Community Picnic - July 4</li></ul>'
        },
        'modal-three': {
            title: 'Member Success Stories',
            content: '<p>Read inspiring stories from our members:</p>'+
            '<div class="modal-inner-container" id="mic-three"></div>'+
            '<div class="story"><h4>Jane Doe</h4><p>How she grew her business by 200% in one year...</p></div>'
        },
        'modal-four': {
            title: 'Community Resources',
            content: '<p>Helpful tools for members:</p>'+
            '<div class="modal-inner-container" id="mic-four"></div>'+
            '<ul><li>Starter Guide PDF</li><li>Video Tutorials</li><li>Expert Contacts</li></ul>'
        }
    };

    // Set up click handlers for grid items
    document.querySelectorAll('.grid-item').forEach(item => {
        item.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal');
            openModal(modalId);
        });
    });

    // Modal open function
    function openModal(modalId) {
        const modalTemplate = document.getElementById('modal-template');
        const modalClone = modalTemplate.cloneNode(true);
        modalClone.id = modalId;
        document.body.appendChild(modalClone);
        
        const modalContent = modalContents[modalId];
        const modalBody = modalClone.querySelector('.modal-body');
        modalBody.innerHTML = `
            <h2>${modalContent.title}</h2>
            ${modalContent.content}
        `;
        
        modalClone.style.display = 'block';
        
        // Close button
        modalClone.querySelector('.close-modal').addEventListener('click', () => {
            modalClone.style.display = 'none';
            setTimeout(() => modalClone.remove(), 300);
        });
        
        // Close when clicking outside content
        modalClone.addEventListener('click', (e) => {
            if (e.target === modalClone) {
                modalClone.style.display = 'none';
                setTimeout(() => modalClone.remove(), 300);
            }
        });
    }
});
function importFormToggle(ID){
    var form = document.getElementById(ID);
    if (form.style.display === "none") {
        form.style.display = "block";
    } else {
        form.style.display = "none";
    }
}

function getBarangays() {
    let cityDropdown = document.forms['select-citymunicipality'].cmfilter;
    let selectedCity = cityDropdown.value;
    let barangayDropdown = document.forms['select-citymunicipality'].bfilter;
    
    // Reset barangay dropdown when city changes
    barangayDropdown.innerHTML = '<option value="">All</option>';
    
    if(selectedCity.trim() === "") {
        barangayDropdown.disabled = true;
        barangayDropdown.selectedIndex = 0;
        filtertable(); // Show all records when no city selected
        return false;
    }
    // Enable barangay dropdown and fetch barangays
    barangayDropdown.disabled = false;
    
    fetch(`getdropdown.php?city=${encodeURIComponent(selectedCity)}`)
    .then(response => {
        if(!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(function(barangays) {
        let out = '<option value="">All</option>';
        
        if(barangays.error) {
            console.error(barangays.error);
            return;
        }
        
        barangays.forEach(barangay => {
            out += `<option value="${barangay['barangay']}">${barangay['barangay']}</option>`;
        });

        barangayDropdown.innerHTML = out;
        filtertable(); // Filter table after loading barangays
    })
    .catch(error => {
        console.error('Error:', error);
        barangayDropdown.innerHTML = '<option value="">Error loading barangays</option>';
    });
}

function filtertable() {
    let cityDropdown = document.forms['select-citymunicipality'].cmfilter;
    let barangayDropdown = document.forms['select-citymunicipality'].bfilter;
    let selectedCity = cityDropdown.value;
    let selectedBarangay = barangayDropdown.value;

    let tableBody = document.getElementById('user-table-body');
    tableBody.innerHTML = '<tr><td colspan="13" class="text-center">Loading...</td></tr>';

    // Build the fetch URL based on selections
    let url = 'adminshowtabledata.php?action=filter';
    // Only add city parameter if a specific city is selected
    if (selectedCity) {
        url += `&city=${encodeURIComponent(selectedCity)}`;
    }
    // Only add barangay parameter if a specific barangay is selected
    if (selectedBarangay) {
        url += `&barangay=${encodeURIComponent(selectedBarangay)}`;
    }
    fetch(url)
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (!data || !Array.isArray(data)) {
            throw new Error('Invalid data format received');
        }
        updateTable(data);
    })
    .catch(error => {
        console.error('Error:', error);
        tableBody.innerHTML = `<tr><td colspan="13" class="text-center">Error loading data: ${error.message}</td></tr>`;
    });
}

function updateTable(data) {
    let tableBody = document.getElementById('user-table-body');
    let html = '';
    
    if (data.length === 0) {
        html = '<tr><td colspan="13" class="text-center">No records found</td></tr>';
    } else {
        data.forEach(item => {
            html += `
                <tr>
                    <td>${item.tempacc_id || ''}</td>
                    <td>${item.firstname || ''}</td>
                    <td>${item.lastname || ''}</td>
                    <td>${item.email || ''}</td>
                    <td>${item.personal_email || ''}</td>
                    <td>${item.password || ''}</td>
                    <td>${item.barangay || ''}</td>
                    <td>${item.city_municipality || ''}</td>
                    <td>${item.accessrole || ''}</td>
                    <td>${item.organization || ''}</td>
                    <td>${item.is_verified || ''}</td>
                    <td>${item.import_date || ''}</td>
                    <td>${item.imported_by || ''}</td>
                </tr>
            `;
        });
    }
    
    tableBody.innerHTML = html;
}
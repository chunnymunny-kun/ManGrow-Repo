function updateAverage() {
    const heights = [...document.querySelectorAll('.height')]
      .map(input => parseFloat(input.value) || 0)
      .filter(h => h > 0);
    
    const avg = heights.reduce((a,b) => a+b, 0) / heights.length;
    document.getElementById('avg-value').textContent = avg.toFixed(2);
    document.getElementById('sample-size').textContent = heights.length;
  }

  // JavaScript for form submission (residents - status reporting)
  document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('tree-measurements-container');
    const addBtn = document.getElementById('add-tree-btn');
    let treeCount = 1;
    const maxTrees = 5;
    let currentMethod = 'visual_estimate';
  
    // Global measurement method change
    document.querySelectorAll('input[name="global_measure_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            currentMethod = this.value;
            updateMeasurementFields();
            updateAverage();
        });
    });
  
    // Add new tree
    addBtn.addEventListener('click', function() {
        if (treeCount >= maxTrees) {
            alert(`Maximum ${maxTrees} trees allowed`);
            return;
        }
  
        treeCount++;
        const newTree = document.querySelector('.tree-measurement').cloneNode(true);
        const newIndex = treeCount;
  
        // Update IDs and names
        newTree.querySelector('h3').textContent = `Tree #${newIndex}`;
        
        ['species', 'height_estimate', 'exact_height'].forEach(field => {
            const elements = newTree.querySelectorAll(`[id^="${field}_"], [name^="${field}"]`);
            elements.forEach(el => {
                if (el.id) el.id = el.id.replace(/_1/, `_${newIndex}`);
                if (el.name && el.name.endsWith('[]')) {
                    el.name = field + '[]'; // Keep as array
                }
                el.value = '';
            });
        });
  
        // Update visibility based on current method
        newTree.querySelector('.visual-estimate-fields').style.display = 
            currentMethod === 'visual_estimate' ? 'block' : 'none';
        newTree.querySelector('.height-pole-fields').style.display = 
            currentMethod === 'height_pole' ? 'block' : 'none';
  
        container.appendChild(newTree);
        updateAverage();
    });
  
    // Update all measurement fields when method changes
    function updateMeasurementFields() {
        document.querySelectorAll('.tree-measurement').forEach(tree => {
            tree.querySelector('.visual-estimate-fields').style.display = 
                currentMethod === 'visual_estimate' ? 'block' : 'none';
            tree.querySelector('.height-pole-fields').style.display = 
                currentMethod === 'height_pole' ? 'block' : 'none';
        });
    }
  
    // Calculate average height
    function updateAverage() {
        const heights = [];
        
        document.querySelectorAll('.tree-measurement').forEach((tree, index) => {
            let height = 0;
            
            if (currentMethod === 'visual_estimate') {
                const select = tree.querySelector('select[name="height_estimate[]"]');
                height = parseFloat(select.value) || 0;
            } 
            else if (currentMethod === 'height_pole') {
                const input = tree.querySelector('input[name="exact_height[]"]');
                height = parseFloat(input.value) || 0;
            }
            
            if (height > 0) heights.push(height);
        });
        
        if (heights.length > 0) {
            const avg = heights.reduce((a, b) => a + b, 0) / heights.length;
            document.getElementById('avg-height-value').textContent = avg.toFixed(2) + ' meters';
            document.getElementById('avg-height-input').value = avg.toFixed(2);
        } else {
            document.getElementById('avg-height-value').textContent = 'No measurements yet';
            document.getElementById('avg-height-input').value = '';
        }
    }
  
    // Recalculate when any height field changes
    container.addEventListener('input', function(e) {
        if (e.target.name === 'height_estimate[]' || e.target.name === 'exact_height[]') {
            updateAverage();
        }
    });
  });

// JavaScript for handling location and form submission (barangay officials - status reporting)
  document.getElementById('get-location').addEventListener('click', function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            document.getElementById('gps-lat').value = position.coords.latitude.toFixed(6);
            document.getElementById('gps-lon').value = position.coords.longitude.toFixed(6);
        }, function(error) {
            alert('Error getting location: ' + error.message);
        });
    } else {
        alert('Geolocation is not supported by your browser.');
    }
});


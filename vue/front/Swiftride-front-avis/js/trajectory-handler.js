function createTrajectoryBox(trajectory) {
    const html = `
        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
            <div class="property-item rounded overflow-hidden">
                <div class="position-relative overflow-hidden">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#trajectoryModal" 
                       onclick="showTrajectoryDetails('${trajectory.ville_D} - ${trajectory.ville_A}', 'Seat mount', '${trajectory.statue}', '$${trajectory.distance}')">
                        <img class="img-fluid" src="img/property-1.jpg" alt="">
                    </a>
                    <div class="bg-primary rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">Available</div>
                    <div class="bg-white rounded-top text-primary position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Seat mount</div>
                </div>
                <div class="p-4 pb-0">
                    <h5 class="text-primary mb-3">$${trajectory.distance}</h5>
                    <a class="d-block h5 mb-2" href="">${trajectory.ville_D} - ${trajectory.ville_A}</a>
                    <p><i class="fa fa-map-marker-alt text-primary me-2"></i>Number of seats available: ${trajectory.statue}</p>
                </div>
                <div class="d-flex border-top">
                    <small class="flex-fill text-center py-2"><i class="fa fa-user text-primary me-2"></i>${trajectory.statue} Seats</small>
                </div>
            </div>
        </div>
    `;
    
    // Add the new trajectory box to the container
    const container = document.querySelector('.trajectory-container');
    if (container) {
        container.insertAdjacentHTML('beforeend', html);
    }
}

// Function to handle the response after adding a trajectory
function handleTrajectoryAdded(response) {
    if (response.success) {
        // Create and add the new trajectory box
        createTrajectoryBox(response.trajectory);
        // Show success message
        alert('Trajectory added successfully!');
    } else {
        alert('Failed to add trajectory: ' + response.message);
    }
}

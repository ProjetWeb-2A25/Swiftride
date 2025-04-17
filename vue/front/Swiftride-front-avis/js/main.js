(function ($) {
    "use strict";

    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner();
    
    // Initiate the wowjs
    new WOW().init();

    // Sticky Navbar
    $(window).scroll(function () {
        if ($(this).scrollTop() > 45) {
            $('.nav-bar').addClass('sticky-top');
        } else {
            $('.nav-bar').removeClass('sticky-top');
        }
    });
    
    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });

    // Header carousel
    $(".header-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1500,
        items: 1,
        dots: true,
        loop: true,
        nav : true,
        navText : [
            '<i class="bi bi-chevron-left"></i>',
            '<i class="bi bi-chevron-right"></i>'
        ]
    });

    // Testimonials carousel
    $(".testimonial-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1000,
        margin: 24,
        dots: false,
        loop: true,
        nav : true,
        navText : [
            '<i class="bi bi-arrow-left"></i>',
            '<i class="bi bi-arrow-right"></i>'
        ],
        responsive: {
            0:{
                items:1
            },
            992:{
                items:2
            }
        }
    });

    // Store all trajectories
    let allTrajectories = [];
    const ITEMS_PER_PAGE = 6;

    // Function to add new trajectory to the interface
    function addTrajectoryToInterface(trajectory) {
        const trajectoryHtml = `
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="property-item rounded overflow-hidden">
                    <div class="position-relative overflow-hidden">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#trajectoryModal" onclick="showTrajectoryDetails('${trajectory.ville_D} - ${trajectory.ville_A}', '${trajectory.type_vehicule}', '${trajectory.statue}', '${trajectory.prix}')">
                            <img class="img-fluid" src="img/property-1.jpg" alt="">
                        </a>
                        <div class="bg-primary rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">Available</div>
                        <div class="bg-white rounded-top text-primary position-absolute start-0 bottom-0 mx-4 pt-1 px-3">${trajectory.type_vehicule}</div>
                    </div>
                    <div class="p-4 pb-0">
                        <h5 class="text-primary mb-3">${trajectory.prix}</h5>
                        <a class="d-block h5 mb-2" href="">${trajectory.ville_D} - ${trajectory.ville_A}</a>
                        <p><i class="fa fa-map-marker-alt text-primary me-2"></i>Number of seats available: ${trajectory.statue}</p>
                        <p><i class="fa fa-clock text-primary me-2"></i>Departure: ${trajectory.date_D}</p>
                        <p><i class="fa fa-hourglass-half text-primary me-2"></i>Duration: ${trajectory.temps_est}</p>
                    </div>
                    <div class="d-flex border-top">
                        <small class="flex-fill text-center py-2"><i class="fa fa-user text-primary me-2"></i>${trajectory.statue} Seats</small>
                    </div>
                </div>
            </div>
        `;
        return trajectoryHtml;
    }

    // Function to display trajectories
    function displayTrajectories(showAll = false) {
        const container = document.querySelector('#trajectoryContainer');
        if (!container) return;

        const trajectoriesToShow = showAll ? allTrajectories : allTrajectories.slice(0, ITEMS_PER_PAGE);

        let html = '';
        trajectoriesToShow.forEach(trajectory => {
            html += addTrajectoryToInterface(trajectory);
        });
        
        container.innerHTML = html;
        new WOW().init();

        // Toggle visibility of "Show More" and "Show Less" buttons
        const showMoreBtn = document.getElementById('showMoreBtn');
        const showLessBtn = document.getElementById('showLessBtn');
        
        if (showMoreBtn && showLessBtn) {
            showMoreBtn.style.display = showAll ? 'none' : 'inline-block';
            showLessBtn.style.display = showAll ? 'inline-block' : 'none';
        }
    }

    // Function to fetch all trajectories
    async function fetchAndDisplayTrajectories() {
        try {
            const response = await fetch('http://localhost/web/controlleur/trajectoireC.php');
            const data = await response.json();
            if (data.success && data.trajectories) {
                allTrajectories = data.trajectories;
                displayTrajectories(false); // Initially show only first 6
            } else {
                console.error('Error fetching trajectories:', data.message);
            }
        } catch (error) {
            console.error('Error fetching trajectories:', error);
        }
    }

    // Handle "Browse More" button click
    $(document).on('click', '#showMoreBtn', function(e) {
        e.preventDefault();
        displayTrajectories(true); // Show all trajectories
    });

    // Handle "Show Less" button click
    $(document).on('click', '#showLessBtn', function(e) {
        e.preventDefault();
        displayTrajectories(false); // Show only first 6 trajectories
    });

    // Call the function to fetch and display initial trajectories
    fetchAndDisplayTrajectories();

})(jQuery);

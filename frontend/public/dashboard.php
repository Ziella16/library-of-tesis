<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Of Thesis</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Menu</h2>
            </div>
            <nav class="menu">
                <a href="#" class="menu-item" id="homePage">
                    <span class="icon">🏠</span> Utama
                </a>
                <a href="#" class="menu-item" id="calendarPage">
                    <span class="icon">📅</span> Kalendar
                </a>
                <div class="submenu" id="workshopMenu">
                    <h3>Bengkel 
                        <span class="arrow">▶</span>
                    </h3>
                    <ul>
                         <li>
                            <a href="#" data-bengkel="Bengkel Jaminan Kualiti">Bengkel Jaminan Kualiti</a>
                        </li>
                        <li>
                            <a href="#" data-bengkel="Bengkel Komputer">Bengkel Komputer</a>
                        </li>
                        <li>
                            <a href="#" data-bengkel="Bengkel Mikoelektronik">Bengkel Mikroelektronik</a>
                        </li>
                        <li>
                            <a href="#" data-bengkel="Bengkel Mekatronik">Bengkel Mekatronik</a>
                        </li>
                        <li>
                            <a href="#" data-bengkel="Bengkel Mekanikal Bahan">Bengkel Mekanikal Bahan</a>
                        </li>
                        <li>
                            <a href="#" data-bengkel="Bengkel Polimer">Bengkel Polimer</a>
                        </li>
                    </ul>
                </div>
                <a href="#" class="menu-item" id="settingsMenu">
                    <span class="icon">⚙️</span> Settings
                </a>
                <a href="#" class="menu-item logout" id="logout">
                    <span class="icon">🚪</span> Log Keluar
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content" style="margin-left: 270px; padding: 20px;">
            <div id="main-content-wrapper" class="card-grid">
                <!-- Dynamic Content -->
            </div>
        </main>
    </div>

    <!-- Profile Modal -->
    <div class="modal" id="editProfileModal">
        <div class="modal-content">
            <h3>Edit Profile</h3>
            <form id="editProfileForm">
                <div class="input-group">
                    <label for="profileImage">Profile Image:</label>
                    <input type="file" id="profileImage" name="profileImage" accept="image/*">
                </div>
                <div class="input-group">
                    <label for="userName">Name:</label>
                    <input type="text" id="userName" name="userName">
                </div>
                <button type="submit">Save Changes</button>
                <button type="button" class="close" id="closeModal">Close</button>
            </form>
        </div>
    </div>

    <!-- Profile Display (for loadProfile function) -->
    <div class="profile-display">
        <img id="profileImg" alt="Profile Image" />
        <p id="userNameDisplay"></p>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            loadProfile();

            // Toggle sidebar menu items
            const sidebarItems = document.querySelectorAll(".menu-item");
            sidebarItems.forEach(item => {
                item.addEventListener("click", () => {
                    alert(`${item.textContent} clicked`);
                });
            });

            // Show calendar when "Kalendar" menu item is clicked
            document.getElementById("calendarPage").addEventListener("click", function(event) {
                event.preventDefault();
                loadCalendar();
            });

            // Toggle workshop submenu
            document.querySelector("#workshopMenu h3").addEventListener("click", function() {
                var submenu = document.getElementById("workshopMenu");
                submenu.classList.toggle("open");
                var arrow = submenu.querySelector(".arrow");
                arrow.classList.toggle("open");
            });

            // Workshop links to load theses
            // Workshop links to load theses
document.querySelectorAll('[data-bengkel]').forEach(link => {
    link.addEventListener('click', function(event) {
        event.preventDefault();
        const department = this.getAttribute('data-bengkel');
        loadTheses(department);
    });
});


            // Log out functionality
            document.getElementById("logout").addEventListener("click", function() {
                window.location.href = "index.php";
            });
        });

        // Fetch and display theses for the selected department
        function loadTheses(department) {
            fetch('fetchthesis.php?department=' + department)
                .then(response => response.json())
                .then(data => {
                    const mainContentWrapper = document.getElementById("main-content-wrapper");
                    mainContentWrapper.innerHTML = ''; // Clear previous content
                    
                    if (data.length === 0) {
                        mainContentWrapper.innerHTML = `<p>No theses found for ${department}</p>`;
                        return;
                    }

                    data.forEach(thesis => {
                        mainContentWrapper.innerHTML += `
                            <div class="card">
                                <div class="card-header">
                                    <h4>${thesis.title}</h4>
                                    <p>${thesis.author} | ${thesis.year}</p>
                                </div>
                                <div class="card-body">
                                    <a href="${thesis.file_path}" target="_blank">View Thesis</a>
                                </div>
                            </div>
                        `;
                    });
                })
                .catch(error => {
                    console.error('Error fetching theses:', error);
                    alert('Failed to load theses. Please try again later.');
                });
        }

        // Load calendar in main content
        function loadCalendar() {
            const mainContentWrapper = document.getElementById("main-content-wrapper");
            mainContentWrapper.innerHTML = `
                <div id="calendar">
                    <button id="prevMonth">Previous</button>
                    <span id="monthYear"></span>
                    <button id="nextMonth">Next</button>
                    <div id="days"></div>
                </div>
            `;
            displayCalendar(new Date());
            
            document.getElementById("prevMonth").addEventListener("click", function() {
                currentDate.setMonth(currentDate.getMonth() - 1);
                displayCalendar(currentDate);
            });

            document.getElementById("nextMonth").addEventListener("click", function() {
                currentDate.setMonth(currentDate.getMonth() + 1);
                displayCalendar(currentDate);
            });
        }

        let currentDate = new Date();

        function displayCalendar(date) {
            const daysElement = document.getElementById("days");
            daysElement.innerHTML = "";

            const monthYear = document.getElementById("monthYear");
            monthYear.textContent = date.toLocaleString("default", { month: "long", year: "numeric" });

            const firstDay = new Date(date.getFullYear(), date.getMonth(), 1).getDay();
            const lastDate = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();

            for (let i = 0; i < firstDay; i++) {
                daysElement.innerHTML += `<div class="empty"></div>`;
            }

            for (let day = 1; day <= lastDate; day++) {
                daysElement.innerHTML += `<div class="day">${day}</div>`;
            }
        }

        // Load user profile data from localStorage
        function loadProfile() {
            var profileImg = localStorage.getItem('profileImage');
            var userName = localStorage.getItem('userName');

            if (profileImg) {
                document.getElementById('profileImg').src = profileImg;
            }
            if (userName) {
                document.getElementById('userNameDisplay').textContent = userName;
            }
        }
    </script>

    <style>
        /* Add CSS for the calendar */
        #calendar {
            text-align: center;
            margin-top: 20px;
        }
        #calendar button {
            padding: 5px 10px;
        }
        #monthYear {
            font-size: 1.2em;
            margin: 0 10px;
        }
        #days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            margin-top: 10px;
        }
        .day, .empty {
            padding: 10px;
            background-color: #f0f0f0;
            border-radius: 5px;
        }
    </style>
</body>
</html>

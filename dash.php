<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <nav>
                <ul>
                    <li><a href="#" class="active" onclick="showPanel('dashboardPanel')"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="#" onclick="showPanel('usersPanel')"><i class="fas fa-users"></i> Users</a></li>
                    <li><a href="#" onclick="showPanel('reportsPanel')"><i class="fas fa-chart-line"></i> Reports</a></li>
                    <li><a href="#" onclick="showPanel('settingsPanel')"><i class="fas fa-cogs"></i> Settings</a></li>
                    <li><a href="#" onclick="showPanel('logoutPanel')"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>
        <div class="main-content">
            <header>
                <h1>Welcome Back, Admin!</h1>
                <p>Your dashboard overview at a glance</p>
            </header>
            <div class="panel" id="dashboardPanel">
                <div class="cards">
                    <div class="card card-1">
                        <i class="fas fa-user-friends card-icon"></i>
                        <h3>Total Users</h3>
                        <p>120</p>
                    </div>
                    <div class="card card-2">
                        <i class="fas fa-user-check card-icon"></i>
                        <h3>Active Users</h3>
                        <p>95</p>
                    </div>
                    <div class="card card-3">
                        <i class="fas fa-user-clock card-icon"></i>
                        <h3>Pending Requests</h3>
                        <p>10</p>
                    </div>
                    <div class="card card-4">
                        <i class="fas fa-tasks card-icon"></i>
                        <h3>Total Assignments</h3>
                        <p>25</p>
                    </div>
                </div>
                <div class="table-section">
                    <h2>Recent Activity</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Action</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>John Doe</td>
                                <td>Added new assignment</td>
                                <td>2024-10-22</td>
                            </tr>
                            <tr>
                                <td>Jane Smith</td>
                                <td>Updated profile</td>
                                <td>2024-10-21</td>
                            </tr>
                            <tr>
                                <td>Bob Johnson</td>
                                <td>Deleted a user</td>
                                <td>2024-10-20</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Hidden Panels -->
            <div class="panel" id="usersPanel" style="display:none;">
                <h2>Users Management</h2>
                <p>Here you can manage users.</p>
                <!-- Add user management content -->
            </div>
            <div class="panel" id="reportsPanel" style="display:none;">
                <h2>Reports</h2>
                <p>View detailed reports here.</p>
                <!-- Add reports content -->
            </div>
            <div class="panel" id="settingsPanel" style="display:none;">
                <h2>Settings</h2>
                <p>Manage your settings here.</p>
                <!-- Add settings content -->
            </div>
            <div class="panel" id="logoutPanel" style="display:none;">
                <h2>Logout</h2>
                <p>You have logged out successfully.</p>
                <!-- Add logout content -->
            </div>
        </div>
    </div>

    <script>
        function showPanel(panelId) {
            const panels = document.querySelectorAll('.panel');
            panels.forEach(panel => {
                if (panel.style.display === 'block') {
                    panel.classList.add('slide-out'); // Add slide out class
                    setTimeout(() => {
                        panel.style.display = 'none'; // Hide after animation
                        panel.classList.remove('slide-out'); // Clean up class
                    }, 300); // Match this duration with CSS
                }
            });
            const selectedPanel = document.getElementById(panelId);
            selectedPanel.style.display = 'block'; // Show the selected panel
            selectedPanel.classList.add('slide-in'); // Add slide in class

            // Add active class to the selected link
            const links = document.querySelectorAll('.sidebar nav ul li a');
            links.forEach(link => {
                link.classList.remove('active');
            });
            document.querySelector(`a[onclick="showPanel('${panelId}')"]`).classList.add('active');
        }
    </script>
</body>
</html>

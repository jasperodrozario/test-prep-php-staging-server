<div class="dashboard-container">
      <!-- Navigation Buttons -->
    <div class="dashboard-buttons">
        <button onclick="window.location.href='revenue.php'">Revenue Analysis</button>
        <button onclick="window.location.href='subscriptions.php'">Subscription Analysis</button>
        <button onclick="window.location.href='user_growth.php'">User Growth</button>
        <button onclick="window.location.href='course_performance.php'">Course Performance</button>
    </div>
    
    <!-- Overview Cards -->
    <div class="cards">
        <div class="card">
            <h3>Total Revenue</h3>
            <p>$<span id="totalRevenue">0.00</span></p>
        </div>
        <div class="card">
            <h3>Total Users</h3>
            <p><span id="totalUsers">0</span></p>
        </div>
        <div class="card">
            <h3>New Subscriptions</h3>
            <p><span id="newSubscriptions">0</span></p>
        </div>
        <div class="card">
            <h3>Referral Earnings</h3>
            <p>$<span id="referralEarnings">0.00</span></p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-container">
        <canvas id="revenueChart"></canvas>
        <canvas id="subscriptionsChart"></canvas>
    </div>

    <!-- User & Course Insights -->
    <div class="tables-container">
        <table>
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Subscribers</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody id="courseStats">
                <!-- Data will be populated dynamically -->
            </tbody>
        </table>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Dummy data (Replace with PHP variables or AJAX calls)
        let totalRevenue = <?=$total_revenue?>;
        let totalUsers = <?=$total_user?>;
        let newSubscriptions = <?=$total_subscription?>;
        let referralEarnings = <?=$total_registration?>;
        
        document.getElementById("totalRevenue").innerText = totalRevenue.toLocaleString();
        document.getElementById("totalUsers").innerText = totalUsers;
        document.getElementById("newSubscriptions").innerText = newSubscriptions;
        document.getElementById("referralEarnings").innerText = referralEarnings.toLocaleString();

        // Revenue Chart
        new Chart(document.getElementById("revenueChart"), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                datasets: [{
                    label: 'Revenue ($)',
                    data: [10000, 12000, 15000, 18000, 20000],
                    borderColor: 'blue',
                    fill: false
                }]
            }
        });
    });
</script>

<style>
    .dashboard-buttons {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        justify-content: space-between;
    }
    .dashboard-buttons button {
        flex: 1; /* Makes all buttons take equal width */
        padding: 12px 25px;
        border: none;
        background: linear-gradient(135deg, #007BFF, #0056b3);
        color: white;
        cursor: pointer;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        transition: all 0.3s ease-in-out;
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
    }
    .dashboard-buttons button:hover {
        background: linear-gradient(135deg, #0056b3, #00408a);
        transform: scale(1.05);
    }
    .dashboard-container {
        padding: 20px;
    }
    .cards {
        display: flex;
        gap: 20px;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .card {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
        flex: 1;
        min-width: 200px;
    }
    .card h3 {
        font-size: 20px;
        margin-bottom: 10px;
        color: #333;
    }

    .card p {
        font-size: 28px; /* Increased font size */
        font-weight: bold;
        color: #007BFF;
    }

    .charts-container {
        display: flex;
        gap: 20px;
        margin-top: 20px;
    }
    .tables-container table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }
    .tables-container th, .tables-container td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }
</style>

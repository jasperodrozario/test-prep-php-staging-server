<?php

$sub_summary = [
    'ios' => 1245,
    'android' => 2180,
    'web_subscription' => 860,
    'web_access' => 1420
];

$monthly_reg = [
    ['month' => 'Jan 2026', 'ios' => 120, 'android' => 180, 'web_subscription' => 75, 'web_access' => 95],
    ['month' => 'Feb 2026', 'ios' => 135, 'android' => 210, 'web_subscription' => 82, 'web_access' => 110],
    ['month' => 'Mar 2026', 'ios' => 150, 'android' => 235, 'web_subscription' => 90, 'web_access' => 120],
    ['month' => 'Apr 2026', 'ios' => 175, 'android' => 260, 'web_subscription' => 100, 'web_access' => 140],
    ['month' => 'May 2026', 'ios' => 190, 'android' => 280, 'web_subscription' => 115, 'web_access' => 160],
    ['month' => 'Jun 2026', 'ios' => 210, 'android' => 310, 'web_subscription' => 130, 'web_access' => 185]
];

$monthly_sub = [
    ['month' => 'Jan 2026', 'ios' => 70, 'android' => 110, 'web_subscription' => 40, 'web_access' => 55],
    ['month' => 'Feb 2026', 'ios' => 80, 'android' => 125, 'web_subscription' => 50, 'web_access' => 65],
    ['month' => 'Mar 2026', 'ios' => 95, 'android' => 140, 'web_subscription' => 58, 'web_access' => 75],
    ['month' => 'Apr 2026', 'ios' => 110, 'android' => 160, 'web_subscription' => 65, 'web_access' => 85],
    ['month' => 'May 2026', 'ios' => 125, 'android' => 180, 'web_subscription' => 72, 'web_access' => 95],
    ['month' => 'Jun 2026', 'ios' => 145, 'android' => 205, 'web_subscription' => 85, 'web_access' => 110]
];

$months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];

$iosReg = [120, 135, 150, 175, 190, 210];
$androidReg = [180, 210, 235, 260, 280, 310];
$webSubReg = [75, 82, 90, 100, 115, 130];
$webAccessReg = [95, 110, 120, 140, 160, 185];

$iosSub = [70, 80, 95, 110, 125, 145];
$androidSub = [110, 125, 140, 160, 180, 205];
$webSubSub = [40, 50, 58, 65, 72, 85];
$webAccessSub = [55, 65, 75, 85, 95, 110];

?>

<br>
<h3><strong>Website Analytics</strong></h3>
<hr>

<div class="row">

    <div class="col-md-3">
        <div class="analytics-card">
            <div>
                <h4>Total iOS Registrations</h4>
                <h3><?php echo $sub_summary['ios']; ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="analytics-card">
            <div>
                <h4>Total Android Registrations</h4>
                <h3>666</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="analytics-card">
            <div>
                <h4>Web Registrations</h4>
                <h3>120</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="analytics-card">
            <div>
                <h4>Web Access</h4>
                <h3>80</h3>
            </div>
        </div>
    </div>

</div>


<br>
<h4><strong>Subscriptions</strong></h4>

<div class="row">

    <div class="col-md-3">
        <div class="analytics-card">
            <div>
                <h4>iOS Subscriptions</h4>
                <h3><?php echo $sub_summary['ios']; ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="analytics-card">
            <div>
                <h4>Android Subscriptions</h4>
                <h3><?php echo $sub_summary['android']; ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="analytics-card">
            <div>
                <h4>Web Subscription</h4>
                <h3><?php echo $sub_summary['web_subscription']; ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="analytics-card">
            <div>
                <h4>Web Access</h4>
                <h3><?php echo $sub_summary['web_access']; ?></h3>
            </div>
        </div>
    </div>

</div>

<br>
<h4><strong>Monthly Registrations</strong></h4>

<div class="table-responsive" id="scrollable-table">
    <table class="table table-bordered table-striped">

        <thead>
            <tr>
                <th>Month</th>
                <th>iOS</th>
                <th>Android</th>
                <th>Web Subscription</th>
                <th>Web Access</th>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($monthly_reg as $m) { ?>

                <tr>

                    <td><?php echo $m['month']; ?></td>

                    <td><?php echo $m['ios']; ?></td>

                    <td><?php echo $m['android']; ?></td>

                    <td><?php echo $m['web_subscription']; ?></td>

                    <td><?php echo $m['web_access']; ?></td>

                    <td>
                        <?php
                        echo $m['ios'] + $m['android'] + $m['web_subscription'] + $m['web_access'];
                        ?>
                    </td>

                </tr>

            <?php } ?>

        </tbody>

    </table>
</div>

<br>
<h4><strong>Monthly Subscriptions</strong></h4>

<div class="table-responsive" id="scrollable-table">
    <table class="table table-bordered table-striped">

        <thead>
            <tr>
                <th>Month</th>
                <th>iOS</th>
                <th>Android</th>
                <th>Web Subscription</th>
                <th>Web Access</th>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($monthly_sub as $m) { ?>

                <tr>

                    <td><?php echo $m['month']; ?></td>

                    <td><?php echo $m['ios']; ?></td>

                    <td><?php echo $m['android']; ?></td>

                    <td><?php echo $m['web_subscription']; ?></td>

                    <td><?php echo $m['web_access']; ?></td>

                    <td>
                        <?php
                        echo $m['ios'] + $m['android'] + $m['web_subscription'] + $m['web_access'];
                        ?>
                    </td>

                </tr>

            <?php } ?>

        </tbody>

    </table>
</div>

<div class="card mt-5 shadow-sm">
    <div class="card-header">
        <h5>Monthly Registrations</h5>
    </div>

    <div class="card-body">
        <canvas id="registrationChart"></canvas>
    </div>
</div>

<div class="card mt-5 shadow-sm">
    <div class="card-header">
        <h5>Monthly Subscriptions</h5>
    </div>

    <div class="card-body">
        <canvas id="subscriptionChart"></canvas>
    </div>
</div>

<div class="card mt-5 shadow-sm mb-5">
    <div class="card-header">
        <h5>Registration vs Subscription</h5>
    </div>

    <div class="card-body">
        <canvas id="comparisonChart"></canvas>
    </div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];

    new Chart(document.getElementById('registrationChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                    label: 'iOS',
                    data: [120, 135, 150, 175, 190, 210]
                },
                {
                    label: 'Android',
                    data: [180, 210, 235, 260, 280, 310]
                },
                {
                    label: 'Web Subscription',
                    data: [75, 82, 90, 100, 115, 130]
                },
                {
                    label: 'Web Access',
                    data: [95, 110, 120, 140, 160, 185]
                }
            ]
        }
    });

    new Chart(document.getElementById('subscriptionChart'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                    label: 'iOS',
                    data: [70, 80, 95, 110, 125, 145]
                },
                {
                    label: 'Android',
                    data: [110, 125, 140, 160, 180, 205]
                },
                {
                    label: 'Web Subscription',
                    data: [40, 50, 58, 65, 72, 85]
                },
                {
                    label: 'Web Access',
                    data: [55, 65, 75, 85, 95, 110]
                }
            ]
        }
    });

    new Chart(document.getElementById('comparisonChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                    label: 'Registrations',
                    data: [470, 537, 595, 675, 745, 835]
                },
                {
                    label: 'Subscriptions',
                    data: [275, 320, 368, 420, 472, 545]
                }
            ]
        }
    });
</script>

<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const months = <?php echo json_encode($months); ?>;

    const iosReg = <?php echo json_encode($iosReg); ?>;
    const androidReg = <?php echo json_encode($androidReg); ?>;
    const webSubReg = <?php echo json_encode($webSubReg); ?>;
    const webAccessReg = <?php echo json_encode($webAccessReg); ?>;

    new Chart(document.getElementById('registrationChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                    label: 'iOS',
                    data: iosReg
                },
                {
                    label: 'Android',
                    data: androidReg
                },
                {
                    label: 'Web Subscription',
                    data: webSubReg
                },
                {
                    label: 'Web Access',
                    data: webAccessReg
                }
            ]
        }
    });
</script> -->

<style>
    .analytics-card {
        height: 150px;
        border: 1px solid #ddd;
        background: #fff;
        border-radius: 6px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .analytics-card h4 {
        font-size: 18px;
        margin-bottom: 10px;
        color: #555;
    }

    .analytics-card h3 {
        font-size: 32px;
        font-weight: 600;
    }
</style>


<!-- ============================================================================== Part 2 ============================================================================== -->

<div class="container-fluid p-4" style="background:#f6f7fb; min-height:100vh;">

    <!-- HEADER -->
    <div class="mb-4">
        <h3 class="fw-bold">📊 Analytics Dashboard</h3>
        <p class="text-muted mb-0">Overview of users, engagement and revenue</p>
    </div>

    <!-- KPI CARDS -->
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm p-3 border-0 rounded-4">
                <h6 class="text-muted">Total Users</h6>
                <h3 class="fw-bold">12,450</h3>
                <small class="text-success">+12% this month</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm p-3 border-0 rounded-4">
                <h6 class="text-muted">New Users</h6>
                <h3 class="fw-bold">320</h3>
                <small class="text-success">+8% this week</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm p-3 border-0 rounded-4">
                <h6 class="text-muted">Active Users</h6>
                <h3 class="fw-bold">1,120</h3>
                <small class="text-danger">-2% yesterday</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm p-3 border-0 rounded-4">
                <h6 class="text-muted">Revenue</h6>
                <h3 class="fw-bold">$18,940</h3>
                <small class="text-success">+15% growth</small>
            </div>
        </div>

    </div>


    <!-- CHART ROW 1 -->
    <div class="row g-3">

        <!-- USER GROWTH -->
        <div class="col-md-8">
            <div class="card shadow-sm p-3 border-0 rounded-4">
                <div class="d-flex justify-content-between">
                    <h6>User Growth</h6>
                    <span class="text-muted small">Last 7 days</span>
                </div>
                <canvas id="userChart"></canvas>
            </div>
        </div>

        <!-- DEVICE DISTRIBUTION -->
        <div class="col-md-4">
            <div class="card shadow-sm p-3 border-0 rounded-4">
                <h6>Device Usage</h6>
                <canvas id="deviceChart"></canvas>
            </div>
        </div>

    </div>


    <!-- CHART ROW 2 -->
    <div class="row g-3 mt-2">

        <!-- LOGIN ACTIVITY -->
        <div class="col-md-6">
            <div class="card shadow-sm p-3 border-0 rounded-4">
                <h6>Login Activity</h6>
                <canvas id="loginChart"></canvas>
            </div>
        </div>

        <!-- REVENUE -->
        <div class="col-md-6">
            <div class="card shadow-sm p-3 border-0 rounded-4">
                <h6>Revenue Trend</h6>
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

    </div>


    <!-- BOTTOM SECTION -->
    <div class="row g-3 mt-2">

        <!-- TOP COURSES -->
        <div class="col-md-6">
            <div class="card shadow-sm p-3 border-0 rounded-4">
                <h6>🔥 Top Courses</h6>
                <canvas id="courseChart"></canvas>
            </div>
        </div>

        <!-- RECENT ACTIVITY -->
        <div class="col-md-6">
            <div class="card shadow-sm p-3 border-0 rounded-4">
                <h6>🟢 Recent Activity</h6>

                <ul class="list-group list-group-flush mt-2">

                    <li class="list-group-item">
                        <b>Rahim</b> joined a new course
                        <small class="text-muted d-block">2 minutes ago</small>
                    </li>

                    <li class="list-group-item">
                        <b>Sara</b> made a purchase
                        <small class="text-muted d-block">10 minutes ago</small>
                    </li>

                    <li class="list-group-item">
                        <b>John</b> logged in
                        <small class="text-muted d-block">30 minutes ago</small>
                    </li>

                    <li class="list-group-item">
                        <b>Mehedi</b> registered
                        <small class="text-muted d-block">1 hour ago</small>
                    </li>

                </ul>

            </div>
        </div>

    </div>

</div>


<!-- =======================
     CHART.JS (DUMMY DATA)
======================= -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // USER GROWTH
    new Chart(document.getElementById("userChart"), {
        type: "line",
        data: {
            labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
            datasets: [{
                label: "Users",
                data: [120, 190, 300, 250, 400, 320, 500],
                borderWidth: 2,
                tension: 0.4
            }]
        }
    });


    // DEVICE CHART
    new Chart(document.getElementById("deviceChart"), {
        type: "pie",
        data: {
            labels: ["Mobile", "Desktop", "Tablet"],
            datasets: [{
                data: [65, 25, 10],
                borderWidth: 1
            }]
        }
    });


    // LOGIN ACTIVITY
    new Chart(document.getElementById("loginChart"), {
        type: "bar",
        data: {
            labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
            datasets: [{
                label: "Logins",
                data: [80, 120, 90, 140, 200, 170, 220],
                borderWidth: 1
            }]
        }
    });


    // REVENUE
    new Chart(document.getElementById("revenueChart"), {
        type: "line",
        data: {
            labels: ["Week 1", "Week 2", "Week 3", "Week 4"],
            datasets: [{
                label: "Revenue ($)",
                data: [3000, 4200, 3800, 5000],
                borderWidth: 2,
                tension: 0.4
            }]
        }
    });


    // COURSE POPULARITY
    new Chart(document.getElementById("courseChart"), {
        type: "bar",
        data: {
            labels: ["PHP", "JS", "Python", "ML", "DSA"],
            datasets: [{
                label: "Enrollments",
                data: [120, 190, 300, 150, 220],
                borderWidth: 1
            }]
        }
    });
</script>


<!-- ******************************************************************** Part 3 ******************************************************************** -->

<!-- Body Content for Analytics Dashboard -->
<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-1 text-dark font-weight-bold">📊 Analytics Dashboard</h4>
                <p class="text-muted mb-0">Real-time insights about your platform performance</p>
            </div>
            <div class="d-flex gap-2 mt-2 mt-sm-0">
                <button class="btn btn-light border rounded-pill px-3" onclick="changeDateRange()">
                    <i class="fas fa-calendar-alt me-2"></i>Last 30 Days
                    <i class="fas fa-chevron-down ms-2"></i>
                </button>
                <button class="btn btn-primary rounded-pill px-4" onclick="refreshData()">
                    <i class="fas fa-sync-alt me-2"></i>Refresh
                </button>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card border-0 shadow-sm hover-shadow transition-all">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Total Users</p>
                                <h3 class="mb-0 fw-bold">24,851</h3>
                                <span class="badge bg-success bg-opacity-10 text-success mt-2">
                                    <i class="fas fa-arrow-up me-1"></i>+12.5%
                                </span>
                            </div>
                            <div class="rounded-circle p-3 bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-users fs-4"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted"><i class="far fa-calendar-alt me-1"></i>1,284 new this month</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card border-0 shadow-sm hover-shadow transition-all">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Today's Visitors</p>
                                <h3 class="mb-0 fw-bold">847</h3>
                                <span class="badge bg-success bg-opacity-10 text-success mt-2">
                                    <i class="fas fa-arrow-up me-1"></i>+8.3%
                                </span>
                            </div>
                            <div class="rounded-circle p-3 bg-success bg-opacity-10 text-success">
                                <i class="fas fa-user-check fs-4"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted"><i class="far fa-clock me-1"></i>23 new today</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card border-0 shadow-sm hover-shadow transition-all">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Active Subscriptions</p>
                                <h3 class="mb-0 fw-bold">3,247</h3>
                                <span class="badge bg-warning bg-opacity-10 text-warning mt-2">
                                    <i class="fas fa-arrow-up me-1"></i>+5.2%
                                </span>
                            </div>
                            <div class="rounded-circle p-3 bg-purple bg-opacity-10 text-purple">
                                <i class="fas fa-check-circle fs-4"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted"><i class="fas fa-layer-group me-1"></i>4,891 total</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card border-0 shadow-sm hover-shadow transition-all">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Total Revenue</p>
                                <h3 class="mb-0 fw-bold">$124,583</h3>
                                <span class="badge bg-success bg-opacity-10 text-success mt-2">
                                    <i class="fas fa-arrow-up me-1"></i>+18.7%
                                </span>
                            </div>
                            <div class="rounded-circle p-3 bg-info bg-opacity-10 text-info">
                                <i class="fas fa-dollar-sign fs-4"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted"><i class="far fa-calendar-alt me-1"></i>$18,432 this month</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Row Stats -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card border-0 shadow-sm hover-shadow transition-all">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Total Logins</p>
                                <h3 class="mb-0 fw-bold">186,432</h3>
                                <span class="badge bg-success bg-opacity-10 text-success mt-2">
                                    <i class="fas fa-arrow-up me-1"></i>+23.1%
                                </span>
                            </div>
                            <div class="rounded-circle p-3 bg-orange bg-opacity-10 text-orange">
                                <i class="fas fa-sign-in-alt fs-4"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted"><i class="far fa-calendar-day me-1"></i>847 today</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card border-0 shadow-sm hover-shadow transition-all">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Course Enrollments</p>
                                <h3 class="mb-0 fw-bold">8,934</h3>
                                <span class="badge bg-success bg-opacity-10 text-success mt-2">
                                    <i class="fas fa-arrow-up me-1"></i>+15.8%
                                </span>
                            </div>
                            <div class="rounded-circle p-3 bg-danger bg-opacity-10 text-danger">
                                <i class="fas fa-graduation-cap fs-4"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted"><i class="fas fa-clock me-1"></i>342 this week</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card border-0 shadow-sm hover-shadow transition-all">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Bounce Rate</p>
                                <h3 class="mb-0 fw-bold">24.8%</h3>
                                <span class="badge bg-danger bg-opacity-10 text-danger mt-2">
                                    <i class="fas fa-arrow-down me-1"></i>-2.1%
                                </span>
                            </div>
                            <div class="rounded-circle p-3 bg-pink bg-opacity-10 text-pink">
                                <i class="fas fa-arrow-trend-down fs-4"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted"><i class="fas fa-check-circle me-1"></i>Good performance</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card border-0 shadow-sm hover-shadow transition-all">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Avg. Session Time</p>
                                <h3 class="mb-0 fw-bold">4m 32s</h3>
                                <span class="badge bg-success bg-opacity-10 text-success mt-2">
                                    <i class="fas fa-arrow-up me-1"></i>+0.8%
                                </span>
                            </div>
                            <div class="rounded-circle p-3 bg-teal bg-opacity-10 text-teal">
                                <i class="fas fa-clock fs-4"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted"><i class="fas fa-users me-1"></i>Above average</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-3 mb-4">
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">Monthly Registrations 2024</h5>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary active">Year</button>
                                <button class="btn btn-outline-primary">Month</button>
                                <button class="btn btn-outline-primary">Week</button>
                            </div>
                        </div>
                        <div style="height: 320px;">
                            <canvas id="registrationsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">User Types Distribution</h5>
                        <div style="height: 280px;">
                            <canvas id="userTypesChart"></canvas>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                <span><span class="badge rounded-circle p-2 bg-primary me-2"></span> Student</span>
                                <span class="fw-bold">12,458</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                <span><span class="badge rounded-circle p-2 bg-success me-2"></span> Tutor</span>
                                <span class="fw-bold">4,892</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                <span><span class="badge rounded-circle p-2 bg-warning me-2"></span> Admin</span>
                                <span class="fw-bold">1,247</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2">
                                <span><span class="badge rounded-circle p-2 bg-info me-2"></span> Guest</span>
                                <span class="fw-bold">6,254</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Users Table -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">🆕 Recent Users</h5>
                    <a href="#" class="text-primary text-decoration-none">View All →</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Registration Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-primary text-white me-2">
                                            <span>JD</span>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">John Doe</div>
                                        </div>
                                    </div>
                                </td>
                                <td>john.doe@email.com</td>
                                <td><span class="badge bg-primary bg-opacity-10 text-primary">Student</span></td>
                                <td>Dec 15, 2024</td>
                                <td><span class="badge bg-success">Active</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-success text-white me-2">
                                            <span>JS</span>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">Jane Smith</div>
                                        </div>
                                    </div>
                                </td>
                                <td>jane.smith@email.com</td>
                                <td><span class="badge bg-success bg-opacity-10 text-success">Tutor</span></td>
                                <td>Dec 14, 2024</td>
                                <td><span class="badge bg-success">Active</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-warning text-white me-2">
                                            <span>RM</span>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">Robert Miller</div>
                                        </div>
                                    </div>
                                </td>
                                <td>robert.m@email.com</td>
                                <td><span class="badge bg-warning bg-opacity-10 text-warning">Admin</span></td>
                                <td>Dec 13, 2024</td>
                                <td><span class="badge bg-success">Active</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-info text-white me-2">
                                            <span>AL</span>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">Alice Johnson</div>
                                        </div>
                                    </div>
                                </td>
                                <td>alice.j@email.com</td>
                                <td><span class="badge bg-primary bg-opacity-10 text-primary">Student</span></td>
                                <td>Dec 12, 2024</td>
                                <td><span class="badge bg-warning">Pending</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-danger text-white me-2">
                                            <span>TB</span>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">Tom Brown</div>
                                        </div>
                                    </div>
                                </td>
                                <td>tom.brown@email.com</td>
                                <td><span class="badge bg-primary bg-opacity-10 text-primary">Student</span></td>
                                <td>Dec 11, 2024</td>
                                <td><span class="badge bg-success">Active</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Logins & Subscriptions -->
        <div class="row g-3 mb-4">
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">🔐 Recent Login Activity</h5>
                            <a href="#" class="text-primary text-decoration-none">View All →</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Last Login</th>
                                        <th>Provider</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-primary text-white me-2" style="width: 28px; height: 28px; font-size: 11px;">
                                                    <span>JD</span>
                                                </div>
                                                <span class="fw-semibold">John Doe</span>
                                            </div>
                                        </td>
                                        <td>Dec 15, 2024 10:30 AM</td>
                                        <td><span class="badge bg-info bg-opacity-10 text-info">Email</span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-success text-white me-2" style="width: 28px; height: 28px; font-size: 11px;">
                                                    <span>JS</span>
                                                </div>
                                                <span class="fw-semibold">Jane Smith</span>
                                            </div>
                                        </td>
                                        <td>Dec 15, 2024 09:15 AM</td>
                                        <td><span class="badge bg-primary bg-opacity-10 text-primary">Google</span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-warning text-white me-2" style="width: 28px; height: 28px; font-size: 11px;">
                                                    <span>RM</span>
                                                </div>
                                                <span class="fw-semibold">Robert Miller</span>
                                            </div>
                                        </td>
                                        <td>Dec 15, 2024 08:45 AM</td>
                                        <td><span class="badge bg-primary bg-opacity-10 text-primary">Facebook</span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-info text-white me-2" style="width: 28px; height: 28px; font-size: 11px;">
                                                    <span>AL</span>
                                                </div>
                                                <span class="fw-semibold">Alice Johnson</span>
                                            </div>
                                        </td>
                                        <td>Dec 14, 2024 11:20 PM</td>
                                        <td><span class="badge bg-info bg-opacity-10 text-info">Email</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">📚 Recent Subscriptions</h5>
                            <a href="#" class="text-primary text-decoration-none">View All →</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Package</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-primary text-white me-2" style="width: 28px; height: 28px; font-size: 11px;">
                                                    <span>JD</span>
                                                </div>
                                                <span class="fw-semibold">John Doe</span>
                                            </div>
                                        </td>
                                        <td>Premium Plan</td>
                                        <td>$49.00</td>
                                        <td><span class="badge bg-success">Active</span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-success text-white me-2" style="width: 28px; height: 28px; font-size: 11px;">
                                                    <span>JS</span>
                                                </div>
                                                <span class="fw-semibold">Jane Smith</span>
                                            </div>
                                        </td>
                                        <td>Pro Plan</td>
                                        <td>$79.00</td>
                                        <td><span class="badge bg-success">Active</span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-danger text-white me-2" style="width: 28px; height: 28px; font-size: 11px;">
                                                    <span>TB</span>
                                                </div>
                                                <span class="fw-semibold">Tom Brown</span>
                                            </div>
                                        </td>
                                        <td>Basic Plan</td>
                                        <td>$29.00</td>
                                        <td><span class="badge bg-warning">Expiring</span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-purple text-white me-2" style="width: 28px; height: 28px; font-size: 11px;">
                                                    <span>SW</span>
                                                </div>
                                                <span class="fw-semibold">Sarah Wilson</span>
                                            </div>
                                        </td>
                                        <td>Premium Plan</td>
                                        <td>$49.00</td>
                                        <td><span class="badge bg-danger">Expired</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Traffic Source -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">🌐 Traffic Sources</h5>
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <div class="p-3 bg-light rounded-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-1">Direct</p>
                                    <h5 class="fw-bold mb-0">38%</h5>
                                </div>
                                <i class="fas fa-link text-primary fs-4"></i>
                            </div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-primary" style="width: 38%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-3 bg-light rounded-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-1">Organic</p>
                                    <h5 class="fw-bold mb-0">32%</h5>
                                </div>
                                <i class="fas fa-search text-success fs-4"></i>
                            </div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-success" style="width: 32%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-3 bg-light rounded-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-1">Social</p>
                                    <h5 class="fw-bold mb-0">18%</h5>
                                </div>
                                <i class="fas fa-share-alt text-info fs-4"></i>
                            </div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-info" style="width: 18%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-3 bg-light rounded-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-1">Referral</p>
                                    <h5 class="fw-bold mb-0">12%</h5>
                                </div>
                                <i class="fas fa-arrow-right text-warning fs-4"></i>
                            </div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-warning" style="width: 12%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
    /* Custom Colors */
    .bg-purple {
        background-color: #7c3aed;
    }

    .text-purple {
        color: #7c3aed;
    }

    .bg-orange {
        background-color: #ea580c;
    }

    .text-orange {
        color: #ea580c;
    }

    .bg-pink {
        background-color: #ec4899;
    }

    .text-pink {
        color: #ec4899;
    }

    .bg-teal {
        background-color: #0d9488;
    }

    .text-teal {
        color: #0d9488;
    }

    /* Avatar Circle */
    .avatar-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 12px;
        flex-shrink: 0;
    }

    /* Hover Effects */
    .hover-shadow {
        transition: all 0.3s ease;
    }

    .hover-shadow:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12) !important;
    }

    .transition-all {
        transition: all 0.3s ease;
    }

    /* Badge Opacity */
    .bg-opacity-10 {
        --bs-bg-opacity: 0.1;
    }

    /* Progress Bar */
    .progress {
        background-color: #e9ecef;
        border-radius: 10px;
    }

    /* Responsive Gap */
    .gap-2 {
        gap: 0.5rem;
    }

    /* Table Styles */
    .table> :not(caption)>*>* {
        padding: 0.75rem 0.5rem;
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 10px;
            padding-right: 10px;
        }
    }
</style>

<!-- Chart.js Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Registrations Chart
    const ctx = document.getElementById('registrationsChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'New Registrations',
                data: [1250, 1380, 1420, 1580, 1620, 1750, 1890, 1950, 2100, 2250, 2380, 2480],
                backgroundColor: 'rgba(79, 70, 229, 0.6)',
                borderColor: '#4f46e5',
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 500
                    },
                    grid: {
                        display: true,
                        color: 'rgba(0,0,0,0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // User Types Chart (Doughnut)
    const ctx2 = document.getElementById('userTypesChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Student', 'Tutor', 'Admin', 'Guest'],
            datasets: [{
                data: [12458, 4892, 1247, 6254],
                backgroundColor: ['#4f46e5', '#059669', '#eab308', '#0ea5e9'],
                borderWidth: 2,
                borderColor: '#ffffff',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '70%'
        }
    });

    // Refresh function
    function refreshData() {
        alert('🔄 Refreshing analytics data...');
        location.reload();
    }

    // Date range function
    function changeDateRange() {
        alert('📅 Date range filter coming soon!');
    }
</script>







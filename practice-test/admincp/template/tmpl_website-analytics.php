<!-- <div>
    
    <pre>
        <?= json_encode($course_chart_data, JSON_PRETTY_PRINT); ?>
    </pre>
    
</div> -->

<!-- Start Header Part -->
<div class="container-fluid" style="background:#f6f7fb; padding: 15px; margin-top:15px;">
    <!-- HEADER -->
    <div class="row" style="margin-bottom: 20px; padding: 0 5px;">
        <div class="col-xs-12">
            <h3 style="font-weight: bold; margin-bottom: 5px;">📊 Analytics Dashboard</h3>
            <p class="text-muted" style="margin-bottom: 0; font-size: 13px;">Overview of users, engagement and revenue</p>
        </div>
    </div>

    <!-- KPI CARDS -->
    <div class="row" style="margin: 0 -5px 20px -5px;">

        <!-- Total Users Card -->
        <div class="col-6 col-md-3" style="padding: 0 5px 10px 5px;">
            <div class="card kpi-card" style="background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); padding: 12px 15px; height: 100%; border: none;">
                <h6 style="color: #6c757d; font-size: 12px; margin-bottom: 4px;">
                    <i class="fa fa-users" style="color: #6c757d;"></i> Total Users
                </h6>
                <h3 style="font-weight: bold; margin-bottom: 4px; font-size: 24px;"><? echo $total_users; ?></h3>
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                    <small style="color: #28a745; font-size: 12px;">
                        <i class="fa fa-check-circle" style="margin-right: 4px;"></i><?php echo number_format($active_users); ?> active today
                    </small>
                    <small style="color: #28a745; font-size: 12px;">
                        <i class="fa fa-user-plus" style="margin-right: 4px;"></i><?php echo $new_users_today; ?> new today
                    </small>
                </div>
            </div>
        </div>

        <!-- Web Users Card -->
        <div class="col-6 col-md-3" style="padding: 0 5px 10px 5px;">
            <div class="card kpi-card" style="background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); padding: 12px 15px; height: 100%; border: none;">
                <h6 style="color: #6c757d; font-size: 12px; margin-bottom: 4px;">
                    <i class="fa fa-desktop" style="color: #6c757d;"></i> Web Users
                </h6>
                <h3 style="font-weight: bold; margin-bottom: 4px; font-size: 24px;"><?php echo $web_users; ?></h3>
                <?
                $web_color = $web_growth >= 0 ? '#059669' : '#dc3545';
                $web_arrow = $web_growth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
                ?>
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                    <small style="color: <?php echo $web_color; ?>; font-size: 12px;">
                        <i class="fa <?php echo $web_arrow; ?>" style="margin-right: 4px;"></i><?php echo $web_growth; ?>% MoM
                    </small>
                    <small style="color: #28a745; font-size: 12px;">
                        <i class="fa fa-user-plus" style="margin-right: 4px;"></i><?php echo $new_web_users_today; ?> new today
                    </small>
                </div>
            </div>
        </div>

        <!-- App Users Card -->
        <div class="col-6 col-md-3" style="padding: 0 5px 10px 5px;">
            <div class="card kpi-card" style="background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); padding: 12px 15px; height: 100%; border: none;">
                <h6 style="color: #6c757d; font-size: 12px; margin-bottom: 4px;">
                    <i class="fa fa-mobile" style="color: #6c757d;"></i> App Users
                </h6>
                <h3 style="font-weight: bold; margin-bottom: 4px; font-size: 24px;"><?php echo $app_users; ?></h3>
                <?
                $app_color = $app_growth >= 0 ? '#059669' : '#dc3545';
                $app_arrow = $app_growth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
                ?>
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                    <small style="color: <?php echo $app_color; ?>; font-size: 12px;">
                        <i class="fa <?php echo $app_arrow; ?>" style="margin-right: 4px;"></i><?php echo $app_growth; ?>% MoM
                    </small>
                    <small style="color: #28a745; font-size: 12px;">
                        <i class="fa fa-user-plus" style="margin-right: 4px;"></i><?php echo $new_app_users_today; ?> new today
                    </small>
                </div>
            </div>
        </div>

        <!-- Revenue Card -->
        <div class="col-6 col-md-3" style="padding: 0 5px 10px 5px;">
            <div class="card kpi-card" style="background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); padding: 12px 15px; height: 100%; border: none;">
                <h6 style="color: #6c757d; font-size: 12px; margin-bottom: 4px;">
                    <i class="fa fa-dollar" style="color: #6c757d;"></i> Monthly Revenue
                </h6>
                <h3 style="font-weight: bold; margin-bottom: 4px; font-size: 24px;">$<?php echo number_format($this_month_revenue); ?></h3>
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                    <small style="color: <?php echo $revenue_growth >= 0 ? '#28a745' : '#dc3545'; ?>; font-size: 12px;">
                        <i class="fa <?php echo $revenue_growth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down'; ?>" style="margin-right: 4px;"></i><?php echo $revenue_growth; ?>% MoM
                    </small>
                    <small style="color: #28a745; font-size: 12px;">
                        <i class="fa fa-user" style="margin-right: 4px;"></i><?php echo $total_subscriptions; ?> subs
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Header Part -->

<br>

<!-- Start Chart Part -->
<div>
    <div class="section-tag">
        <i class="fa fa-line-chart" style="margin-right: 3px;"></i> Growth & Engagement Insights
    </div>
    <div class="row g-3">
        <div class="col-md-6" style="margin-bottom: 15px;">
            <div class="card shadow-sm border-0 rounded-4 p-3 dashboard-wrapper">

                <!-- HEADER -->
                <div class="d-flex justify-content-between align-items-end mb-3 flex-wrap gap-2">

                    <!-- LEFT -->
                    <div>
                        <div style="font-size:18px; font-weight:700; color:#0f172a;">
                            User Engagement Overview
                        </div>
                        <div style="font-size:12px; color:#94a3b8; margin-top:2px; margin-bottom:5px;">
                            User Activity Trends: Logins, Registrations, and Subscriptions
                        </div>
                    </div>

                    <!-- RIGHT (MODERN TOGGLE) -->
                    <div style="display:flex; gap:4px; background:#f1f5f9; padding:4px; border-radius:12px;">
                        <button class="time-btn active" data-period="7d">7 Days</button>
                        <button class="time-btn" data-period="10w">10 Weeks</button>
                        <button class="time-btn" data-period="12m">12 Months</button>
                        <button class="time-btn" data-period="5y">5 Years</button>
                    </div>

                </div>

                <!-- CHART -->
                <div class="chart-wrapper">
                    <canvas id="userChart"></canvas>
                </div>

            </div>
        </div>

        <div class="col-md-6" style="margin-bottom: 15px;">
            <div class="card shadow-sm border-0 rounded-4 p-3 dashboard-wrapper">

                <!-- HEADER -->
                <div class="d-flex justify-content-between align-items-end mb-3 flex-wrap gap-2">

                    <!-- LEFT -->
                    <div>
                        <div style="font-size:18px; font-weight:700; color:#0f172a;">
                            App vs Web Analysis
                        </div>
                        <div style="font-size:12px; color:#94a3b8; margin-top:2px; margin-bottom:5px;">
                            App vs Web registrations and subscriptions across time
                        </div>
                    </div>

                    <!-- RIGHT (MODERN TOGGLE) -->
                    <div style="display:flex; gap:4px; background:#f1f5f9; padding:4px; border-radius:12px;">
                        <button class="app-btn active" data-period="7d">7 Days</button>
                        <button class="app-btn" data-period="10w">10 Weeks</button>
                        <button class="app-btn" data-period="12m">12 Months</button>
                        <button class="app-btn" data-period="5y">5 Years</button>
                    </div>

                </div>

                <!-- CHART -->
                <div class="chart-wrapper">
                    <canvas id="appVsWebChart"></canvas>
                </div>

            </div>
        </div>
    </div>

    <div class="row g-3">

        <div class="col-md-6" style="margin-bottom: 15px;">
            <div class="card shadow-sm border-0 rounded-4 p-3 dashboard-wrapper">

                <!-- HEADER -->
                <div class="d-flex justify-content-between align-items-end mb-3 flex-wrap gap-2">

                    <div>
                        <div style="font-size:18px; font-weight:700; color:#0f172a;">
                            Revenue Overview
                        </div>
                        <div style="font-size:12px; color:#94a3b8; margin-top:2px; margin-bottom:5px;">
                            Total, App & Web revenue trend
                        </div>
                    </div>

                    <div style="display:flex; gap:4px; background:#f1f5f9; padding:4px; border-radius:12px;">
                        <button class="rev-btn active" data-period="7d">7 Days</button>
                        <button class="rev-btn" data-period="10w">10 Weeks</button>
                        <button class="rev-btn" data-period="12m">12 Months</button>
                        <button class="rev-btn" data-period="5y">5 Years</button>
                    </div>

                </div>

                <!-- CHART -->
                <div class="chart-wrapper">
                    <canvas id="revenueChart"></canvas>
                </div>

            </div>
        </div>

        <!-- User login pi chart for app and web -->
        <div class="col-md-6" style="margin-bottom: 15px;">
            <div class="card shadow-sm border-0 rounded-4 p-3 dashboard-wrapper">

                <!-- HEADER -->
                <div class="d-flex justify-content-between align-items-end mb-3 flex-wrap gap-2">

                    <div>
                        <div style="font-size:18px; font-weight:700; color:#0f172a;">
                            Login Distribution (Last 1 Month)
                        </div>
                        <div style="font-size:12px; color:#94a3b8; margin-top:2px; margin-bottom:5px;">
                            App vs Web user login share
                        </div>
                    </div>

                </div>

                <!-- CHART -->
                <div class="chart-wrapper" style="margin-bottom: 38px;">
                    <canvas id="loginPieChart"></canvas>
                </div>

            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Course Chart -->
        <div class="col-md-12" style="margin-bottom: 15px;">
            <div class="card shadow-sm border-0 rounded-4 p-3 dashboard-wrapper">

                <!-- HEADER -->
                <div class="d-flex justify-content-between align-items-end mb-3 flex-wrap gap-2">

                    <!-- LEFT -->
                    <div>
                        <div style="font-size:18px; font-weight:700; color:#0f172a;">
                            Course Enrollment Overview
                        </div>
                        <div style="font-size:12px; color:#94a3b8; margin-top:2px; margin-bottom:5px;">
                            Registration vs Subscription by Course
                        </div>
                    </div>

                    <!-- RIGHT (FILTER) -->
                    <div style="display:flex; gap:4px; background:#f1f5f9; padding:4px; border-radius:12px;">
                        <button class="course-btn active" data-filter="all">Week</button>
                        <button class="course-btn" data-filter="top">Month</button>
                        <button class="course-btn" data-filter="bottom">Year</button>
                    </div>

                </div>

                <!-- CHART -->
                <div style="position:relative; height:320px;">
                    <canvas id="courseChart"></canvas>
                </div>

            </div>
        </div>
    </div>

</div>
<!-- End Chart Part -->


<!-- =======================
     CHART.JS 
======================= -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<!-- -------- user engagement chart data -------- -->
<script>
    const chartData = <?= json_encode($user_engagement_chart_data); ?>;

    // -------- CHART --------
    const ctx = document.getElementById('userChart').getContext('2d');

    let chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData['7d'].labels,
            datasets: [{
                    label: 'Logged In',
                    data: chartData['7d'].loggedIn,
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.08)',
                    borderWidth: 2.5,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3,
                    pointBackgroundColor: '#4f46e5'
                },
                {
                    label: 'Registered',
                    data: chartData['7d'].registered,
                    borderColor: '#0ea5e9',
                    backgroundColor: 'rgba(14, 165, 233, 0.08)',
                    borderWidth: 2.5,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3,
                    pointBackgroundColor: '#0ea5e9'
                },
                {
                    label: 'Subscribed',
                    data: chartData['7d'].subscribed,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.08)',
                    borderWidth: 2.5,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3,
                    pointBackgroundColor: '#10b981'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        padding: 20,
                        font: {
                            size: 11,
                            weight: '500'
                        },
                        color: '#475569'
                    }
                }
            },
            scales: {
                y: {
                    type: 'logarithmic',
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.04)',
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        },
                        color: '#94a3b8'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        },
                        color: '#94a3b8'
                    }
                }
            }
        }
    });

    // -------- PERIOD SWITCHER --------
    document.querySelectorAll('.time-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active from all
            document.querySelectorAll('.time-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const period = this.dataset.period;
            const data = chartData[period];

            chart.data.labels = data.labels;
            chart.data.datasets[0].data = data.loggedIn;
            chart.data.datasets[1].data = data.registered;
            chart.data.datasets[2].data = data.subscribed;
            chart.update();
        });
    });
</script>


<!-- -------- revenue chart data -------- -->
<script>
    const revenueData = <?= json_encode($revenue_chart_data); ?>;

    const rctx = document.getElementById('revenueChart').getContext('2d');

    let revenueChart = new Chart(rctx, {
        type: 'line',
        data: {
            labels: revenueData['7d'].labels,
            datasets: [{
                    label: 'Total Revenue',
                    data: revenueData['7d'].total,
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.08)',
                    borderWidth: 2.5,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3
                },
                {
                    label: 'App Revenue',
                    data: revenueData['7d'].app,
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.08)',
                    borderWidth: 2.5,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3
                },
                {
                    label: 'Web Revenue',
                    data: revenueData['7d'].web,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.08)',
                    borderWidth: 2.5,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            size: 11
                        }
                    }
                }
            },
            scales: {
                y: {
                    type: 'logarithmic',
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.04)',
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        },
                        color: '#94a3b8'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        },
                        color: '#94a3b8'
                    }
                }
            }
        }
    });

    // -------- PERIOD SWITCHER --------
    document.querySelectorAll('.rev-btn').forEach(btn => {
        btn.addEventListener('click', function() {

            document.querySelectorAll('.rev-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const period = this.dataset.period;
            const data = revenueData[period];

            revenueChart.data.labels = data.labels;
            revenueChart.data.datasets[0].data = data.total;
            revenueChart.data.datasets[1].data = data.app;
            revenueChart.data.datasets[2].data = data.web;

            revenueChart.update();
        });
    });
</script>


<!-- -------- app vs web bar chart data ------ -->
<script>
    const appVsWebData = <?= json_encode($app_vs_web_chart_data); ?>;

    // Wait for DOM to load
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('appVsWebChart');

        // Check if canvas exists
        if (!ctx) {
            console.error('appVsWebChart canvas not found');
            return;
        }

        const rctx = ctx.getContext('2d');

        let appVsWebChart = new Chart(rctx, {
            type: 'bar',
            data: {
                labels: appVsWebData['7d'].labels,
                datasets: [{
                    label: 'App Registered',
                    data: appVsWebData['7d'].app_registered,
                    backgroundColor: 'rgba(79, 70, 229, 0.85)',
                    borderRadius: 6
                }, {
                    label: 'App Subscribed',
                    data: appVsWebData['7d'].app_subscribed,
                    backgroundColor: 'rgba(79, 70, 229, 0.45)',
                    borderRadius: 6
                }, {
                    label: 'Web Registered',
                    data: appVsWebData['7d'].web_registered,
                    backgroundColor: 'rgba(16, 185, 129, 0.85)',
                    borderRadius: 6
                }, {
                    label: 'Web Subscribed',
                    data: appVsWebData['7d'].web_subscribed,
                    backgroundColor: 'rgba(16, 185, 129, 0.45)',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: {
                                size: 11
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        stacked: false,
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            color: '#94a3b8'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            color: '#94a3b8'
                        }
                    }
                }
            }
        });

        // Period switcher
        document.querySelectorAll('.app-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.app-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const period = this.dataset.period;
                const data = appVsWebData[period];

                if (data) {
                    appVsWebChart.data.labels = data.labels;
                    appVsWebChart.data.datasets[0].data = data.app_registered;
                    appVsWebChart.data.datasets[1].data = data.app_subscribed;
                    appVsWebChart.data.datasets[2].data = data.web_registered;
                    appVsWebChart.data.datasets[3].data = data.web_subscribed;
                    appVsWebChart.update();
                }
            });
        });
    });
</script>


<!-- course chart data -->
<script>
    const courseData = <?= json_encode($course_chart_data); ?>;

    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('courseChart');

        if (!ctx) {
            console.error('courseChart canvas not found');
            return;
        }

        const courseCtx = ctx.getContext('2d');

        let courseChart = new Chart(courseCtx, {
            type: 'bar',
            data: {
                labels: courseData.all.labels,
                datasets: [{
                        label: 'Enrolled',
                        data: courseData.all.enrolled,
                        backgroundColor: 'rgba(79, 70, 229, 0.85)',
                        borderRadius: 6
                    },
                    {
                        label: 'Subscribed',
                        data: courseData.all.subscribed,
                        backgroundColor: 'rgba(16, 185, 129, 0.85)',
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: {
                                size: 11
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 10
                            },
                            color: '#94a3b8',
                            maxRotation: 45,
                            minRotation: 0
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            color: '#94a3b8'
                        }
                    }
                }
            }
        });

        // Filter switcher
        document.querySelectorAll('.course-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.course-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const filter = this.dataset.filter;
                const data = courseData[filter];

                if (data) {
                    courseChart.data.labels = data.labels;
                    courseChart.data.datasets[0].data = data.enrolled;
                    courseChart.data.datasets[1].data = data.subscribed;
                    courseChart.update();
                }
            });
        });
    });
</script>

<script>
    const loginData = <?= json_encode($login_pie_data); ?>;

    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('loginPieChart').getContext('2d');

        // Center text plugin
        const centerTextPlugin = {
            id: 'centerText',
            afterDraw(chart) {
                const {
                    ctx
                } = chart;
                const total = loginData.app + loginData.web;
                const meta = chart.getDatasetMeta(0);

                if (!meta.data || meta.data.length === 0) return;

                const centerX = (meta.data[0].x + meta.data[1].x) / 2;
                const centerY = (meta.data[0].y + meta.data[1].y) / 2;

                ctx.save();
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';

                // Label
                ctx.fillStyle = '#94a3b8';
                ctx.font = '11px Inter, -apple-system, sans-serif';
                ctx.fillText('Total Logins', centerX, centerY - 16);

                // Value
                ctx.fillStyle = '#0f172a';
                ctx.font = 'bold 24px Inter, -apple-system, sans-serif';
                ctx.fillText(total.toLocaleString(), centerX, centerY + 14);

                ctx.restore();
            }
        };

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [`App`, `Web`],
                datasets: [{
                    data: [loginData.app, loginData.web],
                    backgroundColor: [
                        '#4f46e5',
                        '#10b981'
                    ],
                    hoverBackgroundColor: [
                        '#4338ca',
                        '#059669'
                    ],
                    hoverOffset: 10,
                    borderWidth: 3,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 20,
                            color: '#475569',
                            font: {
                                size: 13,
                                weight: '500'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255,255,255,0.95)',
                        titleColor: '#0f172a',
                        bodyColor: '#475569',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        cornerRadius: 10,
                        padding: 12,
                        callbacks: {
                            label(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const value = context.raw;
                                const percent = ((value / total) * 100).toFixed(1);
                                return `${context.label}: ${value.toLocaleString()} (${percent}%)`;
                            }
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    duration: 1000
                }
            },
            plugins: [centerTextPlugin]
        });
    });
</script>

<style>
    #loginPieChart {
        max-width: 280px;
        max-height: 280px;
        margin: 0 auto;
        display: block;
    }

    .dashboard-wrapper {
        display: flex;
        flex-direction: column;
    }
</style>


<style>
    .course-btn {
        border: none;
        background: transparent;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 16px;
        border-radius: 8px;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .course-btn:hover {
        color: #0f172a;
        background: rgba(255, 255, 255, 0.5);
    }

    .course-btn.active {
        background: #ffffff;
        color: #0f172a;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
</style>


<style>
    .time-btn {
        border: none;
        background: transparent;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 16px;
        border-radius: 8px;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .time-btn:hover {
        color: #0f172a;
        background: rgba(255, 255, 255, 0.5);
    }

    .time-btn.active {
        background: #ffffff;
        color: #0f172a;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
</style>

<style>
    .rev-btn {
        border: none;
        background: transparent;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 16px;
        border-radius: 8px;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .rev-btn:hover {
        color: #0f172a;
        background: rgba(255, 255, 255, 0.5);
    }

    .rev-btn.active {
        background: #ffffff;
        color: #0f172a;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
</style>

<style>
    .app-btn {
        border: none;
        background: transparent;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 16px;
        border-radius: 8px;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .app-btn:hover {
        color: #0f172a;
        background: rgba(255, 255, 255, 0.5);
    }

    .app-btn.active {
        background: #ffffff;
        color: #0f172a;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
</style>

<style>
    .section-tag {
        display: flex;
        align-items: center;
        justify-content: center;

        width: fit-content;
        margin: 0 auto;
        /* 👈 this centers it horizontally */

        margin-bottom: 15px;
        padding: 6px 12px;
        border-radius: 999px;

        background: #eef2ff;
        color: #4f46e5;

        font-size: 11px;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .section-tag:hover {
        background: #e0e7ff;
        cursor: default;
    }

    .dashboard-wrapper {
        background: #f6f7fb;
        padding: 20px;
        border-radius: 16px;
    }
</style>

<style>
    .chart-wrapper {
        position: relative;
        height: 320px;
        margin-top: 38px;
    }

    @media (max-width: 768px) {
        .chart-wrapper {
            height: 260px;
            margin-top: 20px;
        }
    }

    @media (max-width: 576px) {
        .chart-wrapper {
            height: 220px;
            margin-top: 15px;
        }
    }
</style>

<style>
    .kpi-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    padding: 12px 15px;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

/* prevent weird wrapping issues */
.kpi-card h6 {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: nowrap;
}

.kpi-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    flex-wrap: nowrap;
}

/* responsive fix */
@media (max-width: 576px) {
    .kpi-card h3 {
        font-size: 18px;
    }

    .kpi-footer small {
        font-size: 11px;
    }
}
</style>
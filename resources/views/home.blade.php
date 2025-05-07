@extends('layouts.app')

@section('content')

<main id="main" class="main">

    <!-- Vibrant Header -->
    <div class="dashboard-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="header-content">
            <h1>Visitor Management System</h1>
            <div class="header-meta">
                <span class="current-date">{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</span>
                <span class="greeting">Good {{ \Carbon\Carbon::now()->hour < 12 ? 'Morning' : (\Carbon\Carbon::now()->hour < 18 ? 'Afternoon' : 'Evening') }}, Admin</span>
            </div>
        </div>
    </div>

    <section class="dashboard-section">
        <!-- Colorful Stats Overview -->
        <div class="stats-grid">
            <!-- Checked In Today -->
            <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="bi bi-door-open"></i>
                    </div>
                    <div class="stat-text">
                        <span class="stat-label">Checked In</span>
                        <span class="stat-value">{{ $totalCheckedInVisitors }}</span>
                        <span class="stat-period">Today</span>
                    </div>
                </div>
                <div class="stat-trend">
                    <i class="bi bi-graph-up-arrow"></i> 12%
                </div>
            </div>

            <!-- Checked Out Today -->
            <div class="stat-card" style="background: linear-gradient(135deg, #ff758c 0%, #ff7eb3 100%);">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="bi bi-door-closed"></i>
                    </div>
                    <div class="stat-text">
                        <span class="stat-label">Checked Out</span>
                        <span class="stat-value">{{ $totalCheckedOutVisitors }}</span>
                        <span class="stat-period">Today</span>
                    </div>
                </div>
                <div class="stat-trend">
                    <i class="bi bi-graph-down-arrow"></i> 5%
                </div>
            </div>

            <!-- Total Visitors Today -->
            <div class="stat-card" style="background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stat-text">
                        <span class="stat-label">Total Visitors</span>
                        <span class="stat-value">{{ $totalVisitors }}</span>
                        <span class="stat-period">Today</span>
                    </div>
                </div>
                <div class="stat-trend">
                    <i class="bi bi-graph-up-arrow"></i> 8%
                </div>
            </div>

            <!-- Weekly Visitors -->
            <div class="stat-card" style="background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="bi bi-calendar-week"></i>
                    </div>
                    <div class="stat-text">
                        <span class="stat-label">Weekly Visitors</span>
                        <span class="stat-value">{{ $totalCheckInsLastWeek }}</span>
                        <span class="stat-period">Last 7 Days</span>
                    </div>
                </div>
                <div class="stat-trend">
                    <i class="bi bi-graph-up-arrow"></i> 15%
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="content-grid">
            <!-- Visitor Trends Chart -->
            <div class="card chart-card">
                <div class="card-header">
                    <h3><i class="bi bi-graph-up"></i> Visitor Trends</h3>
                    <div class="chart-controls">
                        <button class="time-filter active">7D</button>
                        <button class="time-filter">30D</button>
                        <button class="time-filter">90D</button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="visitorsTrendChart"></canvas>
                </div>
            </div>

            <!-- Recent Check-ins -->
            <div class="card recent-checkins">
                <div class="card-header">
                    <h3><i class="bi bi-clock-history"></i> Recent Check-ins</h3>
                    <a href="#" class="view-all">View All <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="visitors-list">
                    @foreach ($allVisitors->sortByDesc('check_in_time')->take(5) as $visitor)
                    @php
                    $checkInTime = \Carbon\Carbon::parse($visitor->check_in_time);
                    $timeDiff = $checkInTime->diffForHumans();
                    @endphp
                    <div class="visitor-item">
                        <div class="visitor-avatar" style="background-color: {{ '#' . substr(md5($visitor->full_name), 0, 6) }};">
                            {{ substr($visitor->full_name, 0, 1) }}
                        </div>
                        <div class="visitor-info">
                            <div class="visitor-name-status">
                                <h4>{{ $visitor->full_name }}</h4>
                                @if($visitor->check_out_time == null)
                                <span class="status-badge checked-in">Checked In</span>
                                @else
                                <span class="status-badge checked-out">Checked Out</span>
                                @endif
                            </div>
                            <div class="visitor-meta">
                                <span class="visitor-purpose">{{ $visitor->role }}</span>
                                <span class="visitor-time">{{ $timeDiff }}</span>
                            </div>
                        </div>
                        <div class="visitor-host">
                            <i class="bi bi-person-badge"></i>
                            <span>{{ $visitor->employer_name }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- System Flow Quick Actions -->
            <div class="card quick-actions">
                <div class="card-header">
                    <h3><i class="bi bi-lightning-charge"></i> System Flow</h3>
                </div>
                <div class="actions-grid">
                    <!-- Visitor Flow -->
                    <button class="action-btn" data-bs-toggle="modal" data-bs-target="#visitorFlowModal" style="--action-color: #a18cd1;">
                        <div class="action-icon">
                            <i class="bi bi-person"></i>
                        </div>
                        <span>Visitor Flow</span>
                    </button>

                    <!-- Employee Flow -->
                    <button class="action-btn" data-bs-toggle="modal" data-bs-target="#employeeFlowModal" style="--action-color: #ff9a9e;">
                        <div class="action-icon">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <span>Employee Flow</span>
                    </button>

                    <!-- Admin Flow -->
                    <button class="action-btn" data-bs-toggle="modal" data-bs-target="#adminFlowModal" style="--action-color: #43e97b;">
                        <div class="action-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <span>Admin Flow</span>
                    </button>

                    <!-- Check-in Flow -->
                    <button class="action-btn" data-bs-toggle="modal" data-bs-target="#checkinFlowModal" style="--action-color: #667eea;">
                        <div class="action-icon">
                            <i class="bi bi-door-open"></i>
                        </div>
                        <span>Check-in Flow</span>
                    </button>
                </div>
            </div>

            <!-- Activity Feed -->
            <div class="card activity-feed">
                <div class="card-header">
                    <h3><i class="bi bi-list-check"></i> Recent Activity</h3>
                    <a href="#" class="view-all">View All <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="activity-list">
                    @foreach ($allVisitors->sortByDesc('check_in_time')->take(6) as $visitor)
                    @php
                    $checkInTime = \Carbon\Carbon::parse($visitor->check_in_time);
                    $timeDiff = $checkInTime->diffForHumans();
                    @endphp
                    <div class="activity-item">
                        <div class="activity-icon">
                            @if($visitor->check_out_time == null)
                            <i class="bi bi-box-arrow-in-right"></i>
                            @else
                            <i class="bi bi-box-arrow-right"></i>
                            @endif
                        </div>
                        <div class="activity-content">
                            <p>{{ $visitor->full_name }} <span>{{ $visitor->check_out_time == null ? 'checked in' : 'checked out' }}</span> to meet {{ $visitor->employer_name }}</p>
                            <span class="activity-time">{{ $timeDiff }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Visitor Flow Modal -->
<div class="modal fade" id="visitorFlowModal" tabindex="-1" aria-labelledby="visitorFlowModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);">
                <h5 class="modal-title" id="visitorFlowModalLabel"><i class="bi bi-person"></i> Visitor Management Flow</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="flow-steps">
                    <div class="flow-step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h5>View Complete Visitor List</h5>
                            <p>Access all visitor records with filtering and search capabilities</p>
                        </div>
                    </div>
                    <div class="flow-step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h5>Pre-Register Visitors</h5>
                            <p>Add visitor details in advance for faster check-in process</p>
                        </div>
                    </div>
                    <div class="flow-step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h5>Checked In's List</h5>
                            <p>View all currently checked-in visitors with real-time updates</p>
                        </div>
                    </div>
                    <div class="flow-step">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h5>Checked Out's List</h5>
                            <p>Review historical data of visitors who have completed their visits</p>
                        </div>
                    </div>
                    <div class="flow-step">
                        <div class="step-number">5</div>
                        <div class="step-content">
                            <h5>Archived Visitors</h5>
                            <p>Access older visitor records that have been archived</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Got it!</button>
            </div>
        </div>
    </div>
</div>

<!-- Employee Flow Modal -->
<div class="modal fade" id="employeeFlowModal" tabindex="-1" aria-labelledby="employeeFlowModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);">
                <h5 class="modal-title" id="employeeFlowModalLabel"><i class="bi bi-person-badge"></i> Employee Management Flow</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="flow-steps">
                    <div class="flow-step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h5>View Complete Employee List</h5>
                            <p>Access all employee records with department filtering</p>
                        </div>
                    </div>
                    <div class="flow-step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h5>Register New Employee</h5>
                            <p>Add new employees to the system with all necessary details</p>
                        </div>
                    </div>
                    <div class="flow-step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h5>Edit Employee Details</h5>
                            <p>Update employee information as needed</p>
                        </div>
                    </div>
                    <div class="flow-step">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h5>View Archived Employees</h5>
                            <p>Access records of former employees</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Understood</button>
            </div>
        </div>
    </div>
</div>

<!-- Admin Flow Modal -->
<div class="modal fade" id="adminFlowModal" tabindex="-1" aria-labelledby="adminFlowModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <h5 class="modal-title" id="adminFlowModalLabel"><i class="bi bi-shield-lock"></i> Admin Management Flow</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="flow-steps">
                    <div class="flow-step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h5>View Admin Users</h5>
                            <p>See all users with admin privileges</p>
                        </div>
                    </div>
                    <div class="flow-step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h5>Add New Admin Users</h5>
                            <p>Grant admin access to authorized personnel</p>
                        </div>
                    </div>
                    <div class="flow-step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h5>Edit Admin Users</h5>
                            <p>Modify admin permissions and details</p>
                        </div>
                    </div>
                    <div class="flow-step">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h5>View Email Logs</h5>
                            <p>Monitor all system-generated emails</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Clear</button>
            </div>
        </div>
    </div>
</div>

<!-- Check-in Flow Modal -->
<div class="modal fade" id="checkinFlowModal" tabindex="-1" aria-labelledby="checkinFlowModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="modal-title" id="checkinFlowModalLabel"><i class="bi bi-door-open"></i> Check-in Process Flow</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="flow-steps">
                    <div class="flow-step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h5>Visitor Arrival</h5>
                            <p>Visitor arrives at reception and provides identification</p>
                        </div>
                    </div>
                    <div class="flow-step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h5>Information Verification</h5>
                            <p>System verifies visitor details against pre-registered data</p>
                        </div>
                    </div>
                    <div class="flow-step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h5>Host Notification</h5>
                            <p>Automated notification sent to the host employee</p>
                        </div>
                    </div>
                    <div class="flow-step">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h5>Badge Printing</h5>
                            <p>System generates visitor badge with QR code</p>
                        </div>
                    </div>
                    <div class="flow-step">
                        <div class="step-number">5</div>
                        <div class="step-content">
                            <h5>Check-in Completion</h5>
                            <p>Visitor details logged and visit officially recorded</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --primary-color: #667eea;
        --secondary-color: #764ba2;
        --success-color: #43e97b;
        --danger-color: #ff758c;
        --warning-color: #ff9a9e;
        --info-color: #a18cd1;
        --light-color: #f8f9fa;
        --dark-color: #1e293b;
        --transition-speed: 0.3s;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        color: var(--dark-color);
        line-height: 1.6;
        padding: 0;
        margin: 0;
        min-height: 100vh;
        background-color: #f5f7fa;
    }

    /* Dashboard Header */
    .dashboard-header {
        padding: 1.5rem 2rem;
        margin-bottom: 1.5rem;
        color: white;
        border-radius: 0 0 16px 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .dashboard-header .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .dashboard-header h1 {
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0;
    }

    .header-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }

    .current-date {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .greeting {
        font-size: 0.85rem;
        opacity: 0.8;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        padding: 1.5rem;
        border-radius: 12px;
        color: white;
        position: relative;
        overflow: hidden;
        transition: transform var(--transition-speed) ease;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-content {
        display: flex;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.25rem;
    }

    .stat-text {
        display: flex;
        flex-direction: column;
    }

    .stat-label {
        font-size: 0.85rem;
        font-weight: 500;
        opacity: 0.9;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .stat-period {
        font-size: 0.75rem;
        opacity: 0.8;
    }

    .stat-trend {
        position: absolute;
        right: 1.5rem;
        bottom: 1.5rem;
        font-size: 0.85rem;
        opacity: 0.9;
    }

    /* Content Grid */
    .content-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    @media (min-width: 1200px) {
        .content-grid {
            grid-template-columns: 2fr 1fr;
        }
    }

    /* Cards */
    .card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        transition: transform var(--transition-speed) ease;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .card-header h3 {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .view-all {
        font-size: 0.85rem;
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .view-all:hover {
        color: var(--secondary-color);
    }

    /* Chart Card */
    .chart-container {
        height: 300px;
        position: relative;
    }

    .chart-controls {
        display: flex;
        gap: 0.5rem;
    }

    .time-filter {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        border: none;
        background: rgba(226, 232, 240, 0.5);
        color: #64748b;
        font-size: 0.75rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .time-filter.active {
        background: var(--primary-color);
        color: white;
    }

    /* Recent Check-ins */
    .visitors-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .visitor-item {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        border-radius: 12px;
        background: rgba(241, 245, 249, 0.5);
        transition: all 0.2s ease;
    }

    .visitor-item:hover {
        background: rgba(226, 232, 240, 0.7);
    }

    .visitor-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .visitor-info {
        flex-grow: 1;
    }

    .visitor-name-status {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.25rem;
    }

    .visitor-name-status h4 {
        font-size: 0.95rem;
        font-weight: 500;
        margin: 0;
    }

    .status-badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.5rem;
        border-radius: 20px;
        font-weight: 600;
    }

    .status-badge.checked-in {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .status-badge.checked-out {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .visitor-meta {
        display: flex;
        gap: 1rem;
    }

    .visitor-purpose, .visitor-time {
        font-size: 0.8rem;
        color: #64748b;
    }

    .visitor-host {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: #475569;
    }

    /* Quick Actions */
    .quick-actions .actions-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .action-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        border-radius: 12px;
        background: white;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        text-decoration: none;
        color: var(--dark-color);
    }

    .action-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .action-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--action-color) 0%, var(--action-color) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.5rem;
        color: white;
        font-size: 1.25rem;
    }

    .action-btn span {
        font-size: 0.85rem;
        font-weight: 500;
    }

    /* Activity Feed */
    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .activity-item {
        display: flex;
        gap: 1rem;
        position: relative;
        padding-left: 1.5rem;
    }

    .activity-item:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 20px;
        top: 28px;
        bottom: -1.5rem;
        width: 2px;
        background: rgba(203, 213, 225, 0.5);
    }

    .activity-icon {
        width: 24px;
        height: 24px;
        border-radius: 8px;
        background: rgba(226, 232, 240, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: var(--primary-color);
        font-size: 0.9rem;
    }

    .activity-content {
        flex-grow: 1;
    }

    .activity-content p {
        margin: 0 0 0.25rem 0;
        font-size: 0.9rem;
    }

    .activity-content p span {
        color: #64748b;
    }

    .activity-time {
        font-size: 0.75rem;
        color: #94a3b8;
    }

    /* Flow Modals */
    .flow-steps {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .flow-step {
        display: flex;
        gap: 1rem;
        align-items: flex-start;
    }

    .step-number {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: black;
        flex-shrink: 0;
    }

    .step-content h5 {
        font-size: 1rem;
        margin-bottom: 0.25rem;
        color: darkslategray;
        font-weight: bolder;
    }

    .step-content p {
        margin: 0;
        color: darkslategray;
        font-size: 0.9rem;
    }

    .modal-header {
        border-bottom: none;
    }

    .modal-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-content {
        border: none;
        border-radius: 16px;
        overflow: hidden;
    }

    .modal-body {
        background: rgba(255,255,255,0.9);
    }

    .modal-footer {
        border-top: none;
        background: rgba(255,255,255,0.9);
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr 1fr;
        }

        .quick-actions .actions-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-header .header-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .header-meta {
            align-items: flex-start;
        }
    }
</style>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Visitors Trend Chart
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('visitorsTrendChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(102, 126, 234, 0.5)');
        gradient.addColorStop(1, 'rgba(102, 126, 234, 0)');

        const visitorsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Visitors',
                    data: [12, 19, 15, 22, 18, 10, 7],
                    backgroundColor: gradient,
                    borderColor: '#667eea',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#667eea',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(30, 41, 59, 0.9)',
                        titleColor: '#f8fafc',
                        bodyColor: '#e2e8f0',
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        padding: 12,
                        usePointStyle: true,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' visitors';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(203, 213, 225, 0.3)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#64748b'
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: '#64748b'
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });

        // Time filter buttons
        document.querySelectorAll('.time-filter').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.time-filter').forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');

                // Here you would update the chart data based on the selected time period
                // This is just a placeholder for the actual implementation
                const days = this.textContent === '7D' ? 7 : this.textContent === '30D' ? 30 : 90;
                console.log(`Update chart to show last ${days} days`);
            });
        });
    });
</script>

@endsection

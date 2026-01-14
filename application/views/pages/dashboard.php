<div class="container-fluid">

    <!-- Enclosed Page Heading Card -->
    <div class="card shadow mb-4">
        <div class="card-body d-sm-flex align-items-center justify-content-between">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <span class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-calendar fa-sm text-white-50"></i> Today: <?= date('F j, Y'); ?>
            </span>
        </div>
    </div>

    <!-- Master Card for the Data Cards -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <!-- Card 1: Total Families -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Families</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_families ?? '0'; ?></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card 2: Total Residents -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Residents</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_residents ?? '0'; ?></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-user-friends fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card 3: Male Residents -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Male Residents</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $male_residents ?? '0'; ?></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-male fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card 4: Female Residents -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Female Residents</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $female_residents ?? '0'; ?></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-female fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- This is the closing tag for the master card -->

    <!-- Chart Row -->
    <div class="row">
        <!-- Population Overview (Full Width) -->
        <div class="col-12 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Population Overview by Barangay</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height:350px"><canvas id="barangayResidentsBarChart"></canvas></div>
                </div>
            </div>
        </div>

        <!-- Gender Distribution -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Gender Distribution</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height:300px"><canvas id="genderPieChart"></canvas></div>
                </div>
            </div>
        </div>

        <!-- Registered Voters -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Voter Registration Status</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height:300px"><canvas id="voterDoughnutChart"></canvas></div>
                </div>
            </div>
        </div>
    </div>

</div>
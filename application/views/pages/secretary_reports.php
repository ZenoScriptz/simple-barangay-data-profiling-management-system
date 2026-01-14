<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body d-sm-flex align-items-center justify-content-between">
            <h1 class="h3 mb-0 text-gray-800">Secretary Reports</h1>
            <span class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-calendar fa-sm text-white-50"></i> Today: <?= date('F j, Y'); ?>
            </span>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table id="secretaryReportsTable" class="table table-bordered table-hover table-striped" width="100%">
                    <thead class="thead-dark">
                        <tr>
                            <th>Username</th>
                            <th>Barangay</th>
                            <th>Report Date</th>
                            <th>Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
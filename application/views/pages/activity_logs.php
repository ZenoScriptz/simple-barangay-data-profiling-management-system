<!-- We'll add some specific styles for this page right in the file for simplicity -->
<style>
    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        font-weight: bold;
        color: #4e73df; /* Bootstrap primary blue */
    }
    #activityLogTable thead th {
        background-color: #4e73df;
        color: white;
        border-color: #4e73df;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #4e73df !important;
        color: white !important;
        border-color: #4e73df !important;
    }
</style>

<div class="container-fluid">
    <!-- Page Heading -->
     <div class="card shadow mb-4">
        <div class="card-body d-sm-flex align-items-center justify-content-between">
            <h1 class="h3 mb-0 text-gray-800">Activity Logs</h1>
            <span class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-calendar fa-sm text-white-50"></i> Today: <?= date('F j, Y'); ?>
            </span>
        </div>
    </div>

    <!-- DataTables Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">System Activity Records</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="activityLogTable" class="table table-bordered table-hover" width="100%">
                    <thead>
                        <tr>
                            <th>Log ID</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Date / Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data is loaded via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
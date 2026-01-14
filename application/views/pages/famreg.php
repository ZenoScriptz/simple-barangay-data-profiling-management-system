<div class="container-fluid">
    <!-- Page Heading -->
     <div class="card shadow mb-4">
        <div class="card-body d-sm-flex align-items-center justify-content-between">
            <h1 class="h3 mb-0 text-gray-800">Family Lists</h1>
            <span class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-calendar fa-sm text-white-50"></i> Today: <?= date('F j, Y'); ?>
            </span>
        </div>
    </div>

    <!-- Informational Alert -->
    <div class="alert alert-info" role="alert">
        <i class="fas fa-info-circle mr-2"></i>
        This page displays all residents. Use the search box to filter by name, purok, or other details.
    </div>


    <!-- DataTables Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Resident Data</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="familyListTable" class="table table-bordered table-hover" width="100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Street</th>
                            <th>Purok / Zone</th>
                            <th>Occupation</th>
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
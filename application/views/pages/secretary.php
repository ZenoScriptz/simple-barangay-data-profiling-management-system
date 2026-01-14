<div class="container-fluid">
    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-body d-sm-flex align-items-center justify-content-between">
            <div>
                <h1 class="h3 mb-1 text-gray-800">Secretary Management</h1>
                <p class="mb-0 text-muted">Manage all secretary user accounts.</p>
            </div>
            <a href="<?= site_url('secretaries/add'); ?>" class="btn btn-primary btn-icon-split mt-2 mt-sm-0">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Add New Secretary</span>
            </a>
        </div>
    </div>

    <!-- Flash Messages for Success/Error -->
    <?php if ($this->session->flashdata('success_message')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('success_message'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error_message')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('error_message'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- DataTables Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Secretary List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="secretaryTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Account Name</th>
                            <th>Username</th>
                            <th>Assigned Barangay</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data is loaded via AJAX by the script in footer.php -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
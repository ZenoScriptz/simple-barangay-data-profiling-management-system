<div class="container-fluid">
    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <h1 class="h3 mb-1 text-gray-800">Edit Secretary</h1>
            <p class="mb-0 text-muted">Update the details for the selected secretary account.</p>
        </div>
    </div>

    <!-- Flash Messages for Errors -->
    <?php if ($this->session->flashdata('error_message')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('error_message'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Main Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Secretary Details</h6>
        </div>
        <div class="card-body">
            <?php if (isset($secretary) && $secretary): ?>
                <form id="editsecretary" method="post" action="<?= site_url('secretaries/update/' . $secretary->user_ID); ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="account_name" class="form-label font-weight-bold">Full Name</label>
                                <input type="text" class="form-control" id="account_name" name="account_name"
                                    value="<?= htmlspecialchars($secretary->account_name, ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="username" class="form-label font-weight-bold">Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    value="<?= htmlspecialchars($secretary->username, ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="brgy_ID" class="form-label font-weight-bold">Assign to Barangay</label>
                                <select class="form-select" id="brgy_ID" name="brgy_ID" required>
                                    <option value="">-- Select a Barangay --</option>
                                    <?php if (!empty($barangays)): foreach ($barangays as $barangay): ?>
                                        <option value="<?= $barangay->brgy_ID; ?>" <?= ($barangay->brgy_ID == $secretary->brgy_ID) ? 'selected' : ''; ?>>
                                            <?= $barangay->brgy_name; ?>
                                        </option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="password" class="form-label font-weight-bold">New Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Leave blank to keep current password">
                                <small class="form-text text-muted">Only enter a value if you want to change the password.</small>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="<?= site_url('secretaries'); ?>" class="btn btn-secondary">Cancel</a>
                </form>
            <?php else: ?>
                <p class="text-danger">Secretary data not found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
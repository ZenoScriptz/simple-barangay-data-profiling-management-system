<div class="container-fluid">
    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <h1 class="h3 mb-1 text-gray-800">Add New Secretary</h1>
            <p class="mb-0 text-muted">Create a new user account for a barangay secretary.</p>
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
            <h6 class="m-0 font-weight-bold text-primary">New Secretary Account Details</h6>
        </div>
        <div class="card-body">
            <form id="addsecretary" method="post" action="<?= site_url('secretaries/create'); ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="account_name" class="form-label font-weight-bold">Full Name (e.g., Jane R. Doe)</label>
                            <input type="text" class="form-control" id="account_name" name="account_name" placeholder="Enter secretary's full name" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="username" class="form-label font-weight-bold">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="brgy_ID" class="form-label font-weight-bold">Assign to Barangay</label>
                            <select class="form-select" id="brgy_ID" name="brgy_ID" required>
                                <option value="">-- Select a Barangay --</option>
                                <?php if (!empty($barangays)): foreach ($barangays as $barangay): ?>
                                    <option value="<?= $barangay->brgy_ID; ?>"><?= $barangay->brgy_name; ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password" class="form-label font-weight-bold">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Min. 8 characters" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <button type="submit" class="btn btn-primary">Add Secretary</button>
                <a href="<?= site_url('secretaries'); ?>" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<!-- Add this script to your main footer.php -->
<script>
    if ($('#togglePassword').length) {
        $('#togglePassword').on('click', function() {
            const passwordInput = $('#password');
            const icon = $(this).find('i');
            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
    }
</script>
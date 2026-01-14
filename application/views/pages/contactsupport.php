<div class="container-fluid">
    <!-- Page Heading Card -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <h1 class="h3 mb-1 text-gray-800">Contact Support</h1>
            <p class="mb-0 text-muted">Submit a ticket for technical issues or suggestions.</p>
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">New Support Ticket</h6>
        </div>
        <div class="card-body">
            
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

            <!-- The Form -->
            <form action="<?= site_url('submit_report') ?>" method="post">
                <div class="form-group mb-3">
                    <label for="message_content" class="form-label font-weight-bold">Message</label>
                    <textarea class="form-control" id="message_content" name="message_content" rows="8" placeholder="Please describe the issue or your suggestion in as much detail as possible..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fas fa-paper-plane"></i>
                    </span>
                    <span class="text">Submit Report</span>
                </button>
            </form>

        </div>
    </div>
</div>
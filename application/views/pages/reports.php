<div class="container-fluid">
    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-body d-sm-flex align-items-center justify-content-between">
            <div>
                <h1 class="h3 mb-1 text-gray-800">Generate Reports</h1>
                <p class="mb-0 text-muted">Select your criteria and choose an output format.</p>
            </div>
            <span class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-calendar fa-sm text-white-50"></i> Today: <?= date('F j, Y'); ?>
            </span>
        </div>
    </div>

    <!-- Report Filters Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Step 1: Select Filters</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Report Type Dropdown -->
                <div class="col-md-6 mb-3">
                    <label for="report-type" class="form-label font-weight-bold">Report Type:</label>
                    <select id="report-type" class="form-select">
                        <option value="" selected disabled>-- Choose a report --</option>
                        <option value="population-summary">Total Residents by Barangay</option>
                        <option value="gender-distribution">Gender Distribution</option>
                        <option value="family-count">Family Counts by Barangay</option>
                        <option value="age-group">Age Group Distribution</option>
                        <option value="resident_type">Resident Type Distribution</option>
                        <option value="registered-voters">Registered Voters List</option>
                        <option value="currently-studying">Currently Studying List</option>
                    </select>
                </div>

                <!-- PURE BOOTSTRAP Barangay Multi-Select Dropdown -->
                <div class="col-md-6 mb-3">
                    <label for="barangaysDropdown" class="form-label font-weight-bold">Barangay:</label>
                    
                    <?php if ($user_type == 'admin'): ?>
                        <div class="dropdown">
                            <button class="btn btn-light border dropdown-toggle w-100 text-start" type="button" id="barangaysDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                Select Barangays
                            </button>
                            <ul class="dropdown-menu w-100" aria-labelledby="barangaysDropdown" style="max-height: 400px; overflow-y: auto;">
                                <li>
                                    <div class="form-check dropdown-item"><input class="form-check-input barangay-checkbox" type="checkbox" value="all" id="barangay-all"><label class="form-check-label ms-2" for="barangay-all">All Barangays</label></div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <?php if (!empty($barangays)): foreach ($barangays as $barangay): ?>
                                    <li>
                                        <div class="form-check dropdown-item"><input class="form-check-input barangay-checkbox" type="checkbox" value="<?= $barangay->brgy_ID ?>" id="barangay-<?= $barangay->brgy_ID ?>"><label class="form-check-label ms-2" for="barangay-<?= $barangay->brgy_ID ?>"><?= htmlspecialchars($barangay->brgy_name) ?></label></div>
                                    </li>
                                <?php endforeach; endif; ?>
                            </ul>
                        </div>
                    <?php else: ?>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($selected_barangay_name); ?>" disabled>
                        <input type="hidden" id="selected_barangay_id" value="<?= $selected_barangay_id; ?>">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Download Options Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Step 2: Choose Output</h6>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-6">
                    <div class="p-3 border bg-light rounded">
                        <h5>Chart / Graph</h5>
                        <p>View a visual representation of the data.</p>
                        <button class="btn btn-primary btn-view-chart" data-section="chart"><i class="fas fa-chart-bar me-2"></i>View Chart</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border bg-light rounded">
                        <h5>Printable Report</h5>
                        <p>Generate a clean, printable data table.</p>
                        <button class="btn btn-success btn-download" data-section="summary"><i class="fas fa-print me-2"></i>Printable Report</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    
    // --- NO CHANGES TO THIS PART ---
    // Logic for 'All Barangays' checkbox
    $('#barangay-all').on('change', function() {
        var isChecked = $(this).is(':checked');
        $('.barangay-checkbox').not(this).prop('checked', isChecked);
        updateDropdownButtonText();
    });
    // Update dropdown text when individual barangays are selected
    $('.dropdown-menu').on('change', '.barangay-checkbox', function() {
        if (!$(this).is(':checked')) {
            $('#barangay-all').prop('checked', false);
        }
        updateDropdownButtonText();
    });
    function updateDropdownButtonText() {
        var selected = [];
        $('.barangay-checkbox:checked').not('#barangay-all').each(function() {
            selected.push($(this).siblings('label').text().trim());
        });
        var btn = $('#barangaysDropdown');
        if (selected.length === 0) {
            btn.text('Select Barangays');
        } else if (selected.length <= 2) {
            btn.text(selected.join(', '));
        } else {
            btn.text(selected.length + ' barangays selected');
        }
    }

    // --- THIS IS THE FIX FOR THE BUTTONS ---

    // Generic function to handle button clicks
    function handleReportAction(baseUrl) {
        var reportType = $('#report-type').val();
        var userType = '<?= $user_type; ?>';
        var barangays = [];

        if (!reportType) {
            alert('Please select a report type.');
            return;
        }

        if (userType === 'admin') {
            $('.barangay-checkbox:checked').not('#barangay-all').each(function() {
                barangays.push($(this).val());
            });
        } else {
            barangays.push($('#selected_barangay_id').val());
        }

        if (barangays.length === 0) {
            alert('Please select at least one barangay.');
            return;
        }

        var queryParams = $.param({
            report_type: reportType,
            barangays: barangays
        });
        
        // Open the URL in a new tab for a better user experience
        window.open(baseUrl + '?' + queryParams, '_blank');
    }

    // Handle the 'View Chart' button click
    $('.btn-view-chart').on('click', function(e) {
        e.preventDefault();
        // The URL now correctly includes '/reports/'
        var baseUrl = "<?= site_url('reports/display_chart'); ?>";
        handleReportAction(baseUrl);
    });

    // Handle the 'Printable Report' button click
    $('.btn-download').on('click', function(e) {
        e.preventDefault();
        // This points to a new function we are about to create
        var baseUrl = "<?= site_url('reports/download_report'); ?>";
        handleReportAction(baseUrl);
    });
});
</script>
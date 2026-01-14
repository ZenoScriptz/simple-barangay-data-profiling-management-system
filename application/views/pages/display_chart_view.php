<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Report Chart</h1>
        <a href="<?= site_url('reports'); ?>" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Back to Reports
        </a>
    </div>

    <!-- Chart Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 id="chart-title" class="m-0 font-weight-bold text-primary">Loading Chart...</h6>
        </div>
        <div class="card-body">
            <div class="chart-container" style="position: relative; height:40vh; width:80vw">
                <canvas id="myReportChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js if you haven't already included it in your footer template -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(document).ready(function() {
    // Get parameters from the URL to pass to the AJAX call
    var reportType = '<?= htmlspecialchars($report_type); ?>';
    var barangays = <?= json_encode($barangays); ?>;

    $.ajax({
        url: "<?= site_url('reports/get_chart_data'); ?>",
        type: "GET",
        data: {
            report_type: reportType,
            barangays: barangays
        },
        dataType: "json",
        success: function(data) {
            renderChart(data);
        },
        error: function(xhr, status, error) {
            console.error("Failed to fetch chart data:", error);
            $('#chart-title').text('Failed to load chart data.');
        }
    });

    function renderChart(data) {
        var ctx = document.getElementById('myReportChart').getContext('2d');
        var chartType = 'bar'; // Default chart type
        var chartTitle = 'Report';

        // Customize chart based on report type
        if (reportType === 'gender-distribution') {
            chartType = 'pie';
            chartTitle = 'Gender Distribution';
        } else if (reportType === 'population-summary') {
            chartType = 'bar';
            chartTitle = 'Total Residents by Barangay';
        }
        // Add more else if blocks for other report types...

        $('#chart-title').text(chartTitle);

        new Chart(ctx, {
            type: chartType,
            data: {
                labels: data.labels,
                datasets: data.datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                // Add other chart options here
            }
        });
    }
});
</script>
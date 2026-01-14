<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($report_title ?? 'Printable Report'); ?></title>
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
        @media print {
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
        }
        body { background-color: #f8f9fa; }
        .report-container { background-color: white; padding: 2rem; margin: 2rem auto; max-width: 1140px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="p-4">

    <div class="report-container">
        
        <div class="text-center mb-4">
            <h1><?= htmlspecialchars($report_title ?? 'Generated Report'); ?></h1>
            <p class="text-muted">Generated on: <?= date('F j, Y, g:i a'); ?></p>
        </div>

        <div class="text-center mb-4 no-print">
            <button class="btn btn-primary" onclick="window.print();"><i class="fas fa-print me-2"></i> Print Report</button>
            <button class="btn btn-secondary" onclick="window.close();"><i class="fas fa-times me-2"></i> Close</button>
        </div>

        <!-- DYNAMIC CHART SECTION -->
        <?php if (isset($chart_data_json) && $chart_data_json !== 'null' && isset($chart_type) && $chart_type !== 'table'): ?>
            <div class="chart-container mb-5" style="height: 600px; width: 100%;">
                <canvas id="printableChart"></canvas>
            </div>
            <!-- Force a page break when printing if there's a table below -->
            <?php if (!empty($results)): ?>
                <div class="page-break"></div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- DATA TABLE SECTION -->
        <h4 class="mt-4">Data Table</h4>
        <div class="table-responsive">
            <?php if (!empty($results)): ?>
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <?php foreach ($columns as $col): ?>
                                <th><?= htmlspecialchars($col); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $row): ?>
                            <tr>
                                <?php foreach ($row as $cell): ?>
                                    <td><?= htmlspecialchars($cell); ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-warning text-center">No data found for the selected criteria.</div>
            <?php endif; ?>
        </div>

    </div>

    <!-- JavaScript libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <script>
    $(document).ready(function() {
        if ($('#printableChart').length) {
            
            var chartData = JSON.parse('<?= $chart_data_json ?? '{}'; ?>');
            
            if (chartData && chartData.labels && chartData.labels.length > 0) {
                var ctx = document.getElementById('printableChart').getContext('2d');
                
                new Chart(ctx, {
                    type: '<?= $chart_type ?? 'bar'; ?>', // <-- DYNAMICALLY SETS CHART TYPE
                    data: {
                        labels: chartData.labels,
                        datasets: chartData.datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        title: {
                            display: true,
                            text: '<?= htmlspecialchars($report_title ?? "Chart"); ?>' // <-- DYNAMICALLY SETS CHART TITLE
                        },
                        animation: { duration: 0 }
                    }
                });
            }
        }
    });
    </script>
</body>
</html>
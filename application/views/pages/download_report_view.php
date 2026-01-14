<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($report_title); ?></title>
    <link href="<?= base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet" />
    <style>
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body class="p-4">

    <div class="container">
        <div class="text-center mb-4">
            <h1><?= htmlspecialchars($report_title); ?></h1>
            <p>Generated on: <?= date('F j, Y, g:i a'); ?></p>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-center mb-4 no-print">
            <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print me-2"></i>Print Report</button>
            <button onclick="window.close()" class="btn btn-secondary">Close</button>
        </div>

        <?php if (!empty($results)): ?>
            <table class="table table-bordered table-striped">
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

</body>
</html>
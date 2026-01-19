<?php
require 'config.php';
require_once 'auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bank_account_holder_name = $_POST['bank_account_holder_name'] ?? '';
    $bank_name = $_POST['bank_name'] ?? '';
    $bank_account_number = $_POST['bank_account_number'] ?? '';
    $bank_account_iban = $_POST['bank_account_iban'] ?? '';
    $jazzCash_account_holder_name = $_POST['jazzCash_account_holder_name'] ?? '';
    $jazzCash_account_number = $_POST['jazzCash_account_number'] ?? '';
    $easyPasisa_account_holder_name = $_POST['easyPasisa_account_holder_name'] ?? '';
    $easyPasisa_account_number = $_POST['easyPasisa_account_number'] ?? '';

    $stmt = $pdo->prepare("UPDATE accounts SET 
        bank_account_holder_name = ?, 
        bank_name = ?, 
        bank_account_number = ?, 
        bank_account_iban = ?, 
        jazzCash_account_holder_name = ?, 
        jazzCash_account_number = ?, 
        easyPasisa_account_holder_name = ?, 
        easyPasisa_account_number = ? 
        WHERE id = 1");
    $stmt->execute([
        $bank_account_holder_name, 
        $bank_name,
        $bank_account_number, 
        $bank_account_iban, 
        $jazzCash_account_holder_name, 
        $jazzCash_account_number, 
        $easyPasisa_account_holder_name, 
        $easyPasisa_account_number
    ]);
    $message = "Account details updated successfully.";
}

$stmt = $pdo->query("SELECT * FROM accounts LIMIT 1");
$account = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maxxify Academy</title>
    <link rel="shortcut icon" href="assets/images/logo/favicon.png">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/file-upload.css">
    <link rel="stylesheet" href="assets/css/plyr.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="assets/css/full-calendar.css">
    <link rel="stylesheet" href="assets/css/jquery-ui.css">
    <link rel="stylesheet" href="assets/css/editor-quill.css">
    <link rel="stylesheet" href="assets/css/apexcharts.css">
    <link rel="stylesheet" href="assets/css/calendar.css">
    <link rel="stylesheet" href="assets/css/jquery-jvectormap-2.0.5.css">
    <link rel="stylesheet" href="assets/css/main.css">
</head> 
<body>
    
<div class="preloader">
    <div class="loader"></div>
</div>

<div class="side-overlay"></div>

<?php include "sidebar.php" ?>

<div class="dashboard-main-wrapper">
    <?php include "Includes/Header.php" ?>
    <div class="dashboard-body">
        <div class="row gy-4">
            <div class="col-12 mt-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>Account Details</h5>
                                <?php if (!empty($message)): ?>
                                    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
                                <?php endif; ?>
                                
                                <form method="post" class="mb-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="mb-3">Bank Account Details</h6>
                                            <div class="mb-3">
                                                <label class="form-label">Bank Name</label>
                                                <input type="text" name="bank_name" class="form-control" 
                                                    value="<?= htmlspecialchars($account['bank_name'] ?? '') ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Account Holder Name</label>
                                                <input type="text" name="bank_account_holder_name" class="form-control" 
                                                    value="<?= htmlspecialchars($account['bank_account_holder_name'] ?? '') ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Account Number</label>
                                                <input type="text" name="bank_account_number" class="form-control" 
                                                    value="<?= htmlspecialchars($account['bank_account_number'] ?? '') ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">IBAN</label>
                                                <input type="text" name="bank_account_iban" class="form-control" 
                                                    value="<?= htmlspecialchars($account['bank_account_iban'] ?? '') ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="mb-3">JazzCash Account Details</h6>
                                            <div class="mb-3">
                                                <label class="form-label">Account Holder Name</label>
                                                <input type="text" name="jazzCash_account_holder_name" class="form-control" 
                                                    value="<?= htmlspecialchars($account['jazzCash_account_holder_name'] ?? '') ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Account Number</label>
                                                <input type="text" name="jazzCash_account_number" class="form-control" 
                                                    value="<?= htmlspecialchars($account['jazzCash_account_number'] ?? '') ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="mb-3">EasyPaisa Account Details</h6>
                                            <div class="mb-3">
                                                <label class="form-label">Account Holder Name</label>
                                                <input type="text" name="easyPasisa_account_holder_name" class="form-control" 
                                                    value="<?= htmlspecialchars($account['easyPasisa_account_holder_name'] ?? '') ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Account Number</label>
                                                <input type="text" name="easyPasisa_account_number" class="form-control" 
                                                    value="<?= htmlspecialchars($account['easyPasisa_account_number'] ?? '') ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">Update Account Details</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    

                </div>
            </div>
        </div>
    </div>
</div>
    
<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/boostrap.bundle.min.js"></script>
<script src="assets/js/phosphor-icon.js"></script>
<script src="assets/js/file-upload.js"></script>
<script src="assets/js/plyr.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="assets/js/full-calendar.js"></script>
<script src="assets/js/jquery-ui.js"></script>
<script src="assets/js/editor-quill.js"></script>
<script src="assets/js/apexcharts.min.js"></script>
<script src="assets/js/calendar.js"></script>
<script src="assets/js/jquery-jvectormap-2.0.5.min.js"></script>
<script src="assets/js/jquery-jvectormap-world-mill-en.js"></script>
<script src="assets/js/main.js"></script>
    
</body>
</html>
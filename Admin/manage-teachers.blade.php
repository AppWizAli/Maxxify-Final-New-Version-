<?php
require 'config.php';
require_once 'auth_check.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    $id = $_POST['id'] ?? null;

    if ($name === '' || $phone_number === '') {
        $error = 'All fields are required.';
    } else {
        if ($id) {
            $stmt = $pdo->prepare('UPDATE teachers SET name = ?, phone_number = ? WHERE id = ?');
            $stmt->execute([$name, $phone_number, $id]);
            header('Location: manage-teachers.blade.php?success=2');
            exit();
        } else {
            $stmt = $pdo->prepare('INSERT INTO teachers (name, phone_number) VALUES (?, ?)');
            $stmt->execute([$name, $phone_number]);
            header('Location: manage-teachers.blade.php?success=1');
            exit();
        }
    }
}

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare('DELETE FROM teachers WHERE id = ?');
    $stmt->execute([$_GET['delete']]);
    header('Location: manage-teachers.blade.php');
    exit();
}

$editTeacher = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM teachers WHERE id = ?');
    $stmt->execute([$_GET['edit']]);
    $editTeacher = $stmt->fetch();
}

$stmt = $pdo->query('SELECT * FROM teachers ORDER BY id DESC');
$teachers = $stmt->fetchAll();

if (isset($_GET['success'])) {
    if ($_GET['success'] == 1) {
        $message = 'Teacher added successfully.';
    }
    if ($_GET['success'] == 2) {
        $message = 'Teacher updated successfully.';
    }
}
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
    <!--==================== Sidebar Overlay End ====================-->
    <div class="side-overlay"></div>
    <!--==================== Sidebar Overlay End ====================-->
    <!-- ============================ Sidebar Start ============================ -->
    <?php include 'sidebar.php'; ?>
    <!-- ============================ Sidebar End  ============================ -->
    <div class="dashboard-main-wrapper">
        <div class="top-navbar flex-between gap-16">
            <div class="flex-align gap-16">
                <button type="button" class="toggle-btn d-xl-none d-flex text-26 text-gray-500"><i
                        class="ph ph-list"></i></button>
                <form action="#" class="w-350 d-sm-block d-none">
                    <div class="position-relative">
                        <button type="submit" class="input-icon text-xl d-flex text-gray-100 pointer-event-none"><i
                                class="ph ph-magnifying-glass"></i></button>
                        <input type="text"
                            class="form-control ps-40 h-40 border-transparent focus-border-main-600 bg-main-50 rounded-pill placeholder-15"
                            placeholder="Search...">
                    </div>
                </form>
            </div>
            <div class="flex-align gap-16">
                <div class="flex-align gap-8">
                    <div class="dropdown">
                        <button
                            class="dropdown-btn shaking-animation text-gray-500 w-40 h-40 bg-main-50 hover-bg-main-100 transition-2 rounded-circle text-xl flex-center"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="position-relative">
                                <i class="ph ph-bell"></i>
                                <span class="alarm-notify position-absolute end-0"></span>
                            </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu--lg border-0 bg-transparent p-0">
                            <div class="card border border-gray-100 rounded-12 box-shadow-custom p-0 overflow-hidden">
                                <div class="card-body p-0">
                                    <div class="py-8 px-24 bg-main-600">
                                        <div class="flex-between">
                                            <h5 class="text-xl fw-semibold text-white mb-0">Notifications</h5>
                                            <div class="flex-align gap-12">
                                                <button type="button"
                                                    class="bg-white rounded-6 text-sm px-8 py-2 hover-text-primary-600">
                                                    New </button>
                                                <button type="button"
                                                    class="close-dropdown hover-scale-1 text-xl text-white"><i
                                                        class="ph ph-x"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-24 max-h-270 overflow-y-auto scroll-sm">
                                        <div class="d-flex align-items-start gap-12">
                                            <img src="assets/images/thumbs/notification-img1.png" alt=""
                                                class="w-48 h-48 rounded-circle object-fit-cover">
                                            <div class="border-bottom border-gray-100 mb-24 pb-24">
                                                <div class="flex-align gap-4">
                                                    <a href="#"
                                                        class="fw-medium text-15 mb-0 text-gray-300 hover-text-main-600 text-line-2">Ashwin
                                                        Bose is requesting access to Design File - Final Project. </a>
                                                    <div class="dropdown flex-shrink-0">
                                                        <button class="text-gray-200 rounded-4" type="button"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="ph-fill ph-dots-three-outline"></i>
                                                        </button>
                                                        <div
                                                            class="dropdown-menu dropdown-menu--md border-0 bg-transparent p-0">
                                                            <div
                                                                class="card border border-gray-100 rounded-12 box-shadow-custom">
                                                                <div class="card-body p-12">
                                                                    <div
                                                                        class="max-h-200 overflow-y-auto scroll-sm pe-8">
                                                                        <ul>
                                                                            <li class="mb-0">
                                                                                <a href="#"
                                                                                    class="py-6 text-15 px-8 hover-bg-gray-50 text-gray-300 rounded-8 fw-normal text-xs d-block">
                                                                                    <span class="text">Mark as
                                                                                        read</span>
                                                                                </a>
                                                                            </li>
                                                                            <li class="mb-0">
                                                                                <a href="#"
                                                                                    class="py-6 text-15 px-8 hover-bg-gray-50 text-gray-300 rounded-8 fw-normal text-xs d-block">
                                                                                    <span class="text">Delete
                                                                                        Notification</span>
                                                                                </a>
                                                                            </li>
                                                                            <li class="mb-0">
                                                                                <a href="#"
                                                                                    class="py-6 text-15 px-8 hover-bg-gray-50 text-gray-300 rounded-8 fw-normal text-xs d-block">
                                                                                    <span class="text">Report</span>
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-align gap-6 mt-8">
                                                    <img src="assets/images/icons/google-drive.png" alt="">
                                                    <div class="flex-align gap-4">
                                                        <p class="text-gray-900 text-sm text-line-1">Design brief and
                                                            ideas.txt</p>
                                                        <span class="text-xs text-gray-200 flex-shrink-0">2.2 MB</span>
                                                    </div>
                                                </div>
                                                <div class="mt-16 flex-align gap-8">
                                                    <button type="button"
                                                        class="btn btn-main py-8 text-15 fw-normal px-16">Accept</button>
                                                    <button type="button"
                                                        class="btn btn-outline-gray py-8 text-15 fw-normal px-16">Decline</button>
                                                </div>
                                                <span class="text-gray-200 text-13 mt-8">2 mins ago</span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start gap-12">
                                            <img src="assets/images/thumbs/notification-img2.png" alt=""
                                                class="w-48 h-48 rounded-circle object-fit-cover">
                                            <div class="">
                                                <a href="#"
                                                    class="fw-medium text-15 mb-0 text-gray-300 hover-text-main-600 text-line-2">Patrick
                                                    added a comment on Design Assets - Smart Tags file:</a>
                                                <span class="text-gray-200 text-13">2 mins ago</span>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="#"
                                        class="py-13 px-24 fw-bold text-center d-block text-primary-600 border-top border-gray-100 hover-text-decoration-underline">
                                        View All </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button
                            class="text-gray-500 w-40 h-40 bg-main-50 hover-bg-main-100 transition-2 rounded-circle text-xl flex-center"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ph ph-globe"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu--md border-0 bg-transparent p-0">
                            <div class="card border border-gray-100 rounded-12 box-shadow-custom">
                                <div class="card-body">
                                    <div class="max-h-270 overflow-y-auto scroll-sm pe-8">
                                        <div
                                            class="form-check form-radio d-flex align-items-center justify-content-between ps-0 mb-16">
                                            <label
                                                class="ps-0 form-check-label line-height-1 fw-medium text-secondary-light"
                                                for="arabic">
                                                <span
                                                    class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-8">
                                                    <img src="assets/images/thumbs/flag1.png" alt=""
                                                        class="w-32-px h-32-px border borde border-gray-100 rounded-circle flex-shrink-0">
                                                    <span class="text-15 fw-semibold mb-0">Arabic</span>
                                                </span>
                                            </label>
                                            <input class="form-check-input" type="radio" name="language"
                                                id="arabic">
                                        </div>
                                        <div
                                            class="form-check form-radio d-flex align-items-center justify-content-between ps-0 mb-16">
                                            <label
                                                class="ps-0 form-check-label line-height-1 fw-medium text-secondary-light"
                                                for="germany">
                                                <span
                                                    class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-8">
                                                    <img src="assets/images/thumbs/flag2.png" alt=""
                                                        class="w-32-px h-32-px border borde border-gray-100 rounded-circle flex-shrink-0">
                                                    <span class="text-15 fw-semibold mb-0">Germany</span>
                                                </span>
                                            </label>
                                            <input class="form-check-input" type="radio" name="language"
                                                id="germany">
                                        </div>
                                        <div
                                            class="form-check form-radio d-flex align-items-center justify-content-between ps-0 mb-16">
                                            <label
                                                class="ps-0 form-check-label line-height-1 fw-medium text-secondary-light"
                                                for="english">
                                                <span
                                                    class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-8">
                                                    <img src="assets/images/thumbs/flag3.png" alt=""
                                                        class="w-32-px h-32-px border borde border-gray-100 rounded-circle flex-shrink-0">
                                                    <span class="text-15 fw-semibold mb-0">English</span>
                                                </span>
                                            </label>
                                            <input class="form-check-input" type="radio" name="language"
                                                id="english">
                                        </div>
                                        <div
                                            class="form-check form-radio d-flex align-items-center justify-content-between ps-0">
                                            <label
                                                class="ps-0 form-check-label line-height-1 fw-medium text-secondary-light"
                                                for="spanish">
                                                <span
                                                    class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-8">
                                                    <img src="assets/images/thumbs/flag4.png" alt=""
                                                        class="w-32-px h-32-px border borde border-gray-100 rounded-circle flex-shrink-0">
                                                    <span class="text-15 fw-semibold mb-0">Spanish</span>
                                                </span>
                                            </label>
                                            <input class="form-check-input" type="radio" name="language"
                                                id="spanish">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dropdown">
                    <button
                        class="users arrow-down-icon border border-gray-200 rounded-pill p-4 d-inline-block pe-40 position-relative"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="position-relative">
                            <img src="assets/images/thumbs/user-img.png" alt="Image"
                                class="h-32 w-32 rounded-circle">
                            <span
                                class="activation-badge w-8 h-8 position-absolute inset-block-end-0 inset-inline-end-0"></span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu--lg border-0 bg-transparent p-0">
                        <div class="card border border-gray-100 rounded-12 box-shadow-custom">
                            <div class="card-body">
                                <div class="flex-align gap-8 mb-20 pb-20 border-bottom border-gray-100">
                                    <img src="assets/images/thumbs/user-img.png" alt=""
                                        class="w-54 h-54 rounded-circle">
                                    <div class="">
                                        <h4 class="mb-0">Michel John</h4>
                                        <p class="fw-medium text-13 text-gray-200">examplemail@mail.com</p>
                                    </div>
                                </div>
                                <ul class="max-h-270 overflow-y-auto scroll-sm pe-4">
                                    <li class="mb-4">
                                        <a href="setting.html"
                                            class="py-12 text-15 px-20 hover-bg-gray-50 text-gray-300 rounded-8 flex-align gap-8 fw-medium text-15">
                                            <span class="text-2xl text-primary-600 d-flex"><i
                                                    class="ph ph-gear"></i></span>
                                            <span class="text">Account Settings</span>
                                        </a>
                                    </li>
                                    <li class="mb-4">
                                        <a href="pricing-plan.html"
                                            class="py-12 text-15 px-20 hover-bg-gray-50 text-gray-300 rounded-8 flex-align gap-8 fw-medium text-15">
                                            <span class="text-2xl text-primary-600 d-flex"><i
                                                    class="ph ph-chart-bar"></i></span>
                                            <span class="text">Upgrade Plan</span>
                                        </a>
                                    </li>
                                    <li class="mb-4">
                                        <a href="analytics.html"
                                            class="py-12 text-15 px-20 hover-bg-gray-50 text-gray-300 rounded-8 flex-align gap-8 fw-medium text-15">
                                            <span class="text-2xl text-primary-600 d-flex"><i
                                                    class="ph ph-chart-line-up"></i></span>
                                            <span class="text">Daily Activity</span>
                                        </a>
                                    </li>
                                    <li class="mb-4">
                                        <a href="message.html"
                                            class="py-12 text-15 px-20 hover-bg-gray-50 text-gray-300 rounded-8 flex-align gap-8 fw-medium text-15">
                                            <span class="text-2xl text-primary-600 d-flex"><i
                                                    class="ph ph-chats-teardrop"></i></span>
                                            <span class="text">Inbox</span>
                                        </a>
                                    </li>
                                    <li class="mb-4">
                                        <a href="email.html"
                                            class="py-12 text-15 px-20 hover-bg-gray-50 text-gray-300 rounded-8 flex-align gap-8 fw-medium text-15">
                                            <span class="text-2xl text-primary-600 d-flex"><i
                                                    class="ph ph-envelope-simple"></i></span>
                                            <span class="text">Email</span>
                                        </a>
                                    </li>
                                    <li class="pt-8 border-top border-gray-100">
                                        <a href="sign-in.html"
                                            class="py-12 text-15 px-20 hover-bg-danger-50 text-gray-300 hover-text-danger-600 rounded-8 flex-align gap-8 fw-medium text-15">
                                            <span class="text-2xl text-danger-600 d-flex"><i
                                                    class="ph ph-sign-out"></i></span>
                                            <span class="text">Log Out</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dashboard-body">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5><?= $editTeacher ? 'Edit Teacher' : 'Add Teacher' ?></h5>
                                <?php if (!empty($error)): ?>
                                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                                <?php elseif (!empty($message)): ?>
                                <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
                                <?php endif; ?>
                                <form method="post">
                                    <input type="hidden" name="id" value="<?= $editTeacher['id'] ?? '' ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" name="name" class="form-control"
                                            value="<?= htmlspecialchars($editTeacher['name'] ?? '') ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" name="phone_number" class="form-control"
                                            value="<?= htmlspecialchars($editTeacher['phone_number'] ?? '') ?>"
                                            required>
                                    </div>
                                    <button type="submit"
                                        class="btn btn-primary"><?= $editTeacher ? 'Update' : 'Add' ?></button>
                                    <?php if ($editTeacher): ?>
                                    <a href="manage-teachers.blade.php" class="btn btn-secondary ms-2">Cancel</a>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h5>All Teachers</h5>
                                <table class="table table-striped mt-2">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Phone Number</th>
                                            <th>Coupons</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($teachers as $teacher): ?>
                                        <?php
                                        // Fetch all coupons for this teacher
                                        $stmt = $pdo->prepare('SELECT code, id FROM coupons WHERE teacher_id = ?');
                                        $stmt->execute([$teacher['id']]);
                                        $teacher_coupons = $stmt->fetchAll();
                                        $coupon_codes = array_column($teacher_coupons, 'code');
                                        $coupon_ids = array_column($teacher_coupons, 'id');
                                        $has_coupons = count($coupon_codes) > 0;
                                        ?>
                                        <tr>
                                            <td style="color:black;"><?= $teacher['id'] ?></td>
                                            <td style="color:black;"><?= htmlspecialchars($teacher['name']) ?></td>
                                            <td style="color:black;"><?= htmlspecialchars($teacher['phone_number']) ?>
                                            </td>
                                            <td style="color:black;">
                                                <?= $has_coupons ? htmlspecialchars(implode(', ', $coupon_codes)) : '-' ?>
                                            </td>
                                            <td>
                                                <a href="?edit=<?= $teacher['id'] ?>"
                                                    class="btn btn-sm btn-warning">Edit</a>
                                                <a href="?delete=<?= $teacher['id'] ?>"
                                                    onclick="return confirm('Delete this teacher?')"
                                                    class="btn btn-sm btn-danger">Delete</a>
                                                <a href="view-teacher-subs.php?teacher_id=<?= $teacher['id'] ?>"
                                                    class="btn btn-sm btn-info<?= $has_coupons ? '' : ' disabled' ?>"
                                                    style="margin-left:4px;">Coupon Details</a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php if (empty($teachers)): ?>
                                        <tr>
                                            <td colspan="4">No teachers found.</td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
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

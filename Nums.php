<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['attempt_type'])) {
    require 'config.php';
    $user_id = $_SESSION['user_id'] ?? 0;
    $type = $_POST['attempt_type'];
    $id = $_POST['attempt_id'] ?? '';
    $subject_id = $_POST['subject_id'] ?? '0';
    $extra = $_POST['attempt_extra'] ?? '';
    $now = date('Y-m-d');
    $error = '';
    if (!$user_id) {
        $error = 'Please log in to attempt this question.';
    } else {
        $stmt = $pdo->prepare("SELECT end_date, status FROM subscriptions WHERE user_id = ? ORDER BY id DESC LIMIT 1");
        $stmt->execute([$user_id]);
        $row = $stmt->fetch();
        if ($row && $row['status'] === 'approved' && $row['end_date'] && $row['end_date'] >= $now) {
            if ($type === 'chapter' || $type === 'past' || $type === 'mock') {
                header('Location: tutormode.php?topic_id=' . $id . '&subscription=1');
                exit;
            }
        } else {
            $topicStmt = $pdo->prepare("SELECT is_free FROM topics WHERE id = ?");
            $topicStmt->execute([$id]);
            $topic = $topicStmt->fetch();
            
            if ($topic && $topic['is_free'] == 1) {
                if ($type === 'chapter' || $type === 'past' || $type === 'mock') {
                    header('Location: tutormode.php?topic_id=' . $id . '&subscription=0');
                    exit;
                }
            } else {
                $free_attempts = 0;
                if ($type === 'chapter' || $type === 'past' || $type === 'mock') {
                    $check = $pdo->prepare("SELECT id FROM free_attempts WHERE user_id = ?");
                    $check->execute([$user_id]);
                    $free_attempts = $check->rowCount();
                }
                if ($free_attempts == 0) {
                    if ($type === 'chapter' || $type === 'past' || $type === 'mock') {
                        header('Location: tutormode.php?topic_id=' . $id . '&subscription=0');
                        exit;
                    }
                } else {
                    header('Location: pricing.php');
                    exit;
                }
            }
        }
    }
    if ($error) {
        $_SESSION['toast_error'] = $error;
        header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
        exit;
    }
}
if (isset($_SESSION['toast_error'])) {
    $error = $_SESSION['toast_error'];
    unset($_SESSION['toast_error']);
    echo "<script>function showToast(message) {var toast = document.createElement('div');toast.textContent = message;toast.style.position = 'fixed';toast.style.top = '30px';toast.style.right = '30px';toast.style.zIndex = 9999;toast.style.background = '#673AB7';toast.style.color = '#fff';toast.style.padding = '12px 24px';toast.style.borderRadius = '8px';toast.style.boxShadow = '0 2px 8px rgba(0,0,0,0.15)';toast.style.fontSize = '16px';toast.style.width = '240px';toast.style.textAlign = 'center';document.body.appendChild(toast);setTimeout(function(){toast.remove();},2000);}window.addEventListener('DOMContentLoaded',function(){showToast('" . addslashes($error) . "');});</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MDCAT Prep Hub</title>

    <link href="dist/output.css" rel="stylesheet">

    <link href="dist/input.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/Mdcat.css">

    <link rel="stylesheet" href="assets/css/loader.css">
    <link rel="stylesheet" href="assets/css/mouse.css">
    <style>
        .chapter-content {
            transition: max-height 0.3s ease, opacity 0.3s ease, margin-top 0.3s ease;
            overflow: hidden;
            max-height: 0;
            opacity: 0;
            margin-top: 0;
        }
    </style>


    <!-- ✅ CSS -->
    <style>
        .chapter-box {
            background: #D9D9D9;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 16px;
        }

        /* Sizes slightly bigger */
        .chapter-box .icon {
            width: 50px;
            height: 50px;
        }

        .chapter-box .title {
            font-size: 20px;
            color: #673AB7;
        }

        .chapter-box .chapter-label {
            font-size: 18px;
            color: #673AB7;
        }

        .chapter-box .arrow-down,
        .chapter-box .arrow-up {
            width: 22px;
            height: 22px;
        }

        /* Hover effect */
        .chapter-box:hover {
            background: linear-gradient(180deg, #966AE5 0%, #533B7F 100%);
        }

        .chapter-box:hover .title,
        .chapter-box:hover .chapter-label {
            color: white;
        }

        .chapter-box:hover .arrow-down,
        .chapter-box:hover .arrow-up {
            filter: brightness(0) invert(1);
        }

        /* Active state */
        .active-header {
            background: linear-gradient(180deg, #673AB7 0%, #2E1A51 100%) !important;
        }

        .active-header .title,
        .active-header .chapter-label {
            color: white !important;
        }

        .active-header .arrow-down {
            display: none !important;
        }

        .active-header .arrow-up {
            display: block !important;
            filter: brightness(0) invert(1);
        }

        /* Override white inside dropdown */
        .chapter-content {
            background-color: #F1F1F1 !important;
        }
    </style>


</head>

<body class="bg-white min-h-screen flex justify-center relative lg:p-6 xl:p-6">
    <div class="mouse-cursor cursor-outer"></div>
    <div class="mouse-cursor cursor-inner"></div>


    <!-- 🔵 Preloader -->
    <div class="fullscreen-loader" id="preloader">
        <div class="dot-loader">
            <span></span>
            <span></span>
            <span></span>
            <span></span>

        </div>
    </div>
    <!-- Wrapper for sidebar and main content -->
    <div class="flex w-full max-w-[1440px]">

        <!-- 📱 Mobile Header (hidden on lg and up) -->
        <header
            class="lg:hidden fixed top-0 left-0 w-full bg-white shadow-md z-50 px-4 py-3 flex justify-between items-center">
            <button onclick="toggleSidebar()" class="w-6 h-6">
                <img src="assets/Images/quill_hamburger.png" alt="Menu" class="w-full h-full object-contain" />
            </button>
            <div class="absolute left-1/2 transform -translate-x-1/2">
                <img src="assets/Images/logo 34.png" alt="Logo" class="w-[46px] h-[46px] object-contain" />
            </div>
        </header>

        <!-- 📱 Mobile Overlay -->
        <div id="sidebarOverlay" class="fixed inset-0  bg-opacity-40 z-40 hidden lg:hidden"
            onclick="toggleSidebar()">
        </div>

        <!-- 🧭 Sidebar -->
        <aside id="sidebar"
            class="fixed lg:static top-0 left-0 z-50 bg-white w-[240px] h-screen transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow lg:shadow-none">
            <?php include 'Includes/Sidebar.php'; ?>
        </aside>

        <!-- 📝 Main Content -->
        <main class="flex-1 px-4 py-6 lg:pl-6 space-y-8 max-w-full overflow-x-hidden">


            <!-- This is the new section, designed to be placed within a main content area. -->
            <div class="w-full max-w-[700px] mx-auto px-4 text-left space-y-6 transform scale-[0.9] origin-top">

                <!-- Title -->
                <h2 class="text-[40px] leading-[54px] font-bold text-purple-700 mt-6 sm:mt-10 md:mt-0 lg:mt-0 xl:mt-0">
                    NUMS Prep Hub
                </h2>


                <!-- Description Paragraph -->
                <p class="text-gray-800 text-lg leading-relaxed max-w-2xl font-medium">
                    NUMS Practice Zone is your one-stop platform for comprehensive NUMS preparation.<br>
                    We offer:
                </p>

                <!-- Features List with Checkmarks -->
                <div
                    class="flex flex-col sm:flex-row sm:justify-start items-start flex-wrap gap-x-6 gap-y-3 text-gray-800 text-xl font-semibold">
                    <span class="flex items-center">
                        <i class="fas fa-check-square mr-2 text-2xl" style="color: #18ca28;"></i> Chapter-wise MCQs
                    </span>
                    <span class="flex items-center">
                        <i class="fas fa-check-square mr-2 text-2xl" style="color: #18ca28;"></i> Past Papers
                    </span>
                    <span class="flex items-center">
                        <i class="fas fa-check-square mr-2 text-2xl" style="color: #18ca28;"></i> Mock Tests
                    </span>
                </div>

            </div>


            <div
                class="w-full max-w-[820px] bg-[#F3F3F3] rounded-[40px] p-2 flex flex-col sm:flex-row items-stretch justify-center mx-auto shadow-sm mt-10 space-y-2 sm:space-y-0 sm:space-x-2">

                <!-- Chapter Wise Button (Active) -->
                <button id="chapterWiseBtn" data-segment="chapter-wise" class="lg:flex-1 h-[44px] sm:h-[48px] md:h-[48px] lg:h-[56px]
                    px-2 sm:px-4 md:px-4 lg:px-8
                    text-sm md:text-base lg:text-lg
                    rounded-[40px] text-white font-semibold bg-[#673AB7]
                    transition-all duration-300 ease-in-out
                    flex items-center justify-center text-center whitespace-nowrap w-full sm:w-auto">
                    Chapter Wise
                </button>


                <!-- Past Papers Button -->
                <button id="pastPapersBtn" data-segment="past-papers" class="lg:flex-1 h-[44px] sm:h-[48px] md:h-[48px] lg:h-[56px]
                    px-2 sm:px-4 md:px-4 lg:px-8
                    text-sm md:text-base lg:text-lg
                    rounded-[40px] text-[#6B7280] font-semibold
                    hover:bg-gray-200 transition-all duration-300 ease-in-out
                    flex items-center justify-center text-center whitespace-nowrap w-full sm:w-auto">
                    NUMS Past Papers
                </button>

                <!-- Mock Test Button -->
                <!-- Mock Test Button -->
                <button id="mockTestBtn" data-segment="mock-test" class="lg:flex-1 h-[44px] sm:h-[48px] md:h-[48px] lg:h-[56px]
                    px-2 sm:px-4 md:px-4 lg:px-8
                    text-sm md:text-base lg:text-lg
                    rounded-[40px] text-[#6B7280] font-semibold
                    hover:bg-gray-200 transition-all duration-300 ease-in-out
                    flex items-center justify-center text-center whitespace-nowrap w-full sm:w-auto">
                    NUMS Mock Test
                </button>


            </div>


            <?php
            include 'config.php';

            $sql = "SELECT s.id AS subject_id, s.name AS subject_name, t.id AS topic_id, t.name AS topic_name, t.is_publish, t.is_free
                        FROM subjects s
                        LEFT JOIN topics t ON t.subject_id = s.id
                        WHERE s.category_id = 7
                        ORDER BY s.id, t.id";

            $stmt = $pdo->query($sql);

            $subjects = [];
            while ($row = $stmt->fetch()) {
                $subjectId = $row['subject_id'];
                $subjects[$subjectId]['name'] = $row['subject_name'];

                if (!is_null($row['topic_id']) && $row['is_publish'] == 1) {
                    $subjects[$subjectId]['topics'][] = [
                        'id' => $row['topic_id'],
                        'name' => $row['topic_name'],
                        'is_free' => $row['is_free']
                    ];
                }
            }
            ?>
            <div id="chapterWiseContent" class="w-full max-w-[1100px] mx-auto space-y-4 mt-5">
                <?php foreach ($subjects as $subjectId => $subject): ?>
                    <div id="chapterwise-subject-<?= $subjectId ?>">
                        <!-- Accordion Header -->
                        <div onclick="toggleChapters('subject-<?= $subjectId ?>')" id="header-subject-<?= $subjectId ?>" class="chapter-box cursor-pointer flex items-center justify-between px-4 py-3 rounded-lg
                bg-[#D9D9D9] shadow-md hover:bg-gradient-to-b from-[#966AE5] to-[#533B7F]">

                            <!-- Left: Icon + Title -->
                            <div class="flex items-center gap-4">
                                <img src="assets/Images/english.png" class="icon w-[50px] h-[50px]" alt="Subject Icon" />

                                <p id="label-subject-<?= $subjectId ?>" class="title text-[#673AB7] text-[20px] font-semibold"
                                    style="font-family: 'Manrope';">
                                    <?= htmlspecialchars($subject['name']) ?>
                                </p>
                            </div>

                            <!-- Right: Chapters + Arrows -->
                            <div class="flex items-center gap-3">
                                <span class="chapter-label text-[#673AB7] text-[18px]" style="font-family: 'Manrope';">
                                    Chapters
                                </span>

                                <!-- Down Arrow -->
                                <img id="arrow-down-subject-<?= $subjectId ?>" src="assets/Images/mingcute_down-fill (2).png"
                                    class="arrow-down w-[22px] h-[22px]" alt="Arrow Down" />

                                <!-- Up Arrow -->
                                <img id="arrow-up-subject-<?= $subjectId ?>" src="assets/Images/mingcute_down-fill.png"
                                    class="arrow-up hidden w-[22px] h-[22px]" alt="Arrow Up" />
                            </div>
                        </div>

                        <!-- Accordion Body -->
                        <div id="chapters-subject-<?= $subjectId ?>" class="chapter-content bg-[#F1F1F1] rounded-md px-4 py-3 mt-2 space-y-2">
                            <?php if (!empty($subject['topics'])): ?>
                                <?php foreach ($subject['topics'] as $topic): ?>
                                    <div class="flex justify-between items-center px-3 py-2 rounded-md ">
                                        <div class="flex items-center gap-3">
                                            <img src="assets/Images/chap1.png" class="w-[32px] h-[32px]" alt="Chapter Icon" />
                                            <span
                                                class="text-[17px] font-medium text-gray-900"><?= htmlspecialchars($topic['name']) ?></span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-medium text-gray-600">
                                                <?= $topic['is_free'] == 1 ? 'Free' : 'Paid' ?>
                                            </span>
                                            <?php if (isset($_SESSION['user_id'])): ?>
                                                <?php if ($topic['is_free'] == 1): ?>
                                                    <form method="POST" action="">
                                                        <input type="hidden" name="attempt_type" value="chapter">
                                                        <input type="hidden" name="attempt_id" value="<?= $topic['id'] ?>">
                                                        <button type="submit" class="text-white text-sm font-medium rounded-full px-5 py-1.5"
                                                            style="background: linear-gradient(180deg, #673AB7 0%, #2E1A51 100%);">
                                                            Attempt
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <form method="POST" action="" style="display:inline">
                                                        <input type="hidden" name="attempt_type" value="chapter">
                                                        <input type="hidden" name="attempt_id" value="<?= $topic['id'] ?>">
                                                        <button type="submit" class="text-white text-sm font-medium rounded-full px-5 py-1.5"
                                                            style="background: linear-gradient(180deg, #673AB7 0%, #2E1A51 100%);">
                                                            Attempt
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <button onclick="alert('Please log in to attempt this question.')"
                                                    class="text-white text-sm font-medium rounded-full px-5 py-1.5"
                                                    style="background: linear-gradient(180deg, #673AB7 0%, #2E1A51 100%);">
                                                    Attempt
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-4 text-gray-500">
                                    No topics available for this subject.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php
            include 'config.php';

            $sql = "SELECT s.id AS subject_id, s.name AS subject_name, t.id AS topic_id, t.name AS topic_name, t.is_publish, t.is_free
                        FROM subjects s
                        LEFT JOIN topics t ON t.subject_id = s.id
                        WHERE s.category_id = 8
                        ORDER BY s.id, t.id";

            $stmt = $pdo->query($sql);

            $subjects = [];
            while ($row = $stmt->fetch()) {
                $subjectId = $row['subject_id'];
                $subjects[$subjectId]['name'] = $row['subject_name'];

                if (!is_null($row['topic_id']) && $row['is_publish'] == 1) {
                    $subjects[$subjectId]['topics'][] = [
                        'id' => $row['topic_id'],
                        'name' => $row['topic_name'],
                        'is_free' => $row['is_free']
                    ];
                }
            }
            ?>
            <div id="pastPapersContent" class="w-full max-w-[1100px] mx-auto space-y-4 mt-5 hidden">
                <?php foreach ($subjects as $subjectId => $subject): ?>
                    <div id="chapterwise-subject-<?= $subjectId ?>">
                        <div onclick="toggleChapters('subject-<?= $subjectId ?>')" id="header-subject-<?= $subjectId ?>"
                            class="chapter-box cursor-pointer flex items-center justify-between px-4 py-3 rounded-lg
                bg-[#D9D9D9] shadow-md hover:bg-gradient-to-b from-[#966AE5] to-[#533B7F]">

                            <div class="flex items-center gap-4">
                                <img src="assets/Images/english.png" class="icon w-[50px] h-[50px]" alt="Subject Icon" />

                                <p id="label-subject-<?= $subjectId ?>"
                                    class="title text-[#673AB7] text-[20px] font-semibold" style="font-family: 'Manrope';">
                                    <?= htmlspecialchars($subject['name']) ?>
                                </p>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="chapter-label text-[#673AB7] text-[18px]" style="font-family: 'Manrope';">
                                    Years
                                </span>

                                <img id="arrow-down-subject-<?= $subjectId ?>"
                                    src="assets/Images/mingcute_down-fill (2).png" class="arrow-down w-[22px] h-[22px]"
                                    alt="Arrow Down" />

                                <img id="arrow-up-subject-<?= $subjectId ?>" src="assets/Images/mingcute_down-fill.png"
                                    class="arrow-up hidden w-[22px] h-[22px]" alt="Arrow Up" />
                            </div>
                        </div>

                        <div id="chapters-subject-<?= $subjectId ?>"
                            class="chapter-content bg-[#F1F1F1] rounded-md px-4 py-3 mt-2 space-y-2">
                            <?php if (!empty($subject['topics'])): ?>
                                <?php foreach ($subject['topics'] as $topic): ?>
                                    <div class="flex justify-between items-center px-3 py-2 rounded-md ">
                                        <div class="flex items-center gap-3">
                                            <img src="assets/Images/chap1.png" class="w-[32px] h-[32px]" alt="Chapter Icon" />
                                            <span
                                                class="text-[17px] font-medium text-gray-900"><?= htmlspecialchars($topic['name']) ?></span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-medium text-gray-600">
                                                <?= $topic['is_free'] == 1 ? 'Free' : 'Paid' ?>
                                            </span>
                                            <?php if (isset($_SESSION['user_id'])): ?>
                                                <?php if ($topic['is_free'] == 1): ?>
                                                    <form method="post" style="display:inline">
                                                        <input type="hidden" name="attempt_type" value="past">
                                                        <input type="hidden" name="attempt_id" value="<?= $topic['id'] ?>">
                                                        <button type="submit" class="text-white text-sm font-medium rounded-full px-5 py-1.5"
                                                            style="background: linear-gradient(180deg, #673AB7 0%, #2E1A51 100%);">Attempt</button>
                                                    </form>
                                                <?php else: ?>
                                                    <form method="post" style="display:inline">
                                                        <input type="hidden" name="attempt_type" value="past">
                                                        <input type="hidden" name="attempt_id" value="<?= $topic['id'] ?>">
                                                        <button type="submit" class="text-white text-sm font-medium rounded-full px-5 py-1.5"
                                                            style="background: linear-gradient(180deg, #673AB7 0%, #2E1A51 100%);">
                                                            Attempt
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <button onclick="alert('Please log in to attempt this question.')"
                                                    class="text-white text-sm font-medium rounded-full px-5 py-1.5"
                                                    style="background: linear-gradient(180deg, #673AB7 0%, #2E1A51 100%);">
                                                    Attempt
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-4 text-gray-500">
                                    No topics available for this subject.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>





            <?php
            include 'config.php';

            $sql = "SELECT s.id AS subject_id, s.name AS subject_name, t.id AS topic_id, t.name AS topic_name, t.is_publish, t.is_free
                        FROM subjects s
                        LEFT JOIN topics t ON t.subject_id = s.id
                        WHERE s.category_id = 9
                        ORDER BY s.id, t.id";

            $stmt = $pdo->query($sql);

            $subjects = [];
            while ($row = $stmt->fetch()) {
                $subjectId = $row['subject_id'];
                $subjects[$subjectId]['name'] = $row['subject_name'];

                if (!is_null($row['topic_id']) && $row['is_publish'] == 1) {
                    $subjects[$subjectId]['topics'][] = [
                        'id' => $row['topic_id'],
                        'name' => $row['topic_name'],
                        'is_free' => $row['is_free']
                    ];
                }
            }
            ?>
            <div id="mockTestContent" class="w-full max-w-[1100px] mx-auto space-y-4 mt-5 hidden">
                <?php foreach ($subjects as $subjectId => $subject): ?>
                    <div id="chapterwise-subject-<?= $subjectId ?>">
                        <div onclick="toggleChapters('subject-<?= $subjectId ?>')" id="header-subject-<?= $subjectId ?>"
                            class="chapter-box cursor-pointer flex items-center justify-between px-4 py-3 rounded-lg
                bg-[#D9D9D9] shadow-md hover:bg-gradient-to-b from-[#966AE5] to-[#533B7F]">

                            <div class="flex items-center gap-4">
                                <img src="assets/Images/logo.png" class="icon w-[50px] h-[50px]"
                                    alt="Subject Icon" />

                                <p id="label-subject-<?= $subjectId ?>"
                                    class="title text-[#673AB7] text-[20px] font-semibold" style="font-family: 'Manrope';">
                                    <?= htmlspecialchars($subject['name']) ?>
                                </p>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="chapter-label text-[#673AB7] text-[18px]" style="font-family: 'Manrope';">
                                    Tests
                                </span>

                                <img id="arrow-down-subject-<?= $subjectId ?>"
                                    src="assets/Images/mingcute_down-fill (2).png" class="arrow-down w-[22px] h-[22px]"
                                    alt="Arrow Down" />

                                <img id="arrow-up-subject-<?= $subjectId ?>" src="assets/Images/mingcute_down-fill.png"
                                    class="arrow-up hidden w-[22px] h-[22px]" alt="Arrow Up" />
                            </div>
                        </div>

                        <div id="chapters-subject-<?= $subjectId ?>"
                            class="chapter-content bg-[#F1F1F1] rounded-md px-4 py-3 mt-2 space-y-2">
                            <?php if (!empty($subject['topics'])): ?>
                                <?php foreach ($subject['topics'] as $topic): ?>
                                    <div class="flex justify-between items-center px-3 py-2 rounded-md ">
                                        <div class="flex items-center gap-3">
                                            <img src="assets/Images/chap1.png" class="w-[32px] h-[32px]" alt="Chapter Icon" />
                                            <span
                                                class="text-[17px] font-medium text-gray-900"><?= htmlspecialchars($topic['name']) ?></span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-medium text-gray-600">
                                                <?= $topic['is_free'] == 1 ? 'Free' : 'Paid' ?>
                                            </span>
                                            <?php if (isset($_SESSION['user_id'])): ?>
                                                <?php if ($topic['is_free'] == 1): ?>
                                                    <form method="post" style="display:inline">
                                                        <input type="hidden" name="attempt_type" value="mock">
                                                        <input type="hidden" name="attempt_id" value="<?= $topic['id'] ?>">
                                                        <button type="submit" class="text-white text-sm font-medium rounded-full px-5 py-1.5"
                                                            style="background: linear-gradient(180deg, #673AB7 0%, #2E1A51 100%);">Attempt</button>
                                                    </form>
                                                <?php else: ?>
                                                    <form method="post" style="display:inline">
                                                        <input type="hidden" name="attempt_type" value="mock">
                                                        <input type="hidden" name="attempt_id" value="<?= $topic['id'] ?>">
                                                        <button type="submit" class="text-white text-sm font-medium rounded-full px-5 py-1.5"
                                                            style="background: linear-gradient(180deg, #673AB7 0%, #2E1A51 100%);">
                                                            Attempt
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <button onclick="alert('Please log in to attempt this question.')"
                                                    class="text-white text-sm font-medium rounded-full px-5 py-1.5"
                                                    style="background: linear-gradient(180deg, #673AB7 0%, #2E1A51 100%);">
                                                    Attempt
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-4 text-gray-500">
                                    No topics available for this subject.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>


            <script src="assets/js/Exmination.js"></script>
            <script src="assets/js/loader.js"></script>
            <script src="assets/js/mouse.js"></script>
            <script>
                const subjects = ['english', 'physics', 'chemistry', 'biology', 'reasoning'];

                function getActiveSectionElement() {
                    const sectionIds = ['chapterWiseContent', 'pastPapersContent', 'mockTestContent'];
                    for (const id of sectionIds) {
                        const el = document.getElementById(id);
                        if (el && !el.classList.contains('hidden')) {
                            return el;
                        }
                    }
                    return document;
                }

                function toggleChapters(subject) {
                    const activeSection = getActiveSectionElement();

                    const allSubjectContents = activeSection.querySelectorAll('[id^="chapters-subject-"]');

                    allSubjectContents.forEach(content => {
                        const subjectId = content.id.replace('chapters-subject-', '');
                        const header = activeSection.querySelector(`#header-subject-${subjectId}`);
                        const arrowDown = activeSection.querySelector(`#arrow-down-subject-${subjectId}`);
                        const arrowUp = activeSection.querySelector(`#arrow-up-subject-${subjectId}`);
                        const label = activeSection.querySelector(`#label-subject-${subjectId}`);

                        const isTarget = subject === `subject-${subjectId}`;
                        const isOpen = content && content.style.maxHeight && content.style.maxHeight !== '0px';

                        if (!content || !header || !arrowDown || !arrowUp || !label) return;

                        if (isTarget && !isOpen) {
                            content.classList.remove('hidden');
                            content.style.maxHeight = content.scrollHeight + "px";
                            content.style.opacity = '1';
                            content.style.marginTop = '0.5rem';

                            arrowDown.classList.add('hidden');
                            arrowUp.classList.remove('hidden');

                            header.classList.add('active-header', 'bg-[#865ECD]');
                            header.classList.remove('bg-[#D9D9D9]');
                            label.classList.remove('text-[#865ECD]');
                            label.classList.add('text-white');
                        } else {
                            content.style.maxHeight = '0px';
                            content.style.opacity = '0';
                            content.style.marginTop = '0';

                            setTimeout(() => content.classList.add('hidden'), 200);

                            arrowDown.classList.remove('hidden');
                            arrowUp.classList.add('hidden');

                            header.classList.remove('active-header', 'bg-[#865ECD]');
                            header.classList.add('bg-[#D9D9D9]');
                            label.classList.remove('text-white');
                            label.classList.add('text-[#865ECD]');
                        }
                    });
                }

                function collapseAllChapters(section) {
                    const allSubjectContents = section.querySelectorAll('[id^="chapters-subject-"]');

                    allSubjectContents.forEach(content => {
                        const subjectId = content.id.replace('chapters-subject-', '');
                        const header = section.querySelector(`#header-subject-${subjectId}`);
                        const arrowDown = section.querySelector(`#arrow-down-subject-${subjectId}`);
                        const arrowUp = section.querySelector(`#arrow-up-subject-${subjectId}`);
                        const label = section.querySelector(`#label-subject-${subjectId}`);

                        if (!content || !header || !arrowDown || !arrowUp || !label) return;

                        content.style.maxHeight = '0px';
                        content.style.opacity = '0';
                        content.style.marginTop = '0';
                        content.classList.add('hidden');

                        arrowDown.classList.remove('hidden');
                        arrowUp.classList.add('hidden');

                        header.classList.remove('active-header', 'bg-[#865ECD]');
                        header.classList.add('bg-[#D9D9D9]');
                        label.classList.remove('text-white');
                        label.classList.add('text-[#865ECD]');
                    });
                }

                document.addEventListener('DOMContentLoaded', () => {
                    const segmentButtons = document.querySelectorAll('[data-segment]');
                    const chapterWiseContent = document.getElementById('chapterWiseContent');
                    const pastPapersContent = document.getElementById('pastPapersContent');
                    const mockTestContent = document.getElementById('mockTestContent');

                    const segmentSections = {
                        'chapter-wise': chapterWiseContent,
                        'past-papers': pastPapersContent,
                        'mock-test': mockTestContent
                    };

                    function switchTab(targetTab) {
                        segmentButtons.forEach(btn => {
                            btn.classList.remove('bg-[#673AB7]', 'text-white');
                            btn.classList.add('text-[#6B7280]', 'hover:bg-gray-200');
                        });

                        const clicked = document.querySelector(`[data-segment="${targetTab}"]`);
                        if (clicked) {
                            clicked.classList.add('bg-[#673AB7]', 'text-white');
                            clicked.classList.remove('text-[#6B7280]', 'hover:bg-gray-200');
                        }

                        Object.keys(segmentSections).forEach(key => {
                            const section = segmentSections[key];
                            section.classList.toggle('hidden', key !== targetTab);
                        });

                        const activeSection = segmentSections[targetTab];
                        collapseAllChapters(activeSection);
                    }

                    switchTab('chapter-wise');

                    segmentButtons.forEach(btn => {
                        btn.addEventListener('click', () => {
                            const tab = btn.getAttribute('data-segment');
                            switchTab(tab);
                        });
                    });
                });
            </script>

</body>

</html>
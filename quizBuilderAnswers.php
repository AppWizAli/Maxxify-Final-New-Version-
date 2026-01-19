<?php 
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$quizId = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;

if ($quizId <= 0) {
    header('Location: dashboard.php');
    exit();
}

// Fetch quiz details
$quizQuery = "SELECT * FROM user_quizzes WHERE id = ? AND user_id = ?";
$quizStmt = $pdo->prepare($quizQuery);
$quizStmt->execute([$quizId, $userId]);
$quiz = $quizStmt->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
    header('Location: dashboard.php');
    exit();
}

// Fetch user's submitted answers with MCQ details for this specific quiz
$sql = "
  SELECT 
    qa.question_index,
    qa.selected_answer,
    m.question,
    m.option_a,
    m.option_b,
    m.option_c,
    m.option_d,
    m.correct_option,
    m.explanation,
    m.topic_id,
    t.name AS topic_name
  FROM quiz_answers qa
  JOIN quiz_questions qq ON qa.quiz_id = qq.quiz_id AND qa.question_index = qq.question_order - 1
  JOIN mcqs m ON qq.mcq_id = m.id
  LEFT JOIN topics t ON m.topic_id = t.id
  WHERE qa.quiz_id = ? AND qa.user_id = ?
  ORDER BY qa.question_index
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$quizId, $userId]);
$answers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalAnswers = count($answers);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Answer Review</title>

    <link href="dist/output.css" rel="stylesheet">
    <link href="dist/input.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/loader.css">
            <link rel="stylesheet" href="assets/css/mouse.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .correct {
            background-color: #D1FAE5;
            border-color: #10B981;
            color: #065F46;
        }
        .wrong {
            background-color: #FEE2E2;
            border-color: #DC2626;
            color: #7F1D1D;
        }
        .user-selected {
            border-width: 3px;
        }
        .correct-answer {
            border-color: #10B981;
            background-color: #D1FAE5;
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
        <header class="lg:hidden fixed top-0 left-0 w-full bg-white shadow-md z-50 px-4 py-3 flex justify-between items-center">
            <button onclick="toggleSidebar()" class="w-6 h-6">
                <img src="assets/Images/quill_hamburger.png" alt="Menu" class="w-full h-full object-contain" />
            </button>
            <div class="absolute left-1/2 transform -translate-x-1/2">
                <img src="assets/Images/logo 34.png" alt="Logo" class="w-[46px] h-[46px] object-contain" />
            </div>
        </header>

        <!-- 📱 Mobile Overlay -->
        <div id="sidebarOverlay" class="fixed inset-0  bg-opacity-40 z-40 hidden lg:hidden" onclick="toggleSidebar()">
        </div>

        <!-- 🧭 Sidebar -->
        <aside id="sidebar" class="fixed lg:static top-0 left-0 z-50 bg-white w-[240px] h-screen transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow lg:shadow-none">
            <?php include 'Includes/Sidebar.php'; ?>
        </aside>

        <!-- 📝 Main Content -->
        <main class="flex-1 px-4 py-6 lg:pl-6 space-y-8 max-w-full overflow-x-hidden">
            <h1 class="text-2xl md:text-3xl font-semibold text-purple-700 mb-4 font-[Manrope] text-center">Answer Review</h1>
            <h2 class="text-xl font-medium text-gray-600 mb-10 font-[Manrope] text-center"><?= htmlspecialchars($quiz['deck_name']) ?></h2>
            
            <?php if (empty($answers)): ?>
                <div class="text-center text-gray-500">
                    <p>No answers found for review.</p>
                </div>
            <?php else: ?>
                <?php foreach ($answers as $index => $answer): ?>
                    <div class="w-full max-w-4xl p-6 rounded-lg border border-gray-200 mb-6">
                        <!-- Question Counter -->
                        <p class="text-black text-[22px] leading-[28px] font-[Manrope] font-normal mb-4">
                            Question <?= $index + 1 ?> of <?= $totalAnswers ?>
                        </p>

                        <!-- Question -->
                        <p class="font-[Manrope] text-[20px] text-black mb-6">
                            <?= htmlspecialchars($answer['question']) ?>
                        </p>

                        <?php if (!empty($answer['explanation'])): ?>
                            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <h4 class="font-semibold text-blue-800 mb-2">Explanation:</h4>
                                <p class="text-blue-700 text-[16px] leading-[24px]">
                                    <?= htmlspecialchars($answer['explanation']) ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <!-- Options Container -->
                        <div class="space-y-3">
                            <?php
                            $options = [
                                'a' => $answer['option_a'],
                                'b' => $answer['option_b'],
                                'c' => $answer['option_c'],
                                'd' => $answer['option_d']
                            ];
                            
                            foreach ($options as $optionKey => $optionText):
                                // Convert both to lowercase for comparison to handle case mismatch
                                $isCorrect = strtolower($optionKey) === strtolower($answer['correct_option']);
                                $isSelected = strtolower($optionKey) === strtolower($answer['selected_answer']);
                                $isUserCorrect = $isCorrect && $isSelected;
                                
                                $optionClass = "flex items-center rounded-[10px] border-2 border-gray-300 p-4 transition-colors duration-150";
                                
                                if ($isCorrect) {
                                    $optionClass .= " correct-answer";
                                }
                                
                                if ($isSelected) {
                                    $optionClass .= " user-selected";
                                    if ($isUserCorrect) {
                                        $optionClass .= " correct";
                                    } else {
                                        $optionClass .= " wrong";
                                    }
                                }
                            ?>
                                <div class="<?= $optionClass ?>">
                                    <div class="flex items-center w-full">
                                        <div>
                                            <?php if ($isSelected): ?>
                                                <div class="w-3 h-3 rounded-full bg-current"></div>
                                            <?php endif; ?>
                                        </div>
                                        <span class="flex-grow text-gray-700 text-[16px]"><?= htmlspecialchars($optionText) ?></span>
                                        <?php if ($isCorrect): ?>
                                            <span class="text-green-600 font-semibold ml-2">✓ Correct</span>
                                        <?php endif; ?>
                                        <?php if ($isSelected && !$isUserCorrect): ?>
                                            <span class="text-red-600 font-semibold ml-2">✗ Your Answer</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </main>
    </div>
<script src="assets/js/loader.js"></script>
    <script src="assets/js/mouse.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const dropdownContents = document.querySelectorAll('.dropdown-content');
            const arrowIcons = document.querySelectorAll('.arrow-icon');
            const sidebarNav = document.getElementById('sidebarNav');
            const menuItems = sidebarNav.querySelectorAll('.menu-item');
            const LAST_ACTIVE_MENU_ITEM = 'lastActiveMenuItem';

            // 🔁 Sidebar Toggle (Mobile)
            window.toggleSidebar = function() {
                const isHidden = sidebar.classList.contains('-translate-x-full');
                sidebar.classList.toggle('-translate-x-full', !isHidden);
                overlay.classList.toggle('hidden', isHidden);
            };

            // 🔄 Reset All Menu Items
            function resetAllMenuItems() {
                menuItems.forEach(mi => {
                    mi.classList.remove('bg-[#673AB7]', 'active-menu');
                    mi.querySelector('a')?.classList.remove('text-white');
                    mi.querySelector('span')?.classList.remove('text-white');
                    const icon = mi.querySelector('.icon');
                    if (icon) icon.style.filter = '';
                });

                dropdownContents.forEach(dropdown => {
                    dropdown.classList.add('max-h-0');
                    dropdown.style.maxHeight = '0px';
                });
                arrowIcons.forEach(arrow => {
                    arrow.classList.remove('rotate-180');
                    arrow.style.filter = '';
                });
            }

            // ✅ Set Active Item
            function setActiveMenuItem(itemToActivate) {
                resetAllMenuItems();

                if (itemToActivate) {
                    itemToActivate.classList.add('bg-[#673AB7]', 'active-menu');
                    itemToActivate.querySelector('a')?.classList.add('text-white');
                    itemToActivate.querySelector('span')?.classList.add('text-white');
                    const icon = itemToActivate.querySelector('.icon');
                    if (icon) icon.style.filter = 'invert(100%)';

                    let parentDropdownTrigger = null;
                    dropdownContents.forEach(dropdown => {
                        if (dropdown.contains(itemToActivate)) {
                            parentDropdownTrigger = document.querySelector(`[data-dropdown-target="${dropdown.id}"]`);
                        }
                    });

                    if (parentDropdownTrigger) {
                        parentDropdownTrigger.classList.add('bg-[#673AB7]', 'active-menu');
                        parentDropdownTrigger.querySelector('.icon').style.filter = 'invert(100%)';
                        parentDropdownTrigger.querySelector('span').classList.add('text-white');

                        const targetDropdown = document.getElementById(parentDropdownTrigger.dataset.dropdownTarget);
                        targetDropdown.classList.remove('max-h-0');
                        targetDropdown.style.maxHeight = targetDropdown.scrollHeight + 'px';

                        const targetArrow = document.getElementById(parentDropdownTrigger.dataset.arrowTarget);
                        targetArrow.classList.add('rotate-180');
                        targetArrow.style.filter = 'invert(100%)';
                    }

                    localStorage.setItem(LAST_ACTIVE_MENU_ITEM, itemToActivate.dataset.id);
                } else {
                    localStorage.removeItem(LAST_ACTIVE_MENU_ITEM);
                }
            }

            // ⬇️ Toggle Dropdown
            function toggleAnyDropdown(triggerElement) {
                const targetDropdown = document.getElementById(triggerElement.dataset.dropdownTarget);
                const targetArrow = document.getElementById(triggerElement.dataset.arrowTarget);
                const isCollapsed = targetDropdown.classList.contains('max-h-0');

                dropdownContents.forEach(dropdown => {
                    if (dropdown.id !== triggerElement.dataset.dropdownTarget) {
                        dropdown.classList.add('max-h-0');
                        dropdown.style.maxHeight = '0px';
                        const otherTrigger = document.querySelector(`[data-dropdown-target="${dropdown.id}"]`);
                        if (otherTrigger) {
                            const otherArrow = document.getElementById(otherTrigger.dataset.arrowTarget);
                            if (otherArrow) {
                                otherArrow.classList.remove('rotate-180');
                                otherArrow.style.filter = '';
                            }
                        }
                    }
                });

                if (isCollapsed) {
                    setActiveMenuItem(triggerElement);
                    targetDropdown.classList.remove('max-h-0');
                    targetDropdown.style.maxHeight = targetDropdown.scrollHeight + 'px';
                    targetArrow.classList.add('rotate-180');
                    targetArrow.style.filter = 'invert(100%)';
                } else {
                    targetDropdown.style.maxHeight = targetDropdown.scrollHeight + 'px';
                    void targetDropdown.offsetWidth;
                    targetDropdown.classList.add('max-h-0');
                    targetDropdown.style.maxHeight = '0px';

                    triggerElement.classList.remove('bg-[#673AB7]', 'active-menu');
                    triggerElement.querySelector('.icon').style.filter = '';
                    triggerElement.querySelector('span').classList.remove('text-white');
                    targetArrow.classList.remove('rotate-180');
                    targetArrow.style.filter = '';
                }
            }

            // 📦 Sidebar Click Handler
            sidebarNav.addEventListener('click', function(event) {
                const clickedItem = event.target.closest('.menu-item');
                if (clickedItem) {
                    if (clickedItem.hasAttribute('data-dropdown-target')) {
                        toggleAnyDropdown(clickedItem);
                    } else {
                        setActiveMenuItem(clickedItem);

                        // Auto-close sidebar on mobile after selecting menu
                        if (window.innerWidth < 1024) toggleSidebar();
                    }
                }
            });

            // ⚙️ Initial Active Item (URL or Storage)
            function initializeActiveState() {
                const currentPath = window.location.pathname.split('/').pop();
                let itemToActivate = null;

                menuItems.forEach(mi => {
                    const link = mi.querySelector('a');
                    if (link && link.href) {
                        const linkPath = link.href.split('/').pop();
                        const isHomePage = (linkPath === 'Sidebar.html' && (currentPath === '' || currentPath === 'Sidebar.html' || currentPath === 'index.html'));
                        if (linkPath === currentPath || isHomePage) {
                            itemToActivate = mi;
                        }
                    }
                });

                if (itemToActivate) {
                    setActiveMenuItem(itemToActivate);
                } else {
                    const lastActiveId = localStorage.getItem(LAST_ACTIVE_MENU_ITEM);
                    if (lastActiveId) {
                        itemToActivate = document.querySelector(`[data-id="${lastActiveId}"]`);
                        if (itemToActivate) {
                            setActiveMenuItem(itemToActivate);
                        } else {
                            localStorage.removeItem(LAST_ACTIVE_MENU_ITEM);
                            setActiveMenuItem(document.querySelector('[data-id="home"]'));
                        }
                    } else {
                        setActiveMenuItem(document.querySelector('[data-id="home"]'));
                    }
                }
            }

            initializeActiveState();
        });
    </script>

</body>
</html>
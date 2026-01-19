<?php 
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$topicId = isset($_GET['topic_id']) ? intval($_GET['topic_id']) : 0;
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'tutor';

// Fetch all answers of this user for the specific topic
$sql = "
  SELECT 
    a.mcq_id,
    a.selected_option,
    a.is_correct,
    m.topic_id,
    t.name AS topic_name
  FROM answers a
  JOIN mcqs m ON a.mcq_id = m.id
  LEFT JOIN topics t ON m.topic_id = t.id
  WHERE a.user_id = ? AND m.topic_id = ?
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$userId, $topicId]);
$answers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the display name
$displayName = !empty($answers) ? ($answers[0]['topic_name'] ?? 'N/A') : 'N/A';
$displayType = 'Topic';

// Calculate stats directly from answers
$total = count($answers);
$correct = 0;
$incorrect = 0;

foreach ($answers as $ans) {
  if ($ans['is_correct']) {
    $correct++;
  } else {
    $incorrect++;
  }
}

// Calculate percentage
$percentage = $total > 0 ? round(($correct / $total) * 100) : 0;
?>



<!DOCTYPE html>
<html lang="en" class="overflow-x-hidden">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Result Analytics</title>

   <link href="dist/output.css" rel="stylesheet">
   
   <link href="dist/input.css" rel="stylesheet">
   <link rel="stylesheet" href="assets/css/loader.css">
            <link rel="stylesheet" href="assets/css/mouse.css">

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
<div id="sidebarOverlay"
     class="fixed inset-0  bg-opacity-40 z-40 hidden lg:hidden"
     onclick="toggleSidebar()">
</div>

<!-- 🧭 Sidebar -->
<aside id="sidebar"
       class="fixed lg:static top-0 left-0 z-50 bg-white w-[240px] h-screen transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow lg:shadow-none">
  <?php include 'Includes/Sidebar.php'; ?>
</aside>


   <main class="flex-1 px-4 py-6 lg:pl-6 space-y-8 max-w-full overflow-x-hidden">
<!-- ✅ Right Content Section -->
    <main class="space-y-8">

      <!-- Top Buttons -->
    <div class="flex flex-col items-center gap-4 mt-6">
  <!-- Review My Answers Button - Only show in test mode -->
  <?php if ($mode === 'test'): ?>
  <button
    onclick="window.location.href='answer.php?topic_id=<?= $topicId ?>&mode=<?= $mode ?>'"
    class=" w-[380px] h-[50px] bg-[#FFA726] text-white text-base font-semibold rounded-full px-6 py-3 shadow hover:bg-[#fb8c00] transition duration-300"
  >
    Review My Answers
  </button>
  <?php endif; ?>

  <!-- Re-Attempt This Paper Button -->
  <button
    onclick="reAttemptPaper()"
    class=" w-[380px] h-[50px] bg-[#E60000] text-white text-base font-semibold rounded-full px-6 py-3 shadow hover:bg-red-700 transition duration-300"
  >
    Re-Attempt This Paper
  </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5 max-w-4xl mx-auto my-6 px-4">
  <!-- Card 1 -->
  <div class="bg-white rounded-lg shadow-lg p-4 text-center">
    <h2 class="text-xl text-gray-700 mb-3 font-semibold font-[Manrope]">Analytics</h2>
    <img src="assets/Images/Frame.png" alt="Analytics Chart" class="mx-auto w-[200px] h-[200px]">

    <div class="mt-4 space-y-1.5">
      <div class="flex items-center justify-center gap-2 text-sm text-gray-800 font-medium">
        <div class="w-3 h-3 bg-[#39CEF3] rounded-full"></div>
        <p>Correct Questions: <?= $correct ?></p>
      </div>
      <div class="flex items-center justify-center gap-2 text-sm text-gray-800 font-medium">
        <div class="w-3 h-3 bg-[#F36F39] rounded-full"></div>
        <p>Incorrect Questions: <?= $incorrect ?></p>
      </div>
    </div>
  </div>

  <!-- Card 2 -->
  <div class="bg-white rounded-lg shadow-lg p-4 text-center">
    <h2 class="text-xl text-gray-700 mb-3 font-semibold font-[Manrope]">Correct Attempts: <?= $correct ?>/<?= $total ?></h2>
    
    <!-- Dynamic SVG Circle Chart -->
    <div class="relative mx-auto w-[200px] h-[200px] flex items-center justify-center">
      <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
        <!-- Background circle -->
        <circle cx="50" cy="50" r="40" fill="none" stroke="#E5E7EB" stroke-width="8"/>
        <!-- Progress circle -->
        <circle cx="50" cy="50" r="40" fill="none" stroke="#673AB7" stroke-width="8" 
                stroke-dasharray="<?= 2 * M_PI * 40 ?>" 
                stroke-dashoffset="<?= 2 * M_PI * 40 * (1 - $percentage / 100) ?>"
                stroke-linecap="round"/>
      </svg>
      <!-- Percentage text in center -->
      <div class="absolute inset-0 flex items-center justify-center">
        <span class="text-2xl font-bold text-[#673AB7]"><?= $percentage ?>%</span>
      </div>
    </div>

    <div class="mt-4 space-y-1.5">
      <div class="flex items-center justify-center gap-2 text-sm text-gray-800 font-medium">
        <div class="w-3 h-3 bg-[#39CEF3] rounded-full"></div>
        <p>Correct Questions: <?= $correct ?></p>
      </div>
      <div class="flex items-center justify-center gap-2 text-sm text-gray-800 font-medium">
        <div class="w-3 h-3 bg-[#F36F39] rounded-full"></div>
        <p>Incorrect Questions: <?= $incorrect ?></p>
      </div>
    </div>
  </div>
</div>


      <!-- Stats Section -->
  <div class=" mx-auto bg-white rounded-[10px] shadow-[10px_30px_60px_rgba(0,0,0,0.2)] p-6">
  <div class="flex flex-col gap-6 items-center justify-center">

    <!-- Stat 1 -->
   <div class="flex items-center gap-3">
  <img src="assets/Images/image 61.png" alt="Score Icon" class="w-[55px] h-[55px]">
  <div>
    <div class="font-[Manrope] text-[18px] font-medium text-gray-700 leading-snug text-center sm:text-left">Total Marks Scored</div>
    <div class="font-[Manrope] text-[28px] font-bold text-gray-900 text-center sm:text-left"><?= $correct ?> / <?= $total ?></div>
  </div>
</div>



   

    

  </div>
</div>

<!-- Subject Breakdown Section -->
<div class="max-w-[900px] mx-auto bg-white rounded-lg shadow-md p-4 overflow-x-auto">

  <!-- Top Heading -->
  <h3 class="text-[22px] font-[Manrope] font-semibold text-gray-700 mb-4 text-center"><?= $displayType ?> Breakdown</h3>

  <!-- Main Table -->
  <table class="w-full text-left border-collapse font-[Manrope] text-[15px] leading-tight">
    <thead>
      <tr class="text-gray-600 border-b">
        <th class="py-2 px-2">Icon</th>
        <th class="py-2 px-2"><?= $displayType ?></th>
        <th class="py-2 px-2">Total Attempt</th>
        <th class="py-2 px-2">Correct Attempts</th>
      </tr>
    </thead>
    <tbody>
<tr class="border-b hover:bg-gray-50">
  <td class="py-2 px-2">🧬</td>
  <td class="py-2 px-2"><?= htmlspecialchars($displayName) ?></td>
  <td class="py-2 px-2"><?= $total ?></td>
  <td class="py-2 px-2"><?= $correct ?></td>
</tr>
</tbody>
  </table>

  <!-- Correct / Incorrect Table -->
  <div class="flex justify-center mt-4">
    <table class="w-[320px] text-center border-collapse font-[Manrope] text-[15px] leading-tight">
      <thead>
        <tr class="text-gray-600 border-b">
          <th class="py-2 px-2">Correct</th>
          <th class="py-2 px-2">Incorrect</th>
        </tr>
      </thead>
      <tbody>
        <tr class="border-b hover:bg-gray-50">
          <td class="py-2 px-2"><?= $correct ?></td>
          <td class="py-2 px-2"><?= $incorrect ?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

</div>


    </main>
  </main>
</div>
 <script src="assets/js/loader.js"></script>
    <script src="assets/js/mouse.js"></script>
<script>
    
function reAttemptPaper() {
  const topicId = <?= $topicId ?>;
  const mode = '<?= $mode ?>';
  
  fetch('delete_answers.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      topic_id: topicId
    })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      window.location.href = `mcqs.php?topic_id=${topicId}&mode=${mode}`;
    } else {
      alert('Error: ' + data.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('An error occurred while processing your request.');
  });
}

  document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const dropdownContents = document.querySelectorAll('.dropdown-content');
    const arrowIcons = document.querySelectorAll('.arrow-icon');
    const sidebarNav = document.getElementById('sidebarNav');
    const menuItems = sidebarNav.querySelectorAll('.menu-item');
    const LAST_ACTIVE_MENU_ITEM = 'lastActiveMenuItem';

    // 🔁 Sidebar Toggle (Mobile)
    window.toggleSidebar = function () {
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
    sidebarNav.addEventListener('click', function (event) {
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

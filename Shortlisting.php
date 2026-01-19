<?php
require 'config.php';
session_start();

// Function to check if user is logged in
function isUserLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Function to check if user has active subscription
function hasActiveSubscription($pdo, $userId) {
    $currentDate = date('Y-m-d');
    $stmt = $pdo->prepare("SELECT * FROM subscriptions WHERE user_id = ? AND status = 'approved' AND end_date > ?");
    $stmt->execute([$userId, $currentDate]);
    return $stmt->rowCount() > 0;
}

// Check if user can download PDFs
$canDownloadPDF = false;
if (isUserLoggedIn()) {
    $userId = $_SESSION['user_id'];
    $canDownloadPDF = hasActiveSubscription($pdo, $userId);
}

$stmt = $pdo->query('SELECT * FROM shortlistings ORDER BY subject_name, topic_name');
$shortlistings = $stmt->fetchAll();

$subjects = [];
foreach ($shortlistings as $shortlisting) {
    $subject_name = $shortlisting['subject_name'];
    if (!isset($subjects[$subject_name])) {
        $subjects[$subject_name] = [];
    }
    $subjects[$subject_name][] = $shortlisting;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Shortlisting</title>
  <link href="dist/output.css" rel="stylesheet">

  <link href="dist/input.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/loader.css">
  <link rel="stylesheet" href="assets/css/mouse.css">
  <style>
    .custom-shadow {
      box-shadow: 0px 10px 40px 0px #00000040;
    }

    .rotate-180 {
      transform: rotate(180deg);
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
      <h1 class="text-2xl md:text-3xl font-semibold text-purple-700 mb-10 font-[Manrope] text-center">Shortlisting</h1>

      <!-- Subject Cards Container -->
      <div class="space-y-6">

        <?php foreach ($subjects as $subject_name => $topics): ?>
          <div class="relative">
            <!-- Main Box -->
            <div id="<?= strtolower(str_replace(' ', '_', $subject_name)) ?>Box"
              class="bg-[#E4E4E4] rounded-[8px] p-2 md:p-3 hover:bg-gradient-to-b from-[#673AB7] to-[#2E1A51] custom-shadow group cursor-pointer"
              onclick="toggleDropdown('<?= strtolower(str_replace(' ', '_', $subject_name)) ?>Dropdown', '<?= strtolower(str_replace(' ', '_', $subject_name)) ?>Box')">
              <div class="flex justify-between items-center p-1.5 md:p-2">
                <div class="flex items-center gap-3">
                  <img src="https://img.icons8.com/color/96/microscope.png" alt="<?= htmlspecialchars($subject_name) ?>"
                    class="w-[36px] h-[36px] md:w-[42px] md:h-[42px]" />
                  <span
                    class="font-bold text-[14px] md:text-[16px] text-black group-hover:text-white"><?= htmlspecialchars($subject_name) ?></span>
                </div>
                <div class="flex items-center gap-1.5">
                  <span class="text-[13px] md:text-[14px] text-[#4B0082] group-hover:text-white">CHAPTERS</span>
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-[16px] h-[16px] group-hover:stroke-white" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </div>
              </div>
            </div>

            <!-- Dropdown Attached -->
            <div id="<?= strtolower(str_replace(' ', '_', $subject_name)) ?>Dropdown"
              class="dropdown bg-[#F3F3F3] rounded-b-[8px] overflow-hidden">
              <div class="p-4 space-y-3">
                <?php foreach ($topics as $topic): ?>
                  <div class="flex justify-between items-center border-b border-gray-300 pb-2">
                    <span class="text-[#535353] text-lg font-semibold"><?= htmlspecialchars($topic['topic_name']) ?></span>
                    <?php if ($topic['file_name'] && $canDownloadPDF): ?>
                      <a href="Admin/uploads/shortlisting/<?= htmlspecialchars($topic['file_name']) ?>"
                        class="w-[90px] h-[36px] rounded-[30px] text-white text-sm font-semibold bg-gradient-to-b from-[#673AB7] to-[#2E1A51] hover:opacity-90 transition flex items-center justify-center"
                        download>
                        PDF
                      </a>
                    <?php elseif ($topic['file_name']): ?>
                      <!-- <span
                        class="w-[90px] h-[36px] rounded-[30px] text-gray-500 text-sm font-semibold bg-gray-200 flex items-center justify-center">
                        Login Required
                      </span> -->
                    <?php else: ?>
                      <span
                        class="w-[90px] h-[36px] rounded-[30px] text-gray-500 text-sm font-semibold bg-gray-200 flex items-center justify-center">
                        No File
                      </span>
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>

        <?php if (empty($subjects)): ?>
          <div class="text-center py-8">
            <p class="text-gray-500 text-lg">No shortlisting data available.</p>
          </div>
        <?php endif; ?>

        <style>
          .dropdown {
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transition: max-height 0.5s ease, opacity 0.5s ease;
          }

          .dropdown.show {
            max-height: 1000px;
            /* enough to fit content */
            opacity: 1;
          }

          .active {
            background: linear-gradient(to bottom, #673AB7, #2E1A51);
            color: white;
          }

          .active span {
            color: white !important;
          }

          .active svg {
            stroke: white;
          }
        </style>
        <script>
          function toggleDropdown(dropdownId, boxId) {
            const dropdown = document.getElementById(dropdownId);
            const box = document.getElementById(boxId);

            const isOpen = dropdown.classList.contains('show');

            // Close all dropdowns and remove active state
            document.querySelectorAll('.dropdown').forEach(d => d.classList.remove('show'));
            document.querySelectorAll('.active').forEach(b => b.classList.remove('active'));

            // If not already open, open it and activate box
            if (!isOpen) {
              dropdown.classList.add('show');
              box.classList.add('active');
            }
          }
        </script>

      </div>
  </div>
  <script src="assets/js/loader.js"></script>
  <script src="assets/js/mouse.js"></script>

  <!-- 🔁 Toggle Script -->
  <script>
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
<?php
include 'config.php';
session_start();
$topicId = isset($_GET['topic_id']) ? intval($_GET['topic_id']) : 0;

$topicName = 'Unknown Topic';
if ($topicId > 0) {
    try {
        $stmt = $pdo->prepare("SELECT name FROM topics WHERE id = ?");
        $stmt->execute([$topicId]);
        $topic = $stmt->fetch();
        if ($topic) {
            $topicName = $topic['name'];
        }
    } catch (PDOException $e) {
        $topicName = 'Unknown Topic';
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="overflow-x-hidden">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($topicName) ?> - Tutor Mode</title>

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

    <div class="lg:block  sm:flex-row sm:items-center sm:gap-4 mt-4 lg:mt-0">
  <h1 class="font-bold text-[#673AB7] text-[32px] leading-[40px]">
    <?= htmlspecialchars($topicName) ?>
  </h1>
  <p class="text-gray-800 font-medium text-[24px] leading-[28px] mt-2 sm:mt-0">
    Tutor Mode
  </p>
</div>


      <div>
        <button class="w-[171px] h-[51px] bg-[#673AB7] text-white rounded-[10px] hover:bg-[#5c32a9] transition">
          MCQ's
        </button>
      </div>

      <div id="mode-switcher" class="w-full max-w-4xl mx-auto border-2 border-purple-600 rounded-[10px] p-4 mb-6">
        <div class="flex flex-col sm:flex-row justify-center gap-3">

          <a href="mcqs.php?topic_id=<?= $topicId ?>&mode=tutor"
            class="w-full sm:w-[480px]">
            <button id="tutorBtn"
              class="mode-btn w-full h-[80px] rounded-[10px] bg-white text-[#673AB7] flex items-center justify-between px-5 py-3 transition border border-[#673AB7]">
              <span class="text-lg font-semibold">Tutor Mode</span>
              <span
                class="w-[100px] h-[40px] bg-[#673AB7] text-white text-xs font-semibold flex items-center justify-center rounded-full">Free</span>
            </button>
          </a>

          <a href="mcqs.php?topic_id=<?= $topicId ?>&mode=test"
            class="w-full sm:w-[480px]">
            <button id="testBtn"
              class="mode-btn w-full h-[80px] rounded-[10px] bg-white text-[#673AB7] flex items-center justify-between px-5 py-3 transition border border-[#673AB7]">
              <span class="text-lg font-semibold">Timed Test Mode</span>
              <span
                class="w-[100px] h-[40px] bg-[#673AB7] text-white text-xs font-semibold flex items-center justify-center rounded-full">Free</span>
            </button>
          </a>

        </div>

      </div>

      <script>
        const buttons = document.querySelectorAll('.mode-btn');

        buttons.forEach(btn => {
          btn.addEventListener('click', () => {
            buttons.forEach(b => {
              b.classList.remove('bg-[#673AB7]', 'text-white');
              b.classList.add('bg-white', 'text-[#673AB7]');
            });

            btn.classList.remove('bg-white', 'text-[#673AB7]');
            btn.classList.add('bg-[#673AB7]', 'text-white');
          });
        });
      </script>


      <div id="tutorContent"
        class="bg-white rounded-[10px] p-8 shadow-[10px_40px_80px_0px_rgba(0,0,0,0.25)] max-w-5xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">
          <div class="text-center md:text-left">
            <h3 class="text-[22px] font-bold text-[#673AB7] mb-4">Tutor Mode</h3>
            <ul class="space-y-2 text-[#673AB7] font-medium text-[16px] leading-[22px]">
              <li>Learn at your own pace</li>
              <li>Get instant feedback</li>
              <li>Unlimited retries</li>
              <li>Topic-wise breakdown</li>
            </ul>
            <button
              class="mt-6 w-[220px] h-[38px] text-white text-sm font-semibold rounded-full shadow bg-gradient-to-b from-[#673AB7] to-[#2E1A51] hover:scale-105 transition">
              Attempt Completely Free
            </button>
          </div>

          <div class="text-center md:text-left">
            <h3 class="text-[20px] font-semibold text-[#673AB7] mb-3">Instructions</h3>
            <p class="text-gray-600 text-[15px] leading-[24px]">
              Use Tutor Mode to explore and understand concepts freely without time limits.
            </p>
          </div>
        </div>
      </div>

      <div id="testContent"
        class="bg-white rounded-[10px] p-8 shadow-[10px_40px_80px_0px_rgba(0,0,0,0.25)] max-w-5xl mx-auto hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">
          <div class="text-center md:text-left">
            <h3 class="text-[22px] font-bold text-[#673AB7] mb-4">Timed Test Mode</h3>
            <ul class="space-y-2 text-[#673AB7] font-medium text-[16px] leading-[22px]">
              <li>Practice in exam conditions</li>
              <li>Time-limited questions</li>
              <li>Get score instantly</li>
              <li>Track performance</li>
            </ul>
            <button
              class="mt-6 w-[220px] h-[38px] text-white text-sm font-semibold rounded-full shadow bg-gradient-to-b from-[#673AB7] to-[#2E1A51] hover:scale-105 transition">
              Start Timed Test
            </button>
          </div>

          <div class="text-center md:text-left">
            <h3 class="text-[20px] font-semibold text-[#673AB7] mb-3">Instructions</h3>
            <p class="text-gray-600 text-[15px] leading-[24px]">
              Timed Test Mode simulates the real exam environment to evaluate your readiness.
            </p>
          </div>
        </div>
      </div>


      <script src="assets/js/Quizbuilder.js"></script>
      <script src="assets/js/loader.js"></script>
      <script src="assets/js/mouse.js"></script>
      
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            const overlay = document.getElementById("sidebarOverlay");
            const isOpen = !sidebar.classList.contains("-translate-x-full");

            if (isOpen) {
                sidebar.classList.add("-translate-x-full");
                overlay?.classList.add("hidden");
            } else {
                sidebar.classList.remove("-translate-x-full");
                overlay?.classList.remove("hidden");
            }
        }
    </script>
</body>

</html>
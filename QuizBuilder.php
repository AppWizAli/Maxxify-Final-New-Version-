<?php
session_start();
include 'config.php';

$userId = $_SESSION['user_id'] ?? null;
$hasValidSubscription = false;

if ($userId) {
  $subscriptionQuery = "
        SELECT COUNT(*) as subscription_count 
        FROM subscriptions 
        WHERE user_id = ? 
        AND status = 'approved' 
        AND end_date > CURDATE()
    ";
  $subscriptionStmt = $pdo->prepare($subscriptionQuery);
  $subscriptionStmt->execute([$userId]);
  $subscriptionCount = $subscriptionStmt->fetchColumn();
  $hasValidSubscription = $subscriptionCount > 0;
}

$selectedCategory = $_GET['category'] ?? 'mdcat';

$categoryMap = [
  'mdcat' => 1,
  'nums' => 2,
  'fsc' => 3
];

$mcqTypeId = $categoryMap[$selectedCategory] ?? 1;

// Fetch subjects and topics based on the selected category
$query = "
    SELECT 
        s.id as subject_id,
        s.name as subject_name,
        t.id as topic_id,
        t.name as topic_name,
        COUNT(m.id) as mcq_count
        FROM subjects s
        LEFT JOIN topics t ON s.id = t.subject_id
        LEFT JOIN mcqs m ON t.id = m.topic_id
        LEFT JOIN mcq_categories c ON s.category_id = c.id
        WHERE c.mcq_type_id = ?
        GROUP BY s.id, s.name, t.id, t.name
        ORDER BY s.name, t.name
";

$stmt = $pdo->prepare($query);
$stmt->execute([$mcqTypeId]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group by subjects
$subjects = [];
foreach ($results as $row) {
  $subjectId = $row['subject_id'];
  if (!isset($subjects[$subjectId])) {
    $subjects[$subjectId] = [
      'name' => $row['subject_name'],
      'topics' => []
    ];
  }

  if ($row['topic_id']) {
    $subjects[$subjectId]['topics'][] = [
      'id' => $row['topic_id'],
      'name' => $row['topic_name'],
      'mcq_count' => $row['mcq_count']
    ];
  }
}

// Calculate total MCQs available
$totalMcqQuery = "
    SELECT COUNT(m.id) as total_mcqs
    FROM mcqs m
    JOIN topics t ON m.topic_id = t.id
    JOIN subjects s ON t.subject_id = s.id
    JOIN mcq_categories c ON s.category_id = c.id
    WHERE c.mcq_type_id = ?
";

$totalStmt = $pdo->prepare($totalMcqQuery);
$totalStmt->execute([$mcqTypeId]);
$totalMcqs = $totalStmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quiz Builder</title>

  <link href="dist/output.css" rel="stylesheet">
  <link href="dist/input.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/loader.css">
  <link rel="stylesheet" href="assets/css/mouse.css">
</head>

<body class="bg-white min-h-screen flex items-start justify-center gap-10 lg:p-6 xl:p-6 relative">
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
  <div id="sidebarOverlay" class="fixed inset-0  bg-opacity-40 z-40 hidden lg:hidden" onclick="toggleSidebar()">
  </div>

  <!-- 🧭 Sidebar -->
  <aside id="sidebar"
    class="fixed lg:static top-0 left-0 z-50 bg-white w-[240px] h-screen transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow lg:shadow-none">
    <?php include 'Includes/Sidebar.php'; ?>
  </aside>

  <!-- ✅ Main Content (Fully Adjusted) -->
  <main class="space-y-8  sm:px-6 py-6">

    <div class="max-w-6xl mx-auto p-4">
      <!-- Header -->
      <div class="text-center py-4">
        <h1 class="text-[28px] font-bold leading-[32px] tracking-[0] text-purple-600">Quiz Builder</h1>
        <p class="mt-2 text-gray-600 font-bold text-[20px] leading-[32px] max-w-[700px] mx-auto text-center h-auto">
          Create custom quiz decks of various chapters and <br>
          <span class="inline-block px-2">subjects using questions</span> from PreMed's Question Bank,<br>
          and All Past Papers of MDCAT, NUMS, and more!
        </p>
      </div>

      <div class="flex flex-wrap gap-4 mt-4">
        <!-- Name your Deck -->
        <div class="flex flex-col">
          <label class="block mb-1 text-[14px] font-semibold leading-[20px] text-black"
            style="font-family: 'Manrope', sans-serif;">
            Name your Deck:
          </label>
          <input type="text" id="deckName"
            class="w-[220px] h-[40px] border border-[#535353] rounded-[6px] px-2 text-sm">
        </div>

        <!-- Estimated Time -->
        <div class="flex flex-col">
          <label class="block mb-1 text-[14px] font-semibold leading-[20px] text-black"
            style="font-family: 'Manrope', sans-serif;">
            Estimated Time:
          </label>
          <input type="text" id="estimatedTime" readonly
            class="w-[220px] h-[40px] border border-[#535353] rounded-[6px] px-2 text-sm bg-gray-100" value="00:00">
        </div>

        <!-- No of MCQs -->
        <div class="flex flex-col">
          <label class="block mb-1 text-[14px] font-semibold leading-[20px] text-black"
            style="font-family: 'Manrope', sans-serif;">
            No of MCQs:
          </label>
          <input type="number" id="mcqCount"
            class="w-[220px] h-[40px] border border-[#535353] rounded-[6px] px-2 text-sm">
        </div>

        <!-- Select Category -->
        <div class="flex flex-col">
          <label class="block mb-1 text-[14px] font-semibold leading-[20px] text-black"
            style="font-family: 'Manrope', sans-serif;">
            Select Category:
          </label>
          <select id="categorySelect"
            class="w-[220px] h-[40px] border border-[#535353] rounded-[6px] px-2 text-sm bg-white text-black font-medium"
            style="font-family: 'Manrope', sans-serif;">
            <option value="mdcat" <?= $selectedCategory === 'mdcat' ? 'selected' : '' ?>>MDCAT</option>
            <option value="nums" <?= $selectedCategory === 'nums' ? 'selected' : '' ?>>NUMS</option>
            <option value="fsc" <?= $selectedCategory === 'fsc' ? 'selected' : '' ?>>FSC</option>
          </select>
        </div>
      </div>

<div class="flex flex-col lg:flex-row items-center lg:items-start justify-center lg:justify-start gap-4 mt-6">
  <!-- Box 1: TOTAL -->
  <div
    class="text-center w-full sm:w-[90%] md:w-[80%] lg:w-[200px] h-[200px] px-3 py-4 rounded-md border border-gray-300 shadow-md transition duration-300"
    style="box-shadow: 4px 12px 30px rgba(0, 0, 0, 0.2);">
    <p class="text-2xl text-gray-500 font-medium font-[Manrope]">
      TOTAL
    </p>
    <p class="text-2xl font-bold text-black mt-2 font-[Manrope]" id="totalSelectedMcqs">
      0
    </p>
    <p class="text-sm text-gray-500 mt-3 font-[Manrope]">
      MCQS Selected
    </p>
  </div>

  <!-- Box 2: VIEW -->
  <div
    class="text-center w-full sm:w-[90%] md:w-[80%] lg:w-[200px] h-[200px] px-3 py-4 rounded-md border border-gray-300 shadow-md transition duration-300 cursor-pointer"
    style="box-shadow: 4px 12px 30px rgba(0, 0, 0, 0.2);" onclick="showQuizContent()">
    <p class="text-2xl font-bold text-black font-[Manrope]">
      VIEW
    </p>
    <p class="text-sm text-gray-500 mt-3 font-[Manrope]">
      Full Quiz Content
    </p>
  </div>

  <!-- Box 3: Available MCQs -->
  <div
    class="text-center w-full sm:w-[90%] md:w-[80%] lg:w-[200px] h-[200px] px-3 py-4 rounded-md border border-gray-300 shadow-md transition duration-300"
    style="box-shadow: 4px 12px 30px rgba(0, 0, 0, 0.2);">
    <p class="text-2xl text-gray-500 font-medium font-[Manrope]">
      Available Mcqs
    </p>
    <p class="text-2xl font-bold text-black mt-2 font-[Manrope]" id="availableMcqs">
      <?= $totalMcqs ?>
    </p>
  </div>
</div>

      <!-- Topics List -->
    <div class="mt-6 max-w-6xl mx-auto space-y-8" id="subjectsContainer">
  <?php foreach ($subjects as $subject): ?>
    <!-- Subject Card -->
    <div class="bg-white rounded-2xl p-6 sm:p-8" style="box-shadow: rgba(0, 0, 0, 0.15) 0px 20px 40px 0px;">
      <!-- Subject Title -->
      <h2 class="text-xl font-bold text-[#E53935] mb-6 font-[Manrope]">
        <?= htmlspecialchars($subject['name']) ?>
      </h2>

      <!-- Topics Grid (3 per row) -->
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-2">
        <?php foreach ($subject['topics'] as $topic): ?>
          <div class="flex items-center justify-between w-full">
            <!-- Checkbox + Topic Name -->
            <label class="flex items-center gap-2 text-xs sm:text-sm font-medium text-black font-[Manrope] leading-tight flex-1">
              <input type="checkbox"
                     class="w-3.5 h-3.5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                     name="topics[]" value="<?= $topic['id'] ?>"
                     data-mcq-count="<?= $topic['mcq_count'] ?>">

              <span class="truncate">
                <?= htmlspecialchars($topic['name']) ?>
              </span>
            </label>

            <!-- MCQ Count Badge -->
            <span class="ml-2 px-2 py-[1px] text-[11px] font-semibold text-blue-600 border border-blue-500 rounded-full bg-white whitespace-nowrap">
              <?= $topic['mcq_count'] ?>
            </span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>

      <style>
        .custom-scrollbar::-webkit-scrollbar {
          width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
          background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
          background-color: #a78bfa;
          /* Tailwind's purple-400 */
          border-radius: 9999px;
          border: 2px solid transparent;
          background-clip: content-box;
        }

        .custom-scrollbar {
          scrollbar-width: thin;
          scrollbar-color: #a78bfa transparent;
        }
      </style>



      <!-- Generate Button -->
      <div class="mt-8 text-center">
        <?php if ($hasValidSubscription): ?>
          <button type="button" onclick="generateQuiz()"
            class="bg-[#673AB7] text-white px-8 py-3 rounded-full text-lg font-semibold hover:bg-[#5E35B1] transition duration-300 shadow-lg">
            Generate Quiz
          </button>
        <?php else: ?>
          <div class="bg-gray-100 border border-gray-300 rounded-lg p-6 max-w-md mx-auto">
            <div class="text-center">
              <svg class="mx-auto h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">Subscription Required</h3>
              <p class="mt-1 text-sm text-gray-500">You need an active subscription to generate quizzes.</p>
              <div class="mt-6">
                <a href="pricing.php" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#673AB7] hover:bg-[#5E35B1]">
                  View Pricing
                </a>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Quiz Content Modal -->
    <div id="quizContentModal" class="fixed inset-0  bg-opacity-50 hidden z-50 flex items-center justify-center">
      <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b border-gray-200">
          <h2 class="text-2xl font-bold text-gray-800">Selected Quiz Content</h2>
          <button onclick="closeQuizModal()" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        <div class="p-6 overflow-y-auto max-h-[70vh]">
          <div id="selectedContent">
            <p class="text-gray-500 text-center">No topics selected yet.</p>
          </div>
        </div>
      </div>
    </div>

<!-- Scroll to Top Button -->
<div id="scrollToTopBtn" title="Go to top" class="w-12 h-12">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
    <path d="M12 19V6"></path>
    <polyline points="5 12 12 5 19 12"></polyline>
  </svg>
</div>

<script>
  const scrollBtn = document.getElementById("scrollToTopBtn");

  window.addEventListener("scroll", () => {
    // Show button after scrolling 100px
    if (window.scrollY > 100) {
      scrollBtn.classList.add("show");
    } else {
      scrollBtn.classList.remove("show");
    }
  });

  scrollBtn.addEventListener("click", () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
</script>
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
          overlay.classList.add("hidden");
        } else {
          sidebar.classList.remove("-translate-x-full");
          overlay.classList.remove("hidden");
        }
      }

      function generateQuiz() {
        // Get selected topics
        const selectedTopics = [];
        document.querySelectorAll('input[name="topics[]"]:checked').forEach(checkbox => {
          selectedTopics.push(checkbox.value);
        });

        // Get form values
        const deckName = document.getElementById('deckName').value.trim();
        const estimatedTime = document.getElementById('estimatedTime').value.trim();
        const mcqCount = document.getElementById('mcqCount').value.trim();

        // Validate all fields are filled
        if (selectedTopics.length === 0) {
          alert('Please select at least one topic!');
          return;
        }

        if (!deckName) {
          alert('Please enter a deck name!');
          return;
        }

        if (!mcqCount) {
          alert('Please enter number of MCQs!');
          return;
        }

        // Validate MCQ count
        const mcqNum = parseInt(mcqCount);
        if (isNaN(mcqNum) || mcqNum <= 0) {
          alert('Please enter a valid number of MCQs (must be greater than 0)');
          return;
        }

        // Calculate total MCQs from selected topics only
        let selectedTopicsMcqs = 0;
        document.querySelectorAll('input[name="topics[]"]:checked').forEach(checkbox => {
          const mcqCount = parseInt(checkbox.getAttribute('data-mcq-count') || 0);
          selectedTopicsMcqs += mcqCount;
        });

        // Check if requested MCQs exceed available MCQs from selected topics
        if (mcqNum > selectedTopicsMcqs) {
          alert(`You can only request up to ${selectedTopicsMcqs} MCQs from your selected topics. You requested ${mcqNum} MCQs.`);
          return;
        }

        // Create the quiz generation URL with parameters
        const params = new URLSearchParams();
        if (selectedTopics.length > 0) {
          params.append('topics', selectedTopics.join(','));
        }
        params.append('deck_name', deckName);
        params.append('estimated_time', estimatedTime);
        params.append('mcq_count', mcqCount);

        // Redirect to quiz generation page
        window.location.href = `generate_quiz.php?${params.toString()}`;
      }

      // Handle category change
      document.getElementById('categorySelect').addEventListener('change', function() {
        const category = this.value;
        loadSubjectsAndTopics(category);
      });

      function loadSubjectsAndTopics(category) {
        fetch(`get_subjects_topics.php?category=${category}`)
          .then(response => response.json())
          .then(data => {
            updateSubjectsContainer(data.subjects);
            updateAvailableMcqs(data.totalMcqs);
          })
          .catch(error => {
            console.error('Error loading subjects and topics:', error);
          });
      }

      function updateAvailableMcqs(totalMcqs) {
        const availableElement = document.getElementById('availableMcqs');
        if (availableElement) {
          availableElement.textContent = totalMcqs;
        }
      }

      let selectedMcqCount = 0;

      function updateSelectedMcqCount() {
        const totalSelectedElement = document.getElementById('totalSelectedMcqs');
        if (totalSelectedElement) {
          totalSelectedElement.textContent = selectedMcqCount;
        }
      }

      function formatTime(minutes) {
        const mins = Math.floor(minutes);
        const secs = Math.round((minutes - mins) * 60);
        return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
      }

      function updateEstimatedTime() {
        const estimatedTimeInput = document.getElementById('estimatedTime');
        const mcqCountInput = document.getElementById('mcqCount');
        const mcqCount = parseInt(mcqCountInput.value) || 0;
        const timeInMinutes = mcqCount;
        const formattedTime = formatTime(timeInMinutes);
        estimatedTimeInput.value = formattedTime;
      }

      function handleCheckboxChange(checkbox, mcqCount) {
        if (checkbox.checked) {
          selectedMcqCount += parseInt(mcqCount);
        } else {
          selectedMcqCount -= parseInt(mcqCount);
        }
        updateSelectedMcqCount();
      }

      function updateSubjectsContainer(subjects) {
        const container = document.getElementById('subjectsContainer');
        const currentSelectedTopics = new Set();
        const currentSelectedMcqCount = selectedMcqCount;

        document.querySelectorAll('input[name="topics[]"]:checked').forEach(checkbox => {
          currentSelectedTopics.add(checkbox.value);
        });

        container.innerHTML = '';

        subjects.forEach(subject => {
          const subjectDiv = document.createElement('div');
          subjectDiv.className = 'mb-6';

          subjectDiv.innerHTML = `
            <h2 class="text-base sm:text-lg font-bold text-[#E53935] mb-3 font-[Manrope]">${subject.name}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
              ${subject.topics.map(topic => `
                <div class="flex items-center justify-between bg-gray-50 rounded-md py-2 px-2">
                  <label class="flex items-center gap-2 text-xs font-medium text-black font-[Manrope] leading-snug">
                    <input type="checkbox"
                      class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 topic-checkbox" name="topics[]"
                      value="${topic.id}" data-mcq-count="${topic.mcq_count}" ${currentSelectedTopics.has(topic.id.toString()) ? 'checked' : ''}>
                    <span class="break-words">
                      ${topic.name}
                    </span>
                  </label>
                  <span
                    class="bg-white border border-blue-500 text-blue-600 text-[10px] font-semibold px-2 py-[1px] rounded-full">
                    ${topic.mcq_count}
                  </span>
                </div>
              `).join('')}
            </div>
          `;

          container.appendChild(subjectDiv);
        });

                  document.querySelectorAll('.topic-checkbox').forEach(checkbox => {
            const mcqCount = parseInt(checkbox.getAttribute('data-mcq-count'));
            checkbox.addEventListener('change', () => {
              handleCheckboxChange(checkbox, mcqCount);
            });
          });

          document.getElementById('mcqCount').addEventListener('input', function() {
            updateEstimatedTime();
          });

        selectedMcqCount = currentSelectedMcqCount;
        updateSelectedMcqCount();
      }

      document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input[name="topics[]"]').forEach(checkbox => {
          const mcqCount = parseInt(checkbox.getAttribute('data-mcq-count') || checkbox.closest('.flex').querySelector('.rounded-full').textContent);
          checkbox.addEventListener('change', () => {
            handleCheckboxChange(checkbox, mcqCount);
          });
        });

        document.getElementById('mcqCount').addEventListener('input', function() {
          updateEstimatedTime();
        });
      });

      function showQuizContent() {
        const modal = document.getElementById('quizContentModal');
        const selectedContent = document.getElementById('selectedContent');

        const selectedTopics = document.querySelectorAll('input[name="topics[]"]:checked');

        if (selectedTopics.length === 0) {
          selectedContent.innerHTML = '<p class="text-gray-500 text-center">No topics selected yet.</p>';
        } else {
          const subjectsMap = new Map();

          selectedTopics.forEach(checkbox => {
            const topicId = checkbox.value;
            const topicName = checkbox.closest('.flex').querySelector('span').textContent.trim();
            const mcqCount = checkbox.getAttribute('data-mcq-count');
            const subjectName = checkbox.closest('.mb-6').querySelector('h2').textContent.trim();

            if (!subjectsMap.has(subjectName)) {
              subjectsMap.set(subjectName, []);
            }

            subjectsMap.get(subjectName).push({
              name: topicName,
              mcqCount: mcqCount
            });
          });

          let html = '<div class="space-y-6">';
          subjectsMap.forEach((topics, subjectName) => {
            html += `
              <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-lg font-bold text-[#E53935] mb-3">${subjectName}</h3>
                <div class="space-y-2">
            `;

            topics.forEach(topic => {
              html += `
                <div class="flex items-center justify-between bg-gray-50 rounded-md py-2 px-3">
                  <span class="text-sm font-medium text-gray-700">${topic.name}</span>
                  <span class="bg-blue-100 text-blue-600 text-xs font-semibold px-2 py-1 rounded-full">
                    ${topic.mcqCount} MCQs
                  </span>
                </div>
              `;
            });

            html += `
                </div>
              </div>
            `;
          });

          html += `
            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
              <p class="text-sm text-blue-700">
                <strong>Total Selected:</strong> ${selectedTopics.length} topics with ${selectedMcqCount} MCQs
              </p>
            </div>
          </div>
          `;

          selectedContent.innerHTML = html;
        }

        modal.classList.remove('hidden');
      }

      function closeQuizModal() {
        const modal = document.getElementById('quizContentModal');
        modal.classList.add('hidden');
      }
    </script>

</body>

</html>
<?php include 'config.php';
session_start();
$topicId = isset($_GET['topic_id']) ? intval($_GET['topic_id']) : 0;
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'tutor';
$currentQuestion = isset($_GET['q']) ? intval($_GET['q']) : 1;
$limit = 1;
$offset = ($currentQuestion - 1) * $limit;

$totalQuery = $pdo->prepare("SELECT COUNT(*) FROM mcqs WHERE topic_id = ?");
$totalQuery->execute([$topicId]);
$totalMcqs = $totalQuery->fetchColumn();

$stmt = $pdo->prepare("SELECT * FROM mcqs WHERE topic_id = ? LIMIT $limit OFFSET $offset");
$stmt->execute([$topicId]);

$mcq = $stmt->fetch(PDO::FETCH_ASSOC);

// Time calculation for test mode
$totalMinutes = ($mode === 'test') ? $totalMcqs : 0;

// Fetch topic name for display
$displayName = '';
if ($topicId > 0) {
    $topicQuery = $pdo->prepare("SELECT name FROM topics WHERE id = ?");
    $topicQuery->execute([$topicId]);
    $topic = $topicQuery->fetch(PDO::FETCH_ASSOC);
    $displayName = $topic ? $topic['name'] : 'Unknown Topic';
} else {
    $displayName = 'General';
}

if ($mcq):
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Maxxify Checkout with Responsive Sidebar</title>
        <link href="dist/output.css" rel="stylesheet">

        <link href="dist/input.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <style>
            /* Custom colors or specific overrides if needed.
                       The CDN version of Tailwind can be extended via tailwind.config.js
                       in a build process, but with CDN, inline styles or custom utility classes
                       defined below would be the way.
                    */
            .bg-custom-purple {
                background-color: #673AB7;
                /* A custom purple color for the active sidebar item */
            }

            .text-custom-purple-dark {
                color: #673AB7;
                /* Darker purple for text if needed */
            }


            input[type='radio'] {
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                border-radius: 50%;
                border: 2px solid #D1D5DB;
                /* gray-300 */
                width: 1.25rem;
                /* 20px */
                height: 1.25rem;
                /* 20px */
                cursor: pointer;
                outline: none;
                display: inline-block;
                position: relative;
                flex-shrink: 0;
            }

            input[type='radio']:checked {
                border-color: #2563EB;
                /* blue-600 */
                background-color: #2563EB;
                /* blue-600 */
            }

            input[type='radio']:checked::before {
                content: '';
                display: block;
                width: 0.5rem;
                /* 8px */
                height: 0.5rem;
                /* 8px */
                background-color: white;
                border-radius: 50%;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }

            /* Specific style for the correct green radio button */
            input[type='radio'].radio-green-checked {
                border-color: #16A34A !important;
                background-color: #16A34A !important;
            }

            input[type='radio'].radio-green-checked::before {
                content: '';
                display: block;
                width: 0.5rem;
                height: 0.5rem;
                background-color: white !important;
                border-radius: 50%;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }

            /* Override any checked state for green radio */
            input[type='radio'].radio-green-checked:checked {
                border-color: #16A34A !important;
                background-color: #16A34A !important;
            }

            /* Force green styling with higher specificity */
            .option-box input[type='radio'].radio-green-checked {
                border-color: #16A34A !important;
                background-color: #16A34A !important;
            }

            .option-box input[type='radio'].radio-green-checked::before {
                background-color: white !important;
            }

            .donut-chart-container {
                width: 120px;
                height: 120px;
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                /* Equivalent to shadow-lg */
                background-color: white;
                /* Adjust position for responsiveness.
                           On smaller screens, you might want it below content or centered.
                           For this example, it will float to bottom-right.
                        */
                right: 1rem;
                /* Adjust as needed */
                bottom: 1rem;
                /* Adjust as needed */
            }

            .donut-chart-svg {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                transform: rotate(-90deg);
                /* Start from the top */
            }

            .chart-circle {
                fill: none;
                stroke-width: 10;
                stroke-linecap: round;
                /* For rounded ends of segments */
            }

            @media (min-width: 1024px) {
                .your-container {
                    display: block !important;
                    /* or display: initial; or display: unset; */
                }
            }
        </style>
    </head>


    <body class="bg-white min-h-screen flex justify-center relative lg:p-6 xl:p-6">

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

            <!-- Timer Container (Fixed Position - Outside Main Content) -->
                      

            <!-- 📝 Main Content -->
            <main class="flex-1 px-4 py-6 lg:pl-6 space-y-8 max-w-full overflow-x-hidden">


             <div class="w-full px-4 py-2 sm:px-6 sm:py-3 flex justify-end mt-4 lg:mt-0">
                <?php if ($mode === 'test'): ?>
<div class="flex flex-col justify-center items-center
             w-[124px] h-[124px] md:w-36 md:h-36 lg:w-44 lg:h-44
             rounded-full animate-pulse transition duration-300 fixed
             top-[130px] sm:top-[150px] md:top-[170px]
             right-4 sm:right-6 md:right-10 lg:right-[200px] z-50
             p-4 md:p-6 lg:p-8"
     style="background: linear-gradient(180deg, #E1E1E1 0%, #FFFFFF 100%);
            box-shadow: 0px 40.27px 80.53px rgba(51, 51, 51, 0.16),
                        inset 0px 10.65px 10.65px #FFFFFF,
                        inset 0px -10.65px 10.65px #D9D9D9;">

    <p class="text-xs text-gray-600 font-semibold mb-1 text-center">
        Total Time: <?= $totalMinutes ?> mins
    </p>

    <h3 id="timer" class="text-[#673AB7] font-bold leading-none text-[16px] md:text-xl lg:text-2xl text-center">
        00:00:00
    </h3>
</div>
<?php endif; ?>
  <div class="flex flex-wrap items-center gap-2 sm:gap-3">

    <!-- Save Button -->
    <button class="w-[180px] h-[55px] bg-purple-100 text-purple-700 rounded-[10px]
       flex items-center justify-center gap-1 text-sm font-medium
       hover:bg-purple-200 transition duration-200 border-2 border-[#673AB7]">
      <i class="fas fa-bookmark text-purple-600 text-[16px]"></i>
      <span>Save</span>
    </button>

    <!-- Icon Buttons -->
    <button class="w-[48px] h-[55px] rounded-[10px] bg-gray-100 text-gray-700 flex items-center justify-center
       hover:bg-gray-200 transition duration-200 border-2 border-[#673AB7]">
      <i class="fas fa-info text-gray-600 text-[14px]"></i>
    </button>

    <button class="w-[48px] h-[55px] rounded-[10px] bg-gray-100 text-gray-700 flex items-center justify-center
       hover:bg-gray-200 transition duration-200 border-2 border-[#673AB7]">
      <i class="fas fa-flag text-gray-600 text-[14px]"></i>
    </button>

    <button class="w-[48px] h-[55px] rounded-[10px] bg-gray-100 text-gray-700 flex items-center justify-center
       hover:bg-gray-200 transition duration-200 border-2 border-[#673AB7]">
      <i class="fas fa-share-alt text-gray-600 text-[14px]"></i>
    </button>

  </div>
</div>

                <!-- This is the new section. It's designed to be placed within a main content area. -->
                <div class="w-full max-w-4xl   rounded-lg ">

                    <!-- Top Row of Buttons (Modes/Tags) -->
                    <div class="flex flex-wrap gap-3 mb-6 font-[Manrope] font-semibold text-[16px] leading-[20px]">

                        <!-- Button 1 -->
                        <button
                            class="w-[220px] h-[56px] bg-[#B289FA] text-white rounded-[76px] hover:bg-[#A278F0] transition-colors duration-200">
                            <?= htmlspecialchars($displayName) ?>
                        </button>
                        <button
                            class="w-[180px] h-[56px] bg-blue-300 text-white rounded-[76px] hover:bg-blue-400 transition-colors duration-200">
                            <?= ucfirst($mode) ?> Mode
                        </button>
                        <button
                            class="w-[110px] h-[56px] bg-orange-400 text-white rounded-[76px] hover:bg-orange-500 transition-colors duration-200">
                            Q-<?= $currentQuestion ?>
                        </button>

                    </div>

                    <!-- Question Statement -->
                    <p id="mainQuestionStatement"
                        class="font-[Manrope] font-normal text-[20px] leading-[28px] tracking-[0] text-black mt-4 mb-6">
                        <?= $currentQuestion ?>. <?= htmlspecialchars($mcq['question']) ?>
                    </p>


                    <!-- Second Row of Buttons (Tools/Hints) -->
                    <div class="flex flex-wrap gap-3 mb-6 font-[Manrope] font-semibold text-[16px] leading-[20px]">

                        <!-- Elimination Tool Button -->
                        <button class="w-[220px] h-[56px] bg-[#B289FA] text-white rounded-[76px]
                 hover:bg-[#A278F0] transition-colors duration-200">
                            Elimination Tool
                        </button>

                        <!-- Hints Button -->
                        <button class="w-[180px] h-[56px] bg-blue-300 text-white rounded-[76px]
                 hover:bg-blue-400 transition-colors duration-200">
                            Hints
                        </button>
                        <div>

                        </div>
                    </div>


                    <!-- This is the new section, designed to be placed within a main content area. -->
                    <div class="w-full max-w-4xl   rounded-lg ">

                        <!-- Question Counter -->
                        <p id="questionCounter"
                            class="text-black text-[22px] leading-[28px] font-[Manrope] font-normal mb-4">
                            Question <?= $currentQuestion ?> of <?= $totalMcqs ?>
                        </p>


                        <!-- Options Container -->
                        <div class="space-y-4" id="optionContainer">

                            <form id="mcqForm" method="POST" action="save_answer.php">
                                <input type="hidden" name="mcq_id" value="<?= $mcq['id'] ?>">
                                <input type="hidden" name="correct_option" value="<?= $mcq['correct_option'] ?>">
                                <input type="hidden" name="topic_id" value="<?= $topicId ?>">
                                <input type="hidden" name="mode" value="<?= $mode ?>">
                                <input type="hidden" name="current_question" value="<?= $currentQuestion ?>">
                                <input type="hidden" name="general_explanation"
                                    value="<?= htmlspecialchars($mcq['explanation'] ?? '') ?>">

                                <p id="questionStatement" class="font-[Manrope] text-[20px] text-black mt-4 mb-6">
                                    <?= $currentQuestion ?>. <?= htmlspecialchars($mcq['question']) ?>
                                </p>

                                <div class="space-y-4" id="optionContainer">
                                    <?php
                                    $options = ['a', 'b', 'c', 'd'];
                                    foreach ($options as $key):
                                        $optionText = $mcq['option_' . $key];
                                        $explanation = $mcq['option_' . $key . '_explanation'];
                                        ?>
                                        <div class="option-box border-2 border-gray-300 rounded-[10px] px-6 py-4 transition-all duration-300"
                                            data-option="<?= $key ?>">
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center">
                                                    <input type="radio" name="selected_option"
                                                        class="option-radio w-[36px] h-[36px]" value="<?= $key ?>">
                                                    <span
                                                        class="ml-3 text-gray-700 text-[17px]"><?= htmlspecialchars($optionText) ?></span>
                                                </div>

                                                <?php if ($mode != 'test'): ?>
                                                    <button type="button"
                                                        class="explain-btn hidden text-white text-sm font-medium rounded-full px-6 py-2"
                                                        style="background: linear-gradient(180deg, #673AB7 0%, #2E1A51 100%);">Explanation</button>
                                                <?php endif; ?>
                                            </div>

                                            <?php if ($mode != 'test'): ?>
                                                <div
                                                    class="explanation-wrapper max-h-0 overflow-hidden transition-all duration-500">
                                                    <p class="text-sm text-gray-600 mt-4"><?= htmlspecialchars($explanation) ?></p>
                                                </div>
                                            <?php endif; ?>

                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <?php if ($mode != 'test'): ?>
                                    <div class="mt-6 p-4 border-2 border-blue-200 rounded-[10px] min-h-[100px] bg-blue-50 hidden"
                                        id="explanationDisplay">
                                    </div>
                                <?php endif; ?>

                                <div class="flex justify-end mt-6">
                                    <button type="submit"
                                        class="bg-[#673AB7] text-white px-6 py-2 rounded-full text-sm font-semibold"
                                        id="nextBtn">
                                        Next
                                    </button>
                                </div>
                            </form>
                            <!-- Next Button -->

                        <?php else: ?>
                            <p class="text-red-500 text-center">No MCQs found for this topic.</p>
                        <?php endif; ?>

                    </div>

                </div>
        </main>

    </div>

    <script>
        document.querySelectorAll('.option-radio').forEach(radio => {
            radio.addEventListener('change', function () {
                const selected = this.value;
                const correct = document.querySelector('input[name="correct_option"]').value.toLowerCase();
                const mode = '<?= $mode ?>';

                // Reset all option boxes
                document.querySelectorAll('.option-box').forEach(box => {
                    box.classList.remove('border-green-500', 'border-red-500', 'bg-green-50', 'bg-red-50');
                    const radio = box.querySelector('input[type="radio"]');
                    radio.classList.remove('radio-green-checked');
                    // Remove any existing correct labels
                    const existingLabel = box.querySelector('.correct-label');
                    if (existingLabel) {
                        existingLabel.remove();
                    }
                });

                // Only show correct answer and explanations if IN tutor mode
                if (mode === 'tutor') {
                    // Show correct answer in green
                    const correctBox = document.querySelector(`.option-box[data-option="${correct}"]`);
                    if (correctBox) {
                        correctBox.classList.add('border-green-500', 'bg-green-50');
                        // Add a visible "CORRECT" label
                        let correctLabel = correctBox.querySelector('.correct-label');
                        if (!correctLabel) {
                            correctLabel = document.createElement('span');
                            correctLabel.className = 'correct-label';
                            correctLabel.textContent = '✓ CORRECT';
                            correctLabel.style.cssText = 'color: #16A34A; font-weight: bold; margin-left: 10px;';
                            correctBox.querySelector('.flex').appendChild(correctLabel);
                        }
                        const correctRadio = correctBox.querySelector('input[type="radio"]');
                        if (correctRadio) {
                            correctRadio.classList.add('radio-green-checked');
                            correctRadio.style.borderColor = '#16A34A';
                            correctRadio.style.backgroundColor = '#16A34A';
                        }
                    }
                    // Show selected answer
                    const selectedBox = this.closest('.option-box');
                    if (selected === correct) {
                        selectedBox.classList.add('border-green-500', 'bg-green-50');
                    } else {
                        selectedBox.classList.add('border-red-500', 'bg-red-50');
                    }
                    // Show explanation button for selected option
                    const explainBtn = selectedBox.querySelector('.explain-btn');
                    if (explainBtn) {
                        explainBtn.classList.remove('hidden');
                    }
                    // Auto-show explanation for selected option
                    const explanationWrapper = selectedBox.querySelector('.explanation-wrapper');
                    if (explanationWrapper) {
                        explanationWrapper.style.maxHeight = explanationWrapper.scrollHeight + 'px';
                    }

                    // Show general explanation in the highlighted area if mode != test
                    const mode = '<?= $mode ?>';
                    if (mode !== 'test') {
                        const explanationDisplay = document.getElementById('explanationDisplay');
                        const generalExplanation = document.querySelector('input[name="general_explanation"]').value;
                        if (explanationDisplay) {
                            if (generalExplanation && generalExplanation.trim()) {
                                explanationDisplay.innerHTML = `<h4 class="font-semibold text-blue-800 mb-2">Explanation:</h4><p class="text-blue-700 text-[16px] leading-[24px]">${generalExplanation}</p>`;
                                explanationDisplay.classList.remove('hidden');
                            } else {
                                explanationDisplay.classList.add('hidden');
                            }
                        }
                    }
                }

                // Scroll to bottom when option is selected
                setTimeout(() => {
                    window.scrollTo({
                        top: document.body.scrollHeight,
                        behavior: 'smooth'
                    });
                }, 300);
            });
        });

        const explainButtons = document.querySelectorAll('.explain-btn');
        explainButtons.forEach((btn) => {
            btn.addEventListener('click', () => {
                const wrapper = btn.closest('.option-box').querySelector('.explanation-wrapper');
                const isOpen = wrapper.style.maxHeight && wrapper.style.maxHeight !== '0px';
                wrapper.style.maxHeight = isOpen ? '0px' : wrapper.scrollHeight + 'px';
            });
        });
    </script>


    <script>
        function showToast(message) {
            var toast = document.createElement('div');
            toast.textContent = message;
            toast.style.position = 'fixed';
            toast.style.top = '30px';
            toast.style.right = '30px';
            toast.style.zIndex = 9999;
            toast.style.background = '#673AB7';
            toast.style.color = '#fff';
            toast.style.padding = '12px 24px';
            toast.style.borderRadius = '8px';
            toast.style.boxShadow = '0 2px 8px rgba(0,0,0,0.15)';
            toast.style.fontSize = '16px';
            toast.style.width = '240px';
            toast.style.textAlign = 'center';
            document.body.appendChild(toast);
            setTimeout(function () {
                toast.remove();
            }, 2000);
        }

        const totalMcqs = <?= $totalMcqs ?>;
        let currentQuestion = <?= $currentQuestion ?>;
        const mcqForm = document.getElementById('mcqForm');
        const nextBtn = document.getElementById('nextBtn');

        if (mcqForm && nextBtn) {
            mcqForm.addEventListener('submit', function (e) {
                e.preventDefault();

                // Check if any option is selected
                const selectedOption = document.querySelector('input[name="selected_option"]:checked');
                if (!selectedOption) {
                    showToast('Please choose an option before proceeding');
                    return false;
                }

                // Get form data
                const formData = new FormData(mcqForm);

                // Show loading state
                nextBtn.textContent = 'Loading...';
                nextBtn.disabled = true;

                // Submit answer via AJAX
                fetch('save_answer.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.text())
                    .then(data => {
                        // Check if there are more MCQs
                        if (data.includes('no_more=1')) {
                            // No more MCQs - reset timer to 00:00 and redirect
                            resetTimerToZero();
                            showToast('No more MCQs available');
                            setTimeout(function () {
                                window.location.href = 'submission.php?topic_id=<?= $topicId ?>&mode=<?= $mode ?>';
                            }, 2000);
                        } else {
                            // More MCQs available - load next question
                            loadNextQuestion();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Error saving answer. Please try again.');
                        nextBtn.textContent = 'Next';
                        nextBtn.disabled = false;
                    });
            });
        }

        function loadNextQuestion() {
            const nextQuestion = currentQuestion + 1;
            const url = `mcqs.php?topic_id=<?= $topicId ?>&mode=<?= $mode ?>&q=${nextQuestion}`;

            // Set flag to prevent timer reset during AJAX loading
            isAjaxLoading = true;

            // Load next question via AJAX
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    // Create a temporary div to parse the HTML
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = html;

                    // Extract only the MCQ form content, not the entire main
                    const newMcqForm = tempDiv.querySelector('#mcqForm');
                    const newQuestionStatement = tempDiv.querySelector('#questionStatement');
                    const newMainQuestionStatement = tempDiv.querySelector('#mainQuestionStatement');
                    const newQuestionCounter = tempDiv.querySelector('#questionCounter');

                    if (newMcqForm) {
                        // Update only the MCQ form content
                        const currentMcqForm = document.querySelector('#mcqForm');
                        if (currentMcqForm) {
                            currentMcqForm.innerHTML = newMcqForm.innerHTML;
                        }

                        // Update question statements
                        if (newQuestionStatement) {
                            const currentQuestionStatement = document.querySelector('#questionStatement');
                            if (currentQuestionStatement) {
                                currentQuestionStatement.textContent = newQuestionStatement.textContent;
                            }
                        }

                        if (newMainQuestionStatement) {
                            const currentMainQuestionStatement = document.querySelector('#mainQuestionStatement');
                            if (currentMainQuestionStatement) {
                                currentMainQuestionStatement.textContent = newMainQuestionStatement.textContent;
                            }
                        }

                        // Update question counter
                        if (newQuestionCounter) {
                            const currentQuestionCounter = document.getElementById('questionCounter');
                            if (currentQuestionCounter) {
                                currentQuestionCounter.textContent = newQuestionCounter.textContent;
                            }
                        }

                        // Update question number button
                        const questionButton = document.querySelector('.bg-orange-400');
                        if (questionButton) {
                            questionButton.textContent = `Q-${nextQuestion}`;
                        }

                        // Update the currentQuestion variable
                        currentQuestion = nextQuestion;

                        // Reinitialize event listeners for the new content
                        initializeEventListeners();

                        // Reset form and button
                        nextBtn.textContent = 'Next';
                        nextBtn.disabled = false;

                        // Scroll to top
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                })
                .catch(error => {
                    console.error('Error loading next question:', error);
                    showToast('Error loading next question. Please refresh the page.');
                    nextBtn.textContent = 'Next';
                    nextBtn.disabled = false;
                });
        }

        function initializeEventListeners() {
            // Re-initialize option radio event listeners
            document.querySelectorAll('.option-radio').forEach(radio => {
                radio.addEventListener('change', function () {
                    const selected = this.value;
                    const correct = document.querySelector('input[name="correct_option"]').value.toLowerCase();
                    const mode = '<?= $mode ?>';

                    // Reset all option boxes
                    document.querySelectorAll('.option-box').forEach(box => {
                        box.classList.remove('border-green-500', 'border-red-500', 'bg-green-50', 'bg-red-50');
                        const radio = box.querySelector('input[type="radio"]');
                        radio.classList.remove('radio-green-checked');
                        const existingLabel = box.querySelector('.correct-label');
                        if (existingLabel) {
                            existingLabel.remove();
                        }
                    });

                    // Only show correct answer and explanations if IN tutor mode
                    if (mode === 'tutor') {
                        // Show correct answer in green
                        const correctBox = document.querySelector(`.option-box[data-option="${correct}"]`);
                        if (correctBox) {
                            correctBox.classList.add('border-green-500', 'bg-green-50');
                            let correctLabel = correctBox.querySelector('.correct-label');
                            if (!correctLabel) {
                                correctLabel = document.createElement('span');
                                correctLabel.className = 'correct-label';
                                correctLabel.textContent = '✓ CORRECT';
                                correctLabel.style.cssText = 'color: #16A34A; font-weight: bold; margin-left: 10px;';
                                correctBox.querySelector('.flex').appendChild(correctLabel);
                            }
                            const correctRadio = correctBox.querySelector('input[type="radio"]');
                            if (correctRadio) {
                                correctRadio.classList.add('radio-green-checked');
                                correctRadio.style.borderColor = '#16A34A';
                                correctRadio.style.backgroundColor = '#16A34A';
                            }
                        }

                        // Show selected answer
                        const selectedBox = this.closest('.option-box');
                        if (selected === correct) {
                            selectedBox.classList.add('border-green-500', 'bg-green-50');
                        } else {
                            selectedBox.classList.add('border-red-500', 'bg-red-50');
                        }

                        // Show explanation button for selected option
                        const explainBtn = selectedBox.querySelector('.explain-btn');
                        if (explainBtn) {
                            explainBtn.classList.remove('hidden');
                        }

                        // Auto-show explanation for selected option
                        const explanationWrapper = selectedBox.querySelector('.explanation-wrapper');
                        if (explanationWrapper) {
                            explanationWrapper.style.maxHeight = explanationWrapper.scrollHeight + 'px';
                        }

                        // Show general explanation if mode != test
                        const mode = '<?= $mode ?>';
                        if (mode !== 'test') {
                            const explanationDisplay = document.getElementById('explanationDisplay');
                            const generalExplanation = document.querySelector('input[name="general_explanation"]').value;
                            if (explanationDisplay) {
                                if (generalExplanation && generalExplanation.trim()) {
                                    explanationDisplay.innerHTML = `<h4 class="font-semibold text-blue-800 mb-2">Explanation:</h4><p class="text-blue-700 text-[16px] leading-[24px]">${generalExplanation}</p>`;
                                    explanationDisplay.classList.remove('hidden');
                                } else {
                                    explanationDisplay.classList.add('hidden');
                                }
                            }
                        }
                    }

                    // Scroll to bottom when option is selected
                    setTimeout(() => {
                        window.scrollTo({
                            top: document.body.scrollHeight,
                            behavior: 'smooth'
                        });
                    }, 300);
                });
            });

            // Re-initialize explain buttons
            document.querySelectorAll('.explain-btn').forEach((btn) => {
                btn.addEventListener('click', () => {
                    const wrapper = btn.closest('.option-box').querySelector('.explanation-wrapper');
                    const isOpen = wrapper.style.maxHeight && wrapper.style.maxHeight !== '0px';
                    wrapper.style.maxHeight = isOpen ? '0px' : wrapper.scrollHeight + 'px';
                });
            });
        }
        // Show toast if no_more=1 in URL
        if (window.location.search.includes('no_more=1')) {
            // Clear the timer when test is completed
            resetTimerToZero();

            window.addEventListener('DOMContentLoaded', function () {
                showToast('No more MCQs available');
                setTimeout(function () {
                    window.location.href = 'submission.php?topic_id=<?= $topicId ?>&mode=<?= $mode ?>';
                }, 2000);
            });
        }
    </script>


    <script>
        const totalMinutes = <?= $totalMinutes ?>;
        const totalSeconds = totalMinutes * 60;
        const display = document.getElementById('timer');
        let countdownInterval = null;

        function startCountdown(duration, display) {
            // Clear any existing countdown
            if (countdownInterval) {
                clearInterval(countdownInterval);
            }

            let timer = duration;
            const countdown = setInterval(function () {
                if (timer <= 0) {
                    clearInterval(countdown);
                    countdownInterval = null;
                    display.textContent = "Time Over";
                    localStorage.removeItem('testEndTime');

                    // Redirect to dashboard after time is over
                    window.location.href = "submission.php?topic_id=<?= $topicId ?>&mode=<?= $mode ?>";
                    return;
                }

                const hours = Math.floor(timer / 3600);
                const minutes = Math.floor((timer % 3600) / 60);
                const seconds = timer % 60;

                // Format as 2-digit
                const formattedHours = hours < 10 ? "0" + hours : hours;
                const formattedMinutes = minutes < 10 ? "0" + minutes : minutes;
                const formattedSeconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = formattedHours + ":" + formattedMinutes + ":" + formattedSeconds;
                timer--;
            }, 1000);

            countdownInterval = countdown;
        }

        function stopCountdown() {
            if (countdownInterval) {
                clearInterval(countdownInterval);
                countdownInterval = null;
            }
        }

        function resetTimerToZero() {
            stopCountdown();
            if (display) {
                display.textContent = "00:00:00";
            }
            localStorage.removeItem('testEndTime');
        }

        // Clear timer when page is unloaded to prevent memory leaks
        window.addEventListener('beforeunload', function () {
            stopCountdown();
        });



        // Flag to prevent timer reset during AJAX content loading
        let isAjaxLoading = false;

        window.onload = function () {
            // Only run timer logic if display element exists (test mode)
            if (!display) return;

            // Don't reset timer if we're just loading content via AJAX
            if (isAjaxLoading) {
                isAjaxLoading = false;
                return;
            }

            // Always start fresh timer on page load (not AJAX)
            const currentTime = Date.now();

            // Calculate time based on total questions (1 minute per question)
            const timePerQuestion = 60; // 1 minute per question
            const totalTimeForAllQuestions = totalMcqs * timePerQuestion;
            const endTime = currentTime + (totalTimeForAllQuestions * 1000);

            // Store timer state for AJAX navigation
            localStorage.setItem('testEndTime', endTime);

            // Start the countdown immediately
            startCountdown(totalTimeForAllQuestions, display);
        };
    </script>
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
                            parentDropdownTrigger = document.querySelector(
                                `[data-dropdown-target="${dropdown.id}"]`);
                        }
                    });

                    if (parentDropdownTrigger) {
                        parentDropdownTrigger.classList.add('bg-[#673AB7]', 'active-menu');
                        parentDropdownTrigger.querySelector('.icon').style.filter = 'invert(100%)';
                        parentDropdownTrigger.querySelector('span').classList.add('text-white');

                        const targetDropdown = document.getElementById(parentDropdownTrigger.dataset
                            .dropdownTarget);
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
                        const otherTrigger = document.querySelector(
                            `[data-dropdown-target="${dropdown.id}"]`);
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
                        const isHomePage = (linkPath === 'Sidebar.html' && (currentPath === '' ||
                            currentPath === 'Sidebar.html' || currentPath === 'index.html'));
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
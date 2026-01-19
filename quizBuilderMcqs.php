<?php 
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$quizId = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;
$currentQuestion = isset($_GET['q']) ? intval($_GET['q']) : 1;

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

// Fetch total questions for this quiz
$totalQuery = $pdo->prepare("SELECT COUNT(*) FROM quiz_questions WHERE quiz_id = ?");
$totalQuery->execute([$quizId]);
$totalMcqs = $totalQuery->fetchColumn();

// Fetch current question with MCQ details
$questionQuery = "
    SELECT qq.question_order, m.*, t.name as topic_name
    FROM quiz_questions qq
    JOIN mcqs m ON qq.mcq_id = m.id
    LEFT JOIN topics t ON m.topic_id = t.id
    WHERE qq.quiz_id = ? AND qq.question_order = ?
";
$questionStmt = $pdo->prepare($questionQuery);
$questionStmt->execute([$quizId, $currentQuestion]);
$mcq = $questionStmt->fetch(PDO::FETCH_ASSOC);

// Check if question exists
if (!$mcq) {
    $debugQuery = "SELECT qq.question_order, m.id as mcq_id FROM quiz_questions qq JOIN mcqs m ON qq.mcq_id = m.id WHERE qq.quiz_id = ? ORDER BY qq.question_order";
    $debugStmt = $pdo->prepare($debugQuery);
    $debugStmt->execute([$quizId]);
    $existingQuestions = $debugStmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($existingQuestions)) {
        header("Location: quizBuilderSubmission.php?quiz_id=$quizId");
        exit();
    }
    
    if (!empty($existingQuestions)) {
        $firstQuestion = $existingQuestions[0]['question_order'];
        header("Location: quizBuilderMcqs.php?quiz_id=$quizId&q=$firstQuestion");
        exit();
    }
    
    header("Location: quizBuilderSubmission.php?quiz_id=$quizId");
    exit();
}

// Time calculation for test mode (convert estimated_time to minutes)
$timeParts = explode(':', $quiz['estimated_time']);
$totalMinutes = ($timeParts[0] * 60 + $timeParts[1]) / 60; // Convert to minutes

// Check if user has already answered this question
$answerQuery = "SELECT selected_answer FROM quiz_answers WHERE quiz_id = ? AND user_id = ? AND question_index = ?";
$answerStmt = $pdo->prepare($answerQuery);
$answerStmt->execute([$quizId, $userId, $currentQuestion - 1]);
$existingAnswer = $answerStmt->fetchColumn();

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
    <link rel="stylesheet" href="assets/css/loader.css">
            <link rel="stylesheet" href="assets/css/mouse.css">

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
    
    /* Disabled button styling */
    button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        background-color: #9CA3AF !important;
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


            <div class="w-full px-4 py-2 sm:px-6 sm:py-3 flex justify-end">
                <div class="flex flex-wrap items-center gap-2 sm:gap-3">

                    <!-- Save Button (Smaller, Compact) -->
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

            <hr class="w-75 m-auto" style="border-color: rgb(146, 137, 137);">
            <!-- This is the new section. It's designed to be placed within a main content area. -->
            <div class="w-full max-w-4xl   rounded-lg ">

                <!-- Top Row of Buttons (Modes/Tags) -->
                <div class="flex flex-wrap gap-3 mb-6 font-[Manrope] font-semibold text-[16px] leading-[20px]">

                    <!-- Button 1 -->
                    <button
                        class="w-[220px] h-[56px] bg-[#B289FA] text-white rounded-[76px] hover:bg-[#A278F0] transition-colors duration-200">
                        <?= htmlspecialchars($quiz['deck_name']) ?>
                    </button>
                    <button
                        class="w-[180px] h-[56px] bg-blue-300 text-white rounded-[76px] hover:bg-blue-400 transition-colors duration-200">
                        Custom Quiz
                    </button>
                    <button id="questionButton"
                        class="w-[110px] h-[56px] bg-orange-400 text-white rounded-[76px] hover:bg-orange-500 transition-colors duration-200">
                        Q-<?= $currentQuestion ?>
                    </button>

                </div>

                <!-- Timer Display -->
                <div class="flex-shrink-0 flex flex-col justify-center items-center w-[124px] h-[124px] md:w-36 md:h-36 lg:w-44 lg:h-44 rounded-full animate-pulse transition duration-300"
                    style="position: absolute; top:130px; right:200px; background: linear-gradient(180deg, #E1E1E1 0%, #FFFFFF 100%);
              box-shadow: 0px 40.27px 80.53px 0px #3333332A,
                          inset 0px 10.65px 10.65px 0px #FFFFFF,
                          inset 0px -10.65px 10.65px 0px #D9D9D9;">

                    <p class="text-xs text-gray-600 font-semibold mb-1">
                        Total Time: <?= $totalMinutes ?> mins
                    </p>

                    <h3 id="timer" class="text-[#673AB7] font-bold leading-none text-[16px] md:text-xl lg:text-2xl">
                        00:00:00
                    </h3>
                </div>
                <!-- Question Statement -->
                <p id="questionText1" class="font-[Manrope] font-normal text-[20px] leading-[28px] tracking-[0] text-black mt-4 mb-6">
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
                <div class="w-full max-w-4xl  p-6 rounded-lg ">

                    <!-- Question Counter -->
                    <p id="questionCounter" class="text-black text-[22px] leading-[28px] font-[Manrope] font-normal mb-4">
                        Question <?= $currentQuestion ?> of <?= $totalMcqs ?>
                    </p>


                    <!-- Options Container -->
                    <div class="space-y-4" id="optionContainer">

                        <form id="mcqForm" method="POST">
                            <input type="hidden" name="quiz_id" value="<?= $quizId ?>">
                            <input type="hidden" name="question_index" value="<?= $currentQuestion - 1 ?>">
                            <input type="hidden" name="correct_option" value="<?= $mcq['correct_option'] ?>">
                            <input type="hidden" name="current_question" value="<?= $currentQuestion ?>">
                            <input type="hidden" name="total_questions" value="<?= $totalMcqs ?>">

                            <p id="questionText2" class="font-[Manrope] text-[20px] text-black mt-4 mb-6">
                                <?= $currentQuestion ?>. <?= htmlspecialchars($mcq['question']) ?>
                            </p>

                            <div class="space-y-4" id="optionContainer">
                                <?php
                                $options = ['a', 'b', 'c', 'd'];
                                foreach ($options as $key):
                                  $optionText = $mcq['option_' . $key];
                                ?>
                                <div class="option-box border-2 border-gray-300 rounded-[10px] px-6 py-4 transition-all duration-300"
                                    data-option="<?= $key ?>">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center">
                                            <input type="radio" name="selected_option"
                                                class="option-radio w-[36px] h-[36px]" value="<?= $key ?>"
                                                <?= ($existingAnswer === $key) ? 'checked' : '' ?>>
                                            <span
                                                class="ml-3 text-gray-700 text-[17px]"><?= htmlspecialchars($optionText) ?></span>
                                        </div>


                                    </div>



                                </div>
                                <?php endforeach; ?>
                            </div>



                            <div class="flex justify-end mt-6">
                                <button type="submit"
                                    class="bg-[#673AB7] text-white px-6 py-2 rounded-full text-sm font-semibold" id="nextBtn">
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



    <script src="js/Quizbuilder.js"></script>
    <script src="assets/js/loader.js"></script>
    <script src="assets/js/mouse.js"></script>
    <script>
    document.querySelectorAll('.option-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            // Simple selection - no highlighting or explanations
            // Just ensure the radio button is selected
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
    setTimeout(function() {
    toast.remove();
    }, 2000);
    }

    const totalMcqs = <?= $totalMcqs ?>;
    let currentQuestion = <?= $currentQuestion ?>;
    const quizId = <?= $quizId ?>;
    const mcqForm = document.getElementById('mcqForm');
    const nextBtn = document.getElementById('nextBtn');
    
    if (mcqForm && nextBtn) {
        mcqForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const selectedAnswer = document.querySelector('input[name="selected_option"]:checked');
            if (!selectedAnswer) {
                showToast('Please select an answer before proceeding!');
                return;
            }
            
            // Disable the button to prevent double submission
            nextBtn.disabled = true;
            nextBtn.textContent = 'Saving...';
            
            // Scroll to bottom of page
            window.scrollTo({
                top: document.body.scrollHeight,
                behavior: 'smooth'
            });
            
            // Save answer via AJAX
            const formData = new FormData();
            formData.append('quiz_id', quizId);
            formData.append('question_index', currentQuestion - 1);
            formData.append('answer', selectedAnswer.value);
            
            fetch('save_quiz_answer.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showToast('Answer saved successfully!');
                    
                    // Load next question without page reload
                    setTimeout(() => {
                        loadNextQuestion();
                    }, 800);
                    } else {
                    showToast('Error saving answer: ' + (data.error || 'Unknown error'));
                    // Re-enable button on error
                    nextBtn.disabled = false;
                    nextBtn.textContent = 'Next';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error saving answer. Please try again.');
                // Re-enable button on error
                nextBtn.disabled = false;
                nextBtn.textContent = 'Next';
            });
        });
    }

    // Function to load next question dynamically
    function loadNextQuestion() {
        const nextQuestionNumber = currentQuestion + 1;
        
        fetch(`get_next_question.php?quiz_id=${quizId}&q=${nextQuestionNumber}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.completed) {
                // All questions completed, redirect to submission page
                showToast('Quiz completed! Redirecting to results...');
                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 1000);
            } else if (data.success) {
                // Update the current question number
                currentQuestion = data.current_question;
                
                // Update the page content with new question
                updateQuestionContent(data);
                
                // Re-enable the button
                nextBtn.disabled = false;
                nextBtn.textContent = 'Next';
                
                // Update URL without page reload
                const newUrl = `quizBuilderMcqs.php?quiz_id=${quizId}&q=${currentQuestion}`;
                history.pushState({}, '', newUrl);
                
                // Keep the page scrolled to bottom after loading new question
                window.scrollTo({
                    top: document.body.scrollHeight,
                    behavior: 'smooth'
                });
                } else {
                showToast('Error loading next question: ' + (data.error || 'Unknown error'));
                nextBtn.disabled = false;
                nextBtn.textContent = 'Next';
                }
            })
            .catch(error => {
            console.error('Error loading next question:', error);
            showToast('Error loading next question. Please refresh the page.');
            nextBtn.disabled = false;
            nextBtn.textContent = 'Next';
        });
    }

    // Function to update question content on the page
    function updateQuestionContent(data) {
        const question = data.question;
        console.log('Updating question content:', data);
        
        // Update question counter using ID
        const questionCounter = document.getElementById('questionCounter');
        if (questionCounter) {
            questionCounter.textContent = `Question ${data.current_question} of ${data.total_questions}`;
            console.log('Updated question counter');
        } else {
            console.log('Question counter element not found');
        }
        
        // Update question button using ID
        const qButton = document.getElementById('questionButton');
        if (qButton) {
            qButton.textContent = `Q-${data.current_question}`;
            console.log('Updated question button');
        } else {
            console.log('Question button element not found');
        }
        
        // Update the first question text using ID
        const questionText1 = document.getElementById('questionText1');
        if (questionText1) {
            questionText1.textContent = `${data.current_question}. ${question.question}`;
            console.log('Updated question text 1');
        } else {
            console.log('Question text 1 element not found');
        }
        
        // Update the second question text using ID
        const questionText2 = document.getElementById('questionText2');
        if (questionText2) {
            questionText2.textContent = `${data.current_question}. ${question.question}`;
            console.log('Updated question text 2');
        } else {
            console.log('Question text 2 element not found');
        }
        
        // Update form hidden inputs
        document.querySelector('input[name="question_index"]').value = data.current_question - 1;
        document.querySelector('input[name="correct_option"]').value = question.correct_option;
        document.querySelector('input[name="current_question"]').value = data.current_question;
        document.querySelector('input[name="total_questions"]').value = data.total_questions;
        
        // Update options
        const options = ['a', 'b', 'c', 'd'];
        const optionBoxes = document.querySelectorAll('.option-box');
        
        console.log('Found option boxes:', optionBoxes.length);
        
        optionBoxes.forEach((box, index) => {
            const optionKey = options[index];
            const radio = box.querySelector('input[type="radio"]');
            const span = box.querySelector('span');
            
            if (radio && span) {
                // Update option text
                const optionText = question[`option_${optionKey}`];
                span.textContent = optionText;
                console.log(`Updated option ${optionKey}:`, optionText);
                
                // Clear previous selection
                radio.checked = false;
                
                // Check if this option was previously selected
                if (data.existing_answer === optionKey) {
                    radio.checked = true;
                    console.log(`Pre-selected option ${optionKey}`);
                }
                
                // Update radio button value
                radio.value = optionKey;
            } else {
                console.log('Radio or span not found for option', optionKey);
            }
        });
        
        // Reset option box styling
        optionBoxes.forEach(box => {
            box.classList.remove('border-blue-500', 'bg-blue-50');
            box.classList.add('border-gray-300');
        });
        
        console.log('Content update completed');
    }
    </script>


    <script>
    const totalMinutes = <?= $totalMinutes ?>;
    const totalSeconds = totalMinutes * 60;
    const display = document.getElementById('timer');

    function startCountdown(duration, display) {
        let timer = duration,
            hours, minutes, seconds;

        const countdown = setInterval(function() {
            if (timer <= 0) {
                clearInterval(countdown);
                display.textContent = "Time Over";
                localStorage.removeItem('testEndTime');

                // Redirect to quiz submission after time is over
                window.location.href = "quizBuilderSubmission.php?quiz_id=<?= $quizId ?>";
                return;
            }

            hours = Math.floor(timer / 3600);
            minutes = Math.floor((timer % 3600) / 60);
            seconds = timer % 60;

            // Format as 2-digit
            hours = hours < 10 ? "0" + hours : hours;
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.textContent = hours + ":" + minutes + ":" + seconds;
            timer--;
        }, 1000);
    }

    window.onload = function() {
        // Only run timer logic if display element exists (test mode)
        if (!display) return;
        
        const currentQuizId = <?= $quizId ?>;
        const storedQuizId = localStorage.getItem('currentQuizId');
        
        if (storedQuizId !== currentQuizId.toString()) {
            localStorage.removeItem('testEndTime');
            localStorage.setItem('currentQuizId', currentQuizId);
        }
        
        let endTime = localStorage.getItem('testEndTime');

        if (!endTime) {
            // Set a new end time if not already set
            endTime = Date.now() + totalSeconds * 1000;
            localStorage.setItem('testEndTime', endTime);
        }

        const remaining = Math.floor((endTime - Date.now()) / 1000);

        if (remaining > 0) {
            startCountdown(remaining, display);
        } else {
            display.textContent = "Time Over";
            localStorage.removeItem('testEndTime');
            localStorage.removeItem('currentQuizId');
            window.location.href = "quizBuilderSubmission.php?quiz_id=<?= $quizId ?>";
        }
    };
    </script>
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
<?php
require 'config.php';
require_once 'auth_check_mcq.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['mcq_file']) && $_FILES['mcq_file']['error'] === UPLOAD_ERR_OK) {
    $topic_id = $_POST['topic_id'] ?? null;
    if (!$topic_id) {
        die(json_encode(['success' => false, 'message' => 'Please select a topic']));
    }

    $file = $_FILES['mcq_file'];
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($fileExtension, ['csv'])) {
        die(json_encode(['success' => false, 'message' => 'Invalid file format. Please upload CSV file']));
    }

    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $fileName = uniqid() . '_' . $file['name'];
    $filePath = $uploadDir . $fileName;
    
    if (!move_uploaded_file($file['tmp_name'], $filePath)) {
        die(json_encode(['success' => false, 'message' => 'Failed to upload file']));
    }

    try {
        $data = [];
        if ($fileExtension === 'csv') {
            $handle = fopen($filePath, 'r');
            if ($handle !== false) {
                $header = fgetcsv($handle);
                while (($row = fgetcsv($handle)) !== false) {
                    $data[] = array_combine($header, $row);
                }
                fclose($handle);
            }
        } else {
            $handle = fopen($filePath, 'r');
            if ($handle !== false) {
                $header = fgetcsv($handle);
                while (($row = fgetcsv($handle)) !== false) {
                    if (!empty(array_filter($row))) {
                        $data[] = array_combine($header, $row);
                    }
                }
                fclose($handle);
            }
        }
        
        unlink($filePath);
        
        if (empty($data)) {
            die(json_encode(['success' => false, 'message' => 'No data found in file']));
        }

        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM mcqs WHERE question = ? AND topic_id = ?");
        $insertStmt = $pdo->prepare("
            INSERT INTO mcqs (
                question, option_a, option_b, option_c, option_d,
                correct_option, topic_id, explanation,
                option_a_explanation, option_b_explanation,
                option_c_explanation, option_d_explanation
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $successCount = 0;
        $duplicateCount = 0;
        $totalCount = count($data);
        
        foreach ($data as $index => $row) {
            $question = $row['question'] ?? '';
            $option_a = $row['option_a'] ?? '';
            $option_b = $row['option_b'] ?? '';
            $option_c = $row['option_c'] ?? '';
            $option_d = $row['option_d'] ?? '';
            $correct_option = $row['correct_option'] ?? '';
            $explanation = $row['explanation'] ?? '';
            $option_a_explanation = $row['option_a_explanation'] ?? '';
            $option_b_explanation = $row['option_b_explanation'] ?? '';
            $option_c_explanation = $row['option_c_explanation'] ?? '';
            $option_d_explanation = $row['option_d_explanation'] ?? '';

            if (!empty($question) && !empty($option_a) && !empty($option_b) && !empty($option_c) && !empty($option_d) && !empty($correct_option)) {
                $checkStmt->execute([$question, $topic_id]);
                $exists = $checkStmt->fetchColumn();
                
                if ($exists == 0) {
                    $insertStmt->execute([
                        $question, $option_a, $option_b, $option_c, $option_d,
                        $correct_option, $topic_id, $explanation,
                        $option_a_explanation, $option_b_explanation,
                        $option_c_explanation, $option_d_explanation
                    ]);
                    $successCount++;
                } else {
                    $duplicateCount++;
                }
            }
        }
        
        $message = "Successfully imported {$successCount} MCQs";
        if ($duplicateCount > 0) {
            $message .= " ({$duplicateCount} duplicates skipped)";
        }
        
        echo json_encode([
            'success' => true,
            'message' => $message,
            'imported' => $successCount,
            'duplicates' => $duplicateCount
        ]);
        exit;
        
    } catch (Exception $e) {
        unlink($filePath);
        die(json_encode(['success' => false, 'message' => 'Error processing file: ' . $e->getMessage()]));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (is_array($_POST['question'])) {
        $questions = $_POST['question'];
        $option_as = $_POST['option_a'];
        $option_bs = $_POST['option_b'];
        $option_cs = $_POST['option_c'];
        $option_ds = $_POST['option_d'];
        $correct_options = $_POST['correct_option'];
        $topic_ids = $_POST['topic_id'];
        $explanations = $_POST['explanation'] ?? [];
        $option_a_expls = $_POST['option_a_explanation'] ?? [];
        $option_b_expls = $_POST['option_b_explanation'] ?? [];
        $option_c_expls = $_POST['option_c_explanation'] ?? [];
        $option_d_expls = $_POST['option_d_explanation'] ?? [];

        $stmt = $pdo->prepare("
            INSERT INTO mcqs (
                question, option_a, option_b, option_c, option_d,
                correct_option, topic_id,
                explanation,
                option_a_explanation, option_b_explanation,
                option_c_explanation, option_d_explanation
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $successCount = 0;
        for ($i = 0; $i < count($questions); $i++) {
            if (!empty($questions[$i]) && !empty($topic_ids[$i])) {
                $stmt->execute([
                    $questions[$i],
                    $option_as[$i],
                    $option_bs[$i],
                    $option_cs[$i],
                    $option_ds[$i],
                    $correct_options[$i],
                    $topic_ids[$i],
                    $explanations[$i] ?? null,
                    $option_a_expls[$i] ?? null,
                    $option_b_expls[$i] ?? null,
                    $option_c_expls[$i] ?? null,
                    $option_d_expls[$i] ?? null
                ]);
                $successCount++;
            }
        }

        } else {
            $question = $_POST['question'];
            $option_a = $_POST['option_a'];
            $option_b = $_POST['option_b'];
            $option_c = $_POST['option_c'];
            $option_d = $_POST['option_d'];
            $correct_option = $_POST['correct_option'];
            $topic_id = $_POST['topic_id'];
            $explanation = $_POST['explanation'];

            $option_a_expl = $_POST['option_a_explanation'] ?? null;
            $option_b_expl = $_POST['option_b_explanation'] ?? null;
            $option_c_expl = $_POST['option_c_explanation'] ?? null;
            $option_d_expl = $_POST['option_d_explanation'] ?? null;

        $stmt = $pdo->prepare("
            INSERT INTO mcqs (
                question, option_a, option_b, option_c, option_d,
                correct_option, topic_id,
                explanation,
                option_a_explanation, option_b_explanation,
                option_c_explanation, option_d_explanation
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $question,
            $option_a,
            $option_b,
            $option_c,
            $option_d,
            $correct_option,
            $topic_id,
            $explanation,
            $option_a_expl,
            $option_b_expl,
            $option_c_expl,
            $option_d_expl
        ]);

    }
}

$sql = "SELECT 
            t.id AS topic_id,
            t.name AS topic_name,
            s.id AS subject_id,
            s.name AS subject_name,
            c.id AS category_id,
            c.name AS category_name,
            mt.id AS mcq_type_id,
            mt.name AS mcq_type_name
            FROM topics t
            JOIN subjects s ON t.subject_id = s.id
            JOIN mcq_categories c ON s.category_id = c.id
            JOIN mcq_types mt ON c.mcq_type_id = mt.id
            ORDER BY mt.name, c.name, s.name, t.name";

$topics_hierarchy = $pdo->query($sql)->fetchAll();
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/full-calendar.css">
    <link rel="stylesheet" href="assets/css/jquery-ui.css">
    <link rel="stylesheet" href="assets/css/editor-quill.css">
    <link rel="stylesheet" href="assets/css/apexcharts.css">
    <link rel="stylesheet" href="assets/css/calendar.css">
    <link rel="stylesheet" href="assets/css/jquery-jvectormap-2.0.5.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <style>
        .dashboard-body {
            overflow-x: hidden;
        }

        .row {
            margin-left: 0;
            margin-right: 0;
        }

        .col-md-6,
        .col-lg-3,
        .col-md-12 {
            padding-left: 10px;
            padding-right: 10px;
        }

        .form-control,
        .form-select {
            max-width: 100%;
        }
        .selection{
            width: 100% !important;
        }
        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
            padding-left: 12px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
        .select2-container--default.select2-container--disabled .select2-selection--single {
            background-color: #e9ecef;
            opacity: 1;
        }
        .select2-dropdown {
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }
        .select2 select2-container select2-container--default select2-container--focus{
            width: 100% !important;
        }
    </style>
</head>

<body>
    <div class="preloader">
        <div class="loader"></div>
    </div>
    <div class="side-overlay"></div>

    <?php include "sidebar.php" ?>

    <div class="dashboard-main-wrapper">
        <?php include "Includes/Header.php" ?>
        <div class="dashboard-body p-4 bg-light rounded shadow-sm">
            <h4 class="mb-4 text-primary">Add New MCQ</h4>
            <form method="POST" class="row g-3" enctype="multipart/form-data">
                <div class="col-md-12">
                    <label class="form-label">Select Topic</label>
                    <select name="topic_id" class="form-select">
                        <option value="">Select Topic</option>
                        <?php foreach ($topics_hierarchy as $item): ?>
                            <option value="<?= $item['topic_id'] ?>">
                                <?= htmlspecialchars($item['mcq_type_name']) ?> →
                                <?= htmlspecialchars($item['category_name']) ?> →
                                <?= htmlspecialchars($item['subject_name']) ?> →
                                <?= htmlspecialchars($item['topic_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Import MCQs from CSV File</label>
                    <input type="file" name="mcq_file" id="mcq_file" class="form-control" accept=".csv">
                    <small class="text-muted">Upload CSV file with MCQ data</small>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Question</label>
                    <input type="text" name="question" class="form-control" placeholder="Enter question">
                </div>

                <div class="col-md-6 col-lg-3">
                    <input type="text" name="option_a" class="form-control" placeholder="Option A">
                    <textarea name="option_a_explanation" class="form-control mt-2" rows="2" placeholder="Option A Explanation (optional)"></textarea>
                </div>
                <div class="col-md-6 col-lg-3">
                    <input type="text" name="option_b" class="form-control" placeholder="Option B">
                    <textarea name="option_b_explanation" class="form-control mt-2" rows="2" placeholder="Option B Explanation (optional)"></textarea>
                </div>
                <div class="col-md-6 col-lg-3">
                    <input type="text" name="option_c" class="form-control" placeholder="Option C">
                    <textarea name="option_c_explanation" class="form-control mt-2" rows="2" placeholder="Option C Explanation (optional)"></textarea>
                </div>
                <div class="col-md-6 col-lg-3">
                    <input type="text" name="option_d" class="form-control" placeholder="Option D">
                    <textarea name="option_d_explanation" class="form-control mt-2" rows="2" placeholder="Option D Explanation (optional)"></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Correct Option</label>
                    <select name="correct_option" class="form-select">
                        <option value="">Select Correct Option</option>
                        <option value="A">Option A</option>
                        <option value="B">Option B</option>
                        <option value="C">Option C</option>
                        <option value="D">Option D</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Explanation (optional)</label>
                    <textarea name="explanation" class="form-control" rows="3" placeholder="Provide explanation if needed..."></textarea>
                </div>

                <div class="col-12 text-end" id="firstFormButtons">
                    <button type="button" id="addMoreBtn" class="btn btn-secondary px-4 me-2">Add More MCQ</button>
                    <button type="submit" class="btn btn-primary px-4" id="submitBtn">Store MCQ</button>
                </div>

                <div id="importProgress" class="col-12" style="display: none;">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Importing MCQs...</h6>
                            <div class="progress mb-3">
                                <div class="progress-bar" role="progressbar" style="width: 0%" id="progressBar"></div>
                            </div>
                            <div id="progressText">Preparing import...</div>
                        </div>
                    </div>
                </div>
            </form>

            <div id="mcqFormTemplate" style="display: none;">
                <form method="POST" class="row g-3 mt-4 border-top pt-4">
                    <h5 class="col-12 text-primary">Additional MCQ</h5>

                    <div class="col-md-12">
                        <label class="form-label">Select Topic</label>
                        <select name="topic_id" class="form-select template-select">
                            <option value="">Select Topic</option>
                            <?php foreach ($topics_hierarchy as $item): ?>
                                <option value="<?= $item['topic_id'] ?>">
                                    <?= htmlspecialchars($item['mcq_type_name']) ?> →
                                    <?= htmlspecialchars($item['category_name']) ?> →
                                    <?= htmlspecialchars($item['subject_name']) ?> →
                                    <?= htmlspecialchars($item['topic_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Question</label>
                        <input type="text" name="question" class="form-control" placeholder="Enter question">
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <input type="text" name="option_a" class="form-control" placeholder="Option A">
                        <textarea name="option_a_explanation" class="form-control mt-2" rows="2" placeholder="Option A Explanation (optional)"></textarea>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <input type="text" name="option_b" class="form-control" placeholder="Option B">
                        <textarea name="option_b_explanation" class="form-control mt-2" rows="2" placeholder="Option B Explanation (optional)"></textarea>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <input type="text" name="option_c" class="form-control" placeholder="Option C">
                        <textarea name="option_c_explanation" class="form-control mt-2" rows="2" placeholder="Option C Explanation (optional)"></textarea>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <input type="text" name="option_d" class="form-control" placeholder="Option D">
                        <textarea name="option_d_explanation" class="form-control mt-2" rows="2" placeholder="Option D Explanation (optional)"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Correct Option</label>
                        <select name="correct_option" class="form-select template-select">
                            <option value="">Select Correct Option</option>
                            <option value="A">Option A</option>
                            <option value="B">Option B</option>
                            <option value="C">Option C</option>
                            <option value="D">Option D</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Explanation (optional)</label>
                        <textarea name="explanation" class="form-control" rows="3" placeholder="Provide explanation if needed..."></textarea>
                    </div>

                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-danger px-4 me-2 remove-form-btn">Remove Form</button>
                        <button type="button" class="btn btn-secondary px-4 me-2 add-more-btn">Add More MCQ</button>
                        <button type="submit" class="btn btn-primary px-4">Store MCQ</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script src="assets/js/boostrap.bundle.min.js"></script>
    <script src="assets/js/phosphor-icon.js"></script>
    <script src="assets/js/file-upload.js"></script>
    <script src="assets/js/plyr.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/full-calendar.js"></script>
    <script src="assets/js/jquery-ui.js"></script>
    <script src="assets/js/editor-quill.js"></script>
    <script src="assets/js/apexcharts.min.js"></script>
    <script src="assets/js/calendar.js"></script>
    <script src="assets/js/jquery-jvectormap-2.0.5.min.js"></script>
    <script src="assets/js/jquery-jvectormap-world-mill-en.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        $(document).ready(function() {
            $('.form-select:not(.template-select)').select2({
                placeholder: 'Select an option',
                allowClear: true,
                width: '100%'
            });

            $('#mcq_file').change(function() {
                const file = this.files[0];
                if (file) {
                    $('#submitBtn').text('Import MCQs').removeClass('btn-primary').addClass('btn-success');
                } else {
                    $('#submitBtn').text('Store MCQ').removeClass('btn-success').addClass('btn-primary');
                }
            });
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            let formCounter = 1;
            document.addEventListener('click', function(e) {
                if (e.target.id === 'addMoreBtn' || e.target.classList.contains('add-more-btn')) {
                    addMoreMCQForm(e.target);
                }
                if (e.target.classList.contains('remove-form-btn')) {
                    removeForm(e.target);
                }
            });
            document.addEventListener('submit', function(e) {
                if (e.target.tagName === 'FORM') {
                    e.preventDefault();
                    const fileInput = document.getElementById('mcq_file');
                    if (fileInput && fileInput.files.length > 0) {
                        importMCQs(e.target);
                    } else {
                        const questionInput = e.target.querySelector('input[name="question"]');
                        const topicSelect = e.target.querySelector('select[name="topic_id"]');
                        if (questionInput && questionInput.value.trim() && topicSelect && topicSelect.value) {
                            submitAllForms();
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Required Fields',
                                text: 'Please fill in the question and select a topic, or upload a CSV file.',
                                confirmButtonColor: '#3085d6'
                            });
                        }
                    }
                }
            });

            function addMoreMCQForm(clickedButton) {

                const template = document.getElementById('mcqFormTemplate');

                const newForm = template.cloneNode(true);
                newForm.style.display = 'block';
                newForm.id = 'mcqForm_' + formCounter;
                
                $(newForm).find('.form-select').each(function() {
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2('destroy');
                    }
                });

                const sourceForm = clickedButton.closest('form');

                const newFormElements = newForm.querySelector('form');

                const topicSelect = sourceForm.querySelector('select[name="topic_id"]');
                const questionInput = sourceForm.querySelector('input[name="question"]');
                const optionAInput = sourceForm.querySelector('input[name="option_a"]');
                const optionBInput = sourceForm.querySelector('input[name="option_b"]');
                const optionCInput = sourceForm.querySelector('input[name="option_c"]');
                const optionDInput = sourceForm.querySelector('input[name="option_d"]');
                const correctSelect = sourceForm.querySelector('select[name="correct_option"]');
                const explanationTextarea = sourceForm.querySelector('textarea[name="explanation"]');
                const optionAExplTextarea = sourceForm.querySelector('textarea[name="option_a_explanation"]');
                const optionBExplTextarea = sourceForm.querySelector('textarea[name="option_b_explanation"]');
                const optionCExplTextarea = sourceForm.querySelector('textarea[name="option_c_explanation"]');
                const optionDExplTextarea = sourceForm.querySelector('textarea[name="option_d_explanation"]');

                const newTopicSelect = newFormElements.querySelector('select[name="topic_id"]');
                const newQuestionInput = newFormElements.querySelector('input[name="question"]');
                const newOptionAInput = newFormElements.querySelector('input[name="option_a"]');
                const newOptionBInput = newFormElements.querySelector('input[name="option_b"]');
                const newOptionCInput = newFormElements.querySelector('input[name="option_c"]');
                const newOptionDInput = newFormElements.querySelector('input[name="option_d"]');
                const newCorrectSelect = newFormElements.querySelector('select[name="correct_option"]');
                const newExplanationTextarea = newFormElements.querySelector('textarea[name="explanation"]');
                const newOptionAExplTextarea = newFormElements.querySelector('textarea[name="option_a_explanation"]');
                const newOptionBExplTextarea = newFormElements.querySelector('textarea[name="option_b_explanation"]');
                const newOptionCExplTextarea = newFormElements.querySelector('textarea[name="option_c_explanation"]');
                const newOptionDExplTextarea = newFormElements.querySelector('textarea[name="option_d_explanation"]');

                if (topicSelect && newTopicSelect) newTopicSelect.value = topicSelect.value;
                if (questionInput && newQuestionInput) newQuestionInput.value = questionInput.value;
                if (optionAInput && newOptionAInput) newOptionAInput.value = optionAInput.value;
                if (optionBInput && newOptionBInput) newOptionBInput.value = optionBInput.value;
                if (optionCInput && newOptionCInput) newOptionCInput.value = optionCInput.value;
                if (optionDInput && newOptionDInput) newOptionDInput.value = optionDInput.value;
                if (correctSelect && newCorrectSelect) newCorrectSelect.value = correctSelect.value;
                if (explanationTextarea && newExplanationTextarea) newExplanationTextarea.value = explanationTextarea.value;
                if (optionAExplTextarea && newOptionAExplTextarea) newOptionAExplTextarea.value = optionAExplTextarea.value;
                if (optionBExplTextarea && newOptionBExplTextarea) newOptionBExplTextarea.value = optionBExplTextarea.value;
                if (optionCExplTextarea && newOptionCExplTextarea) newOptionCExplTextarea.value = optionCExplTextarea.value;
                if (optionDExplTextarea && newOptionDExplTextarea) newOptionDExplTextarea.value = optionDExplTextarea.value;

                if (newQuestionInput) newQuestionInput.value = '';
                const container = document.querySelector('.dashboard-body');
                if (container) {
                    container.appendChild(newForm);
                    
                    setTimeout(() => {
                        initializeSelect2(newForm);
                    }, 100);
                    
                    updateButtonVisibility();
                }

                formCounter++;

                newForm.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }

            function hideFirstFormButtons() {
                const firstFormButtons = document.getElementById('firstFormButtons');
                if (firstFormButtons) {
                    firstFormButtons.style.display = 'none';
                }
            }

            function showFirstFormButtons() {
                const firstFormButtons = document.getElementById('firstFormButtons');
                if (firstFormButtons) {
                    firstFormButtons.style.display = 'block';
                }
            }

            function updateButtonVisibility() {
                const allForms = document.querySelectorAll('div[id^="mcqForm_"]');
                const firstFormButtons = document.getElementById('firstFormButtons');

                if (allForms.length === 0) {
                    if (firstFormButtons) {
                        firstFormButtons.style.display = 'block';
                    }
                } else {
                    if (firstFormButtons) {
                        firstFormButtons.style.display = 'none';
                    }

                    allForms.forEach((form, index) => {
                        const addMoreBtn = form.querySelector('.add-more-btn');
                        const removeBtn = form.querySelector('.remove-form-btn');
                        const storeBtn = form.querySelector('button[type="submit"]');

                        if (index === allForms.length - 1) {
                            if (addMoreBtn) addMoreBtn.style.display = 'inline-block';
                            if (removeBtn) removeBtn.style.display = 'inline-block';
                            if (storeBtn) storeBtn.style.display = 'inline-block';
                        } else {
                            if (addMoreBtn) addMoreBtn.style.display = 'none';
                            if (removeBtn) removeBtn.style.display = 'inline-block';
                            if (storeBtn) storeBtn.style.display = 'none';
                        }
                    });
                }
            }

            function removeForm(button) {
                const formContainer = button.closest('div[id^="mcqForm_"]');
                if (formContainer) {
                    formContainer.remove();
                    updateButtonVisibility();
                }
            }

            function submitAllForms() {
                const forms = document.querySelectorAll('form');
                if (forms.length === 0) return;

                const formData = new FormData();
                const formCount = forms.length;

                forms.forEach((form, index) => {
                    if (!form || !form.elements) return;

                    const formElements = form.elements;
                    for (let i = 0; i < formElements.length; i++) {
                        const element = formElements[i];
                        if (element && element.name && element.value) {
                            const fieldName = element.name;

                            if (formCount > 1) {
                                formData.append(fieldName + '[]', element.value);
                            } else {
                                formData.append(fieldName, element.value);
                            }
                        }
                    }
                });

                console.log('Submitting', formCount, 'forms');

                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                }).then(response => {
                    if (response.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'MCQs stored successfully!',
                            confirmButtonColor: '#3085d6',
                            timer: 2000,
                            timerProgressBar: true
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                }).catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while storing MCQs.',
                        confirmButtonColor: '#d33'
                    });
                });
            }

            function initializeSelect2(container) {
                $(container).find('.form-select').each(function() {
                    if (!$(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2({
                            placeholder: 'Select an option',
                            allowClear: true,
                            width: '100%'
                        });
                    }
                });
            }

            function importMCQs(form) {
                const formData = new FormData(form);
                const progressContainer = document.getElementById('importProgress');
                const progressBar = document.getElementById('progressBar');
                const progressText = document.getElementById('progressText');
                const submitBtn = document.getElementById('submitBtn');

                progressContainer.style.display = 'block';
                submitBtn.disabled = true;
                submitBtn.textContent = 'Importing...';

                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        progressBar.style.width = '100%';
                        progressText.textContent = data.message;
                        setTimeout(() => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Import Successful!',
                                text: data.message,
                                confirmButtonColor: '#3085d6',
                                timer: 2000,
                                timerProgressBar: true
                            }).then(() => {
                                window.location.reload();
                            });
                        }, 1000);
                    } else {
                        progressText.textContent = 'Error: ' + data.message;
                        progressBar.classList.add('bg-danger');
                        Swal.fire({
                            icon: 'error',
                            title: 'Import Failed!',
                            text: data.message,
                            confirmButtonColor: '#d33'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    progressText.textContent = 'An error occurred during import';
                    progressBar.classList.add('bg-danger');
                    Swal.fire({
                        icon: 'error',
                        title: 'Import Error!',
                        text: 'An error occurred during import',
                        confirmButtonColor: '#d33'
                    });
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Import MCQs';
                });
            }
        });
    </script>
</body>

</html>
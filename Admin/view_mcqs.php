<?php
require_once 'config.php';
require_once 'auth_check_mcq.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <title>Maxxify Academy</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/logo/favicon.png">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- file upload -->
    <link rel="stylesheet" href="assets/css/file-upload.css">
    <!-- file upload -->
    <link rel="stylesheet" href="assets/css/plyr.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- full calendar -->
    <link rel="stylesheet" href="assets/css/full-calendar.css">
    <!-- jquery Ui -->
    <link rel="stylesheet" href="assets/css/jquery-ui.css">
    <!-- editor quill Ui -->
    <link rel="stylesheet" href="assets/css/editor-quill.css">
    <!-- apex charts Css -->
    <link rel="stylesheet" href="assets/css/apexcharts.css">
    <!-- calendar Css -->
    <link rel="stylesheet" href="assets/css/calendar.css">
    <!-- jvector map Css -->
    <link rel="stylesheet" href="assets/css/jquery-jvectormap-2.0.5.css">
    <!-- Main css -->
    <link rel="stylesheet" href="assets/css/main.css">
    <style>
        .table {
            width: 100%;
            table-layout: fixed;
        }
        
        .table th,
        .table td {
            padding: 8px;
            vertical-align: top;
            border: 1px solid #dee2e6;
        }
        
        .question-cell {
            width: 25%;
            max-height: 100px;
            overflow-y: auto;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
        }
        
        .options-cell {
            width: 30%;
            max-height: 120px;
            overflow-y: auto;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
        }
        
        .scrollable-content {
            color: black;
            max-height: inherit;
            overflow-y: auto;
            padding-right: 5px;
        }
        
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .selection{
            width: 100% !important;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0,0,0,.02);
        }
        
        .table-striped tbody tr:nth-of-type(even) {
            background-color: rgba(0,0,0,.05);
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
        <div class="dashboard-body">
            <div class="row gy-4">
                <div class="col-12 mt-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5>MCQ Management</h5>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="mcq_type" class="form-label">Select MCQ Type</label>
                                            <select class="form-select" id="mcq_type" name="mcq_type">
                                                <option value="">Select MCQ Type</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="mcq_category" class="form-label">Select MCQ Category</label>
                                            <select class="form-select" id="mcq_category" name="mcq_category" disabled>
                                                <option value="">Select Category</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="subject" class="form-label">Select Subject</label>
                                            <select class="form-select" id="subject" name="subject" disabled>
                                                <option value="">Select Subject</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="topic" class="form-label">Select Topic</label>
                                            <select class="form-select" id="topic" name="topic" disabled>
                                                <option value="">Select Topic</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div id="mcqs-container" style="margin-top: 2em;">
                                        <table class="table table-striped mt-2" id="mcqs-table" style="display: none;">
                                            <thead>
                                                <tr>
                                                    <th>Question</th>
                                                    <th>Options</th>
                                                    <th>Correct</th>
                                                    <th>MCQ Type</th>
                                                    <th>Category</th>
                                                    <th>Subject</th>
                                                    <th>Topic</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="mcqs-tbody">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    </div>

    <!-- Jquery js -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap Bundle Js -->
    <script src="assets/js/boostrap.bundle.min.js"></script>
    <!-- Phosphor Js -->
    <script src="assets/js/phosphor-icon.js"></script>
    <!-- file upload -->
    <script src="assets/js/file-upload.js"></script>
    <!-- file upload -->
    <script src="assets/js/plyr.js"></script>
    <!-- dataTables -->
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- full calendar -->
    <script src="assets/js/full-calendar.js"></script>
    <!-- jQuery UI -->
    <script src="assets/js/jquery-ui.js"></script>
    <!-- jQuery UI -->
    <script src="assets/js/editor-quill.js"></script>
    <!-- apex charts -->
    <script src="assets/js/apexcharts.min.js"></script>
    <!-- Calendar Js -->
    <script src="assets/js/calendar.js"></script>
    <!-- jvectormap Js -->
    <script src="assets/js/jquery-jvectormap-2.0.5.min.js"></script>
    <!-- jvectormap world Js -->
    <script src="assets/js/jquery-jvectormap-world-mill-en.js"></script>

    <!-- main js -->
    <script src="assets/js/main.js"></script>

    <script>
    $(document).ready(function() {
        $('.form-select').select2({
            placeholder: 'Select an option',
            allowClear: true,
            width: 'resolve'
        });
        
        loadMcqTypes();
        
        $('#mcq_type').change(function() {
            var mcqTypeId = $(this).val();
            if(mcqTypeId) {
                loadCategories(mcqTypeId);
                resetDropdowns(['mcq_category', 'subject', 'topic']);
            } else {
                resetDropdowns(['mcq_category', 'subject', 'topic']);
            }
        });
        
        $('#mcq_category').change(function() {
            var categoryId = $(this).val();
            if(categoryId) {
                loadSubjects(categoryId);
                resetDropdowns(['subject', 'topic']);
            } else {
                resetDropdowns(['subject', 'topic']);
            }
        });
        
        $('#subject').change(function() {
            var subjectId = $(this).val();
            if(subjectId) {
                loadTopics(subjectId);
                resetDropdowns(['topic']);
            } else {
                resetDropdowns(['topic']);
            }
        });
        
        $('#topic').change(function() {
            var topicId = $(this).val();
            if(topicId) {
                loadMcqs(topicId);
            } else {
                hideMcqsTable();
            }
        });
        
        function loadMcqTypes() {
            $.get('get_dynamic_data.php?action=mcq_types', function(data) {
                var options = '<option value="">Select MCQ Type</option>';
                $.each(data, function(index, item) {
                    options += '<option value="' + item.id + '">' + item.name + '</option>';
                });
                $('#mcq_type').html(options).trigger('change.select2');
            });
        }
        
        function loadCategories(mcqTypeId) {
            $.get('get_dynamic_data.php?action=categories&mcq_type_id=' + mcqTypeId, function(data) {
                var options = '<option value="">Select Category</option>';
                $.each(data, function(index, item) {
                    options += '<option value="' + item.id + '">' + item.name + '</option>';
                });
                $('#mcq_category').html(options).prop('disabled', false).trigger('change.select2');
            });
        }
        
        function loadSubjects(categoryId) {
            $.get('get_dynamic_data.php?action=subjects&category_id=' + categoryId, function(data) {
                var options = '<option value="">Select Subject</option>';
                $.each(data, function(index, item) {
                    options += '<option value="' + item.id + '">' + item.name + '</option>';
                });
                $('#subject').html(options).prop('disabled', false).trigger('change.select2');
            });
        }
        
        function loadTopics(subjectId) {
            $.get('get_dynamic_data.php?action=topics&subject_id=' + subjectId, function(data) {
                var options = '<option value="">Select Topic</option>';
                $.each(data, function(index, item) {
                    options += '<option value="' + item.id + '">' + item.name + '</option>';
                });
                $('#topic').html(options).prop('disabled', false).trigger('change.select2');
            });
        }
        
        function loadMcqs(topicId) {
            $.get('get_dynamic_data.php?action=mcqs&topic_id=' + topicId, function(data) {
                var tbody = $('#mcqs-tbody');
                tbody.empty();
                
                if(data.length > 0) {
                    $.each(data, function(index, mcq) {
                        var row = '<tr>' +
                            '<td class="question-cell">' +
                                '<div class="scrollable-content">' + escapeHtml(mcq.question) + '</div>' +
                            '</td>' +
                            '<td class="options-cell">' +
                                '<div class="scrollable-content">' +
                                    '<strong>A:</strong> ' + escapeHtml(mcq.option_a) + '<br>' +
                                    '<strong>B:</strong> ' + escapeHtml(mcq.option_b) + '<br>' +
                                    '<strong>C:</strong> ' + escapeHtml(mcq.option_c) + '<br>' +
                                    '<strong>D:</strong> ' + escapeHtml(mcq.option_d) +
                                '</div>' +
                            '</td>' +
                            '<td style="color: black;">' + mcq.correct_option + '</td>' +
                            '<td style="color: black;">' + escapeHtml(mcq.mcq_type_name || 'N/A') + '</td>' +
                            '<td style="color: black;">' + escapeHtml(mcq.category_name || 'N/A') + '</td>' +
                            '<td style="color: black;">' + escapeHtml(mcq.subject_name || 'N/A') + '</td>' +
                            '<td style="color: black;">' + escapeHtml(mcq.topic_name || 'N/A') + '</td>' +
                            '<td>' +
                                '<a href="edit_mcq.php?id=' + mcq.id + '" class="btn btn-sm btn-warning">Edit</a> ' +
                                '<a href="delete_mcq.php?id=' + mcq.id + '" onclick="return confirm(\'Delete this MCQ?\')" class="btn btn-sm btn-danger">Delete</a>' +
                            '</td>' +
                        '</tr>';
                        tbody.append(row);
                    });
                    showMcqsTable();
                } else {
                    tbody.append('<tr><td colspan="8" style="color: black;">No MCQs found for this topic.</td></tr>');
                    showMcqsTable();
                }
            });
        }
        
        function resetDropdowns(dropdownIds) {
            $.each(dropdownIds, function(index, id) {
                $('#' + id).html('<option value="">Select ' + id.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) + '</option>').prop('disabled', true).trigger('change.select2');
            });
        }
        
        function showMcqsTable() {
            $('#no-selection-message').hide();
            $('#mcqs-table').show();
        }
        
        function hideMcqsTable() {
            $('#mcqs-table').hide();
            $('#no-selection-message').show();
        }
        
        function escapeHtml(text) {
            var map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }
    });
    </script>

</body>

</html>
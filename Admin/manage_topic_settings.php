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
        
        #topics-table {
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        #topics-table thead th {
            background-color: #6c757d !important;
            color: white !important;
            font-weight: 600;
            padding: 12px 15px;
            border: none;
            text-align: left;
        }
        
        #topics-table tbody td {
            padding: 12px 15px;
            border: none;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
        }
        
        #topics-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        #topics-table tbody tr:nth-child(even) {
            background-color: #e9ecef !important;
        }
        
        #topics-table tbody tr:nth-child(even) td {
            background-color: #e9ecef !important;
        }
        
        #topics-table tbody tr:nth-child(odd) {
            background-color: #ffffff !important;
        }
        
        #topics-table tbody tr:nth-child(odd) td {
            background-color: #ffffff !important;
        }
        
        #topics-table tbody tr:hover {
            background-color: #e9ecef !important;
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
                                    <h5>Topic Settings Management</h5>
                                    
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
                                    
                                    <div id="settings-container" style="display: none;">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="publish_status" class="form-label">Publish Status</label>
                                                <select class="form-select" id="publish_status" name="publish_status">
                                                    <option value="">Select Status</option>
                                                    <option value="1">Publish</option>
                                                    <option value="0">Unpublish</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="payment_status" class="form-label">Payment Status</label>
                                                <select class="form-select" id="payment_status" name="payment_status">
                                                    <option value="">Select Status</option>
                                                    <option value="0">Paid</option>
                                                    <option value="1">Free</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12" style="display: flex;align-items: center;justify-content: center; margin: 1em;">
                                                <button type="button" id="update-settings" class="btn btn-primary">Update Settings</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <h5>All Topics Settings</h5>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" id="table-search" placeholder="Search topics, subjects, categories...">
                                                        </div>
                                                    </div>
                                                    <table class="table mt-2" id="topics-table">
                                                        <thead>
                                                            <tr>
                                                                <th>MCQ Type</th>
                                                                <th>Category</th>
                                                                <th>Subject</th>
                                                                <th>Topic</th>
                                                                <th>Publish Status</th>
                                                                <th>Payment Status</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
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
        </div>
    </div>

    <div class="modal fade" id="editTopicModal" tabindex="-1" aria-labelledby="editTopicModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTopicModalLabel">Edit Topic Settings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editTopicForm">
                        <input type="hidden" id="edit_topic_id" name="topic_id">
                        <div class="mb-3">
                            <label for="edit_publish_status" class="form-label">Publish Status</label>
                            <select class="form-select" id="edit_publish_status" name="publish_status" required>
                                <option value="">Select Status</option>
                                <option value="1">Publish</option>
                                <option value="0">Unpublish</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_payment_status" class="form-label">Payment Status</label>
                            <select class="form-select" id="edit_payment_status" name="payment_status" required>
                                <option value="">Select Status</option>
                                <option value="0">Paid</option>
                                <option value="1">Free</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveTopicSettings">Save Changes</button>
                </div>
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
        $('.form-select:not(#edit_publish_status):not(#edit_payment_status)').select2({
            placeholder: 'Select an option',
            allowClear: true,
            width: 'resolve'
        });
        
        loadMcqTypes();
        loadAllTopics();
        
        $('#table-search').on('keyup', function() {
            var searchText = $(this).val().toLowerCase();
            var $tableBody = $('#topics-table tbody');
            var $rows = $tableBody.find('tr');
            
            if(searchText === '') {
                $rows.show();
                reapplyAlternatingColors();
                return;
            }
            
            var matchingRows = [];
            var nonMatchingRows = [];
            
            $rows.each(function() {
                var $row = $(this);
                var rowText = $row.text().toLowerCase();
                
                if(rowText.indexOf(searchText) > -1) {
                    matchingRows.push($row);
                } else {
                    nonMatchingRows.push($row);
                }
            });
            
            $tableBody.empty();
            
            $.each(matchingRows, function(index, $row) {
                $tableBody.append($row);
            });
            
            $.each(nonMatchingRows, function(index, $row) {
                $tableBody.append($row);
                $row.hide();
            });
            
            reapplyAlternatingColors();
        });
        
        $('#mcq_type').change(function() {
            var mcqTypeId = $(this).val();
            if(mcqTypeId) {
                loadCategories(mcqTypeId);
                resetDropdowns(['mcq_category', 'subject', 'topic']);
                hideSettingsContainer();
            } else {
                resetDropdowns(['mcq_category', 'subject', 'topic']);
                hideSettingsContainer();
            }
        });
        
        $('#mcq_category').change(function() {
            var categoryId = $(this).val();
            if(categoryId) {
                loadSubjects(categoryId);
                resetDropdowns(['subject', 'topic']);
                hideSettingsContainer();
            } else {
                resetDropdowns(['subject', 'topic']);
                hideSettingsContainer();
            }
        });
        
        $('#subject').change(function() {
            var subjectId = $(this).val();
            if(subjectId) {
                loadTopics(subjectId);
                resetDropdowns(['topic']);
                hideSettingsContainer();
            } else {
                resetDropdowns(['topic']);
                hideSettingsContainer();
            }
        });
        
        $('#topic').change(function() {
            var topicId = $(this).val();
            if(topicId) {
                loadTopicSettings(topicId);
                showSettingsContainer();
            } else {
                hideSettingsContainer();
            }
        });
        
        $('#update-settings').click(function() {
            var topicId = $('#topic').val();
            var publishStatus = $('#publish_status').val();
            var paymentStatus = $('#payment_status').val();
            
            if(!topicId || publishStatus === '' || paymentStatus === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Required Fields',
                    text: 'Please select all required fields.',
                    confirmButtonColor: '#673AB7'
                });
                return;
            }
            
            $.ajax({
                url: 'update_topic_settings.php',
                type: 'POST',
                data: {
                    topic_id: topicId,
                    is_publish: publishStatus,
                    is_free: paymentStatus
                },
                dataType: 'json',
                success: function(response) {
                    if(response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Settings updated successfully!',
                            confirmButtonColor: '#673AB7'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                            confirmButtonColor: '#673AB7'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while updating settings.',
                        confirmButtonColor: '#673AB7'
                    });
                }
            });
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
        
        function loadTopicSettings(topicId) {
            $.get('get_topic_settings.php?topic_id=' + topicId, function(data) {
                if(data.success) {
                    $('#publish_status').val(data.settings.is_publish).trigger('change.select2');
                    $('#payment_status').val(data.settings.is_free).trigger('change.select2');
                } else {
                    $('#publish_status').val('').trigger('change.select2');
                    $('#payment_status').val('').trigger('change.select2');
                }
            });
        }
        
        function resetDropdowns(dropdownIds) {
            $.each(dropdownIds, function(index, id) {
                $('#' + id).html('<option value="">Select ' + id.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) + '</option>').prop('disabled', true).trigger('change.select2');
            });
        }
        
        function showSettingsContainer() {
            $('#settings-container').show();
            $('#publish_status, #payment_status').select2({
                placeholder: 'Select an option',
                allowClear: true,
                width: 'resolve'
            });
        }
        
        function hideSettingsContainer() {
            $('#settings-container').hide();
        }
        
        function reapplyAlternatingColors() {
            $('#topics-table tbody tr:visible').each(function(index) {
                $(this).removeClass('even-row odd-row');
                if(index % 2 === 0) {
                    $(this).addClass('odd-row');
                    $(this).css('background-color', '#ffffff');
                    $(this).find('td').css('background-color', '#ffffff');
                } else {
                    $(this).addClass('even-row');
                    $(this).css('background-color', '#e9ecef');
                    $(this).find('td').css('background-color', '#e9ecef');
                }
            });
        }
        
        function loadAllTopics() {
            $.get('get_dynamic_data.php?action=all_topics_with_settings', function(data) {
                var tbody = $('#topics-table tbody');
                tbody.empty();
                
                if(data && data.length > 0) {
                    $.each(data, function(index, topic) {
                        var publishStatus = topic.is_publish == 1 ? 'Publish' : 'Unpublish';
                        var paymentStatus = topic.is_free == 1 ? 'Free' : 'Paid';
                        
                        var row = '<tr>' +
                            '<td style="color:black;">' + (topic.mcq_type_name || 'N/A') + '</td>' +
                            '<td style="color:black;">' + (topic.category_name || 'N/A') + '</td>' +
                            '<td style="color:black;">' + (topic.subject_name || 'N/A') + '</td>' +
                            '<td style="color:black;">' + topic.topic_name + '</td>' +
                            '<td style="color:black;">' + publishStatus + '</td>' +
                            '<td style="color:black;">' + paymentStatus + '</td>' +
                            '<td><button class="btn btn-sm btn-warning" onclick="openEditModal(' + topic.topic_id + ', ' + topic.is_publish + ', ' + topic.is_free + ')" title="Edit"><i class="ph ph-pencil"></i></button></td>' +
                            '</tr>';
                        tbody.append(row);
                    });
                } else {
                    tbody.append('<tr><td colspan="7" style="color:black;">No topics found.</td></tr>');
                }
            });
        }
        
        $('#saveTopicSettings').click(function() {
            var topicId = $('#edit_topic_id').val();
            var publishStatus = $('#edit_publish_status').val();
            var paymentStatus = $('#edit_payment_status').val();
            
            if(!topicId || publishStatus === '' || paymentStatus === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Required Fields',
                    text: 'Please select all required fields.',
                    confirmButtonColor: '#673AB7'
                });
                return;
            }
            
            $.ajax({
                url: 'update_topic_settings.php',
                type: 'POST',
                data: {
                    topic_id: topicId,
                    is_publish: publishStatus,
                    is_free: paymentStatus
                },
                dataType: 'json',
                success: function(response) {
                    if(response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Settings updated successfully!',
                            confirmButtonColor: '#673AB7'
                        });
                        $('#editTopicModal').modal('hide');
                        loadAllTopics();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                            confirmButtonColor: '#673AB7'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while updating settings.',
                        confirmButtonColor: '#673AB7'
                    });
                }
            });
        });
        
    });
    
    function openEditModal(topicId, publishStatus, paymentStatus) {
        $('#edit_topic_id').val(topicId);
        $('#edit_publish_status').val(publishStatus);
        $('#edit_payment_status').val(paymentStatus);
        $('#editTopicModal').modal('show');
    }
    </script>

</body>

</html>

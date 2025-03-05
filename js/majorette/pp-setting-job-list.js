$(document).ready(function () {
    // 🔹 แสดงข้อมูลใน Modal เมื่อกดปุ่มแก้ไข
    $('#setting_job_modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // ปุ่มที่ถูกคลิก
        var jobId = button.data('job-id') || '';

        console.log("Fetching job data for ID:", jobId); // Debugging

        if (!jobId) {
            alert("Error: Job ID is missing.");
            return;
        }

        // 🔹 ตรวจสอบว่าค่าส่งไปยัง PHP ถูกต้อง
        console.log("Sending AJAX request to get_job_info.php with id_job:", jobId);

        $.ajax({
            url: "ajax/get_job_info.php",
            type: "POST",
            data: { id_job: jobId },
            cache: false,
            success: function (response) {
                console.log("Server Response:", response); // Debugging

                try {
                    var data = JSON.parse(response);
                    if (data.statusCode === 200 && data.data) {
                        var job = data.data;

                        $('#modal_id_job').text(job.id_job || "N/A");
                        $('#modal_job_operation').val(job.operation || "");
                        $('#modal_job_machine').val(job.machine || "");
                        $('#modal_job_workorder').val(job.work_order || "");
                        $('#modal_job_item').val(job.item_no || "");
                        $('#modal_job_color').val(job.op_color || "");
                        $('#modal_job_side').val(job.op_side || "");
                        $('#modal_job_due').val(job.date_due || "");

                        console.log("Job data loaded successfully!"); // Debugging
                    } else {
                        alert("Error fetching job data: " + (data.message || "Unknown error."));
                    }
                } catch (e) {
                    alert("Unexpected error occurred while parsing server response.");
                    console.error("JSON Parsing Error:", e);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
                alert("Failed to fetch job data.");
            }
        });
    });


    // 🔹 บันทึกข้อมูลเมื่อกดปุ่ม Save
    $('#modal_button_save').click(function () {
        console.log("✅ Save button clicked!");

        var id = $('#modal_id_job').text().trim();
        var operation = $('#modal_job_operation').val().trim();
        var machine = $('#modal_job_machine').val().trim();
        var workOrder = $('#modal_job_workorder').val().trim();
        var itemNo = $('#modal_job_item').val().trim();
        var color = $('#modal_job_color').val().trim();
        var side = $('#modal_job_side').val().trim();
        var dueDate = $('#modal_job_due').val().trim();

        if (!id) {
            alert("❌ Error: Job ID is missing.");
            console.error("❌ Error: Job ID is missing.");
            return;
        }

        // ✅ ตรวจสอบค่าที่จะส่งไปยัง PHP
        console.log("📤 Sending AJAX Data:", {
            id_job: id,
            operation: operation,
            machine: machine,
            work_order: workOrder,
            item_no: itemNo,
            op_color: color,
            op_side: side,
            date_due: dueDate
        });

        $.ajax({
            url: "ajax/update_job.php",
            type: "POST",
            data: {
                id_job: id,
                operation: operation,
                machine: machine,
                work_order: workOrder,
                item_no: itemNo,
                op_color: color,
                op_side: side,
                date_due: dueDate
            },
            cache: false,
            success: function (response) {
                console.log("📥 Server Response:", response);
                try {
                    var data = JSON.parse(response);
                    if (data.statusCode === 200) {
                        alert("✅ Update successful.");
                        location.reload();
                    } else {
                        alert("❌ Update failed: " + (data.message || "Unknown error."));
                        console.error("❌ Update failed:", data);
                    }
                } catch (e) {
                    alert("❌ Unexpected error occurred while updating job.");
                    console.error("❌ JSON Parsing Error:", e);
                }
            },
            error: function (xhr, status, error) {
                console.error("❌ AJAX Error:", status, error);
                alert("❌ Failed to update job data.");
            }
        });
    });



    // 🔹 ลบ Job เมื่อกดปุ่ม Delete
    $('#job_info_delete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // ปุ่มที่ถูกคลิก
        var jobId = button.data('job-id') || '';
        var operation = button.data('job-operation') || '';
        var machine = button.data('job-machine') || '';
        var dateDue = button.data('job-datedue') || '';

        console.log("Job ID:", jobId);
        console.log("Operation:", operation);
        console.log("Machine:", machine);
        console.log("Date Due:", dateDue);

        $('#modal_delete_job_id').text(jobId);
        $('#modal_delete_operation').text(operation);
        $('#modal_delete_machine').text(machine);
        $('#modal_delete_date_due').text(dateDue);
    });

// 🔹 ปุ่มลบ Job
    $('#modal_button_delete_job').click(function () {
        var jobId = $('#modal_delete_job_id').text().trim();
        var operation = $('#modal_delete_operation').text().trim();
        var machine = $('#modal_delete_machine').text().trim();
        var dateDue = $('#modal_delete_date_due').text().trim();

        if (!jobId || !operation || !machine || !dateDue) {
            alert("Error: Missing job details.");
            return;
        }

        if (!confirm("Are you sure you want to delete this job row?")) {
            return;
        }

        $.ajax({
            url: "ajax/delete_job_info.php",
            type: "POST",
            data: {
                id_job: jobId,
                operation: operation,
                machine: machine,
                date_due: dateDue
            },
            cache: false,
            success: function (response) {
                console.log("Server Response:", response);
                try {
                    var data = JSON.parse(response);
                    if (data.statusCode === 200) {
                        alert("Job row deleted successfully.");
                        location.reload();
                    } else {
                        alert("Delete failed: " + (data.message || "Unknown error."));
                    }
                } catch (e) {
                    alert("Unexpected error occurred while deleting job row.");
                    console.error("JSON Parsing Error:", e);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
                alert("Failed to delete job row.");
            }
        });
    });

});

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // เมื่อคลิกที่ปุ่มแก้ไข
        $('.user_edit').click(function() {
            var idStaff = $(this).data('id_staff');

            $.ajax({
                url: 'pp-user-get-details.php', // ไฟล์ PHP ที่จะดึงข้อมูล
                type: 'GET',
                data: { id_staff: idStaff },
                dataType: 'json',
                success: function(response) {
                    if(response.statusCode === 200) {
                        $('#modal_id_staff').val(response.data.id_staff);
                        $('#modal_first_name').text(response.data.name_first);
                        $('#modal_last_name').text(response.data.name_last);
                        $('#modal_username').val(response.data.username);
                        $('#modal_password').val(response.data.password); // คุณอาจไม่ต้องการแสดงรหัสผ่าน
                    } else {
                        alert('ไม่พบข้อมูล');
                    }
                },
                error: function() {
                    alert('เกิดข้อผิดพลาดในการดึงข้อมูล');
                }
            });
        });

        // เมื่อคลิกที่ปุ่มลบ
        $('.user_delete').click(function() {
            var idStaff = $(this).data('id_staff');
            $('#modal_user_delete').text(idStaff);

            $('#delete_user_modal').off('click').on('click', function() {
                $.ajax({
                    url: 'pp-user-delete.php', // ไฟล์ PHP ที่จะทำการลบข้อมูล
                    type: 'POST',
                    data: { id_staff: idStaff },
                    success: function(response) {
                        if(response.statusCode === 200) {
                            alert('ลบข้อมูลสำเร็จ');
                            location.reload(); // รีเฟรชหน้าเพื่อแสดงข้อมูลล่าสุด
                        } else {
                            alert('ไม่สามารถลบข้อมูลได้');
                        }
                    },
                    error: function() {
                        alert('เกิดข้อผิดพลาดในการลบข้อมูล');
                    }
                });
            });
        });
    });
</script>

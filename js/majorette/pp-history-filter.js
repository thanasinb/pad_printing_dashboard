document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.getElementById('combinedFilterForm');
    const addFilterBtn = document.getElementById('addFilter');
    const removeFilterBtn = document.getElementById('removeFilter');
    const extraFiltersContainer = document.getElementById('extra-filters');

    // ปุ่ม Reset
    document.getElementById('resetFilters').addEventListener('click', function () {
        // เคลียร์ค่า Date
        document.getElementById('start_date').value = '';
        document.getElementById('end_date').value = '';

        // เคลียร์ Action หลัก
        const mainSelect = document.getElementById('filter-action');
        if (mainSelect) mainSelect.value = '';

        // ลบตัวกรองที่เพิ่ม
        extraFiltersContainer.innerHTML = '';
        removeFilterBtn.style.display = 'none';

        // ส่งฟอร์ม (หรือจะให้กด Filter เองก็ได้)
        filterForm.submit();
    });

    // ปุ่ม +
    addFilterBtn.addEventListener('click', function () {
        const newFilter = document.createElement('div');
        newFilter.classList.add('form-group'); // ใช้สไตล์เดิม
        newFilter.innerHTML = `
            <label class="form-label">Action:</label>
            <select name="filter_action[]" class="form-select">
                  <option value="">All Actions</option>
                                        <option value="Login" data-icon="log-in">🔐 Login</option>
                                        <option value="Logout (Log out yourself)" data-icon="log-out">🔓 Logout (Log out yourself)</option>
                                        <option value="Logout (session expired)" data-icon="log-out">⌛ Logout (session expired)</option>
                                        <option value="Download QR Code" data-icon="download">⬇️ ดาวน์โหลด QR Code ลงเครื่อง</option>
                                        <option value="บันทึก QR Code" data-icon="save">💾 บันทึก QR Code ลงฐานข้อมูล</option>
                                        <option value="สร้างพนักงาน">👤 สร้างพนักงาน</option>
                                        <option value="แก้ไขข้อมูลพนักงาน">✏️ แก้ไขพนักงาน</option>
                                        <option value="ลบพนักงาน:">➖ ลบพนักงาน</option>
                                        <option value="เพิ่มผู้ใช้" data-icon="plus-circle">➕ เพิ่ม User </option>
                                        <option value="แก้ไขบัญชีของ:">✏️ แก้ไข User</option>
                                        <option value="ลบผู้ใช้" data-icon="minus-circle">➖ ลบ User</option>
                                        <option value="สร้าง Machine ID:">➕ สร้าง Machine </option>
                                        <option value="แก้ไขข้อมูล Machine ID:">✏️ แก้ไข Machine</option>
                                        <option value="ลบ Machine ที่มี ID">➖ ลบ Machine</option>
                                        <option value="เพิ่ม Downtime Code:" data-icon="plus-circle">➕ เพิ่ม Downtime</option>
                                        <option value="แก้ไข Downtime Box Code:" data-icon="edit">✏️ แก้ไข Downtime </option>
                                        <option value="ลบ Downtime" data-icon="minus-circle">➖ ลบ Downtime</option>
                                        <option value="สร้าง job ด้วย Job ID:" data-icon="plus-circle">➕ สร้าง Job </option>
                                        <option value="แก้ไขข้อมูล Job ID:" data-icon="edit">✏️ แก้ไขข้อมูล Job </option>
                                        <option value="ลบงาน: Work Order" data-icon="minus-circle">➖ ลบ Job </option>
                                        <option value="เพิ่ม job ID" data-icon="plus-circle">➕ เพิ่ม Job ไปที่ machine </option>
                                        <option value="แก้ไข job ID" data-icon="edit">✏️ แก้ไข job ที่ machine </option>
                                        <option value="เอา job ID" data-icon="minus-circle">➖ ลบ Job ออกจาก machine</option>
            </select>
        `;
        extraFiltersContainer.appendChild(newFilter);
        removeFilterBtn.style.display = 'inline-block';
    });

    // ปุ่ม -
    removeFilterBtn.addEventListener('click', function () {
        const filters = extraFiltersContainer.querySelectorAll('.form-group');
        if (filters.length > 0) {
            filters[filters.length - 1].remove();
            if (filters.length - 1 === 0) {
                removeFilterBtn.style.display = 'none';
            }
        }
    });
});

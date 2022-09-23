<?php
function get_planning($conn, $id_task) {
    $sql = "SELECT 
    id_task, 
    id_job, 
    item_no, 
    operation, 
    planning.op_color, 
    planning.op_side, 
    op_des AS op_name, 
    qty_order, 
    qty_comp, 
    qty_open, 
    divider AS multiplier, 
    qty_per_pulse2 AS qty_per_tray
    FROM planning INNER JOIN divider ON (
    planning.op_color=divider.op_color AND 
    planning.op_side=divider.op_side) 
    WHERE id_task=" . $id_task;

    $result = $conn->query($sql);
    $data = $result->fetch_assoc();
    $data['item_no'] = substr($data['item_no'], 0, -3);
    return $data;
}
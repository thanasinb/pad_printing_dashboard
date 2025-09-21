<?php
const STATUS_WORKING=1;
const STATUS_BREAK=2;
const STATUS_CLOSED=3;
const STATUS_EXPORTED=4;
const STATUS_UPDATED=5;
const STATUS_COMPLETE=6;

const ACTIVITY_BACKFLUSH=1;
const ACTIVITY_REWORK=2;
const ACTIVITY_DOWNTIME=3;

const TABLE_BACKFLUSH='activity';
const TABLE_REWORK='activity_rework';
const TABLE_DOWNTIME='activity_downtime';
?>
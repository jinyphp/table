<?php


function error_danger($message) {
    return [
        'type' => 'danger',
        'message' => $message
    ];
}

function error_success($message) {
    return [
        'type' => 'success',
        'message' => $message
    ];
}

function error_warning($message) {
    return [
        'type' => 'warning',
        'message' => $message
    ];
}




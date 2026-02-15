<?php
// Section helper functions
function getAllSections() {
    // Example: return static list, replace with DB query if needed
    return [
        'BSIT-1A', 'BSIT-1B', 'BSIT-2A', 'BSIT-2B',
        'BSCS-1A', 'BSCS-1B', 'BSCS-2A', 'BSCS-2B',
        'BSBA-1A', 'BSBA-1B', 'BSBA-2A', 'BSBA-2B'
    ];
}

function getSectionStudentCount($section) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT COUNT(*) FROM students WHERE section = ? AND status = 'active'");
    $stmt->execute([$section]);
    return (int)$stmt->fetchColumn();
}

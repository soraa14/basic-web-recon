<?php
// Session start
session_start();

// Include the configuration
    include '../config/config.php';
    include '../config/db.php';
    $conn = OpenCon();

    // Get the value from POST form
    $project_name = $_POST['project_name'];
    $url = $_POST['url'];
    $nikto = $_POST['nikto_check'];
    $whatweb = $_POST['whatweb_check'];
    $wafw00f = $_POST['wafw00f_check'];
    $testssl = $_POST['testssl_check'];
    $feroxbuster = $_POST['feroxbuster_check'];
    $feroxbuster_wordlist = $_POST['feroxbuster_wordlist'];

    if ( empty($nikto) ) $nikto = '0';
    if ( empty($whatweb) ) $whatweb = '0';
    if ( empty($wafw00f) ) $wafw00f = '0';
    if ( empty($testssl) ) $testssl = '0';
    if ( empty($feroxbuster) ) $feroxbuster = '0';
    if ( empty($feroxbuster_wordlist) ) $feroxbuster_wordlist = '0';


    if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
        header('Location: ' . $base_url . '/new_scan.php?message=http_error_1');
        die('Not a valid URL');
    }

    if (substr($url , 0, 7) === 'http://' AND $testssl === '1') {
        header('Location: ' . $base_url . '/new_scan.php?message=http_error_2');
        die();
    }

    $wordlists = array('common.txt' , 'big.txt', 'directory-list-2.3-medium.txt');
    if (!in_array($feroxbuster_wordlist, $wordlists) AND $feroxbuster === '1') {
        header('Location: ' . $base_url . '/new_scan.php?message=http_error_3');
        die();
    }
    
    $feroxbuster_wordlist = '/usr/share/seclists/Discovery/Web-Content/' . $feroxbuster_wordlist;

    // Get project_owner_id from session
    $project_id = $_SESSION['project_id'];
    
    $sql = "INSERT INTO projects (project_owner_id, project_name, urls, nikto, whatweb, wafw00f, testssl, feroxbuster, feroxbuster_wordlist, is_executed) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiiiiis", $project_id, $project_name, $url, $nikto, $whatweb, $wafw00f, $testssl, $feroxbuster, $feroxbuster_wordlist);
    $stmt->execute();
    CloseCon($stmt);
    
    // Redirect to home page
    header('Location: ' . $base_url . '/home.php?page=1');
    ?>
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $userN = htmlspecialchars(trim($_POST["userN"]));
    $pwd = trim($_POST["pwd"]);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);


    if (empty($userN) || empty($pwd)) {

        header("Location: ../test.php");
        exit();
    }


    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        header("Location: ../test.php?error=invalidemail");
        exit();
    }

    try {
        $hashed_pwd = password_hash($pwd, PASSWORD_DEFAULT);
        require_once "handle.php";


        $query = "INSERT INTO userdb (userName, pwd, email) VALUES (?, ?, ?);";
        $stmt = $pdo->prepare($query);

        $stmt->execute([$userN, $hashed_pwd, $email]);

        $pdo = null;
        $stmt = null;


        header("Location: ../test.php?success=true");
        exit();
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {

    header("Location: ../test.php");
    exit();
}


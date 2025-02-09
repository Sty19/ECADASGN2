<?php 
include_once("header.php"); 
include_once("db_config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = $_POST["subject"];
    $content = $_POST["content"];
    $rank = $_POST["rank"];
    $shopperID = $_SESSION["ShopperID"];
    
    if (empty($subject) || empty($content) || empty($rank)) {
        $message = "Please fill out all fields!";
    } else {
        $query = "INSERT INTO Feedback (ShopperID, Subject, Content, Rank, DateTimeCreated) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $shopperID, $subject, $content, $rank);
        
        if ($stmt->execute()) {
            $message = "Feedback submitted successfully!";
        } else {
            $message = "Error submitting feedback!";
        }

        $stmt->close();
    }
}

$feedbacklist = [];
$ShopperID = $_SESSION["ShopperID"];

$query = "SELECT Shopper.Name, Feedback.Subject, Feedback.Content, Feedback.Rank, Feedback.DateTimeCreated 
          FROM Feedback 
          INNER JOIN Shopper ON Feedback.ShopperID = Shopper.ShopperID";
$stmt = $conn->prepare($query);
$stmt->execute();
$stmt->bind_result($shopperName, $Subject, $content, $rank, $datetimecreated);

while ($stmt->fetch()) {
    $feedbacklist[] = [
        "Name" => $shopperName,
        "Subject" => $Subject,
        "Content" => $content,
        "Rank" => $rank,
        "DateTimeCreated" => $datetimecreated
    ];
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta ProductTitle="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to BabyJoy Store</title>
    <link rel="stylesheet" type="text/css" href="ECAD2024Oct_Assignment_1_Input_Files/css/styles.css">
    <link rel="stylesheet" type="text/css" href="ECAD2024Oct_Assignment_1_Input_Files/css/feedback.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
</head>
<body>
    <h1 class="page-title">Feedback History</h1>
    <div class="feedback-form">
        <h2>Submit Your Feedback</h2>
        <?php if (isset($message)): ?>
            <div class="<?= strpos($message, 'Error') === false ? 'message' : 'error' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        <form action="" method="post">
            <div>
                <label for="subject">Subject:</label>
                <input type="text" name="subject" id="subject" required />
            </div>
            <div>
                <label for="content">Content:</label>
                <textarea name="content" id="content" rows="4" required></textarea>
            </div>
            <div>
                <label for="rank">Rank (1-5):</label>
                <input type="number" name="rank" id="rank" min="1" max="5" required />
            </div>
            <div>
                <button type="submit">Submit Feedback</button>
            </div>
        </form>
    </div>

    <?php if (empty($feedbacklist)): ?>
        <div class="no-feedback-container">
            <p class="no-feedback">You have not given any feedback.</p>
        </div>
    <?php else: ?>
        <table class="feedback-table">
            <thead>
                <tr>
                    <th>Shopper Name</th>
                    <th>Subject</th>
                    <th>Content</th>
                    <th>Rank</th>
                    <th>Date/Time Created</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($feedbacklist as $feedback): ?>
                    <tr>
                        <td><?= htmlspecialchars($feedback["Name"]) ?></td>
                        <td><?= htmlspecialchars($feedback["Subject"]) ?></td>
                        <td><?= nl2br(htmlspecialchars($feedback["Content"])) ?></td>
                        <td><span class="rank"><?= htmlspecialchars($feedback["Rank"]) ?>/5</span></td>
                        <td>
                            <span class="datetime">
                                <?= htmlspecialchars(date("F j, Y", strtotime($feedback["DateTimeCreated"]))) ?>
                                <?= htmlspecialchars(date("h:i A", strtotime($feedback["DateTimeCreated"]))) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>
</html>

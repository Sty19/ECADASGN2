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

$query = "SELECT Subject, Content, Rank, DateTimeCreated FROM Feedback WHERE ShopperID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $ShopperID);
$stmt->execute();
$stmt->bind_result($Subject, $content, $rank, $datetimecreated);
while ($stmt->fetch()) {
    $feedbacklist[] = [
        "Subject" => $Subject,
        "Content" => $content,
        "Rank" => $rank,
        "DateTimeCreated" => $datetimecreated
    ];
}

$stmt->close();
?>

<h2>Feedback History</h2>
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
    <p>You have not given any feedback.</p>
<?php else: ?>
    <?php foreach ($feedbacklist as $feedback): ?>
        <div class="feedback-container">
            <h3><?= htmlspecialchars($feedback["Subject"]) ?></h3>
            <p><strong>Content:</strong> <?= nl2br(htmlspecialchars($feedback["Content"])) ?></p>
            <p><strong>Rank:</strong> <?= htmlspecialchars($feedback["Rank"]) ?></p>
            <p><strong>Date:</strong> <?= htmlspecialchars($feedback["DateTimeCreated"]) ?></p>
        </div>
    <?php endforeach; ?>
<?php endif; ?>


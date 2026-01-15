<?php 
require('config.php');

$isEdit = false;
$id = '';
$review = '';
$user_id = (int)($_SESSION['user_id'] ?? 0);
$player_id = (int)($_GET['player_id'] ?? 0);

// POST method
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $review = $_POST['review'] ?? '';
    $user_id = (int)($_SESSION['user_id'] ?? 0);
    $player_id = (int)($_POST['player_id'] ?? 0);

    if ($review === '' || $user_id === 0 || $player_id === 0) {
            echo 'All fields are required.';
    } 

    $reviewSpecial = $conn->real_escape_string($review);

    if ($id === ''){
        $query = "INSERT INTO reviews (review, user_id, player_id) VALUES ('$reviewSpecial', $user_id, $player_id)";
        $records = $conn->query($query);
    } 

    if ($records){
        header('Location: dashboardFan.php');
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// GET method
$query = "SELECT players.id, players.name, players.age, players.position, players.salary, players.weight, players.height, reviews.review FROM players
          LEFT JOIN reviews ON reviews.player_id = players.id AND reviews.user_id = $user_id";
$records = $conn->query($query);

$playerSelected = null;
$reviewSelected = ''; 
if($player_id > 0){
    $queryPlayer = "SELECT players.id, players.name, players.age, players.position, players.salary, players.weight, players.height, reviews.review FROM players
                    LEFT JOIN reviews ON reviews.player_id = players.id AND reviews.user_id = $user_id WHERE players.id = $player_id";
    $recordPlayer = $conn->query($queryPlayer);
    if ($recordPlayer && $recordPlayer->num_rows > 0) {
        $playerSelected = $recordPlayer->fetch_assoc();
        $reviewSelected = $playerSelected['review'] ?? '';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./style/fan.css">
        <title>Fan Dashboard</title>
    </head>
    <body>
        <nav class="navbar">
            <ul>
                <li class="role">
                    <span class="roleBadge">Fan</span>
                </li>
                <li><a class="logoutLink" href="logout.php">Logout</a></li>
            </ul>
        </nav>

        <div class="pageContainer">
            <div class="mainContent">
                <h1 class="pageTitle">Players to Review</h1>
                
                <?php if ($playerSelected) { ?>
                    <div class="reviewSection">
                        <h2 class="reviewTitle">Review <?php echo $playerSelected['name']; ?></h2>
                        
                        <?php if ($reviewSelected){ ?>
                            <div class="existingReview">
                                <strong>Your review:</strong>
                                <p><?php echo $reviewSelected; ?></p>
                            </div>
                        <?php } else { ?>
                            <div class="noReview">
                                <p>No review submitted</p>
                            </div>
                            <form class="reviewForm" action="dashboardFan.php"  method="POST">
                                <input type="hidden" name="player_id" value="<?php echo $player_id; ?>" />
                                <textarea name="review" placeholder="Write your review here" required></textarea><br>
                                
                                <div class="formActions">
                                    <button class="submitButton" type="submit">Submit</button>
                                    <a href="dashboardFan.php" class="cancelLink">Cancel</a>
                                </div>
                            </form>
                        <?php } ?>
                    </div>
                <?php } ?>
                
                <div class="playersSection">
                    <table class="playersTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Position</th>
                                <th>Salary</th>
                                <th>Weight</th>
                                <th>Height</th>
                                <th>Review</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $records->data_seek(0);
                        while ($row = $records->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['age']; ?></td>
                                <td><?php echo $row['position']; ?></td>
                                <td><?php echo $row['salary']; ?></td>
                                <td><?php echo $row['weight']; ?></td>
                                <td><?php echo $row['height']; ?></td>
                                <td><?php echo $row['review'] ?? ''; ?></td>
                                <td>
                                    <a class="reviewLink" href="dashboardFan.php?player_id=<?php echo $row['id']; ?>">Review</a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
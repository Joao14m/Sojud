<?php 
require('config.php');

$currentUserId = (int)($_SESSION['user_id'] ?? 0);
$isEdit = false;
$showCreatePopUp = isset($_GET['create']);

$id = '';
$name = '';
$age = '';
$position = '';
$salary = '';
$weight = '';
$height = '';

// POST method
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $age = $_POST['age'] ?? '';
    $position = $_POST['position'] ?? '';
    $salary = $_POST['salary'] ?? '';
    $weight = $_POST['weight'] ?? '';
    $height = $_POST['height'] ?? '';

    if ($id === '' && ($name === '' || $age === '' || $position === '' || $salary === '' || $weight === '' || $height === '')) {
            echo 'All fields are required.';
            return;
    } 

    $age = (int) $age;
    $salary = (int) $salary;
    $weight = (int) $weight;
    $height = (int) $height;
    
    if ($id === '') {
        $query = "INSERT INTO players (user_id, name, age, position, salary, weight, height) 
        VALUES ($currentUserId, '$name', $age, '$position', $salary, $weight, $height)";
        $records = $conn->query($query);
    } else {
        $id = (int) $id;
        $query = "UPDATE players SET name='$name', age=$age, position='$position', salary=$salary, weight=$weight, height=$height WHERE id=$id";
        $records = $conn->query($query);
    }

    if ($records){
        header('Location: dashboardAdmin.php');
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// GET method
$query = "SELECT id, name, age, position, salary, weight, height FROM players WHERE user_id = $currentUserId";
$records = $conn->query($query);

// UPDATE method
if(isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    $record = $conn->query("SELECT id, name, age, position, salary, weight, height FROM players WHERE id = $editId");
    $player = $record->fetch_assoc();
    if ($player) {
        $id = $player['id'];
        $name = $player['name'];
        $age = $player['age'];
        $position = $player['position'];
        $salary = $player['salary'];
        $weight = $player['weight'];
        $height = $player['height'];
        $isEdit = true;
    }
}

// DELETE method
if(isset($_GET['delete'])) {
    $deleteId = (int) $_GET['delete'];
    $record = $conn->query("DELETE FROM players WHERE id = $deleteId");
    header('Location: dashboardAdmin.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./style/admin.css">
        <title>Admin Dashboard</title>
    </head>
    <body >
        <nav class="navbar">
            <ul>
                <li class="role">
                    <span class="roleBadge">Admin</span>
                </li>
                <li><a class="logoutLink" href="logout.php">Logout</a></li>
            </ul>
        </nav>

        <div class="pageContainer">
            <div class="mainContent">
                <h1 class="pageTitle">My Players</h1>
                <div class="actionBar">
                    <a href="dashboardAdmin.php?create=1" class="createButton">Create New Player</a>
                </div>
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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $records->data_seek(0);
                            while ($row = $records->fetch_assoc()) {
                                if ($isEdit && $row['id'] == $id) {
                            ?>
                                <tr class="editRow">
                                    <td colspan="7">
                                        <form action="dashboardAdmin.php" method="POST">
                                            <input type ="hidden" name="id" value="<?php echo $id; ?>">
                                            <div class="editField">
                                                <div class="formGroup">
                                                    <input type="text" name="name" placeholder="Name" 
                                                    value="<?php echo $name?>" required>
                                                </div>
                                                <div class="formGroup">
                                                    <input type="number" name="age" placeholder="Age"
                                                    value="<?php echo $age?>" required>
                                                </div>
                                                <div class="formGroup">
                                                    <input type="text" name="position" placeholder="Position"
                                                    value="<?php echo $position?>" required>
                                                </div>
                                                <div class="formGroup">
                                                    <input type="number" name="salary" placeholder="Salary"
                                                    value="<?php echo $salary?>" required>
                                                </div>
                                                <div class="formGroup">
                                                    <input type="number" name="weight" placeholder="Weight"
                                                    value="<?php echo $weight?>" required>
                                                </div>
                                                <div class="formGroup">
                                                    <input type="number" name="height" placeholder="Height"
                                                    value="<?php echo $height?>" required>
                                                </div>
                                            </div>
                                            <div class="editActions">
                                                <button type="submit" class="saveButton">Save</button>
                                                <a href="dashboardAdmin.php" class="cancelButton">Cancel</a>
                                        </form>
                                    </td>
                                </tr>
                            <?php } else {  ?>
                                <tr>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['age']; ?></td>
                                    <td><?php echo $row['position']; ?></td>
                                    <td><?php echo $row['salary']; ?></td>
                                    <td><?php echo $row['weight']; ?></td>
                                    <td><?php echo $row['height']; ?></td>
                                    <td class="actionCell">
                                        <a href="dashboardAdmin.php?edit=<?php echo $row['id']; ?>" class="actionEdit">Edit</a>
                                        <a href="dashboardAdmin.php?delete=<?php echo $row['id']; ?>" class="actionDelete" 
                                        onclick="return confirm('Delete this player?');">Delete</a>
                                    </td>
                                </tr>
                            <?php } 
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php if ($showCreatePopUp){ ?>
        <div class="popUpOver">
            <div class="popUpContent">
                <div class="popUpPlay">
                    <h2>Create New Player</h2>
                    <a href="dashboardAdmin.php" class="popUpClose">×</a>
                </div>
                <form action="dashboardAdmin.php" method="POST" class="popUpForm">
                    <input type="hidden" name="id" value="">
                    
                    <div class="formGroup">
                        <label>Name</label>
                        <input type="text" name="name" placeholder="Enter player name" required>
                    </div>
                    
                    <div class="formGroup">
                        <label>Age</label>
                        <input type="number" name="age" placeholder="Enter age" required>
                    </div>
                    
                    <div class="formGroup">
                        <label>Position</label>
                        <input type="text" name="position" placeholder="Enter position" required>
                    </div>
                    
                    <div class="formGroup">
                        <label>Salary</label>
                        <input type="number" name="salary" placeholder="Enter salary" required>
                    </div>
                    
                    <div class="formGroup">
                        <label>Weight</label>
                        <input type="number" name="weight" placeholder="Enter weight" required>
                    </div>
                    
                    <div class="formGroup">
                        <label>Height</label>
                        <input type="number" name="height" placeholder="Enter height" required>
                    </div>
                    
                    <div class="popUpActions">
                        <button type="submit" class="submitButton">Create Player</button>
                        <a href="dashboardAdmin.php" class="cancelButton">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
        <?php } ?>
    </body>
</html>
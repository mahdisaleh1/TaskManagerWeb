<?php
include '../config.php';
session_start();
if (isset($_SESSION['user_id'])) {
    $user_idd = $_SESSION['user_id'];
    $stmt = "SELECT * FROM users WHERE id = '$user_idd' AND status = 'active'";
    $result = mysqli_query($con, $stmt);
    if ($result) {
        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['name'];
        }
    }
    // User is not logged in, redirect to login page
    //header("Location: ../patient/patientdashboard.php");
}
else {
    // User is not logged in, redirect to login page
    $_SESSION['login_error'] = "You must be logged in to access this page.";
    // Redirect to login page
    header("Location: ../users/login.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $status_id = $_POST['status_id'];
    $due_date = $_POST['due_date'];
    $priority = $_POST['priority'];
    $updated_at = $_POST['updated_at'];
    $project_id = isset($_POST['project_id']) && $_POST['project_id'] !== '' ? $_POST['project_id'] : null;

    // Validate inputs
    if (empty($title) || empty($description) || empty($status_id) || empty($due_date) || empty($priority)) {
        $_SESSION['task_error'] = "All fields are required.";
        header("Location: ./addtask.php");
        exit();
    }

    // Insert task into database
    if ($project_id !== null) {
        $stmt = "INSERT INTO tasks (user_id, title, description, status_id, due_date, priority, updated_at, project_id) VALUES ('$user_id', '$title', '$description', '$status_id', '$due_date', '$priority', '$updated_at', '$project_id')";
    } else {
        $stmt = "INSERT INTO tasks (user_id, title, description, status_id, due_date, priority, updated_at) VALUES ('$user_id', '$title', '$description', '$status_id', '$due_date', '$priority', '$updated_at')";
    }

    if (mysqli_query($con, $stmt)) {
        $_SESSION['task_success'] = "Task added successfully.";
        header("Location: ./addtask.php");
        exit();
    } else {
        $_SESSION['task_error'] = "Error adding task: " . mysqli_error($con);
        header("Location: ./addtask.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/v4-shims.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/v4-shims.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/v4-shims.min.css">
    <meta name="description" content="Task Manager Application">
    <meta name="keywords" content="task, manager, application, PHP, MySQL">
    <meta name="author" content="Mahdi Saleh">
    <link rel="shortcut icon" href="../images/icon.png" type="image/x-icon">
    <title>Add New Task ~ Task Manager</title>
</head>

<body>
    <main>
        <!-- Logo and Mobile Nav Toggle Button -->
        <div class="divheaderlogo" id="divheaderlogo">
            <img src="../images/logo.png" alt="Logo" style="width: 40px; height: 40px;">
            <span class="h5 ml-2 mr-auto">Task Manager</span>
            <button id="navToggle" class="btn btn-primary d-md-none ml-2">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <div class="d-flex">
            <!-- Sidebar Navigation -->
            <nav>
                <div class="d-flex flex-column bg-light vh-100 p-3" style="width: 220px;">
                    <a href="../index.php" class="mb-4 text-center">
                        <img src="../images/logo.png" alt="Logo" style="width: 50px;">
                        <span class="h5 ml-2" style="margin-top: 100px;">Task Manager</span>
                    </a>
                    <a>
                        <?php
                        if (!isset($_SESSION['user_id'])) {
                            // Not logged in: show login button
                            echo '<a href="../users/login.php" class="btn btn-primary btn-block mb-3"><i class="fas fa-sign-in-alt mr-2"></i>Login</a>';
                        } else {
                            // Logged in: show user image, name, and dropdown
                            $user_id = $_SESSION['user_id'];
                            $stmt = "SELECT * FROM users WHERE id = '$user_id' AND status = 'active'";
                            $result = mysqli_query($con, $stmt);
                            if ($result) {
                                if (mysqli_num_rows($result) === 1) {
                                    $user = mysqli_fetch_assoc($result);
                                    $_SESSION['user_role'] = $user['role'];
                                    $_SESSION['user_name'] = $user['name'];
                                }
                            }

                            $userImage = !empty($user['image']) ? $user['image'] : '../images/userlogo.png';
                            $userName = htmlspecialchars($user['name']);
                        ?>
                            <div class="dropdown mb-3 text-center">
                                <a href="#" class="dropdown-toggle d-flex align-items-center justify-content-center" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src="../images/userlogo.png" alt="User" class="rounded-circle mr-2" style="width:32px;height:32px;">
                                    <span><?php echo $userName; ?></span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="../users/profile.php"><i class="fas fa-user mr-2"></i>Profile</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="../users/logout.php"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="./settings.php"><i class="fas fa-cog mr-2"></i>Settings</a>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </a>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="../index.php"><i class="fas fa-home mr-2"></i>Dashboard</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="../manage/myday.php"><i class="fas fa-sun mr-2"></i>My Day</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="../manage/important.php"><i class="fas fa-star mr-2"></i>Important</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link active" href="../manage/tasks.php"><i class="fas fa-tasks mr-2"></i>Tasks</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="../manage/project.php"><i class="fas fa-project-diagram mr-2"></i>Projects</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="../manage/settings.php"><i class="fas fa-cog mr-2"></i>Settings</a>
                        </li>
                    </ul>
                    <div class="mt-auto">
                        <a href="../manage/addtask.php" class="btn btn-secondary btn-block"><i class="fas fa-plus mr-2"></i>Add Task</a>
                        <a href="../manage/addproject.php" class="btn btn-secondary btn-block"><i class="fas fa-plus mr-2"></i>Add Project</a>
                    </div>
                </div>
            </nav>
            <!-- Main Content -->
            <div class="container-fluid d-flex flex-column align-items-center justify-content-center" style="margin-left:0; min-height:100vh; margin-top:20px;">
                <div class="row justify-content-center w-100">
                    <div class="col-12 col-md-10 col-lg-8">
                        <div class="card shadow-sm" style="width:100%; max-width:100%; min-height:600px;">
                            <div class="card-header bg-primary text-white text-center">
                                <h2 class="mb-2"><i class="fas fa-plus mr-2"></i>Add New Task</h2>
                            </div>
                            <div class="card-body">
                                <?php
                                if (isset($_SESSION['task_error'])) {
                                    echo '<div class="alert alert-danger">' . $_SESSION['task_error'] . '</div>';
                                    unset($_SESSION['task_error']);
                                }
                                if (isset($_SESSION['task_success'])) {
                                    echo '<div class="alert alert-success">' . $_SESSION['task_success'] . '</div>';
                                    unset($_SESSION['task_success']);
                                }
                                ?>
                                <form action="addtask.php" method="post">
                                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                                    <div class="form-group">
                                        <label for="title"><i class="fas fa-tasks mr-2"></i>Title</label>
                                        <input type="text" class="form-control form-control-lg" id="title" name="title" required autofocus>
                                    </div>
                                    <div class="form-group">
                                        <label for="project"><i class="fas fa-tasks mr-2"></i>Project (optional)</label>
                                        <select class="form-control form-control-lg" id="project" name="project_id">
                                            <option value="">Select Project</option>
                                            <?php
                                            $project_query = "SELECT id, name FROM projects WHERE user_id = '{$_SESSION['user_id']}' ORDER BY name ASC";
                                            $project_result = mysqli_query($con, $project_query);
                                            if ($project_result && mysqli_num_rows($project_result) > 0) {
                                                while ($project = mysqli_fetch_assoc($project_result)) {
                                                    echo '<option value="' . $project['id'] . '">' . htmlspecialchars($project['name']) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="description"><i class="fas fa-align-left mr-2"></i>Description</label>
                                        <textarea class="form-control form-control-lg" id="description" name="description" rows="4" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="status_id"><i class="fas fa-info-circle mr-2"></i>Status</label>
                                        <select class="form-control form-control-lg" id="status_id" name="status_id" required>
                                            <option value="">Select Status</option>
                                            <option value="1">Pending</option>
                                            <option value="2">In Progress</option>
                                            <option value="3">Completed</option>
                                            <option value="4">Cancelled</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="due_date"><i class="fas fa-calendar-alt mr-2"></i>Due Date</label>
                                        <input type="date" class="form-control form-control-lg" id="due_date" name="due_date" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="priority"><i class="fas fa-exclamation mr-2"></i>Priority</label>
                                        <select class="form-control form-control-lg" id="priority" name="priority" required>
                                            <option value="">Select Priority</option>
                                            <option value="low">Low</option>
                                            <option value="medium">Medium</option>
                                            <option value="high">High</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="updated_at"><i class="fas fa-clock mr-2"></i>Updated At</label>
                                        <input type="datetime-local" class="form-control form-control-lg" id="updated_at" name="updated_at" value="<?php echo date('Y-m-d\TH:i'); ?>" readonly>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">Add Task</button>
                                </form>
                                <div class="mt-3 text-center">
                                    <a href="./tasks.php" class="text-primary">Back to Tasks</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="bg-white border-top shadow-sm py-4 mt-4">
            <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="mb-2 mb-md-0">
                    <img src="../images/logo.png" alt="Task Manager Logo" style="width:32px;vertical-align:middle;">
                    <span class="ml-2 font-weight-bold text-primary">Task Manager</span>
                </div>
                <div class="mb-2 mb-md-0">
                    <a href="#" class="text-secondary mx-2">Privacy Policy</a>
                    <a href="#" class="text-secondary mx-2">Terms of Service</a>
                    <a href="#" class="text-secondary mx-2">Contact</a>
                </div>
                <div>
                    <span class="text-muted">&copy; 2025 Task Manager. All rights reserved.</span>
                    <span id="spanname" class="ml-2">Created by <a href="https://mahdisaleh.ct.ws" target="_blank" class="text-primary font-weight-bold">Mahdi Saleh</a></span>
                    <span class="ml-2">
                        <a href="https://mahdisaleh.ct.ws" target="_blank" class="text-danger mx-1"><i class="fas fa-code"></i></a>
                        <a href="https://github.com/mahdisaleh1" target="_blank" class="text-dark mx-1"><i class="fab fa-github"></i></a>
                        <a href="https://instagram.com/mahdisaleh_" target="_blank" class="text-info mx-1"><i class="fab fa-instagram"></i></a>
                        <a href="mailto:salehmahdi883@gmail.com" class="text-danger mx-1"><i class="fas fa-envelope"></i></a>
                    </span>
                </div>
            </div>
        </footer>
    </main>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/v4-shims.min.js"></script>
    <script src="../js/script.js"></script>
    <style>
        .card {
            max-width: 100%;
            width: 100%;
        }

        @media (max-width: 768px) {
            footer { 
                width: 100%;
                z-index: 100;
            }
            .card{ 
                height: 100%;
                min-height: 100vh;
            }
            
        }
    </style>
</body>

</html>
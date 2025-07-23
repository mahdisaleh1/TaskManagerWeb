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

} else {
    // User is not logged in, redirect to login page
    $_SESSION['login_error'] = "You must be logged in to access this page.";
    // Redirect to login page
    header("Location: ../users/login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/v4-shims.min.css">
    <meta name="description" content="Task Manager Application">
    <meta name="keywords" content="task, manager, application, PHP, MySQL">
    <meta name="author" content="Mahdi Saleh">
    <link rel="shortcut icon" href="../images/icon.png" type="image/x-icon">
    <title>My Day ~ Task Manager</title>
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
                            <a class="nav-link active" href="../manage/myday.php"><i class="fas fa-sun mr-2"></i>My Day</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="../manage/important.php"><i class="fas fa-star mr-2"></i>Important</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="../manage/tasks.php"><i class="fas fa-tasks mr-2"></i>Tasks</a>
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
            <div class="container-fluid d-flex flex-column align-items-center" style="margin-left:0; min-height:100vh; margin-top: 30px;">
                <div class="row justify-content-center w-100">

                    <div class="card shadow-sm" style="width:100%; max-width:100%; min-height:600px;">
                        <div class="card-header bg-primary text-white text-center">
                            <h2 class="mb-2"><i class="fas fa-sun mr-2"></i> My Day Tasks</h2>
                        </div>
                        <div class="card-body">
                            <?php
                            if (isset($_SESSION['project_error'])) {
                                echo '<div class="alert alert-danger">' . $_SESSION['project_error'] . '</div>';
                                unset($_SESSION['project_error']);
                            }
                            if (isset($_SESSION['project_success'])) {
                                echo '<div class="alert alert-success">' . $_SESSION['project_success'] . '</div>';
                                unset($_SESSION['project_success']);
                            }
                            ?>
                            <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap">
                                <div class="flex-grow-1 mr-2 mb-2">
                                    <input type="text" class="form-control mb-3" id="searchInput" placeholder="Search My Day..." onkeyup="searchProjects()">
                                    <script>
                                        function searchProjects() {
                                            var input, filter, table, tr, td, i, j, txtValue;
                                            input = document.getElementById("searchInput");
                                            filter = input.value.toLowerCase();
                                            table = document.querySelector(".table-responsive table");
                                            tr = table.getElementsByTagName("tr");

                                            for (i = 1; i < tr.length; i++) {
                                                tr[i].style.display = "none"; // Hide all rows initially
                                                td = tr[i].getElementsByTagName("td");
                                                for (j = 0; j < td.length; j++) {
                                                    if (td[j]) {
                                                        txtValue = td[j].textContent || td[j].innerText;
                                                        if (txtValue.toLowerCase().indexOf(filter) > -1) {
                                                            tr[i].style.display = ""; // Show row if match found
                                                            break; // No need to check other cells in this row
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    </script>
                                </div>
                                <div class="mb-4">
                                    <a href="./addtask.php" class="btn btn-success"><i class="fas fa-plus mr-2"></i>Add New Task</a>
                                </div>
                            </div>
                            <?php
                            $today = date('Y-m-d');

                            // Get tasks due today
                            $sql = "   
                                    SELECT tasks.*, projects.name AS project_name, statuses.name AS status_name
                                    FROM tasks
                                    LEFT JOIN projects ON tasks.project_id = projects.id
                                    LEFT JOIN statuses ON tasks.status_id = statuses.id
                                    WHERE tasks.user_id = ? AND tasks.due_date = ?
                                    ORDER BY tasks.due_date ASC, tasks.priority DESC";
                            $stmt = $con->prepare($sql);
                            $stmt->bind_param("is", $user_id, $today);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if ($result->num_rows > 0) {

                                echo '<div class="table-responsive">';
                                echo '<table class="table table-bordered table-hover" style="width:100%;">';
                                echo '<thead class="thead-light">';
                                echo '<tr>
                                            <th>Title</th>
                                            <th>Project</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Due Date</th>
                                            <th>Priority</th>
                                            <th>Updated At</th>
                                            <th>Actions</th>
                                        </tr>';
                                echo '</thead><tbody>';
                                while ($task = mysqli_fetch_assoc($result)) {
                                    $statusArr = [
                                        1 => 'Pending',
                                        2 => 'In Progress',
                                        3 => 'Completed',
                                        4 => 'Cancelled'
                                    ];
                                    $statusText = isset($statusArr[$task['status_id']]) ? $statusArr[$task['status_id']] : 'Unknown';
                                    $priorityText = ucfirst($task['priority']);
                                    $projectName = !empty($task['project_name']) ? htmlspecialchars($task['project_name']) : '-';
                                    echo '<tr>
                                                <td>' . htmlspecialchars($task['title']) . '</td>
                                                <td>' . $projectName . '</td>
                                                <td>' . htmlspecialchars($task['description']) . '</td>
                                                <td>' . $statusText . '</td>
                                                <td>' . htmlspecialchars($task['due_date']) . '</td>
                                                <td>' . $priorityText . '</td>
                                                <td>' . htmlspecialchars($task['updated_at']) . '</td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-info mr-1" onclick="openUpdateModal(' . $task['id'] . ', \'' . htmlspecialchars(addslashes($task['title'])) . '\', \'' . htmlspecialchars(addslashes($task['description'])) . '\', ' . $task['status_id'] . ', \'' . htmlspecialchars($task['due_date']) . '\', \'' . htmlspecialchars($task['priority']) . '\', \'' . $projectName . '\')"><i class="fas fa-edit"></i></button>
                                                </td>
                                            </tr>';
                                }
                                echo '</tbody></table></div>';
                            }
                             else {
                                echo '<div class="alert alert-info text-center">No tasks found. <a href="addtask.php" class="btn btn-primary btn-sm ml-2"><i class="fas fa-plus"></i> Add Task</a></div>';
                            }

                            // Handle update
                            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_task_id'])) {
                                $update_id = intval($_POST['update_task_id']);
                                $update_title = mysqli_real_escape_string($con, $_POST['update_title']);
                                $update_project = !empty($_POST['update_project']) ? intval($_POST['update_project']) : null;
                                $update_description = mysqli_real_escape_string($con, $_POST['update_description']);
                                $update_status = intval($_POST['update_status']);
                                $update_due_date = mysqli_real_escape_string($con, $_POST['update_due_date']);
                                $update_priority = mysqli_real_escape_string($con, $_POST['update_priority']);

                                // Build project part of query
                                if ($update_project) {
                                    $project_sql = "project_id='$update_project',";
                                } else {
                                    $project_sql = "project_id=NULL,";
                                }

                                $update_query = "UPDATE tasks SET title='$update_title', {$project_sql} description='$update_description', status_id='$update_status', due_date='$update_due_date', priority='$update_priority', updated_at=NOW() WHERE id='$update_id' AND user_id='$user_id'";
                                if (mysqli_query($con, $update_query)) {
                                    $_SESSION['task_success'] = "Task updated successfully.";
                                } else {
                                    $_SESSION['task_error'] = "Failed to update task.";
                                }
                                echo "<script>window.location.href='important.php';</script>";
                                exit;
                            }
                            ?>
                            <!-- Update Modal -->
                            <div class="modal fade" id="updateTaskModal" tabindex="-1" role="dialog" aria-labelledby="updateTaskModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form method="post" action="">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="updateTaskModalLabel">Update Task</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="update_task_id" id="update_task_id">
                                                <div class="form-group">
                                                    <label for="update_title">Title</label>
                                                    <input type="text" class="form-control" name="update_title" id="update_title" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="update_project">Project</label>
                                                    <select class="form-control" name="update_project" id="update_project">
                                                        <option value="">None</option>
                                                        <?php
                                                        $current_project_Name = isset($projectName) ? $projectName : '';
                                                        // Fetch projects for the user
                                                        $projects_query = "SELECT id, name FROM projects WHERE user_id = '$user_id'";
                                                        $projects_result = mysqli_query($con, $projects_query);
                                                        if ($projects_result && mysqli_num_rows($projects_result) > 0) {
                                                            while ($project = mysqli_fetch_assoc($projects_result)) {
                                                                $selected = ($project['name'] == $current_project_Name) ? 'selected' : '';
                                                                echo '<option value="' . $project['id'] . '" ' . $selected . '>' . htmlspecialchars($project['name']) . '</option>';
                                                                //echo '<option value="' . $project['id'] . '">' . htmlspecialchars($project['name']) . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="update_description">Description</label>
                                                    <textarea class="form-control" name="update_description" id="update_description" rows="3"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="update_status">Status</label>
                                                    <select class="form-control" name="update_status" id="update_status">
                                                        <option value="1">Pending</option>
                                                        <option value="2">In Progress</option>
                                                        <option value="3">Completed</option>
                                                        <option value="4">Cancelled</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="update_due_date">Due Date</label>
                                                    <input type="date" class="form-control" name="update_due_date" id="update_due_date">
                                                </div>
                                                <div class="form-group">
                                                    <label for="update_priority">Priority</label>
                                                    <select class="form-control" name="update_priority" id="update_priority">
                                                        <option value="low">Low</option>
                                                        <option value="medium">Medium</option>
                                                        <option value="high">High</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Update Task</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <script>
                                function openUpdateModal(id, title, description, status, due_date, priority, project) {
                                    $('#update_task_id').val(id);
                                    $('#update_title').val(title);
                                    $('#update_description').val(description);
                                    $('#update_status').val(status);
                                    $('#update_due_date').val(due_date);
                                    $('#update_priority').val(priority);
                                    $('#updateTaskModal').modal('show');
                                }
                            </script>

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
        .container-fluid {
            width: 100%;
            max-width: 100%;
        }

        @media (max-width: 768px) {
            footer {
                width: 100%;
                z-index: 100;
            }

            .card {
                height: 100%;
                min-height: 100vh;
            }
        }
    </style>
</body>

</html>
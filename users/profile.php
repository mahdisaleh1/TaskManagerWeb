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
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_phone'] = $user['phone'];
            $_SESSION['user_password'] = $user['password'];
        }
    }
} else {
    echo '<script>alert("You are not logged in. Please log in to access your profile.")</script>';
    header("Refresh:0.11; url=login.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
    <title>Profile ~ Task Manager</title>
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
                            echo '<a href="./users/login.php" class="btn btn-primary btn-block mb-3"><i class="fas fa-sign-in-alt mr-2"></i>Login</a>';
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
                                    <a class="dropdown-item" href="./profile.php"><i class="fas fa-user mr-2"></i>Profile</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="./logout.php"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="./manage/settings.php"><i class="fas fa-cog mr-2"></i>Settings</a>
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
            <div class="container-fluid vh-100 d-flex flex-column align-items-center justify-content-center" style="margin-left:0;">
                <div class="row justify-content-center w-100">
                    <div class="col-md-6 col-lg-5">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white text-center">
                                <h2 class="mb-2"><i class="fas fa-user mr-2"></i>Profile</h2>
                            </div>
                            <div class="card-body">
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <?php
                                    $userName = htmlspecialchars($_SESSION['user_name']);
                                    $userEmail = htmlspecialchars($_SESSION['user_email']);
                                    $userPhone = htmlspecialchars($_SESSION['user_phone']);
                                    ?>
                                    <form action="profile.php" method="post">
                                        <div class="form-group">
                                            <label for="name"><i class="fas fa-user mr-2"></i>Name</label>
                                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $userName; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email"><i class="fas fa-envelope mr-2"></i>Email Address</label>
                                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $userEmail; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="phone"><i class="fas fa-phone mr-2"></i>Phone</label>
                                            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $userPhone; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="password"><i class="fas fa-lock mr-2"></i>New Password</label>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block">Update Profile</button>
                                    </form>
                                    <?php
                                    // Handle profile update
                                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                        $name = mysqli_real_escape_string($con, $_POST['name']);
                                        $email = mysqli_real_escape_string($con, $_POST['email']);
                                        $phone = mysqli_real_escape_string($con, $_POST['phone']);
                                        $password = $_POST['password'];
                                        $user_id = $_SESSION['user_id'];

                                        if (!empty($password)) {
                                            //$hashed_password = password_hash($password, PASSWORD_DEFAULT);
                                            $update = "UPDATE users SET name='$name', email='$email', phone='$phone', password='$password' WHERE id='$user_id'";
                                        } else {
                                            $update = "UPDATE users SET name='$name', email='$email', phone='$phone' WHERE id='$user_id'";
                                        }
                                        if (mysqli_query($con, $update)) {
                                            echo '<div class="alert alert-success mt-3">Profile updated successfully.</div>';
                                            $_SESSION['user_name'] = $name;
                                            $_SESSION['user_email'] = $email;
                                            $_SESSION['user_phone'] = $phone;
                                        } else {
                                            echo '<div class="alert alert-danger mt-3">Error updating profile.</div>';
                                        }
                                    }
                                    ?>
                                <?php else: ?>
                                    <div class="alert alert-warning text-center">You are not logged in.</div>
                                <?php endif; ?>
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
    </style>
</body>

</html>
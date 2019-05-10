<?php include('../../config.php'); ?>
<?php include(INCLUDE_PATH . '/logic/common_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/middleware.php'); ?>
<?php include(ROOT_PATH . '/admin/users/userLogic.php'); ?>
<?php
$sql = "SELECT id, username, email, profile_picture FROM users WHERE id=?";
$user = getSingleRecord($sql, 'i', [$_SESSION['user']['id']]);
$roles = getMultipleRecords("SELECT * FROM roles");

$user_id = $user['id'];
$username = $user['username'];
$email = $user['email'];
$profile_picture = $user['profile_picture'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>UserAccounts - Edit Profile</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <!-- Custom styles -->
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<?php include(INCLUDE_PATH . "/layouts/admin_navbar.php") ?>

<div class="container">
    <div class="row">

        <form action="editProfile.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
            <div class="col-md-8 col-md-offset-2">
                <h2 class="text-center">Edit Your Profile Info</h2>
                <hr>
                <div class="col-md-6" style="text-align: center;">
                    <?php if (isset($profile_picture)): ?>
                        <img src="<?php echo BASE_URL . '/assets/images/' . $profile_picture; ?>" id="profile_img" style="height: 150px; border-radius: 50%" alt="">
                    <?php else: ?>
                        <img src="http://via.placeholder.com/150x150" id="profile_img" style="height: 150px; border-radius: 50%" alt="">
                    <?php endif; ?>
                    <h3>Change Profile Picture</h3>
                    <!-- hidden file input to trigger with JQuery  -->
                    <input type="file" name="profile_picture" id="profile_input" value="" style="display: none;">
                </div>

                <div class="col-md-6">
                    <div class="form-group <?php echo isset($errors['username']) ? 'has-error' : '' ?>">
                        <label class="control-label">Username</label>
                        <input type="text" name="username" value="<?php echo $username; ?>" class="form-control">
                        <?php if (isset($errors['username'])): ?>
                            <span class="help-block"><?php echo $errors['username'] ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group <?php echo isset($errors['email']) ? 'has-error' : '' ?>">
                        <label class="control-label">Email Address</label>
                        <input type="email" name="email" value="<?php echo $email; ?>" class="form-control">
                        <?php if (isset($errors['email'])): ?>
                            <span class="help-block"><?php echo $errors['email'] ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group <?php echo isset($errors['passwordOld']) ? 'has-error' : '' ?>">
                        <label class="control-label">Old Password</label>
                        <input type="password" name="passwordOld" class="form-control">
                        <?php if (isset($errors['passwordOld'])): ?>
                            <span class="help-block"><?php echo $errors['passwordOld'] ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group <?php echo isset($errors['password']) ? 'has-error' : '' ?>">
                        <label class="control-label">Password</label>
                        <input type="password" name="password" class="form-control">
                        <?php if (isset($errors['password'])): ?>
                            <span class="help-block"><?php echo $errors['password'] ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group <?php echo isset($errors['passwordConf']) ? 'has-error' : '' ?>">
                        <label class="control-label">Password confirmation</label>
                        <input type="password" name="passwordConf" class="form-control">
                        <?php if (isset($errors['passwordConf'])): ?>
                            <span class="help-block"><?php echo $errors['passwordConf'] ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group <?php echo isset($errors['role_id']) ? 'has-error' : '' ?>">
                        <label class="control-label">User Role</label>
                        <select class="form-control" name="role_id" >
                            <option value="" ></option>
                            <?php foreach ($roles as $role): ?>
                                <?php if ($role['name'] === $_SESSION['user']['role']): ?>
                                    <option value="<?php echo $role['id'] ?>" selected><?php echo $role['name'] ?></option>
                                <?php else: ?>
                                    <option value="<?php echo $role['id'] ?>"><?php echo $role['name'] ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['role_id'])): ?>
                            <span class="help-block"><?php echo $errors['role_id'] ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="signup_btn" class="btn btn-danger pull-right">Delete Your Account</button>
                        <button type="submit" name="update_profile" class="btn btn-success">Update Profile</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
<?php include(INCLUDE_PATH . "/layouts/footer.php") ?>
<script type="text/javascript" src="../../assets/js/display_profile_image.js"></script>
<?php include("../conn.php");
session_start();
?>
<?php $active4 = "active"; ?>
<?php
if (isset($_GET['remove']) || isset($_GET['add'])) {
    $id = $_GET['id'];
    if (isset($_GET['remove'])) {
        $sql = "DELETE FROM unapproved_job WHERE `unapproved_job`.`id` = '$id';";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo '<script> alert("Delete successful.");</script>';
        }
    } else {
        $sql_getdata = "SELECT * FROM unapproved_job WHERE `unapproved_job`.`id`='$id';";
        $result_getdata = mysqli_query($conn, $sql_getdata);
        $row_getdata = mysqli_fetch_assoc($result_getdata);

        $userId = $row_getdata['userid'];
        $ctg = $row_getdata['category'];
        $title = $row_getdata['title'];
        $jobType = $row_getdata['job_type'];
        $company = $row_getdata['company'];
        $location = $row_getdata['location'];
        $price = $row_getdata['price'];
        $exitDay = $row_getdata['exit_day'];
        $responsibilities = $row_getdata['responsibilities'];
        $requirements = $row_getdata['requirement'];

        $sql_setdata = "INSERT INTO `jobtable` (`userId`, `category`, `title`, `jobType`, `company`, `location`, `price`, `exitDay`, `responsibilities`, `requirements`)
                       VALUES ('$userId', '$ctg', '$title', '$jobType', '$company', '$location', '$price', '$exitDay', '$responsibilities', '$requirements')";
        mysqli_query($conn, $sql_setdata);
        $sql = "DELETE FROM unapproved_job WHERE `unapproved_job`.`id` = $id";
        $result = mysqli_query($conn, $sql);
        $conn->close();
        if ($result) {
            echo '<script> alert("Job approved successful.");</script>';
            header('location:unapproved_job.php');
        }
    }
}
?>
<?php
$note = "";
if (isset($_GET["search"]) || isset($_GET["filter"])) {
    $search1 = $_GET["search"];
    $filter = $_GET["filter"];
    $search = mysqli_real_escape_string($conn, $search1);

    if (!empty($search)) {
        if ($filter == "All Type") {
            $sql = "SELECT * FROM unapproved_job WHERE (title LIKE '%$search%' OR company LIKE '%$search%' OR location LIKE '%$search%' OR price LIKE '%$search%' OR exitDay LIKE '%$search%')";
            $result = mysqli_query($conn, $sql);
            if ($result == false) {
                // echo '<script> alert("Data not found.");</script>';
            }
        } else if ($filter == "Full Time" || $filter == "Part Time") {
            $sql = "SELECT * FROM unapproved_job WHERE (title LIKE '%$search%' OR company LIKE '%$search%' OR location LIKE '%$search%' OR price LIKE '%$search%' OR exitDay LIKE '%$search%') AND job_type = '$filter'";
            $result = mysqli_query($conn, $sql);
            if ($result == false) {
            }
        }
    } else {
        if ($filter == "Full Time" || $filter == "Part Time") {
            $sql = "SELECT * FROM unapproved_job WHERE job_type = '$filter'";
            $result = mysqli_query($conn, $sql);
            if ($result == false) {
                // echo '<script> alert("Data not found.");</script>';
                $note = "Data not found.";
            }
        } else {
            $sql = "SELECT * FROM unapproved_job ";
        }
    }
} else {
    $sql = "SELECT * FROM unapproved_job ";
}

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/job_list.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Unapproved Jobs</title>
    <script>
        function confirmAction(action, id) {
            if (confirm(`Are you sure you want to ${action} this job?`)) {
                window.location.href = `unapproved_job.php?${action}=${action}&id=${id}`;
            }
        }
    </script>
</head>

<body>
    <header>
        <link rel="stylesheet" href="../../CSS/header.css">
        <div class="headerbar">
            <h3>The #1 Site for Remote Jobs</h3>
        </div>
    </header>

    <?php include_once("admin_navbar.php"); ?>
    <form action="unapproved_job.php" method="get">
        <div class="searchbar">
            <input type="search" name="search" placeholder="<?php if (isset($_GET["search"])) {echo $_GET["search"];} else {echo "Search";} ?>">
            <select name="filter" id="">
                <option value="All Type">Job Type</option>
                <option value="Full Time">Full Time</option>
                <option value="Part Time">Part Time</option>
            </select>
            <button><i class="fa fa-fw fa-search"></i></button>
        </div>
    </form>
    <h3 id="phpmg">
        <?php
        if (isset($_GET["filter"])) {
            echo "Job Type: " . $_GET['filter'];
        }
        ?>
    </h3>
    <br>
    <div class="job_listings">
        <div class="job_row">
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                    <div class="job">
                        <button id="remove" type="button" onclick="confirmAction('remove', <?php echo $row['id']; ?>)"><i class="fa fa-remove"></i></button>
                        <button id="remove" type="button" onclick="confirmAction('add', <?php echo $row['id']; ?>)"><i class="fa fa-check-circle-o" style="color:blue"></i></button>

                        <h2><?php echo $row["title"]; ?></h2>
                        <div class="job_details">
                            <h4>Job Category:</h4>
                            <p><?php echo $row['category']; ?></p>
                            <h4>Job Type:</h4>
                            <p><?php echo $row['job_type']; ?></p>
                            <h4>Company:</h4>
                            <p><?php echo $row['company']; ?></p>
                            <h4>Location:</h4>
                            <p><?php echo $row['location']; ?></p>
                            <h4>Price:</h4>
                            <p>$<?php echo $row['price']; ?> per month</p>
                        </div>
                        <center>
                            <form action="more_details.php" method="get">
                                <button class="apply-btn" type="submit" name="apply">
                                    <input type="hidden" name="jobId" value="<?php echo $row['id']; ?>">
                                    More Details <i class="fas fa-info-circle"></i>
                                </button>
                            </form>
                        </center>
                    </div>
                <?php
                }
                ?>
        </div>
    </div>
<?php
            } else {
                $note = "Data not found.";
?>

    <div class="note" style="width:100%; text-align: center; ">
        <h1 style="color: red">
            <?php echo $note; ?>
        </h1>
    </div>
<?php
            }
?>
</body>

</html>

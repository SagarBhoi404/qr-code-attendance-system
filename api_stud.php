<?php
// Include PHPExcel
require 'PHPExcel/PHPExcel.php';
require 'PHPExcel/PHPExcel/IOFactory.php';
// print_r($_POST);
require "conn.php";
session_start();

if ($_SESSION['usertype'] != 'ADMIN') {
    session_destroy();
    header("location: login.php");
    exit();
}

if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {

    if (isset($_GET['type']) && $_GET['type'] == "add") {
        $enrollmentno = mysqli_escape_string($conn, $_POST['enrollmentno']);
        $studentname = mysqli_escape_string($conn, $_POST['studentname']);
        $semester = mysqli_escape_string($conn, $_POST['semester']);
        $branch = mysqli_escape_string($conn, $_POST['branch']);
        $rollno = mysqli_escape_string($conn, $_POST['rollno']);
        $batch = mysqli_escape_string($conn, $_POST['batch']);
        $password = mysqli_escape_string($conn, $_POST['password']);

        $sql = "INSERT INTO `students`(`enrollment_no`, `name`, `semester`, `branch`, `roll_no`, `batch`, `password`) VALUES ('$enrollmentno','$studentname','$semester','$branch','$rollno','$batch','$password')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
        Student Added.
        </div>';
            header("location: stud_details.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
        Something went wrong!.
        </div>';
            header("location: stud_details.php");
            exit();
        }
    }

    if (isset($_GET['type']) && $_GET['type'] == "delete") {
        $enroll = mysqli_escape_string($conn, $_GET['enroll']);

        $sql = "DELETE FROM `students` WHERE enrollment_no='$enroll'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
        Student Deleted.
        </div>';
            header("location: stud_details.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
        Something went wrong!.
        </div>';
            header("location: stud_details.php");
            exit();
        }
    }

    if (isset($_GET['type']) && $_GET['type'] == "update") {
        $enrollmentno = mysqli_escape_string($conn, $_POST['enrollmentno']);
        $studentname = mysqli_escape_string($conn, $_POST['studentname']);
        $semester = mysqli_escape_string($conn, $_POST['semester']);
        $branch = mysqli_escape_string($conn, $_POST['branch']);
        $shift = mysqli_escape_string($conn, $_POST['shift']);
        $rollno = mysqli_escape_string($conn, $_POST['rollno']);
        $batch = mysqli_escape_string($conn, $_POST['batch']);
        $password = mysqli_escape_string($conn, $_POST['password']);

        $sql = "UPDATE `students` SET `name`='$studentname',`semester`='$semester',`branch`='$branch',`shift`='$shift',`roll_no`='$rollno',`batch`='$batch',`password`='$password' WHERE `enrollment_no`='$enrollmentno'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
        Student Details Updated.
        </div>';
            header("location: stud_details.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
        Something went wrong!.
        </div>';
            header("location: stud_details.php");
            exit();
        }
    }


    if (isset($_GET['type']) && $_GET['type'] == "bulk" && isset($_FILES['file'])) {
        // File upload
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['file']['name']);

        // Check file extension
        $fileExtension = pathinfo($uploadFile, PATHINFO_EXTENSION);
        if (!in_array($fileExtension, ['xls', 'xlsx'])) {
            // die('Invalid file type. Please upload an Excel file.');
            $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
       Invalid file type. Please upload an Excel file.
        </div>';
            header("location: stud_details.php");
            exit();
        }

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {

            // Expected headers
            $expectedHeaders = ['enrollment', 'name', 'semester', 'branch', 'roll no', 'batch', 'password'];


            try {
                // Load the Excel file
                $objPHPExcel = PHPExcel_IOFactory::load($uploadFile);

                // Get the first worksheet
                $worksheet = $objPHPExcel->getSheet(0);

                // Get the header row
                $headerRow = $worksheet->getRowIterator(1)->current();
                $cellIterator = $headerRow->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $headers = [];
                foreach ($cellIterator as $cell) {
                    $headers[] = trim(strtolower($cell->getValue()));  // Trim and convert to lowercase
                }

                // Print the read headers for debugging
                // echo "<pre>Read headers: ";
                // print_r($headers);

                // Validate headers
                if ($headers !== $expectedHeaders) {
                    // echo "Expected headers: ";
                    // print_r($expectedHeaders);
                    // die('Invalid file structure. Please ensure the Excel file has the correct headers in the correct order.');
                    $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
       Invalid file structure. Please ensure the Excel file has the correct headers in the correct order.
        </div>';
                    header("location: stud_details.php");
                    exit();
                }

                // Prepare SQL statement
                $stmt = $conn->prepare("INSERT INTO students (enrollment_no, name, semester, branch, roll_no, batch, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param(
                    "isisiss",
                    $enrollment,
                    $name,
                    $semester,
                    $branch,
                    $rollno,
                    $batch,
                    $password
                );

                // Iterate through rows and columns
                $firstRow = true;
                foreach ($worksheet->getRowIterator() as $row) {
                    if ($firstRow) {
                        $firstRow = false;
                        continue; // Skip the first row (header)
                    }

                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false); // Loop through all cells, even if they are empty

                    $rowData = [];
                    foreach ($cellIterator as $cell) {
                        $rowData[] = $cell->getValue();
                    }

                    // Assign values from row data
                    $enrollment = $rowData[0];
                    $name = $rowData[1];
                    $semester = $rowData[2];
                    $branch = $rowData[3];
                    $rollno = $rowData[4];
                    $batch = $rowData[5];
                    $password = $rowData[6];

                    // Execute SQL statement
                    $stmt->execute();
                }

                // Close statement and connection
                $stmt->close();
                $conn->close();

                // echo "Data inserted successfully!";
                $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
        Data inserted successfully.
        </div>';
                header("location: stud_details.php");
                exit();
            } catch (Exception $e) {
                // die('Error loading file "' . pathinfo($filePath, PATHINFO_BASENAME) . '": ' . $e->getMessage());
                $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
       Error loading file.
        </div>';
                header("location: stud_details.php");
                exit();
            }
        } else {

            $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
       File upload failed.
        </div>';
            header("location: stud_details.php");
            exit();
        }
    } else {
        $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
        Something went wrong! OR File Is Not Select.
        </div>';
        header("location: stud_details.php");
        exit();
    }
} else {
    header("location: login.php");
    exit();
}

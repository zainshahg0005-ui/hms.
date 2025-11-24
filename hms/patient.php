<?php
session_start();
include('db.php');
if(!isset($_SESSION['admin'])){
    header("Location: index.php");
    exit;
}

// Add Patient
if(isset($_POST['add'])){
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact'];
    $conn->query("INSERT INTO Patient (Name,DOB,Gender,ContactInfo) VALUES ('$name','$dob','$gender','$contact')");
}

// Edit Patient
if(isset($_POST['edit'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact'];
    $conn->query("UPDATE Patient SET Name='$name', DOB='$dob', Gender='$gender', ContactInfo='$contact' WHERE PatientID=$id");
}

// Delete Patient
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM Patient WHERE PatientID=$id");
}

// Fetch Patients
$result = $conn->query("SELECT * FROM Patient");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patients - HMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('navbar.php'); ?>
    
    <style>
    body {
    background-image: url('img/img5.jpg');
    background-size:cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    }
    </style>

<h2>Patients</h2>

<form method="POST">
    <input type="hidden" name="id" id="patient_id">
    <input type="text" name="name" id="patient_name" placeholder="Name" required>
    <input type="date" name="dob" id="patient_dob" required>
    <select name="gender" id="patient_gender" required>
        <option value="">Select Gender</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
    </select>
    <input type="text" name="contact" id="patient_contact" placeholder="Contact Info" required>
    <input type="submit" name="add" value="Add Patient" id="addBtn">
    <input type="submit" name="edit" value="Update Patient" id="editBtn" style="display:none;">
</form>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>DOB</th>
        <th>Gender</th>
        <th>Contact</th>
        <th>Action</th>
    </tr>
    <?php while($row = $result->fetch_assoc()){ ?>
    <tr>
        <td><?php echo $row['PatientID']; ?></td>
        <td><?php echo $row['Name']; ?></td>
        <td><?php echo $row['DOB']; ?></td>
        <td><?php echo $row['Gender']; ?></td>
        <td><?php echo $row['ContactInfo']; ?></td>
        <td>
            <a href="#" onclick="editPatient('<?php echo $row['PatientID']; ?>','<?php echo $row['Name']; ?>','<?php echo $row['DOB']; ?>','<?php echo $row['Gender']; ?>','<?php echo $row['ContactInfo']; ?>')">Edit</a> |
            <a href="patient.php?delete=<?php echo $row['PatientID']; ?>">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>

<script>
function editPatient(id, name, dob, gender, contact){
    document.getElementById('patient_id').value = id;
    document.getElementById('patient_name').value = name;
    document.getElementById('patient_dob').value = dob;
    document.getElementById('patient_gender').value = gender;
    document.getElementById('patient_contact').value = contact;
    document.getElementById('addBtn').style.display = 'none';
    document.getElementById('editBtn').style.display = 'inline';
}
</script>

</body>
</html>

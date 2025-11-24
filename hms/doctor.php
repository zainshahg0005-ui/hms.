<?php
session_start();
include('db.php');
if(!isset($_SESSION['admin'])){
    header("Location: index.php");
    exit;
}

// Add Doctor
if(isset($_POST['add'])){
    $name = $_POST['name'];
    $specialty = $_POST['specialty'];
    $contact = $_POST['contact'];
    $conn->query("INSERT INTO Doctor (Name,Specialty,ContactInfo) VALUES ('$name','$specialty','$contact')");
}

// Edit Doctor
if(isset($_POST['edit'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $specialty = $_POST['specialty'];
    $contact = $_POST['contact'];
    $conn->query("UPDATE Doctor SET Name='$name', Specialty='$specialty', ContactInfo='$contact' WHERE DoctorID=$id");
}

// Delete Doctor
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM Doctor WHERE DoctorID=$id");
}

// Fetch Doctors
$result = $conn->query("SELECT * FROM Doctor");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctors - HMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <?php include('navbar.php'); ?>

    <style>
    body {
    background-image: url('img/doc.jpg');
    background-size:cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    }
    </style>

<h2>Doctors</h2>

<!-- Add/Edit Form -->
<form method="POST">
    <input type="hidden" name="id" id="doctor_id">
    <input type="text" name="name" id="doctor_name" placeholder="Name" required>
    <input type="text" name="specialty" id="doctor_specialty" placeholder="Specialty" required>
    <input type="text" name="contact" id="doctor_contact" placeholder="Contact Info" required>
    <input type="submit" name="add" value="Add Doctor" id="addBtn">
    <input type="submit" name="edit" value="Update Doctor" id="editBtn" style="display:none;">
</form>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Specialty</th>
        <th>Contact</th>
        <th>Action</th>
    </tr>
    <?php while($row = $result->fetch_assoc()){ ?>
    <tr>
        <td><?php echo $row['DoctorID']; ?></td>
        <td><?php echo $row['Name']; ?></td>
        <td><?php echo $row['Specialty']; ?></td>
        <td><?php echo $row['ContactInfo']; ?></td>
        <td>
            <a href="#" onclick="editDoctor('<?php echo $row['DoctorID']; ?>','<?php echo $row['Name']; ?>','<?php echo $row['Specialty']; ?>','<?php echo $row['ContactInfo']; ?>')">Edit</a> |
            <a href="doctor.php?delete=<?php echo $row['DoctorID']; ?>">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>

<script>
function editDoctor(id, name, specialty, contact){
    document.getElementById('doctor_id').value = id;
    document.getElementById('doctor_name').value = name;
    document.getElementById('doctor_specialty').value = specialty;
    document.getElementById('doctor_contact').value = contact;
    document.getElementById('addBtn').style.display = 'none';
    document.getElementById('editBtn').style.display = 'inline';
}
</script>

</body>
</html>

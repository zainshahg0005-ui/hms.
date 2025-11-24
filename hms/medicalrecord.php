<?php
session_start();
include('db.php');
if(!isset($_SESSION['admin'])){
    header("Location: index.php");
    exit;
}

$patients = $conn->query("SELECT * FROM Patient");

// Add Medical Record
if(isset($_POST['add'])){
    $patient = $_POST['patient'];
    $diagnosis = $_POST['diagnosis'];
    $treatment = $_POST['treatment'];
    $date = $_POST['date'];
    $conn->query("INSERT INTO MedicalRecord (PatientID, Diagnosis, Treatment, RecordDate) VALUES ('$patient','$diagnosis','$treatment','$date')");
}

// Edit Medical Record
if(isset($_POST['edit'])){
    $id = $_POST['id'];
    $patient = $_POST['patient'];
    $diagnosis = $_POST['diagnosis'];
    $treatment = $_POST['treatment'];
    $date = $_POST['date'];
    $conn->query("UPDATE MedicalRecord SET PatientID='$patient', Diagnosis='$diagnosis', Treatment='$treatment', RecordDate='$date' WHERE RecordID=$id");
}

// Delete Record
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM MedicalRecord WHERE RecordID=$id");
}

// Fetch Records
$result = $conn->query("SELECT m.RecordID, p.Name as PatientName, m.Diagnosis, m.Treatment, m.RecordDate 
                        FROM MedicalRecord m 
                        JOIN Patient p ON m.PatientID = p.PatientID");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Medical Records - HMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('navbar.php'); ?>

        <style>
    body {
    background-image: url('img/rec.jpg');
    background-size:cover;
    background-position: center;
    background-repeat: no-repeat;
    /* background-attachment: fixed; */
    }
    </style>


<h2>Medical Records</h2>

<form method="POST">
    <input type="hidden" name="id" id="record_id">
    <select name="patient" id="record_patient" required>
        <option value="">Select Patient</option>
        <?php
        $patients = $conn->query("SELECT * FROM Patient");
        while($row = $patients->fetch_assoc()){
            echo "<option value='".$row['PatientID']."'>".$row['Name']."</option>";
        }
        ?>
    </select>
    <input type="text" name="diagnosis" id="record_diagnosis" placeholder="Diagnosis" required>
    <input type="text" name="treatment" id="record_treatment" placeholder="Treatment" required>
    <input type="date" name="date" id="record_date" required>
    <input type="submit" name="add" value="Add Record" id="addBtn">
    <input type="submit" name="edit" value="Update Record" id="editBtn" style="display:none;">
</form>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Patient</th>
        <th>Diagnosis</th>
        <th>Treatment</th>
        <th>Date</th>
        <th>Action</th>
    </tr>
    <?php while($row = $result->fetch_assoc()){ ?>
    <tr>
        <td><?php echo $row['RecordID']; ?></td>
        <td><?php echo $row['PatientName']; ?></td>
        <td><?php echo $row['Diagnosis']; ?></td>
        <td><?php echo $row['Treatment']; ?></td>
        <td><?php echo $row['RecordDate']; ?></td>
        <td>
            <a href="#" onclick="editRecord('<?php echo $row['RecordID']; ?>','<?php echo $row['PatientName']; ?>','<?php echo $row['Diagnosis']; ?>','<?php echo $row['Treatment']; ?>','<?php echo $row['RecordDate']; ?>')">Edit</a> |
            <a href="medicalrecord.php?delete=<?php echo $row['RecordID']; ?>">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>

<script>
function editRecord(id, patientName, diagnosis, treatment, date){
    let patientSelect = document.getElementById('record_patient');
    for(let i=0;i<patientSelect.options.length;i++){
        if(patientSelect.options[i].text === patientName){
            patientSelect.selectedIndex = i;
        }
    }
    document.getElementById('record_id').value = id;
    document.getElementById('record_diagnosis').value = diagnosis;
    document.getElementById('record_treatment').value = treatment;
    document.getElementById('record_date').value = date;
    document.getElementById('addBtn').style.display = 'none';
    document.getElementById('editBtn').style.display = 'inline';
}
</script>

</body>
</html>

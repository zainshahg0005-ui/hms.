<?php
session_start();
include('db.php');
if(!isset($_SESSION['admin'])){
    header("Location: index.php");
    exit;
}

$patients = $conn->query("SELECT * FROM Patient");
$doctors = $conn->query("SELECT * FROM Doctor");

// Add Appointment
if(isset($_POST['add'])){
    $patient = $_POST['patient'];
    $doctor = $_POST['doctor'];
    $date = $_POST['date'];
    $reason = $_POST['reason'];
    $conn->query("INSERT INTO Appointment (PatientID, DoctorID, AppointmentDate, Reason) VALUES ('$patient','$doctor','$date','$reason')");
}

// Edit Appointment
if(isset($_POST['edit'])){
    $id = $_POST['id'];
    $patient = $_POST['patient'];
    $doctor = $_POST['doctor'];
    $date = $_POST['date'];
    $reason = $_POST['reason'];
    $conn->query("UPDATE Appointment SET PatientID='$patient', DoctorID='$doctor', AppointmentDate='$date', Reason='$reason' WHERE AppointmentID=$id");
}

// Delete Appointment
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM Appointment WHERE AppointmentID=$id");
}

// Fetch Appointments
$result = $conn->query("SELECT a.AppointmentID, p.Name as PatientName, d.Name as DoctorName, a.AppointmentDate, a.Reason 
                        FROM Appointment a 
                        JOIN Patient p ON a.PatientID = p.PatientID 
                        JOIN Doctor d ON a.DoctorID = d.DoctorID");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Appointments - HMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('navbar.php'); ?>
    
    <style>
    body {
    background-image: url('img/samp1.jpg');
    background-size:cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    }
    </style>


    <h2>Appointments</h2>

<form method="POST">
    <input type="hidden" name="id" id="appointment_id">
    <select name="patient" id="appointment_patient" required>
        <option value="">Select Patient</option>
        <?php
        $patients = $conn->query("SELECT * FROM Patient");
        while($row = $patients->fetch_assoc()){
            echo "<option value='".$row['PatientID']."'>".$row['Name']."</option>";
        }
        ?>
    </select>
    <select name="doctor" id="appointment_doctor" required>
        <option value="">Select Doctor</option>
        <?php
        $doctors = $conn->query("SELECT * FROM Doctor");
        while($row = $doctors->fetch_assoc()){
            echo "<option value='".$row['DoctorID']."'>".$row['Name']."</option>";
        }
        ?>
    </select>
    <input type="date" name="date" id="appointment_date" required>
    <input type="text" name="reason" id="appointment_reason" placeholder="Reason" required>
    <input type="submit" name="add" value="Add Appointment" id="addBtn">
    <input type="submit" name="edit" value="Update Appointment" id="editBtn" style="display:none;">
</form>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Patient</th>
        <th>Doctor</th>
        <th>Date</th>
        <th>Reason</th>
        <th>Action</th>
    </tr>
    <?php while($row = $result->fetch_assoc()){ ?>
    <tr>
        <td><?php echo $row['AppointmentID']; ?></td>
        <td><?php echo $row['PatientName']; ?></td>
        <td><?php echo $row['DoctorName']; ?></td>
        <td><?php echo $row['AppointmentDate']; ?></td>
        <td><?php echo $row['Reason']; ?></td>
        <td>
            <a href="#" onclick="editAppointment('<?php echo $row['AppointmentID']; ?>','<?php echo $row['PatientName']; ?>','<?php echo $row['DoctorName']; ?>','<?php echo $row['AppointmentDate']; ?>','<?php echo $row['Reason']; ?>')">Edit</a> |
            <a href="appointment.php?delete=<?php echo $row['AppointmentID']; ?>">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>

<script>
function editAppointment(id, patientName, doctorName, date, reason){
    // Map names to IDs
    let patientSelect = document.getElementById('appointment_patient');
    for(let i=0;i<patientSelect.options.length;i++){
        if(patientSelect.options[i].text === patientName){
            patientSelect.selectedIndex = i;
        }
    }
    let doctorSelect = document.getElementById('appointment_doctor');
    for(let i=0;i<doctorSelect.options.length;i++){
        if(doctorSelect.options[i].text === doctorName){
            doctorSelect.selectedIndex = i;
        }
    }
    document.getElementById('appointment_id').value = id;
    document.getElementById('appointment_date').value = date;
    document.getElementById('appointment_reason').value = reason;
    document.getElementById('addBtn').style.display = 'none';
    document.getElementById('editBtn').style.display = 'inline';
}
</script>

</body>
</html>

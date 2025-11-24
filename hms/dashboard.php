<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: index.php");
    exit;
}

include 'db.php';

// Fetch counts
$patientCount = $conn->query("SELECT COUNT(*) AS total FROM Patient")->fetch_assoc()['total'];
$doctorCount = $conn->query("SELECT COUNT(*) AS total FROM Doctor")->fetch_assoc()['total'];
$appointmentCount = $conn->query("SELECT COUNT(*) AS total FROM Appointment WHERE AppointmentDate = CURDATE()")->fetch_assoc()['total'];
$recordCount = $conn->query("SELECT COUNT(*) AS total FROM MedicalRecord")->fetch_assoc()['total'];
$billingCount = $conn->query("SELECT COUNT(*) AS total FROM Bill WHERE Status='Unpaid'")->fetch_assoc()['total'];

// Since you don't have an activities table, we can show latest 5 appointments as recent activities
$recentActivities = $conn->query("
    SELECT a.AppointmentID, p.Name AS PatientName, d.Name AS DoctorName, a.AppointmentDate 
    FROM Appointment a 
    JOIN Patient p ON a.PatientID = p.PatientID 
    JOIN Doctor d ON a.DoctorID = d.DoctorID 
    ORDER BY a.AppointmentDate DESC 
    LIMIT 5
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - HMS</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <header>
        <h1>Welcome, <?php echo $_SESSION['admin']; ?></h1>
        <nav>
            <a href="patient.php">Patients</a> |
            <a href="doctor.php">Doctors</a> |
            <a href="appointment.php">Appointments</a> |
            <a href="medicalrecord.php">Medical Records</a> |
            <a href="bill.php">Billing</a> |
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <section class="dashboard-cards">
            <div class="card">
                <h2>Patients</h2>
                <p>Total: <?php echo $patientCount; ?></p>
            </div>
            <div class="card">
                <h2>Doctors</h2>
                <p>Available: <?php echo $doctorCount; ?></p>
            </div>
            <div class="card">
                <h2>Appointments</h2>
                <p>Today: <?php echo $appointmentCount; ?></p>
            </div>
            <div class="card">
                <h2>Medical Records</h2>
                <p>Updated: <?php echo $recordCount; ?></p>
            </div>
            <div class="card">
                <h2>Billing</h2>
                <p>Pending: <?php echo $billingCount; ?></p>
            </div>
        </section>

        <section class="recent-activities">
            <h2>Recent Appointments</h2>
            <ul>
                <?php
                if($recentActivities && $recentActivities->num_rows > 0){
                    while($row = $recentActivities->fetch_assoc()){
                        echo "<li>Appointment #" . $row['AppointmentID'] . ": " . htmlspecialchars($row['PatientName']) . 
                             " with Dr. " . htmlspecialchars($row['DoctorName']) . 
                             " on " . $row['AppointmentDate'] . "</li>";
                    }
                } else {
                    echo "<li>No recent appointments</li>";
                }
                ?>
            </ul>
        </section>
    </main>
</body>
</html>

<?php $conn->close(); ?>

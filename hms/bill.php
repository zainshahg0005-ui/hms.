<?php
session_start();
include('db.php');
if(!isset($_SESSION['admin'])){
    header("Location: index.php");
    exit;
}

$patients = $conn->query("SELECT * FROM Patient");

// Add Bill
if(isset($_POST['add'])){
    $patient = $_POST['patient'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $status = $_POST['status'];
    $conn->query("INSERT INTO Bill (PatientID, Amount, BillDate, Status) VALUES ('$patient','$amount','$date','$status')");
}

// Edit Bill
if(isset($_POST['edit'])){
    $id = $_POST['id'];
    $patient = $_POST['patient'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $status = $_POST['status'];
    $conn->query("UPDATE Bill SET PatientID='$patient', Amount='$amount', BillDate='$date', Status='$status' WHERE BillID=$id");
}

// Delete Bill
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM Bill WHERE BillID=$id");
}

// Fetch Bills
$result = $conn->query("SELECT b.BillID, p.Name as PatientName, b.Amount, b.BillDate, b.Status 
                        FROM Bill b 
                        JOIN Patient p ON b.PatientID = p.PatientID");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Billing - HMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('navbar.php'); ?>

    <style>
body {
    background-image: url('img/img1.jpg');
    background-size:cover;
    background-position: center;
    background-repeat: no-repeat;
}
</style>

<h2>Billing</h2>

<form method="POST">
    <input type="hidden" name="id" id="bill_id">
    <select name="patient" id="bill_patient" required>
        <option value="">Select Patient</option>
        <?php
        $patients = $conn->query("SELECT * FROM Patient");
        while($row = $patients->fetch_assoc()){
            echo "<option value='".$row['PatientID']."'>".$row['Name']."</option>";
        }
        ?>
    </select>
    <input type="number" step="0.01" name="amount" id="bill_amount" placeholder="Amount" required>
    <input type="date" name="date" id="bill_date" required>
    <select name="status" id="bill_status" required>
        <option value="">Select Status</option>
        <option value="Paid">Paid</option>
        <option value="Unpaid">Unpaid</option>
    </select>
    <input type="submit" name="add" value="Add Bill" id="addBtn">
    <input type="submit" name="edit" value="Update Bill" id="editBtn" style="display:none;">
</form>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Patient</th>
        <th>Amount</th>
        <th>Date</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php while($row = $result->fetch_assoc()){ ?>
    <tr>
        <td><?php echo $row['BillID']; ?></td>
        <td><?php echo $row['PatientName']; ?></td>
        <td><?php echo $row['Amount']; ?></td>
        <td><?php echo $row['BillDate']; ?></td>
        <td><?php echo $row['Status']; ?></td>
        <td>
            <a href="#" onclick="editBill('<?php echo $row['BillID']; ?>','<?php echo $row['PatientName']; ?>','<?php echo $row['Amount']; ?>','<?php echo $row['BillDate']; ?>','<?php echo $row['Status']; ?>')">Edit</a> |
            <a href="bill.php?delete=<?php echo $row['BillID']; ?>">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>

<script>
function editBill(id, patientName, amount, date, status){
    let patientSelect = document.getElementById('bill_patient');
    for(let i=0;i<patientSelect.options.length;i++){
        if(patientSelect.options[i].text === patientName){
            patientSelect.selectedIndex = i;
        }
    }
    document.getElementById('bill_id').value = id;
    document.getElementById('bill_amount').value = amount;
    document.getElementById('bill_date').value = date;
    document.getElementById('bill_status').value = status;
    document.getElementById('addBtn').style.display = 'none';
    document.getElementById('editBtn').style.display = 'inline';
}
</script>

</body>
</html>

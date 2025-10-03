<?php
$message = "";
$records = []; // For displaying all submitted records

if(isset($_POST['submit'])){
    // Collect and sanitize inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $age = intval($_POST['age']);
    $course = htmlspecialchars(trim($_POST['course']));

    // Validation
    if(empty($name) || empty($email) || empty($course) || $age <= 0){
        $message = "Please fill all fields correctly.";
    } else {
        // Save to CSV
        $data = [$name, $email, $age, $course];
        $file = fopen("students_data.csv", "a");
        fputcsv($file, $data);
        fclose($file);
        $message = "Record saved successfully!";
    }
}

// Read existing CSV records to display
if(file_exists("students_data.csv")){
    $file = fopen("students_data.csv","r");
    while(($row = fgetcsv($file)) !== false){
        $records[] = $row;
    }
    fclose($file);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Form Submission</title>
<style>
body {
    font-family: 'Verdana', sans-serif;
    background: #f0f4f7;
    margin: 0;
    padding: 0;
    display:flex;
    justify-content:center;
}
.container {
    width: 500px;
    background:#fff;
    padding:30px 35px;
    margin-top:40px;
    border-radius:12px;
    box-shadow:0 6px 18px rgba(0,0,0,0.15);
}
h2 {
    text-align:center;
    color:#2c3e50;
    margin-bottom:25px;
}
input[type=text], input[type=email], input[type=number] {
    width:100%;
    padding:10px;
    margin:8px 0;
    border-radius:6px;
    border:1px solid #ccc;
    font-size:14px;
}
button {
    width:100%;
    padding:10px;
    margin-top:10px;
    border:none;
    border-radius:6px;
    background:#3498db;
    color:#fff;
    font-weight:bold;
    cursor:pointer;
}
button:hover { background:#2980b9; }
.message {
    text-align:center;
    color:green;
    font-weight:bold;
    margin-bottom:10px;
}
table {
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}
th, td {
    border:1px solid #ccc;
    padding:8px;
    text-align:left;
}
th {
    background:#f2f2f2;
}
</style>
</head>
<body>
<div class="container">
<h2>Student Registration</h2>

<?php if($message) echo "<p class='message'>$message</p>"; ?>

<form method="POST" action="">
<input type="text" name="name" placeholder="Name" required>
<input type="email" name="email" placeholder="Email" required>
<input type="number" name="age" placeholder="Age" min="1" required>
<input type="text" name="course" placeholder="Course" required>
<button type="submit" name="submit">Submit</button>
</form>

<?php if(!empty($records)): ?>
<h3>Submitted Records</h3>
<table>
<tr><th>Name</th><th>Email</th><th>Age</th><th>Course</th></tr>
<?php foreach($records as $rec): ?>
<tr>
<td><?php echo htmlspecialchars($rec[0]); ?></td>
<td><?php echo htmlspecialchars($rec[1]); ?></td>
<td><?php echo htmlspecialchars($rec[2]); ?></td>
<td><?php echo htmlspecialchars($rec[3]); ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>
</div>
</body>
</html>

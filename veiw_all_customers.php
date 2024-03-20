<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View All Customers</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #222;
        color: #fff;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        text-align: center;
    }

    h1 {
        font-size: 3em;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 20px;
        background-color: #333;
    }

    th, td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        text-align: center;
    }

    th {
        background-color: #117a8b;
        color: #fff;
    }

    tr:nth-child(even) {
        background-color: #444;
    }

    tr:hover {
        background-color: #666;
    }

    .btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #117a8b;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        margin-top: 20px;
        transition: background-color 0.3s ease;
    }

    .btn:hover {
        background-color: #0056b3;
    }

    .footer {
        background-color: #333;
        color: #fff;
        padding: 10px 0;
        position: relative;
        bottom: 0;
        left: 0;
        width: 100%;
        text-align: center;
    }
</style>
</head>
<body>
    <div class="container">
        <h1>All Customers</h1>
        <a href="index.html" class="btn">Back to Home</a>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Current Balance($)</th>
                <th>Action</th>
            </tr>
            <?php
            include("connection.php");
            
            $host = 'localhost';
            $dbname = 'mayur';
            $user = 'postgres';
            $password = 'postgres';

            // Connect to PostgreSQL
            $conn = pg_connect("host=$host dbname=$dbname user=$user password=$password");
            
            $select_query = "SELECT * FROM customers";
            $result = pg_query($conn, $select_query);

            if ($result) {
                while ($row = pg_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['current_balance'] . "</td>";
                    echo "<td><a href='view_customer.php?id=" . $row['id'] . "' class='btn'>View</a>
                    <a href='transfer_money.php?id=" . $row['id'] . "' class='btn'>Send</a></td>
                    ";
                    echo "</tr>";
                }
            } else {
                echo "Error fetching data: " . pg_last_error() . "<br>";
            }

            pg_close($conn);
            ?>
        </table>
        
    </div>
    <div class="footer">
        <p>&copy; 2024 Banking System. All rights reserved.</p>
    </div>
</body>
</html>

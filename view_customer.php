<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Customer Details</title>
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
        color: #117a8b;
    }

    h2 {
        font-size: 2em;
        margin-bottom: 10px;
        color: white;
    }

    p {
        font-size: 1.2em;
        margin-bottom: 20px;
        color: #ccc;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 20px;
        align:center;
        background-color: #333;
    }

    th, td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        text-align: center;
        color: #fff;
    }

    th {
        background-color: #117a8b;
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
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        text-align: center;
    }
</style>
</head>
<body>
    <div class="container">
        <?php
        $host = 'localhost';
        $dbname = 'mayur';
        $user = 'postgres';
        $password = 'postgres';

        // Connect to PostgreSQL
        $conn = pg_connect("host=$host dbname=$dbname user=$user password=$password");

        if (!$conn) {
            die("Connection failed: " . pg_last_error());
        }

        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            // Fetch customer details
            $customer_query = "SELECT * FROM customers WHERE id = $id";
            $customer_result = pg_query($conn, $customer_query);
            $customer = pg_fetch_assoc($customer_result);

            if ($customer) {
                echo "<h1>Customer Details</h1>";
                echo "<h2>Name: <strong>" . $customer['name'] . "</strong></h2>";
                echo "<p>Email: " . $customer['email'] . "</p>";

                // Fetch transactions where the customer is the sender or receiver
                $transactions_query = "SELECT t.id, c_from.email as from_email, c_to.email as to_email, t.amount 
                                       FROM transactions t
                                       INNER JOIN customers c_from ON t.from_customer_id = c_from.id
                                       INNER JOIN customers c_to ON t.to_customer_id = c_to.id
                                       WHERE t.from_customer_id = $id OR t.to_customer_id = $id";
                $transactions_result = pg_query($conn, $transactions_query);

                if (pg_num_rows($transactions_result) > 0) {
                    echo "<h2>Transactions</h2>";
                    echo "<table>";
                    echo "<tr><th>From</th><th>To</th><th>Transaction ID</th><th>Amount($)</th></tr>";
                    while ($transaction = pg_fetch_assoc($transactions_result)) {
                        echo "<tr>";
                        echo "<td>" . $transaction['from_email'] . "</td>";
                        echo "<td>" . $transaction['to_email'] . "</td>";
                        echo "<td>" . $transaction['id'] . "</td>";
                        echo "<td>" . $transaction['amount'] . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No transactions found for this customer.</p>";
                }

                echo "<p>Current Balance: " . $customer['current_balance'] . "</p>";
            } else {
                echo "<p>Customer not found.</p>";
            }
        } else {
            echo "<p>Invalid request. Please provide a customer ID.</p>";
        }

        pg_close($conn);
        ?>
        <a href="veiw_all_customers.php" class="btn">Back to All Customers</a>
    </div>
    <div class="footer">
        <p>&copy; 2024 Banking System. All rights reserved.</p>
    </div>
</body>
</html>

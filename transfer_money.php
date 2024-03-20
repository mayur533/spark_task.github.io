<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Transfer Money</title>
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

    form {
        margin-top: 20px;
    }

    label {
        display: block;
        margin-bottom: 10px;
    }

    input[type="text"], select {
        padding: 10px;
        width: 100%;
        margin-bottom: 20px;
        border-radius: 5px;
        border: 1px solid #ccc;
        box-sizing: border-box;
    }

    input[type="submit"] {
        padding: 10px 20px;
        background-color: #117a8b;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover {
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
        <h1>Transfer Money</h1>
        <form method="post">
            <label for="recipient">Select Recipient:</label>
            <select name="recipient" id="recipient">
                <?php
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
                        if($row['id']!= $_GET['id']){
                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                        }
                    }
                
                }

                pg_close($conn);
                ?>
            </select>
            <label for="amount">Enter Amount:</label>
            <input type="text" name="amount" id="amount" placeholder="Enter amount to transfer">
            <input type="submit" name="submit" value="Transfer">
        </form>
        <?php
        if (isset($_POST['submit'])) {
            $host = 'localhost';
            $dbname = 'mayur';
            $user = 'postgres';
            $password = 'postgres';

            // Connect to PostgreSQL
            $conn = pg_connect("host=$host dbname=$dbname user=$user password=$password");

            $sender_id =$_GET['id']; // Assuming the sender is always the first customer in the table for this example
            $recipient_id = $_POST['recipient'];
            $amount = $_POST['amount'];

            // Check if sender has enough balance
            $sender_balance_query = "SELECT current_balance FROM customers WHERE id = $sender_id";
            $sender_balance_result = pg_query($conn, $sender_balance_query);
            $sender_balance = pg_fetch_assoc($sender_balance_result)['current_balance'];

            if ($sender_balance >= $amount) {
                // Deduct amount from sender
                $update_sender_query = "UPDATE customers SET current_balance = current_balance - $amount WHERE id = $sender_id";
                pg_query($conn, $update_sender_query);

                // Add amount to recipient
                $update_recipient_query = "UPDATE customers SET current_balance = current_balance + $amount WHERE id = $recipient_id";
                pg_query($conn, $update_recipient_query);
                
                // Add tranasction to transactions table
                $insert_transaction_query = "INSERT INTO transactions (from_customer_id, to_customer_id, amount) VALUES ($sender_id, $recipient_id, $amount)";
                pg_query($conn, $insert_transaction_query);

                echo "<script>alert('Transfer successful.')</script>";
                header("Location:veiw_all_customers.php");
                exit();
            } else {
                echo "<script>alert('Insufficient balance.')</script>";
            }

            pg_close($conn);
        }
        ?>
    </div>
    <div class="footer">
        <p>&copy; 2024 Banking System. All rights reserved.</p>
    </div>
</body>
</html>

<?php
$host = 'localhost';
$dbname = 'mayur';
$user = 'postgres';
$password = 'postgres';


$conn = pg_connect("host=$host dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}


$customers_table_exists = pg_query($conn, "SELECT EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'customers')");

if ($customers_table_exists) {
    $row = pg_fetch_row($customers_table_exists);
    if ($row[0] == 'f') { 
       
        $create_customers_table_query = "CREATE TABLE customers (
                                id SERIAL PRIMARY KEY,
                                name VARCHAR(255) NOT NULL,
                                email VARCHAR(255) NOT NULL,
                                current_balance int NOT NULL
                            )";
        pg_query($conn, $create_customers_table_query);

        // Sample customer data
        $customers = array(
            array('John Doe', 'john.doe@example.com', 500.00),
            array('Jane Smith', 'jane.smith@example.com', 1000.00),
            array('Alice Johnson', 'alice.johnson@example.com', 750.50),
            array('Bob Brown', 'bob.brown@example.com', 1200.75),
            array('Eve Wilson', 'eve.wilson@example.com', 800.25),
            array('Charlie Davis', 'charlie.davis@example.com', 950.30),
            array('Grace Lee', 'grace.lee@example.com', 600.80),
            array('Sam Green', 'sam.green@example.com', 1100.45),
            array('Olivia Martinez', 'olivia.martinez@example.com', 850.65),
            array('Max Anderson', 'max.anderson@example.com', 700.00)
        );

        // Insert customer records into the customers table
        foreach ($customers as $customer) {
            $name = $customer[0];
            $email = $customer[1];
            $balance = $customer[2];
            $insert_customer_query = "INSERT INTO customers (name, email, current_balance) VALUES ('$name', '$email', $balance)";
            pg_query($conn, $insert_customer_query);
        }
       
    } 
} else {
    echo "<script>alert('Error checking customers table existence: " . pg_last_error(). ")</script>";
    
}

// Check if transactions table exists
$transactions_table_exists = pg_query($conn, "SELECT EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'transactions')");

if ($transactions_table_exists) {
    $row = pg_fetch_row($transactions_table_exists);
    if ($row[0] == 'f') {
        // Create transactions table
        $create_transactions_table_query = "CREATE TABLE transactions (
                                id SERIAL PRIMARY KEY,
                                from_customer_id INTEGER NOT NULL,
                                to_customer_id INTEGER NOT NULL,
                                amount int NOT NULL,
                                FOREIGN KEY (from_customer_id) REFERENCES customers(id),
                                FOREIGN KEY (to_customer_id) REFERENCES customers(id)
                            )";
        pg_query($conn, $create_transactions_table_query);


    } 
} else {
    echo "<script>alert('Error checking transaction table existence: " . pg_last_error(). ")</script>";
}

// Close connection
pg_close($conn);
?>

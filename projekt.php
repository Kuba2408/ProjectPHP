<!DOCTYPE html>

<head>
    <meta charset="utf-8" />
    <title></title>
    <script> 
    function hide(){
        document.getElementById('Users').style.display = "none";
        document.getElementById('Orders').style.display = "none";
        document.getElementById('Articles').style.display = "none";
    }
    function showdiv(name) {
        hide();
        var x = document.getElementById(name);
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
        x.style.display = "none";
        }
} 
    
    </script>
</head>
<body onload="hide()">
<?php

$servername = "localhost";
$username = "root";
$password = "";
$db = "projekt";
$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$result = $conn->query("SHOW DATABASES LIKE '$db'");
if ($result->num_rows == 0) {
    $sql = "CREATE DATABASE IF NOT EXISTS $db";
    if ($conn->query($sql) === FALSE) {
        echo "Error creating database: " . $conn->error . "<br>";
        $conn->close();
        exit();
    }
}
$conn->select_db($db);
$tables_exist = $conn->query("SHOW TABLES LIKE 'articles'");
if ($tables_exist->num_rows == 0) {
    $sql = file_get_contents('database.sql');
    if ($conn->multi_query($sql) === FALSE) {
        echo "Error creating tables: " . $conn->error;
    }
}
$conn->close();
        
        function connect(){
            $servername = "localhost";
            $username = "root";
            $password = "";
            $db = "projekt";

            $conn = new mysqli($servername, $username, $password, $db);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            return $conn;
        }
        function display($table){
            $conn = connect();
            $values = $conn->query("SELECT * from $table");
            $data = $values->fetch_all(MYSQLI_ASSOC);

            $conn->close(); 
            return $data;
        }
        function updateU($val1, $val2, $id){
            $conn = connect();
            $conn->query("UPDATE USERS SET firstname = '$val1', lastname = '$val2' WHERE ID = $id");
            $conn->close();
        }
        function updateA($val1, $val2, $val3, $id){
            $conn = connect();
            $conn->query("UPDATE Articles SET name = '$val1', price = '$val2', amount = '$val3' WHERE ID = $id");
            $conn->close();
        }
        function updateO($val1, $val2, $val3, $id){
            $conn = connect();
            try{
            $value = $conn->query("SELECT price FROM Articles WHERE ID = $val2");
            $price = $value->fetch_array()[0] * $val3;
            } catch(Exception $e) {
                $price = 0;
            }
            $conn->query("UPDATE Orders SET IDUser = '$val1', IDarticle = '$val2', amount = '$val3', Price = '$price'  WHERE ID = $id");
            $conn->close();
        }
        function delete($table, $id){
            $conn = connect();
            $conn->query("DELETE FROM $table WHERE ID=$id");
            $conn->close();
        }
        function insertU($val2, $val3){
            $conn = connect();    
            $value = $conn->query("SELECT MAX(ID) FROM USERS");
            $id = $value->fetch_array()[0] + 1;
            
            $sql = "INSERT INTO USERS (ID, firstname, lastname) VALUES ('$id', '$val2', '$val3')";
            
            if ($conn->query($sql) === TRUE) {
            
                echo "<br> New record created successfully <br>";
                } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
                }
            $conn->close(); 
        }      
        function insertA($val1, $val2, $val3){
            $conn = connect();    
            $value = $conn->query("SELECT MAX(ID) FROM Articles");
            $id = $value->fetch_array()[0] + 1;
            
            $sql = "INSERT INTO Articles (ID, name, price, amount) VALUES ('$id', '$val1', '$val2', '$val3')";
            
            if ($conn->query($sql) === TRUE) {
            
                echo "<br> New record created successfully <br>";
                } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
                }
            $conn->close(); 
        }  
        function insertO($val1, $val2, $val3){
            $conn = connect();    
            $value = $conn->query("SELECT MAX(ID) FROM Orders");
            $id = $value->fetch_array()[0] + 1;
            try{
            $value = $conn->query("SELECT price FROM Articles WHERE ID = $val2");
            $price = $value->fetch_array()[0] * $val3;
            } catch(Exception $e) {
                $price = 0;
            }
     
            $sql = "INSERT INTO Orders (ID, IDUser, IDarticle, amount, price) VALUES ('$id', '$val1', '$val2', '$val3', '$price')";
            
            if ($conn->query($sql) === TRUE) {
            
                echo "<br> New record created successfully <br>";
                } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
                }
            $conn->close(); 
        }   
        if(array_key_exists('insertU', $_POST)) { 
            if(isset($_POST['firstName']) && isset($_POST['lastName'])){
            $firstname = $_POST['firstName'];  
            $lastname = $_POST['lastName'];
            
            insertU($firstname, $lastname); 
            }
        } 
        else if(array_key_exists('deleteU', $_POST)) {
            if(isset($_POST['id'])){
            $id = $_POST['id'];             
            delete("users", $id); 
            }
        }  
        else if(array_key_exists('updateU', $_POST)) {
            $firstname = $_POST['firstName2'];
            $lastname = $_POST['lastName2'];
            $id = $_POST['id2'];
            updateU($firstname, $lastname, $id);
        }
        else if(array_key_exists('insertA', $_POST)) {
            if(isset($_POST['nameA']) && isset($_POST['priceA']) && isset($_POST['amountA'])){
            $name = $_POST['nameA'];  
            $price = $_POST['priceA'];
            $amount = $_POST['amountA'];
            
            insertA($name, $price, $amount); 
            }
        }  
        else if(array_key_exists('updateA', $_POST)) {
            if(isset($_POST['nameA2']) && isset($_POST['priceA2']) && isset($_POST['amountA2']) && isset($_POST['idA2'])){
            $name = $_POST['nameA2'];  
            $price = $_POST['priceA2'];
            $amount = $_POST['amountA2'];
            $id = $_POST['idA2'];
            updateA($name, $price, $amount, $id);
            }
        }
        else if(array_key_exists('deleteA', $_POST)) {
            if(isset($_POST['idA'])){
            $id = $_POST['idA'];             
            delete("articles", $id); 
            }
        }  
        else if(array_key_exists('insertO', $_POST)) {
            if(isset($_POST['UserID']) && isset($_POST['ArticleID']) && isset($_POST['AmountO'])){
            $userID = $_POST['UserID'];
            $articleID = $_POST['ArticleID'];
            $amount = $_POST['AmountO'];
            insertO($userID, $articleID, $amount);
            }
        } 
        else if(array_key_exists('updateO', $_POST)) {
            if(isset($_POST['UserID2']) && isset($_POST['ArticleID2']) && isset($_POST['AmountO2']) && isset($_POST['idO2'])){
            $userID = $_POST['UserID2'];
            $articleID = $_POST['ArticleID2'];
            $amount = $_POST['AmountO2'];
            $id = $_POST['idO2'];
            updateO($userID, $articleID, $amount, $id);
            }
        } 
        else if(array_key_exists('deleteO', $_POST)) {
            if(isset($_POST['idO'])){
            $id = $_POST['idO'];             
            delete("orders", $id); 
            }
        } 
        
      ?> 
      <button onclick="showdiv('Users')">USERS</button>
      <button onclick="showdiv('Articles')">ARTICLES</button>
      <button onclick="showdiv('Orders')">ORDERS</button>
      <br><hr><br>
      <div id="Users">
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Firstname</th>
                <th>Lastname</th>
            </tr>
            <?php foreach($data=display('users') as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['ID']) ?></td>
                <td><?= htmlspecialchars($row['firstname']) ?></td>
                <td><?= htmlspecialchars($row['lastname']) ?></td>
            </tr>
            <?php endforeach ?>
    
        </table><br><br><hr><br>

        <form action="" method="post">
        <input type="text" name="firstName" value="name">
        <input type="text" name="lastName" value="last name">
        <input type="submit" name="insertU" class="button" value="Insert" /> <br>

        <input type="text" name="firstName2" value="name">
        <input type="text" name="lastName2" value="last name">
        <input type="text" name="id2" value="ID">
        <input type="submit" name="updateU" class="button" value="update" /> <br>

        <input type="text" name="id" value="ID">
        <input type="submit" name="deleteU" class="button" value="delete" /> <br>
        </form>
      </div>
      <div id="Orders">
        <table border="1">
            <tr>
                <th>ORDER ID</th>
                <th>USER ID</th>
                <th>ARTICLE ID</th>
                <th>AMOUNT</th>
                <th>PRICE</th>
            </tr>

            <?php foreach($data=display('orders') as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['ID']) ?></td>
                <td><?= htmlspecialchars($row['IDUser']) ?></td>
                <td><?= htmlspecialchars($row['IDarticle']) ?></td>
                <td><?= htmlspecialchars($row['amount']) ?></td>
                <td><?= htmlspecialchars($row['price']) ?></td>
            </tr>
            <?php endforeach ?>
        </table><br><br><hr><br>

        <form action="" method="post">
        <input type="text" name="UserID" value="User ID">
        <input type="text" name="ArticleID" value="Article ID">
        <input type="text" name="AmountO" value="Amount">
        <input type="submit" name="insertO" class="button" value="Insert" /> <br>

        <input type="text" name="UserID2" value="User ID">
        <input type="text" name="ArticleID2" value="Article ID">
        <input type="text" name="AmountO2" value="Amount">
        <input type="text" name="idO2" value="ID">
        <input type="submit" name="updateO" class="button" value="update" /> <br>

        <input type="text" name="idO" value="ORDER ID">
        <input type="submit" name="deleteO" class="button" value="delete" /> <br>
        </form>
      </div>
      <div id="Articles">
        <table border="1">
            <tr>
                <th>ID</th>
                <th>NAME</th>
                <th>PRICE</th>
                <th>AMOUNT</th>               
            </tr>
            <?php foreach($data=display('articles') as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['ID']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['price']) ?></td>
                <td><?= htmlspecialchars($row['amount']) ?></td>               
            </tr>
            <?php endforeach ?>
        </table><br><br><hr><br>
         <form action="" method="post">
            <input type="text" name="nameA" value="name">
            <input type="text" name="priceA" value="price">
            <input type="text" name="amountA" value="amount">
            <input type="submit" name="insertA" class="button" value="Insert" /> <br>

            <input type="text" name="nameA2" value="name">
            <input type="text" name="priceA2" value="price">
            <input type="text" name="amountA2" value="amount">
            <input type="text" name="idA2" value="ID">
            <input type="submit" name="updateA" class="button" value="update" /> <br>

            <input type="text" name="idA" value="ID">
            <input type="submit" name="deleteA" class="button" value="delete" /> <br>
            </form>
      </div>
      
</body>

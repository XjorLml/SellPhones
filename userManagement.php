<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2>User Managment</h2>
        <a class ="btn btn-primary" href="userCreate.php" role="button">New Client</a>
        <br>
        <table class= "table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "sellphone";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed". $conn->connect_error);
                }

                $sql = "SELECT * FROM usertbl ";
                $result = $conn->query($sql);

                if (!$result) {
                    die("Invalid query". $conn->connect_error);
                }

                while ($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>$row[userID]</td>
                        <td>$row[fName]</td>
                        <td>$row[lName]</td>
                        <td>$row[email]</td>
                        <td>$row[phoneNumber]</td>
                        <td>
                            <a class='btn btn-primary btn-sm' href='/phpxampptutorial/userEdit.php?userID=$row[userID]'>Edit</a>
                            <a class='btn btn-danger btn-sm' href='/PHPXAMPPTUTORIAL/delete.php?userID=$row[userID]'>Delete</a>
                        </td>
                    </tr>
                    ";
                }        
                ?>     
            </tbody>
        </table>
    </div>
</body>
</html>
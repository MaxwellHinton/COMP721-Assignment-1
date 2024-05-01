<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-type"Content-type="text/html;charset=utf-8"/>
        <title>Drop Database table</title>
    </head>
    <body>
        <?php
                
            $servername = "webdev.aut.ac.nz";
            $username = "qwv9850";
            $dbname = "qwv9850";
            $pswd = "ieiiiiflqkxypghzarebahfudsxmvom";

            $conn = new mysqli($servername, $username, $pswd, $dbname);

            if($conn->connect_error){

                die("Connection failed: " . $conn->connect_error);
            }

            $stmt = "DROP TABLE IF EXISTS status_updates";

            if($conn->query($stmt) === TRUE){

                echo "Database table reset successfully.";
            } 
            else{

                echo "Error resetting database: " . $conn->error;
            }

            $conn->close();
            
            echo "<p><a href='index.html'>Return to Home Page</a></p>";
        ?>
    </body>
</html>
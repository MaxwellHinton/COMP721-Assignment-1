<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-type"Content-type="text/html;charset=utf-8"/>
        <title>Post Status process</title>
    </head>

    <!-- Post status processing code. Checks input, writes to database, and generates 
        corresponding HTML output. -->
    <h2>Submission page</h2>
    <body>
    <div>
        <?php
            // Check that a form has been submitted.
            if($_SERVER["REQUEST_METHOD"] == "POST")
            {
                echo "passed submission check";
                // Check that all parts of the form have been completed.
                if(isset($_POST["stcode"]) && 
                    isset($_POST["st"]) && 
                    isset($_POST["share"]) && 
                    isset($_POST["date"]) &&
                    isset($_POST["likes"]) &&
                    isset($_POST["comments"]) &&
                    isset($_POST["sharing"])
                ){
                    $stcode = $_POST["stcode"];
                    $st = $_POST["st"];
                    $share = $_POST["share"];
                    $status_date = $_POST["date"];
                    $likes = isset($_POST["likes"]) ? TRUE : FALSE;
                    $comments = isset($_POST["comments"]) ? TRUE : FALSE;
                    $sharing = isset($_POST["sharing"]) ? TRUE : FALSE;

                    $servername = "localhost";
                    $username = "root";
                    $dbname = "phpmyadmin";

                    $conn = new mysqli($servername, $username, '', $dbname);

                    if($conn->connect_error){
                        die("Connection to db failed". $conn->connect_error);
                    }
                    else{
                        $stmt = $conn->prepare("INSERT INTO status_updates(st_code, st, share, date, likes, comments, sharing)
                                                            VALUES(?, ?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("ssssiii", $stcode, $st, $share, $status_date, $likes, $comments, $sharing);
                        $stmt->execute();
                        echo "Status update posted succesfully.";
                        echo "<a href='index.html'>Return to homepage</a>";
                        $stmt->close();
                        $conn->close();
                    }
                }
                else{

                    echo "Error: Missing required fields.";
                }
            }
        ?>
    </div>
    </body>
</html>


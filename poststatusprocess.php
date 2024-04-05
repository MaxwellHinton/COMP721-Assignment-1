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
            // Duplicate error code constant to avoid magic number.
            // Catching this error is essential because i'm using the status code
            // as the primary key.
            define("DUPLICATE_KEY_ERROR" , 1062);

            // Check that a form has been submitted.
            if($_SERVER["REQUEST_METHOD"] == "POST")
            {
                echo "passed submission check";
                
                // Check that all parts of the form have been completed.
                // We don't need to check for likes,comments, or sharing (not compulsory).
                if(isset($_POST["stcode"]) && 
                    isset($_POST["st"]) && 
                    isset($_POST["share"]) && 
                    isset($_POST["date"])
                ){
                    echo "passed form component check";
                    $stcode = $_POST["stcode"];
                    $st = $_POST["st"];
                    $share = $_POST["share"];
                    $status_date = $_POST["date"];
                    $likes = isset($_POST["likes"]) ? TRUE : FALSE;
                    $comments = isset($_POST["comments"]) ? TRUE : FALSE;
                    $sharing = isset($_POST["sharing"]) ? TRUE : FALSE;

                    // NOTE: When the time comes to put onto aut server, just change server/dbnames.
                    $servername = "localhost";
                    $username = "root";
                    $dbname = "a1database";
                    //$table = "status_updates"; - Could use this or just hard-code the table name.

                    $conn = new mysqli($servername, $username, '', $dbname);

                    if($conn->connect_error){
                        die("Connection to db failed". $conn->connect_error);
                    }
                    else
                    {
                        // Check if the status_updates table exists.
                        echo "<p>SQL statement is being prepared.</p>";

                        // Return a result set of all tables in the db that match 'status_updates'
                        // This is determining whether to create the table or not.
                        $statement = "SHOW TABLES LIKE 'status_updates'";
                        $result = $conn->query($statement);

                        if($result->num_rows == 0){

                            echo "entered table creation";
                            
                            // Create the status_updates table.
                            $create_table_stmt = ("CREATE TABLE status_updates(
                                st_code VARCHAR(5) NOT NULL,
                                st TEXT NOT NULL,
                                share VARCHAR(20) NOT NULL,
                                date TEXT NOT NULL,
                                likes INT NOT NULL,
                                comments INT NOT NULL,
                                sharing INT NOT NULL,
                                CONSTRAINT pkey_status_updates PRIMARY KEY (st_code))"
                            );
                            
                            // Check table creation was a success.
                            if($conn->query($create_table_stmt) === TRUE){
                                echo "table was created";
                            }
                            else{
                                echo "error creating table: " . $conn->error;
                            }
                        }

                        try{
                            
                            // Prepare statement. 
                            $stmt = $conn->prepare(
                                "INSERT INTO status_updates(st_code, st, share, date, likes, comments, sharing)
                                VALUES(?, ?, ?, ?, ?, ?, ?)"
                            );

                            $stmt->bind_param("ssssiii", 
                                $stcode, $st, 
                                $share, $status_date, 
                                $likes, $comments, 
                                $sharing
                            );
                            $stmt->execute();
                            $stmt->close();

                            echo "<p>Status update posted succesfully.</p>";
                            echo "<p><a href='poststatusform.php'>Post another status.</a></p>";
                            echo "<p><a href='index.html'>Return to homepage</a></p>";
                        } 
                        
                        // Catching duplicate status code errors. 
                        catch(Exception $e){

                            if ($e->getCode() === DUPLICATE_KEY_ERROR){

                                echo "<p>Error: Status code: " . $stcode . " already exists</p>";
                                echo "<p><a href='poststatusform.php'>Please Try again</a></p>";
                            } else {

                                echo "<p>Error: ". $e->getMessage() . "</p>";
                            }
                        }

                    }
                        $conn->close();
                }
                else{
                    echo "Error: Missing required fields.";
                }
            }
        ?>
    </div>
    </body>
</html>


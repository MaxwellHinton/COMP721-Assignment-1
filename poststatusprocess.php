<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-type"Content-type="text/html;charset=utf-8"/>
        <title>Post Status process</title>
        <link rel="stylesheet"href="styles.css">
    </head>
    <h2>Submission page</h2>
    <body>
    <div class="content">
        <?php
            define("DUPLICATE_KEY_ERROR" , 1062);  // Duplicate error code constant (to avoid magic numbers)
            
            // Check that a form has been submitted.
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                #echo "passed submission check";
                
                // Check that all parts of the form have been completed.
                // We don't need to check for likes,comments, or sharing (not compulsory).
                if(isset($_POST["stcode"]) && 
                    isset($_POST["st"]) && 
                    isset($_POST["share"]) && 
                    isset($_POST["date"])
                ){

                    $stcode = $_POST["stcode"];
                    $st = trim($_POST["st"]);  // Trim the status (could indicate a blank status).
                    $share = $_POST["share"];
                    $status_date = $_POST["date"];
                    $likes = isset($_POST["likes"]) ? TRUE : FALSE;
                    $comments = isset($_POST["comments"]) ? TRUE : FALSE;
                    $sharing = isset($_POST["sharing"]) ? TRUE : FALSE;

                    if(empty($st)){
                        echo "<p>The status cannot be blank!</p>";
                        echo "<p><a href=poststatusform.php>Please try again</a></p>";
                        echo "<p><a href='index.html'>Return to homepage</a></p>";
                    }

                    else{

                        // NOTE: When the time comes to put onto aut server, just change server/dbnames.
                        $servername = "localhost";
                        $username = "root";
                        $dbname = "a1database";
                        $pswd = "";

                        $conn = new mysqli($servername, $username, $pswd, $dbname);

                        if($conn->connect_error){
                            die("Connection to db failed". $conn->connect_error);
                        }

                        else{

                            // Check if the status_updates table exists.

                            // Return a result set of all tables in the db that match 'status_updates'
                            // This is determining whether to create the table or not.
                            $statement = "SHOW TABLES LIKE 'status_updates'";
                            $result = $conn->query($statement);

                            if($result->num_rows == 0){

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
                                echo "<p><a id='post_again' href='poststatusform.php'>Post another status</a></p>";
                            } 
                            
                            // Catching duplicate status code errors. 
                            catch(Exception $e){

                                if ($e->getCode() === DUPLICATE_KEY_ERROR){
                                    echo "<p>Error: Status code: " . $stcode . " already exists</p>";
                                } 

                                else{
                                    echo "<p>Error: ". $e->getMessage() . "</p>";
                                }

                                echo "<p><a href='poststatusform.php'>Please try again</a></p>";
                            }

                        }

                        $conn->close();
                    }
                }

                else{
                    echo "Error: Please make sure to select a community to post your status to.";
                    echo "<p><a href='poststatusform.php'>Please try again</a></p>";
                }
            }
            echo "<p><a class='homePageLink' href='index.html'>Return to Home Page</a></p>";
        ?>
    </div>
    </body>
</html>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-type"Content-type="text/html;charset=utf-8"/>
        <title>Search Status Process</title>
    </head>
    <body>
        <h2>Status information</h2>
        <div class="content">
        <?php
            if($_SERVER['REQUEST_METHOD'] == 'GET'){
                
                //a string has been parsed.
                if(isset($_GET['Search'])){
                    $search = trim($_GET['Search']);
                    
                    if(!empty($search)){
                        
                        $servername = "localhost";
                        $username = "root";
                        $dbname = "a1database";
                        $conn = new mysqli($servername, $username, '', $dbname);

                        if($conn->connect_error){

                            die("Connection to db failed". $conn->connect_error);
                        }
                        else{

                            $stmt = "SHOW TABLES LIKE 'status_updates'";
                            $result = $conn->query($stmt);

                            if($result->num_rows == 0){

                                // table does not exist
                                echo "<p>No status found in the system. Please use the Post Status Page to post one.</p>";
                                echo "<p><a href='poststatusformhtml'>Post Status</a></p>";
                            }
                            else{

                                $statement = $conn->prepare("SELECT * FROM status_updates WHERE st LIKE ?");
                                $to_Search = "%$search%";
                                $statement->bind_param("s", $to_Search);
                                $statement->execute();

                                $search_Result = $statement->get_result();


                                // Process data
                                // check that there are matches
                                if($search_Result->num_rows == 0){

                                    // No search results were found
                                    echo "<p>No status was found that contains the keyword " . $search . " Please try again.</p>";
                                }
                                else{

                                    while($row = mysqli_fetch_assoc($search_Result)){ // Will return null (false) after last entry is received
                                        
                                        $stcode = $row['st_code'];
                                        $st = $row['st'];
                                        $share = $row['share'];
                                        $date = $row['date']; // Convert to text format e.g., August 22, 2017
                                        
                                        // Permissions are represented as a 1 or 0 in the DB. This converts them to a string for printing.
                                        $likes = $row['likes'] == 1 ? "Allow Likes" : "";
                                        $comments = $row['comments'] == 1 ? "Allow Comments" : "";
                                        $sharing = $row['sharing'] == 1 ? "Allow Sharing" : "";                                    

                                        echo "<p>Status: " . $st . "<br>";
                                        echo "Status code: " . $stcode . "</p>";

                                        echo "<p>Share: " . $share . "<br>";
                                        echo "Date Posted: " . $date . "<br>";
                                        echo "Permissions: ";
                                        if(!empty($likes)) echo "$likes, ";
                                        if(!empty($comments)) echo "$comments, ";
                                        if(!empty($sharing)) echo "$sharing";
                                        echo "</p>";
                                    
                                    }
                                }

                                $statement->close();
                            }
                        }
                    }
                    else{ //An empty string was given.

                        echo "The string given is empty. Please enter a keyword to search.";
                    }

                    echo "<p><a href='searchstatusform.html'>Search again</a></p>";
                    echo "<p><a href='index.html'>Return to Home Page</a></p>";
                }
                else{

                    echo "The search string is empty or contains invalid input. Please input a keyword to search.";
                }
            }
            else{

                echo "The form has not been submitted.";
            }

            $conn->close();
        ?>
        </div>
    </body>
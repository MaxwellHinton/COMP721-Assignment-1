<!DOCTYPE html>
<html lang="en">
<head>
        <title>Post a status update.</title>
        <meta http-equiv="Content-type" content ="text/html;charset=utf-8"/>
        <link rel="stylesheet" href="styles.css">
</head>
    <body>
        <h1 class="header">Status Posting System</h1>
        <form id="post_form" action="poststatusprocess.php" method="POST">

            <!-- Status Code -->
            <div class="post_fields">
                <label for="stcode">Status Code:</label>
                <input type="text" name="stcode" pattern="S\d{4}"
                            title="Status Code starts with a captial 'S' followed by 4 digits. 'S0001' etc." required>
            </div>
            <br>

            <!-- Status -->
            <div class="post_fields">
                <label for="st">Status:</label>
                <input type="text" name="st"pattern="[A-Za-Z0-9,.\s!?\x20]+"
                            title="Alphanumeric characters, commas, periods, !, and ? are allowed."required>
            </div>
            <br>

            <!-- Share -->
            <div class="post_fields">
                <label>Share:</label>
            
                <input type = "radio" id="University" name="share" value="University">
                <label for="University">University</label>
                
                <input type="radio" id="Class" name="share" value="Class">
                <label for="Class">Class</label>

                <input type="radio" id="Private" name="share" value="Private">
                <label for="Private">Private</label>
                
            </div>
            <br>

            <!-- Date -->
            <!-- The date has to be equal to the current date. You can't post in the past or future. -->
            <div class="post_fields">
                <?php $date = date("d/m/Y"); ?>

                <label for="date">Date</label>
                <input type="text" id="date" name="date" value = "<?=$date?>" pattern="\d{1,2}/\d{2}/\d{4}"
                            title="Date must follow the format: dd/mm/yyyy"required>  
            </div>
            <br>

            <!-- Permission -->
            <div class="post_fields">
                <label for="Permission">Permission:</label>
                
                <input type="checkbox" id="likes" name="likes" value="likes">
                <label for="likes">Allow Likes</label>

                <input type="checkbox" id="comments" name="comments" value="comments">
                <label for="comments">Allow comments</label>

                <input type="checkbox" id="sharing" name="sharing" value="sharing">
                <label for="sharing">Allow Sharing</label>
            </div>

            <!-- Submit button -->
            <div>
                <input type="submit" id="submit_button" value="Submit">
            </div>
        </form>
        <br>
        <a class="homePageLink" href="index.html">Return to home page</a>
    </body>
</html>

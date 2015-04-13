<html>
    <head>
        <title>
            mTrain Data Entry
        </title>
    </head>
    
    <body>
        <div align="center">
        <h3>mTrain Test Questions & Answers Data Entry Form</h3>
        <form action="data_entry.php" method="post">
            <p><label>Question: </label><br><textarea style="width: 325px; height: 150px;" name="question" required></textarea></p>
            <p><label>Option A: </label><br><input type="text" size="50" name="optA" required></p>
            <p><label>Option B: </label><br><input type="text" size="50" name="optB" required></p>
            <p><label>Option C: </label><br><input type="text" size="50" name="optC" required></p>
            <p><label>Option D: </label><br><input type="text" size="50" name="optD" required></p>
            <p><label>Correct Option: </label><br><input type="text" size="50" name="correctAns" required></p>
<!--            <p><label>Tiptext: </label><br><input type="text" size="50" name="tiptext" required></p>-->
            <p><label>Test Id: </label><br><input type="text" size="25" name="testId" value="3" required></p>
            <p><input type="submit" name="submit"><br></p>
        </form>
        </div>
    </body>
</html>
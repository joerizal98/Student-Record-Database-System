<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Student Achievements</title>
        <style>
            /* Style for the page header */
            header {
                background-color: #004466;
                color: white;
                padding: 10px;
            }

            /* Style for the page content */
            main {
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 20px;
            }

            /* Style for the achievement list */
            .achievement-list {
                list-style-type: none;
                padding: 0;
                margin: 0;
                max-width: 500px;
            }

            /* Style for each achievement item */
            .achievement-item {
                display: flex;
                justify-content: space-between;
                border: 1px solid #ddd;
                padding: 10px;
                margin-bottom: 10px;
                max-width: 400px;
            }

            /* Style for the achievement name */
            .achievement-name {
                font-weight: bold;
            }

            /* Style for the achievement date */
            .achievement-date {
                font-style: italic;
                color: #777;
            }
        </style>
    </head>
    <body>
        <header>
            <h1>Student Achievements</h1>
        </header>
        <main>
            <?php
                // Define an array of student achievements
                $achievements = array(
                    array("name" => "Received an A+ in Database Design", "date" => "May 1, 2023"),
                    array("name" => "Achieved perfect attendance in course Database Design", "date" => "April 15, 2023")
                );

                // Display the student achievements as an unordered list
                echo "<ul class='achievement-list'>";
                foreach ($achievements as $achievement) {
                    echo "<li class='achievement-item'>";
                    echo "<span class='achievement-name'>" . $achievement["name"] . "</span>";
                    echo "<span class='achievement-date'>" . $achievement["date"] . "</span>";
                    echo "</li>";
                }
                echo "</ul>";
            ?>
        </main>
    </body>
</html>

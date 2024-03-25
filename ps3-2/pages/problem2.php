<!DOCTYPE html>
<html>
    <head>
        <title>Problem Set 3-2 Problem 2</title>
        <meta http-equiv="description" content="Problem Set 3-2 Solution Problem 2" />
        <meta http-equiv="keywords" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!-- Import "Inter" google fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter">

        <!-- CSS -->
        <style type="text/css">
            body {
                font-family: "Inter", serif;
                max-width: 945px;
            }
            h1 {
                text-align: center;
            }
            .hours {
                text-align: left; /* ensure hours are left aligned */
            }
        </style>
    </head>
    <body>
        <h1>Problem 2</h1>

        <?php 
            /* Define office hours associative array */ 
            $office_hours = array(
                "Sunday" => "1:00pm-5:00pm",
                "Monday" => "9:00am-4:00pm",
                "Tuesday" => "12:00pm-7:00pm",
                "Wednesday" => "9:00am-3:30pm",
                "Thursday" => "6:00pm-11:30pm",
                "Friday" => "7:30am-2:30pm",
                "Saturday" => "8:00am-12:00pm"
            );

            /* 
             * Takes in an associative array of office hours that maps 
             * days of the week to hours (keys and values are both strings) and
             * returns HTML code that would display the array contents. 
             */
            function get_office_hours_display($office_hours) 
            {
                $result = ""; /* to store HTML string to return */ 

                $result .= "<table>";
                $result .= "<tr>";

                /* Write header of table */
                $result .= "<th>Day of Week</th>";
                $result .= "<th>Office Hours</th>";

                $result .= "</tr>";

                /* Write office hour info for each weekday */
                foreach ($office_hours as $weekday=>$hours) {
                    $result .= "<tr>";
                    $result .= "<td>$weekday</td>";
                    /* The class="hours" is to ensure left alignment of hours */
                    $result .= "<td class=\"hours\">$hours</td>";
                    $result .= "</tr>";
                }

                $result .= "</table>";

                return $result;
            }

            /* Display information in `office_hours` to the page */ 
            echo get_office_hours_display($office_hours);
        ?>
    </body>
</html>
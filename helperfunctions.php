<?php

function redirect($url, $statusCode = 303)
{
    header('Location: ' . $url, true, $statusCode);
    die();
}

function setCookieValue($cookie_name, $cookie_value, $seconds)
{
    setcookie($cookie_name, $cookie_value, time() + $seconds, "/");
}

function delCookieValue($cookie_name)
{
    if (isset($_COOKIE[$cookie_name])) {
        unset($_COOKIE[$cookie_name]);
        setcookie($cookie_name, null, -1, '/');
        return true;
    }
}

function getStatusFromInt($temp)
{
    switch ($temp) {
        case 0:
            return "Upcoming";
            break;
        case 1:
            return "Pending";
            break;
        case 2:
            return "Overdue";
            break;
        case 3:
            return "NotStarted";
            break;
        case 4:
            return "Active";
            break;
        case 5:
            return "Canceled";
            break;
    }
}

function getStatusFromString($temp)
{
    switch ($temp) {
        case "Upcoming":
            return 0;
            break;
        case "Pending":
            return 1;
            break;
        case "Overdue":
            return 2;
            break;
        case "NotStarted":
            return 3;
            break;
        case "Active":
            return 4;
            break;
        case "Canceled":
            return 5;
            break;
    }
}

function getIssueStatusFromInt($temp)
{
    switch ($temp) {
        case 0:
            return "Open";
            break;
        case 1:
            return "In progress";
            break;
        case 2:
            return "Done";
            break;
        case 3:
            return "Rejected";
            break;
        case 4:
            return "Reopen";
            break;
        case 5:
            return "Closed";
            break;
        case 6:
            return "In review";
            break;
    }
}

function input_data($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

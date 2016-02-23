<?php
/**
 * Created by PhpStorm.
 * User: Luca Doglione
 * Date: 05/10/14
 * Time: 14:58
 */

include_once dirname(__FILE__) . "/../classes/DBConnection.php";
include_once dirname(__FILE__) . "/../classes/User.php";


/*
 * Function that check if parameters correspond to an existent and active user in DB
 */
function checklogin($username, $password)
{

    $username = trim($username);
    $usernameN = strip_tags($username);

    if ($usernameN != $username)
        throw new Exception("Inserted Username is not valid");

    $username = strtolower($username);
    $password = clearInput($password);

    if ($username == "" || $password == "")
        throw new Exception("Username and Password cannot be empty");

    if (strlen($username) > 20)
        throw new Exception("Username cannot be longer then 20 chars");

    $utente = new User($username);

    if (!$utente->IsValid())
        throw new Exception("User is not valid or it's not active");

    if ($utente->HasPassword($password))
        return TRUE;
    else
        throw new Exception("Invalid Password");

}


/*
 * Function that Build index Menu for the user passed as parameter
 */
function buildMenu(User $user)
{
    $username = $user->getID();
    $categories = getCategories();
    $db = DBConnection::getConnection();

    if (count($categories) == 0)
    {
        throw new Exception("There are no available categories for you");
    } else
    {
        foreach ($categories as $catName)
        {

            $id = str_replace(" ", "", $catName) . "_btn";
            echo "<p class='category' id='$id' onclick='showPanel(this)'>" . $catName . "</p>";

            $query = "SELECT ID, NAME, ALERT FROM SCRIPTS WHERE CATEGORY = '$catName' AND ID IN (SELECT ID_SCRIPT FROM AUTHORIZATIONS WHERE ID_USER='$username')";

            try
            {
                $res = $db->query($query);
                $namePanel = str_replace(" ", "", $catName) . "_Panel";
                if ($res->rowCount() <= 0)
                {
                    echo "<ul class=\"scripts\" id=\"$namePanel\">";
                    echo "<p class='error'>No Available Scripts in this category</p>";
                    echo "</ul>";
                } else
                {

                    echo "<ul class=\"scripts\" id=\"$namePanel\">";
                    while (($info = $res->fetch(PDO::FETCH_ASSOC)))
                    {
                        $scriptName = $info["NAME"];
                        $scriptId = $info["ID"];
                        $scriptAlert = $info["ALERT"];
                        $row = "<li id=\"$scriptId\" about=\"$scriptAlert\" onclick=\"execCmd(this)\">$scriptName</li>";
                        echo $row;
                    }
                    echo "</ul>";

                }
            } catch (Exception $e)
            {
                throw new Exception("Impossible to Execute Query that retrieve scripts for category " . $catName . ": " . $e->getMessage());
            }


        }
    }
}

/*
 * Function That retrieves the list of categories where there are scripts for user passed as parameter
 */
function getCategories()
{
    //TODO Select only categories where there are scripts for specified user (passed as parameter)
    $sql = "SELECT * FROM CATEGORIES";
    $db = DBConnection::getConnection();
    $count = 0;
    $categories = NULL;
    try
    {
        foreach ($db->query($sql) as $tmp)
        {
            $categories[$count] = $tmp['CATEGORY'];
            $count++;
        }
    } catch (Exception $e)
    {
        throw new Exception("Impossible to retrieve the ist of categories");
    }

    return $categories;
}

/*
 * Function that retrieves list of user and print the selection menu
 */
function printUsers($idInput, $idList)
{
    $sql = "SELECT ID FROM USERS";
    $db = DBConnection::getConnection();
    $users = NULL;
    try
    {
        foreach ($db->query($sql) as $tmp)
        {
            $id = $tmp['ID'];
            echo "<li about=\"$id\" onclick=\"setSelectValue(this, '$idInput', '$idList')\">$id</li>";
        }
    } catch (Exception $e)
    {
        throw new Exception("Impossible to retrieve the users list");
    }
}

/*
 * Redirect to a new Page in a correct way
 */
function redirect($url, $code)
{
    if ($code == 301)
        header("HTTP/1.1 $code Moved Permanently");
    else if ($code == 302)
        header("HTTP/1.1 $code Moved Temporary");

    header("Location: $url");


}

/*
 * Clear Input
 */
function clearInput($str)
{
    $str = trim($str);
    $str = htmlentities($str);
    return $str;
}

?>
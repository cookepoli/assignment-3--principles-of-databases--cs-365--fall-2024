<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Password Manager</title>
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <header>
      <h1>Password Manager</h1>
    </header>
    <form id="clear-results" method="post"
          action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <input id="clear-results__submit-button" type="submit" value="Clear Results">
    </form>

<?php
require_once "includes/config.php";
require_once "includes/helpers.php";

$option = (isset($_POST['submitted']) ? $_POST['submitted'] : null);

if ($option != null) {
    switch ($option) {
        case SEARCH:
            if ("" == $_POST['search']) {
                echo '<div id="error">Search query empty. Please try again.</div>' .
                    "\n";
            } else {
                if (NOTHING_FOUND === (search($_POST['search'], $_POST['attribute']))) {
                    echo '<div id="error">Nothing found.</div>' . "\n";
                }
            }

            break;

        case UPDATE:
            if ((0 == $_POST['new-attribute']) && ("" == $_POST['pattern'])) {
                echo '<div id="error">One or both fields were empty, ' .
                    'but both must be filled out. Please try again.</div>' . "\n";
            } if ((update($_POST['current-attribute'], $_POST['new-attribute'],
                    $_POST['query-attribute'], $_POST['pattern']) == -1)){
                echo '<div id="error">Duplicate primary key.</div>' . "\n";
            } else {
                update($_POST['current-attribute'], $_POST['new-attribute'],
                    $_POST['query-attribute'], $_POST['pattern']);
            }

            break;

        case INSERT:
            if (("" == $_POST['website-name']) || ("" == $_POST['website-url'])
                    || ("" == $_POST['first-name']) || ("" == $_POST['last-name'])
                    || ("" == $_POST['email']) || ("" == $_POST['username'])
                    || ("" == $_POST['password']) || ("" == $_POST['comment'])) {
                echo '<div id="error">At least one field in your insert request ' .
                     'is empty. Please try again.</div>' . "\n";
            } else {
                insert($_POST['website-name'],$_POST['website-url'],$_POST['first-name'],
                        $_POST['last-name'],$_POST['email'],$_POST['username'],
                        $_POST['password'],$_POST['comment']);
            }
            break;

        case DELETE:
            if (("" == $_POST['current-attribute']) || ("" == $_POST['pattern'])) {
            echo '<div id="error">At least one field in your delete procedure ' .
                 'is empty. Please try again.</div>' . "\n";
        } else {
            delete($_POST['current-attribute'], $_POST['pattern']);
        }

        break;

    }
}
?>
  </body>
</html>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <fieldset>
    <legend>Search</legend>
    SELECT FROM * WHERE
    <select name="attribute" id="attribute">
        <option>website.website_id</option>
        <option>user.user_id</option>
        <option>website_name</option>
        <option>website_url</option>
        <option>first_name</option>
        <option>last_name</option>
        <option>email</option>
        <option>username</option>
        <option>password</option>
        <option>creation_time</option>
        <option>comment</option>
        <option>all</option>
    </select>
    LIKE
    <input type="text" name="search" autofocus required>
    ;
    <input type="hidden" name="submitted" value="1">
     (Case Sensitive)
    <p><input type="submit" value="search"></p>
  </fieldset>
</form>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <fieldset>
    <legend>Update</legend>
    UPDATE relation SET
    <select name="current-attribute" id="current-attribute">
        <option>website.website_id</option>
        <option>user.user_id</option>
        <option>website_name</option>
        <option>website_url</option>
        <option>first_name</option>
        <option>last_name</option>
        <option>email</option>
        <option>username</option>
        <option>password</option>
        <option>creation_time</option>
        <option>comment</option>
    </select>
    = <input type="text" name="new-attribute" required> WHERE
    <select name="query-attribute" id="query-attribute">
        <option>website.website_id</option>
        <option>user.user_id</option>
        <option>website_name</option>
        <option>website_url</option>
        <option>first_name</option>
        <option>last_name</option>
        <option>email</option>
        <option>username</option>
        <option>password</option>
        <option>creation_time</option>
        <option>comment</option>
    </select>
    = <input type="text" name="pattern" required>;
    <input type="hidden" name="submitted" value="2">
    <p><input type="submit" value="update"></p>
  </fieldset>
</form>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <fieldset>
    <legend>Insert</legend>
    INSERT INTO relation VALUES <br> <br>
    ( <input type="text" name="website-name" placeholder="website_name" required>,
                                    <input type="text" name="website-url" placeholder="website_url" required>,
                                    <input type="text" name="first-name" placeholder="first_name" required>,
                                    <input type="text" name="last-name" placeholder="last_name" required>,
                                    <input type="text" name="email" placeholder="email" required>,
                                    <input type="text" name="username" placeholder="username" required>,
                                    <input type="text" name="password" placeholder="password" required>,
                                    <textarea id="comment" name="comment" placeholder="comment" required></textarea>);
    <input type="hidden" name="submitted" value="3">
    <p><input type="submit" value="insert"></p>
  </fieldset>
</form>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <fieldset>
    <legend>Delete</legend>
    DELETE FROM relation WHERE
    <select name="current-attribute" id="current-attribute">
        <option>website.website_id</option>
        <option>user.user_id</option>
        <option>website_name</option>
        <option>website_url</option>
        <option>first_name</option>
        <option>last_name</option>
        <option>email</option>
        <option>username</option>
        <option>password</option>
        <option>creation_time</option>
        <option>comment</option>
    </select>
    = <input type="text" name="pattern" required>;
      <input type="hidden" name="submitted" value="4">
    <p><input type="submit" value="delete"></p>
  </fieldset>
</form>

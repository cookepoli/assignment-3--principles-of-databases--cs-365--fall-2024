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
            } else {
                update($_POST['current-attribute'], $_POST['new-attribute'],
                    $_POST['query-attribute'], $_POST['pattern']);
            }

            break;

        case INSERT:
            if (("" == $_POST['artist-id']) || ("" == $_POST['artist-name'])) {
                echo '<div id="error">At least one field in your insert request ' .
                     'is empty. Please try again.</div>' . "\n";
            } else {
                insert($_POST['artist-id'],$_POST['artist-name']);
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
        <option>account_id</option>
        <option>website_id</option>
        <option>user_id</option>
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

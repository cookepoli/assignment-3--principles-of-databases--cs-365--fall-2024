<?php

function set_aes($db){
    $db -> exec("SET block_encryption_mode = '".BLOCK_ENCRYPTION_MODE."'");
    $db -> exec("SET @init_vector = '".INIT_VECTOR."'");
    $db -> exec("SET @key_str = '".KEY_STR."'");
}

function search($search, $attribute) {
    try {
        $db = new PDO(
            "mysql:host=" . DBHOST . "; dbname=" . DBNAME . ";charset=utf8",
            DBUSER
        );
        set_aes($db);

        if ("all" == $attribute){
            $select_query = "SELECT *,CAST(AES_DECRYPT(password, '".KEY_STR."', '".INIT_VECTOR."') AS CHAR)
                                FROM website INNER JOIN registersfor ON (website.website_id = registersfor.website_id)
                                INNER JOIN user ON (user.user_id = registersfor.user_id)
                                WHERE website.website_id LIKE \"%{$search}%\" OR website_name LIKE \"%{$search}%\"
                                OR website_url LIKE \"%{$search}%\"  OR user.user_id LIKE \"%{$search}%\"
                                OR first_name LIKE \"%{$search}%\" OR last_name LIKE \"%{$search}%\"
                                OR email LIKE \"%{$search}%\" OR username LIKE \"%{$search}%\"
                                OR password LIKE \"%{$search}%\" OR creation_time LIKE \"%{$search}%\"
                                OR comment LIKE \"%{$search}%\"";
            $statement = $db -> prepare($select_query);
            $statement -> execute();
        }elseif("password" == $attribute){
            $select_query = "SELECT *,CAST(AES_DECRYPT(password, '".KEY_STR."', '".INIT_VECTOR."') AS CHAR) FROM website
                                INNER JOIN registersfor ON (website.website_id = registersfor.website_id)
                                INNER JOIN user ON (user.user_id = registersfor.user_id)
                                WHERE CAST(AES_DECRYPT({$attribute},'".KEY_STR."', '".INIT_VECTOR."')AS CHAR) LIKE \"%{$search}%\"";
            $statement = $db -> prepare($select_query);
            $statement -> execute();
        }else{
            $select_query = "SELECT *,CAST(AES_DECRYPT(password, '".KEY_STR."', '".INIT_VECTOR."') AS CHAR) FROM website
                                INNER JOIN registersfor ON (website.website_id = registersfor.website_id)
                                INNER JOIN user ON (user.user_id = registersfor.user_id)
                                WHERE {$attribute} LIKE \"%{$search}%\"";
            $statement = $db -> prepare($select_query);
            $statement -> execute();
        }

        if (count($statement -> fetchAll()) == 0) {
            return 0;
        } else {
            echo "      <table>\n";
            echo "        <thead>\n";
            echo "          <tr>\n";
            echo "            <th>Website ID</th>\n";
            echo "            <th>User ID</th>\n";
            echo "            <th>Website Name</th>\n";
            echo "            <th>Website URL</th>\n";
            echo "            <th>First Name</th>\n";
            echo "            <th>Last Name </th>\n";
            echo "            <th>Email</th>\n";
            echo "            <th>Username</th>\n";
            echo "            <th>Password</th>\n";
            echo "            <th>Creation Time</th>\n";
            echo "            <th>Comment</th>\n";
            echo "          </tr>\n";
            echo "        </thead>\n";
            echo "        <tbody>\n";

            // Populate the table with data coming from the database...
            foreach ($db ->query($select_query) as $row) {
                echo "          <tr>\n";
                echo "            <td>" . htmlspecialchars($row[0]) . "</td>\n";
                echo "            <td>" . htmlspecialchars($row[9]) . "</td>\n";
                echo "            <td>" . htmlspecialchars($row[1]) . "</td>\n";
                echo "            <td>" . htmlspecialchars($row[2]) . "</td>\n";
                echo "            <td>" . htmlspecialchars($row[10]) . "</td>\n";
                echo "            <td>" . htmlspecialchars($row[11]) . "</td>\n";
                echo "            <td>" . htmlspecialchars($row[12]) . "</td>\n";
                echo "            <td>" . htmlspecialchars($row[5]) . "</td>\n";
                echo "            <td>" . htmlspecialchars($row[13]) . "</td>\n";
                echo "            <td>" . htmlspecialchars($row[7]) . "</td>\n";
                echo "            <td>" . htmlspecialchars($row[8]) . "</td>\n";
                echo "          </tr>\n";
            }

            echo "         </tbody>\n";
            echo "      </table>\n";
        }
    } catch( PDOException $e ) {
        echo '<p>The following message was generated by function <code>search</code>:</p>' . "\n";
        echo '<p id="error">' . $e -> getMessage() . '</p>' . "\n";
        echo "<p>There are a few reasons for this. Perhaps the database doesn’t exist or wasn’t mounted. Does the volume/drive containing the database have read and write permissions?</p>\n";
        echo '<p>Click <a href="./">here</a> to go back.</p>';

        exit;
    }
}

function update($current_attribute, $new_component, $query_attribute, $pattern) {
    try {
        $db = new PDO(
            "mysql:host=" . DBHOST . "; dbname=" . DBNAME . ";charset=utf8",
            DBUSER
        );
        set_aes($db);
        try{
            if($current_attribute == "password" && $query_attribute == "password"){
                $db -> query("UPDATE website
                INNER JOIN registersfor ON (website.website_id = registersfor.website_id)
                INNER JOIN user ON (user.user_id = registersfor.user_id)
                SET {$current_attribute}=AES_ENCRYPT(\"{$new_component}\",'".KEY_STR."', '".INIT_VECTOR."')
                WHERE CAST(AES_DECRYPT({$query_attribute},'".KEY_STR."', '".INIT_VECTOR."')AS CHAR)=\"{$pattern}\"");
            }
            elseif($current_attribute == "password"){
                $db -> query("UPDATE website
                INNER JOIN registersfor ON (website.website_id = registersfor.website_id)
                INNER JOIN user ON (user.user_id = registersfor.user_id)
                SET {$current_attribute}=AES_ENCRYPT(\"{$new_component}\",'".KEY_STR."', '".INIT_VECTOR."')
                WHERE {$query_attribute}=\"{$pattern}\"");
            }elseif($query_attribute == "password") {
                $db -> query("UPDATE website
                INNER JOIN registersfor ON (website.website_id = registersfor.website_id)
                INNER JOIN user ON (user.user_id = registersfor.user_id)
                SET {$current_attribute}=\"{$new_component}\"
                WHERE CAST(AES_DECRYPT({$query_attribute},'".KEY_STR."', '".INIT_VECTOR."')AS CHAR)=\"{$pattern}\"");
            }else{
                $db -> query("UPDATE website
                        INNER JOIN registersfor ON (website.website_id = registersfor.website_id)
                        INNER JOIN user ON (user.user_id = registersfor.user_id)
                        SET {$current_attribute}=\"{$new_component}\" WHERE {$query_attribute}=\"{$pattern}\"");
            }
        }catch( Exception $e){
            return -1;
            exit;
        }
    } catch( PDOException $e ) {
        echo '<p>The following message was generated by function <code>update</code>:</p>' . "\n";
        echo '<p id="error">' . $e -> getMessage() . '</p>' . "\n";

        exit;
    }
}

function insert($website_name, $website_url, $first_name, $last_name, $email, $username, $password, $comment) {
    try {
        $db = new PDO(
            "mysql:host=" . DBHOST . "; dbname=" . DBNAME . ";charset=utf8",
            DBUSER
        );
        set_aes($db);

        $website_id = 0;
        $user_id = 0;
        $creation_time = '2022-12-11 03:40:21';

        $website_query = "SELECT website_id FROM website WHERE website_name = \"{$website_name}\" AND website_url = \"{$website_url}\"";
        $website_statement = $db -> prepare($website_query);
        $website_statement -> execute();

        if(!empty($website_statement -> fetch())){
            $website_id = $website_statement -> fetch();
        }else{
            $statement = $db -> prepare("INSERT INTO website VALUES (:website_id, :website_name, :website_url)");
            $statement -> execute(
                array(
                    'website_id'    => $website_id,
                    'website_name'  => $website_name,
                    'website_url'   => $website_url
                )
            );
        }

        $website_statement = $db -> prepare($website_query);
        $website_statement -> execute();
        $website_id = $website_statement -> fetch();

        $user_query = "SELECT user_id FROM user WHERE first_name = \"{$first_name}\" AND last_name = \"{$last_name}\" AND email = \"{$email}\"";
        $user_statement = $db -> prepare($user_query);
        $user_statement -> execute();

        if(!empty($user_statement -> fetch())){
            $user_id = $user_statement -> fetch();
        }else{
            $statement = $db -> prepare("INSERT INTO user VALUES (:user_id, :first_name, :last_name, :email)");
            $statement -> execute(
                array(
                    'user_id'       => $user_id,
                    'first_name'    => $first_name,
                    'last_name'     => $last_name,
                    'email'         => $email
                )
            );
        }

        $user_statement = $db -> prepare($user_query);
        $user_statement -> execute();
        $user_id = $user_statement -> fetch();

        $getdate_statement = $db -> prepare("SELECT NOW()");
        $getdate_statement -> execute();
        $creation_time = $getdate_statement -> fetch();

        $encrypt_query = $db -> prepare("SELECT AES_ENCRYPT(\"{$password}\",'".KEY_STR."', '".INIT_VECTOR."')");
        $encrypt_query -> execute();
        $encrypt_pass = $encrypt_query -> fetch();

        $statement = $db -> prepare("INSERT INTO registersfor VALUES (:website_id, :user_id, :username, :password, :creation_time, :comment)");
        $statement -> execute(
            array(
                'website_id'    => $website_id[0],
                'user_id'       => $user_id[0],
                'username'      => $username,
                'password'      => $encrypt_pass[0],
                'creation_time' => $creation_time[0],
                'comment'       => $comment
            )
        );

    } catch(PDOException $e) {
        echo '<p>The following message was generated by function <code>insert</code>:</p>' . "\n";
        echo '<p id="error">' . $e -> getMessage() . '</p>' . "\n";

        exit;
    }
}

function delete($current_attribute, $pattern) {
    try {
        $db = new PDO(
            "mysql:host=" . DBHOST . "; dbname=" . DBNAME . ";charset=utf8",
            DBUSER
        );
        set_aes($db);
        $db -> query("DELETE website, registersfor, user FROM website
                        INNER JOIN registersfor ON (website.website_id = registersfor.website_id)
                        INNER JOIN user ON (user.user_id = registersfor.user_id)
                        WHERE {$current_attribute}=\"{$pattern}\"");
    } catch(PDOException $e) {
        echo '<p>The following message was generated by function <code>delete</code>:</p>' . "\n";
        echo '<p id="error">' . $e -> getMessage() . '</p>' . "\n";

        exit;
    }
}

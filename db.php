<?php

class DBAccess {

    //private $resulttype = MYSQLI_ASSOC;
    private $dbhost = "localhost";
    private $dbuser = "root";
    private $dbpass = "Himmel12";
    private $dbdb = "CA";

    private function connect() {
        $connection = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbdb);
        if ($connection->connect_error) {
            //Sollte nicht ausgeführt werden, wenn der Zugriff funktioniert
            printf("Verbindung fehlgeschlagen: %s (%s)\n", $connection->connect_errno, $connection->connect_error);
            exit();
        } else {
            return $connection;
        }
    }

    private function query($query) {
        $connection = $this->connect();
        //Escapen nicht sinnvoll für besondere anwendungen; query ist private
        //$query = $connection->real_escape_string($query);
        //Query ausführen
        $rows = $this->query_secuence($connection, $query);
        //Verbindung sauber trennen
        $connection->close();
        return $rows;
    }

    /**
     *
     * SELECT Abfragen
     * Jede Selectabfrage gibt ein zweidimensionales Array zurück, wird kein Datensatz übergeben, dann NULL
     *
     * */
    //QuerySequence ausführen, ist für alle SelectAbragen gleich
    private function query_secuence($connection, $query) {
        //Achtung dieses query unterhalb ist von der MYSQLI Klasse, nicht von der DBAccess Klasse
        $result = $connection->query($query);
        $obj_array = array();
        if ($result == NULL) {
            //Sollte nicht ausgeführt werden, wenn der Query erfolgreich war, auch wenn kein Datensatz zurückgeliefert wird, das NULL bei keinen Datensätzen wird unten gesetzt.
            return null;
        } else {
            //gibt ein zweidimensionales Array für alle Anwendungen aus
            while ($obj = $result->fetch_object()) { //$result->fetch_array($this->resulttype)){
                $obj_array[] = $obj;
            }
        }
        //Result freigeben
        $result->free();
        return $obj_array;
    }

    private function select($select, $from, $where_clause = array()) {
        $connection = $this->connect();
        if ($select == "*" or $select == "" or $select === NULL)
            $select = "*";
        //escape
        $select = $connection->real_escape_string($select);
        $from = $connection->real_escape_string($from);
        $where = "";
        if (is_array($where_clause) and count($where_clause) > 0) {
            foreach ($where_clause as $where_value) {
                if ((strpos($where_value, "'") !== false
                        and strrpos($where_value, "'") !== false
                        and strpos($where_value, "'") < strrpos($where_value, "'")
                        ) or ( strpos($where_value, '"') !== false
                        and strrpos($where_value, '"') !== false
                        and strpos($where_value, '"') < strrpos($where_value, '"')
                        ) or ( strpos($where_value, "`") !== false
                        and strrpos($where_value, "`") !== false
                        and strpos($where_value, "`") < strrpos($where_value, "`")))
                    $where .= "'".$connection->real_escape_string(substr($where_value, 1, strlen($where_value) - 2))."'";
                else
                    $where .= $connection->real_escape_string($where_value);
            }
        }

        $query = count($where_clause) == 0 ? "SELECT $select FROM $from" : "SELECT $select FROM $from WHERE $where";
        //Query ausführen
        $rows = $this->query_secuence($connection, $query);
        //Verbindung sauber trennen
        $connection->close();
        return $rows;
    }

    //spezifische Select-Abfragen
    function get_role_all() {
        return $this->select("*", "role");
    }

    function get_role_all_where($where) {
        return $this->select("*", "role", $where);
    }

    function get_user_all() {
        return $this->select("*", "user");
    }

    function get_user_all_where($where) {
        return $this->select("*", "user", $where);
    }

    function get_request_all() {
        return $this->select("*", "request");
    }

    function get_request_all_where($where) {
        return $this->select("*", "request", $where);
    }

    function get_sans_all() {
        return $this->select("*", "sans");
    }

    function get_sans_all_where($where) {
        return $this->select("*", "sans", $where);
    }

    private function insert($table, $cols, $values) {
        $connection = $this->connect();
        //cols und values sind eindimensionale arrays, es gilt count($cols) gleich count($values) wenn nicht dann gibt es einen fehler beim query der als error ausgegeben wird
        //Escapes und Arrays auswerten
        $table = $connection->real_escape_string($table);
        if (count($cols) != count($values)) {
            printf("Ung&uuml;tige Parameter");
            exit();
        }
//Hole den Auto_Increment-Wert, sofern vorhanden.
//$ai = NULL;
//$ai_array = $this->query("SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA LIKE 'COCKTAIL_EXPERTS' AND TABLE_NAME LIKE '$table'");
//$ai = reset(reset($ai_array));

        $col = NULL;
        foreach ($cols as $part) {
            $col .= ", `" . $connection->real_escape_string($part) . "`";
        }
        if (strlen($col) > 0)
            $col = substr($col, 1);
        $value = NULL;
        foreach ($values as $part) {
            $value .= ",'" . $connection->real_escape_string($part) . "'";
        }
        if (strlen($value) > 0)
            $value = substr($value, 1);

        //Query ausführen und Verbindung trennen
        $query = "INSERT INTO `$table` ($col) VALUES($value)";
        $connection->query($query);
        return $connection->errno == 0 ? true : printf("%d: %s", $connection->errno, $connection->error); //Alles größer 0 ist ein Fehler
        //wenn Query erfolgreich durchgelaufen ist und kein autoincrement vorhanden war wird true ausgegeben war ein fehler vorhanden und der auto increment wert konnte gelesen werden wird $ai auf false gesetzt
        /*
          if ($errno == 0 && $ai == false) {
          //ok, kein auto_inc vorhanden
          $ai = true;
          } else if ($errno != 0 && $ai != false) {
          // nicht gut obwohl auto_inc vorhanden war
          $ai = false;
          }
          $connection->close();
          return $ai;
         */
    }

    //spezifisch
    function insert_role($name, $read_own, $write_own, $execute_own, $read_foreign, $write_foreign, $execute_foreign) {
        $role_name = $name; // hier current User nehmen
        return $this->insert("role", array("name", "read_own", "write_own", "execute_own", "read_foreign", "write_foreign", "execute_foreign", "role_name"), array($name, $read_own, $write_own, $execute_own, $read_foreign, $write_foreign, $execute_foreign, $role_name));
    }

    function insert_user($email, $firstname, $surname, $password, $role_name) {
        return $this->insert("user", array("email", "firstname", "surname", "passwordhash", "role_name"), array($email, $firstname, $surname, $password, $role_name));
    }

    function insert_request($start, $end, $country, $state, $city, $organisation_name, $organisation_unit_name, $common_name, $responsible_email, $challenge_password, $optional_company_name, $intermediate, $id_from_intermediate, $status, $verifier, $path) {
    	require_once('./UserHelper.inc');
    	$user_email = UserHelper::GetUserEmail();
    	if (!empty($user_email)) {
        	return $this->insert("request", array("start", "end", "country", "state", "city", "organisation_name", "organisation_unit_name", "common_name", "responsible_email", "challenge_password", "optional_company_name", "intermediate", "id_from_intermediate", "status", "verifier", "path", "user_email"), array($start, $end, $country, $state, $city, $organisation_name, $organisation_unit_name, $common_name, $responsible_email, $challenge_password, $optional_company_name, $intermediate, $id_from_intermediate, $status, $verifier, $path, $user_email));
        }
        else {
        	return false;
        }
    }

    function insert_sans($request_id, $name) {
        return $this->insert("sans", array("request_id", "name"), array($request_id, $name));
    }

    /**
     *
     * UPDATE
     *
     * */
    private function update($table, $cols, $values, $where, $like) {
        $connection = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbdb);
        if ($connection->connect_error) {
            //Sollte nicht ausgeführt werden, wenn der Zugriff funktioniert
            printf("Verbindung fehlgeschlagen: %s\n", $connection->connect_errno);
            exit();
        }
        //cols und values sind eindimensionale arrays, es gilt count($cols) gleich count($values) wenn nicht dann gibt es einen fehler beim query der als error ausgeben wird
        //Escapes und Arrays auswerten
        $table = $connection->real_escape_string($table);
        if (count($cols) != count($values)) {
            return "Ung&uuml;tige Parameter";
        }
        $set = NULL;
        for ($i = 0; $i < count($cols); $i++) {
            $set .= ", `" . $connection->real_escape_string($cols[$i]) . "`='" . $connection->real_escape_string($values[$i]) . "'";
        }
        if (strlen($set) > 0) {
            $set = substr($set, 1);
        }
        $where = $connection->real_escape_string($where);
        $like = $connection->real_escape_string($like);
        //Query ausführen und Verbindung trennen
        $query = "UPDATE `$table` SET $set WHERE `$where` LIKE '$like'";
        $connection->query($query);

        //Alles größer 0 ist ein Fehler
        $ret = false;
        if ($connection->errno == 0) {
            $ret = true;
        } else {
            $ret = false;
        }

        $connection->close();
        return $ret;
    }

    //spezifisch
    //ID ist nicht änderbar
    function update_recipe($id, $name, $description, $category_ID, $external_ID) {
        return $this->update("recipe", array("name", "description", "category_ID", "external_ID"), array($name, $description, $category_ID, $external_ID), "ID", $id);
    }

    function update_quantity_unit($short, $long, $sign, $external_ID) {
        return $this->update("quantity_unit", array("short", "long", "sign", "external_ID"), array($short, $long, $sign, $external_ID), "short", $short);
    }

    function update_currency($short, $long, $sign, $external_ID) {
        return $this->update("currency", array("short", "long", "sign", "external_ID"), array($short, $long, $sign, $external_ID), "short", $short);
    }

    //ID ist nicht änderbar
    function update_category($id, $long, $external_ID) {
        return $this->update("category", array("long", "external_ID"), array($long, $external_ID), "ID", $id);
    }

    //ID ist nicht änderbar	
    function update_article($id, $name, $price, $currency_short, $quantity, $quantity_unit_short, $description, $category_ID, $external_ID) {
        return $this->update("article", array("name", "price", "currency_short", "quantity", "quantity_unit_short", "description", "category_ID", "external_ID"), array($name, $price, $currency_short, $quantity, $quantity_unit_short, $description, $category_ID, $external_ID), "ID", $id);
    }

    function update_customer($email, $password, $surname, $prename, $address, $token) {
        return $this->update("customer", array("email", "password", "surname", "prename", "address", "token"), array($email, $password, $surname, $prename, $address, $token), "email", $email);
    }

    //ID ist nicht änderbar
    function update_order($id, $creation_date, $customer_email) {
        return $this->update("order", array("creation_date", "customer_email"), array($creation_date, $customer_email), "ID", $id);
    }

    //ID ist nicht änderbar
    function update_orderline($id, $order_id, $quantity, $price, $article_id) {
        return $this->update("orderline", array("order_ID", "quantity", "price", "article_ID"), array($order_id, $quantity, $price, $article_id), "ID", $id);
    }

    function update_admin($email, $password, $permission_add_admin, $permission_add_element, $permission_view_statistics) {
        return $this->update("admin", array("email", "password", "permission_add_admin", "permission_add_element", "permission_view_statistics"), array($email, $password, $permission_add_admin, $permission_add_element, $permission_view_statistics), "email", $email);
    }

    //extra Implementierung der Methode nötig
    function update_recipe_article($recipe_ID, $article_ID, $quantity, $quantity_unit_short) {
        $connection = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbdb);
        if ($connection->connect_error) {
            //Sollte nicht ausgeführt werden, wenn der Zugriff funktioniert
            printf("Verbindung fehlgeschlagen: %s\n", $connection->connect_errno);
            exit();
        }
        //cols und values sind eindimensionale arrays, es gilt count($cols) gleich count($values) wenn nicht dann gibt es einen fehler beim query der als error ausgeben wird
        //Escapes und Arrays auswerten
        $quantity = $connection->real_escape_string($quantity);
        $quantity_unit_short = $connection->real_escape_string($quantity_unit_short);
        $recipe_ID = $connection->real_escape_string($recipe_ID);
        $article_ID = $connection->real_escape_string($article_ID);
        //Query ausführen und Verbindung trennen
        $query = "UPDATE `recipe_article` SET `quantity`=$quantity, `quantity_unit_short`=$quantity_unit_short WHERE `recipe_ID`='$recipe_ID' AND `article_ID`='$article_ID'";
        $connection->query($query);

        $ret = false;
        if ($connection->errno == 0) {
            $ret = true;
        } else {
            $ret = false;
        }

        $connection->close();
        return $ret;
    }

    /**
     *
     * DELETE
     *
     * */
    private function delete($table, $where, $like) {
        $connection = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbdb);
        if ($connection->connect_error) {
            //Sollte nicht ausgeführt werden, wenn der Zugriff funktioniert
            printf("Verbindung fehlgeschlagen: %s\n", $connection->connect_errno);
            exit();
        }
        //cols und values sind eindimensionale arrays, es gilt count($cols) gleich count($values) wenn nicht dann gibt es einen fehler beim query der als error ausgeben wird
        //Escapes und Arrays auswerten
        $table = $connection->real_escape_string($table);
        $where = $connection->real_escape_string($where);
        $like = $connection->real_escape_string($like);
        //Query ausführen und Verbindung trennen
        $query = "DELETE FROM `$table` WHERE `$where` LIKE '$like'";
        $connection->query($query);

        $ret = false;
        if ($connection->errno == 0) {
            $ret = true;
        } else {
            $ret = false;
        }

        $connection->close();
        return $ret;
    }

    //spezifisch
    function delete_recipe($id) {
        //gibt errornummer > 0 zurück wenn es verwendet wird... 
        //return $this->delete("recipe", "ID", $id);
        //löscht stückliste und danach rezept
        $connection = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbdb);
        if ($connection->connect_error) {
            //Sollte nicht ausgeführt werden, wenn der Zugriff funktioniert
            printf("Verbindung fehlgeschlagen: %s\n", $connection->connect_errno);
            exit();
        }
        $id = $connection->real_escape_string($id);
        $connection->query("START TRANSACTION");
        $connection->query("DELETE FROM `recipe_article` WHERE `recipe_ID` LIKE '$id'");
        $connection->query("DELETE FROM `recipe` WHERE `ID` LIKE '$id'");
        $connection->query("COMMIT");
        $errno = $connection->errno;
        $ret = false;
        if ($connection->errno == 0) {
            $ret = true;
        } else {
            $ret = false;
        }
        $connection->close();
        return $ret;
    }

    function delete_quantity_unit($short) {
        return $this->delete("quantity_unit", "short", $short);
    }

    function delete_currency($short) {
        return $this->delete("currency", "short", $short);
    }

    function delete_category($id) {
        return $this->delete("category", "ID", $id);
    }

    function delete_article($id) {
        //artikel darf nicht gelöscht werden wenn eine stückliste dazu existiert
        //gibt errornummer > 0 zurück wenn es verwendet wird... 
        return $this->delete("article", "ID", $id);
    }

    function delete_user($email) {
        return $this->delete("user", "email", $email);
    }

    function delete_order($id) {
        $connection = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbdb);
        if ($connection->connect_error) {
            //Sollte nicht ausgeführt werden, wenn der Zugriff funktioniert
            printf("Verbindung fehlgeschlagen: %s\n", $connection->connect_errno);
            exit();
        }
        $id = $connection->real_escape_string($id);
        $connection->query("START TRANSACTION");
        $connection->query("DELETE FROM `orderline` WHERE `order_ID` LIKE '$id'");
        $connection->query("DELETE FROM `order` WHERE `ID` LIKE '$id'");
        $connection->query("COMMIT");
        $errno = $connection->errno;
        $ret = false;
        if ($connection->errno == 0) {
            $ret = true;
        } else {
            $ret = false;
        }
        $connection->close();
        return $ret;
    }

    function delete_admin($email) {
        return $this->delete("admin", "email", $email);
    }

    function delete_recipe_article($recipe_ID, $article_ID) {
        $connection = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbdb);
        if ($connection->connect_error) {
            //Sollte nicht ausgeführt werden, wenn der Zugriff funktioniert
            printf("Verbindung fehlgeschlagen: %s\n", $connection->connect_errno);
            exit();
        }
        $recipe_ID = $connection->real_escape_string($recipe_ID);
        $article_ID = $connection->real_escape_string($article_ID);
        $connection->query("DELETE FROM `recipe_article` WHERE `recipe_ID` LIKE '$recipe_ID' AND `article_ID` LIKE '$article_ID'");
        return $errno = $connection->errno;
        $ret = false;
        if ($connection->errno == 0) {
            $ret = true;
        } else {
            $ret = false;
        }
        $connection->close();
        return $ret;
    }

    function delete_recipe_external($external_ID) {
        //gibt errornummer > 0 zurück wenn es verwendet wird... 
        //return $this->delete("recipe", "ID", $id);
        //löscht stückliste und danach rezept
        $connection = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbdb);
        if ($connection->connect_error) {
            //Sollte nicht ausgeführt werden, wenn der Zugriff funktioniert
            printf("Verbindung fehlgeschlagen: %s\n", $connection->connect_errno);
            exit();
        }
        $external_ID = $connection->real_escape_string($external_ID);
        $id = reset(reset($this->selectFilter("ID", "recipe", "external_ID", $external_ID)));
        if (!is_numeric($id)) {
            $id = -1;
        }
        $id = $connection->real_escape_string($id);
        $connection->query("START TRANSACTION");
        $connection->query("DELETE FROM `recipe_article` WHERE `ID` LIKE '$id'");
        $connection->query("DELETE FROM `recipe` WHERE `ID` LIKE '$id'");
        $connection->query("COMMIT");
        $errno = $connection->errno;
        $ret = false;
        if ($connection->errno == 0) {
            $ret = true;
        } else {
            $ret = false;
        }
        $connection->close();
        return $ret;
    }

    function delete_recipe_article_by_recipe($recipe_ID) {
        $connection = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbdb);
        if ($connection->connect_error) {
            //Sollte nicht ausgeführt werden, wenn der Zugriff funktioniert
            printf("Verbindung fehlgeschlagen: %s\n", $connection->connect_errno);
            exit();
        }
        $recipe_ID = $connection->real_escape_string($recipe_ID);
        $article_ID = $connection->real_escape_string($article_ID);
        $connection->query("DELETE FROM `recipe_article` WHERE `recipe_ID` LIKE '$recipe_ID'");
        return $errno = $connection->errno;
        $ret = false;
        if ($connection->errno == 0) {
            $ret = true;
        } else {
            $ret = false;
        }
        $connection->close();
        return $ret;
    }

}

?>

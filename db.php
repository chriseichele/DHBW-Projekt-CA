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
            printf("Verbindung zur DB fehlgeschlagen: %s (%s)\n", $connection->connect_errno, $connection->connect_error);
            exit();
        } else {
            return $connection;
        }
    }

    private function query($connection_handler, $query) {
        if ($connection_handler == NULL)
            $connection = $this->connect();
        else
            $connection = $connection_handler;
        //Escapen nicht sinnvoll für besondere anwendungen; query ist private
        //$query = $connection->real_escape_string($query);
        //Query ausführen
        $rows = $this->query_secuence($connection, $query);
        //Verbindung sauber trennen
        if ($connection_handler == NULL)
            $connection->close();
        return $rows;
    }

    //QuerySequence ausführen, ist für alle Abfragen gleich
    private function query_secuence($connection, $query) {
        //Achtung: Dieses query unterhalb ist von der MYSQLI Klasse, nicht von der DBAccess Klasse
        $result = $connection->query($query);
        $rows = array();
        if ($connection->errno > 0) {
        //Alles größer 0 ist ein Fehler
            $rows[] = printf("%d: %s", $connection->errno, $connection->error);
        } else {
            //gibt ein zweidimensionales Array für alle Anwendungen aus
            //falls ein DB-Objekt zurueckgeliefert wurde, iteriere ueber dieses
            if (is_object($result)) {
                while ($row = $result->fetch_object()) {//fetch_assoc()) {
                    $rows[] = $row;
                }
            }
        }
        //Result freigeben, macht nur bei DB-Objekten Sinn
        if (is_object($result))
            $result->free();
        return $rows;
    }

    protected function escape_where_clause($connection, $where_clause) {
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
                    $where .= "'" . $connection->real_escape_string(substr($where_value, 1, strlen($where_value) - 2)) . "'";
                else
                    $where .= $connection->real_escape_string($where_value);
            }
        }
        return $where;
    }

    protected function escape_value($connection, $values) {
        $value = "";
        foreach ($values as $part) {
            if ($part === NULL)
                $value .= ", NULL";
            else if (is_numeric($part))
                $value .= ", " . $part;
            else
                $value .= ", '" . $connection->real_escape_string($part) . "'";
        }
        if (strlen($value) > 0)
            $value = substr($value, 2);
        return $value;
    }

    /**
     * SELECT Abfragen
     * Jede Selectabfrage gibt ein zweidimensionales Array zurück, wird kein Datensatz übergeben, dann NULL
     * */
    private function select($select, $from, $where_clause = array()) {
        $connection = $this->connect();
        if ($select == "*" or $select == "" or $select === NULL)
            $select = "*";
        //escape
        $select = $connection->real_escape_string($select);
        $from = $connection->real_escape_string($from);
        $where = $this->escape_where_clause($connection, $where_clause);
        $query = count($where_clause) == 0 ? "SELECT $select FROM $from" : "SELECT $select FROM $from WHERE $where";    
        $return = $this->query($connection, $query);
        //Verbindung sauber trennen
        $connection->close();
        return $return;
    }

    //spezifisch
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

    /**
     * INSERT
     * NULL-Werte koennen eingefuegt werden, auch trotz Foreign-Key-Constraints
     * */
    private function insert($table, $cols, $values) {
        $connection = $this->connect();
        //cols und values sind eindimensionale arrays, es gilt count($cols) gleich count($values) wenn nicht dann gibt es einen fehler beim query der als error ausgegeben wird
        //Escapes und Arrays auswerten
        $table = $connection->real_escape_string($table);
        if (count($cols) != count($values)) {
            printf("Ung&uuml;ltige Parameter.<br />\n");
            exit();
        }

        $col = NULL;
        foreach ($cols as $part) {
            $col .= ", `" . $connection->real_escape_string($part) . "`";
        }
        if (strlen($col) > 0)
            $col = substr($col, 2);

        $value = $this->escape_value($connection, $values);

        $query = "INSERT INTO `$table` ($col) VALUES ($value)";
        $return = $this->query($connection, $query);
        $connection->close();
        return $return;
    }

    //spezifisch
    function insert_role($name, $read_own, $write_own, $execute_own, $read_foreign, $write_foreign, $execute_foreign) {
        $user_email = $_SESSION["email"];
        $result = $this->get_user_all_where(array("email", "=", "'$user_email'"));
        $obj = reset($result);
        $role = !empty($obj) ? $obj->role : "";
        if ($role == "administrator")
            return $this->insert("role", array("name", "read_own", "write_own", "execute_own", "read_foreign", "write_foreign", "execute_foreign"), array($name, $read_own, $write_own, $execute_own, $read_foreign, $write_foreign, $execute_foreign));
        else
            return FALSE; //print("Sie besitzen nicht die notwendigen Berechtigungen, um eine Benutzerrolle anzulegen.<br />\n");
    }

    function insert_user($email, $firstname, $surname, $passwordhash, $role) {
        return $this->insert("user", array("email", "firstname", "surname", "passwordhash", "role"), array($email, $firstname, $surname, $passwordhash, $role));
    }

    function insert_request($start, $end, $country, $state, $city, $organisation_name, $common_name, $status, $organisation_unit_name = NULL, $responsible_email = NULL, $challenge_password = NULL, $optional_company_name = NULL, $intermediate = NULL, $verifier = NULL, $path_csr = NULL, $path_cer = NULL) {
        $requester = $_SESSION["email"];
        if (empty($requester))
            return "Nur angemeldete User k&ouml;nnen Zertifikate erstellen.<br />\n";
        //Intermediate uebergeben, existiert auch dieser?
        if (!empty($intermediate) and empty($this->get_request_all_where(array("id", "=", $intermediate))))
            return "Kein g&uuml;ltiges Intermediate-Zertifikat angegeben.<br />\n";

        return $this->insert("request", array("requester", "start", "end", "country", "state", "city", "organisation_name", "organisation_unit_name", "common_name", "responsible_email", "challenge_password", "optional_company_name", "intermediate", "verifier", "status", "path_csr", "path_cer"), array($requester, $start, $end, $country, $state, $city, $organisation_name, $organisation_unit_name, $common_name, $responsible_email, $challenge_password, $optional_company_name, $intermediate, $verifier, $status, $path_csr, $path_cer));
    }

    function insert_sans($request_id, $name) {
        return $this->insert("sans", array("request_id", "name"), array($request_id, $name));
    }

    /**
     * UPDATE
     * PrimaryKey ist nie aenderbar
     * */
    private function update($table, $cols, $values, $where_clause = array()) {
        $connection = $this->connect();
        //cols und values sind eindimensionale arrays, es gilt count($cols) gleich count($values) wenn nicht dann gibt es einen fehler beim query der als error ausgeben wird
        //Escapes und Arrays auswerten
        $table = $connection->real_escape_string($table);
        if (count($cols) != count($values)) {
            printf("Ung&uuml;ltige Parameter.<br />\n");
            exit();
        }
        $set = NULL;
        $i = 0;
        foreach ($values as $part) {
            if ($part === NULL)
                $set .= ", " . $cols[$i] . "=NULL ";
            else if (is_numeric($part))
                $set .= ", " . $cols[$i] . "=" . $part . " ";
            else
                $set .= ", " . $cols[$i] . "='" . $connection->real_escape_string($part) . "' ";
            $i++;
        }
        if (strlen($set) > 0)
            $set = substr($set, 2);

        $where = $this->escape_where_clause($connection, $where_clause);

        $query = "UPDATE `$table` SET $set WHERE $where";
        $return = $this->query($connection, $query);
        $return['affected_rows'] = $connection->affected_rows;
        $connection->close();
        return $return;
    }

    //spezifisch
    function update_role($where_clause, $read_own, $write_own, $execute_own, $read_foreign, $write_foreign, $execute_foreign) {
        return $this->update("role", array("read_own", "write_own", "execute_own", "read_foreign", "write_foreign", "execute_foreign"), array($read_own, $write_own, $execute_own, $read_foreign, $write_foreign, $execute_foreign), $where_clause);
    }

    function update_user($where_clause, $firstname, $surname, $passwordhash, $role) {
        return $this->update("user", array("firstname", "surname", "passwordhash", "role"), array($firstname, $surname, $passwordhash, $role), $where_clause);
    }

    function update_request_all($where_clause, $start, $end, $country, $state, $city, $organisation_name, $organisation_unit_name, $common_name, $responsible_email, $challenge_password, $optional_company_name, $intermediate, $verifier, $status, $path_csr, $path_cer) {
        return $this->update("request", array("start", "end", "country", "state", "city", "organisation_name", "organisation_unit_name", "common_name", "responsible_email", "challenge_password", "optional_company_name", "intermediate", "verifier", "status", "path_csr", "path_cer"), array($start, $end, $country, $state, $city, $organisation_name, $organisation_unit_name, $common_name, $responsible_email, $challenge_password, $optional_company_name, $intermediate, $verifier, $status, $path_csr, $path_cer), $where_clause);
    }

    function update_request_intermediate($where_clause, $intermediate) {
        return $this->update("request", array("intermediate"), array($intermediate), $where_clause);
    }

    function update_request_verifier($where_clause, $verifier) {
        return $this->update("request", array("verifier"), array($verifier), $where_clause);
    }

    function update_request_status($where_clause, $status) {
        return $this->update("request", array("status"), array($status), $where_clause);
    }

    function update_request_path_csr($where_clause, $path_csr) {
        return $this->update("request", array("path_csr"), array($path_csr), $where_clause);
    }
    
    function update_request_path_cer($where_clause, $path_cer) {
        return $this->update("request", array("path_cer"), array($path_cer), $where_clause);
    }

    function update_request_dates($where_clause, $start, $end) {
        return $this->update("request", array("start", "end"), array($start, $end), $where_clause);
    }

    function update_sans($where_clause, $name) {
        return $this->update("sans", array("name"), array($name), $where_clause);
    }

    /**
     * DELETE
     *
     * */
    private function delete($table, $where_clause) {
        $connection = $this->connect();
        //cols und values sind eindimensionale arrays, es gilt count($cols) gleich count($values) wenn nicht dann gibt es einen fehler beim query der als error ausgeben wird
        //Escapes und Arrays auswerten
        $table = $connection->real_escape_string($table);
        $where = $this->escape_where_clause($connection, $where_clause);

        $query = "DELETE FROM `$table` WHERE $where";
        $return = $this->query($connection, $query);
        $connection->close();
        return $return;
    }

    //spezifisch
    function delete_role($name) {
        return $this->delete("role", array("name", "=", "'" . $name . "'"));
    }

    function delete_user($email) {
        return $this->delete("user", array("email", "=", "'" . $email . "'"));
    }

    function delete_request($id) {
        return $this->delete("request", array("id", "=", "$id"));
    }

    function delete_sans($request_id, $name) {
        return $this->delete("sans", array("request_id", "=", "$request_id", " and ", "name", "=", "'" . $name . "'"));
    }

}

?>

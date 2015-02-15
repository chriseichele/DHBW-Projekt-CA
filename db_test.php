<?php
include ("./db.php");

$connection = new DBAccess();
echo "<pre>";
echo "<h1>role</h1>";
foreach($connection->get_role_all() as $result) {
    print_r($result);
}
echo "con: "; print_r($connection->get_role_all_where("read_own = 1"));
echo "con: "; print_r($connection->get_role_all_where("name = `ich`"));
echo "ins: "; print_r($connection->insert_role("ich", true, 1, "1", 0, 0, 0));
echo "<hr />";
echo "<h1>user</h1>";
foreach($connection->get_user_all() as $result) {
    print_r($result); 
}
echo "con: "; print_r($connection->get_user_all_where("firstname=florian"));
echo "ins: "; print_r($connection->insert_user("test@test.de", "flo", "saumweber", "mein pw", "cu"));
echo "<hr />";
echo "<h1>request</h1>";
foreach($connection->get_request_all() as $result) {
    print_r($result); 
}
echo "con: "; print_r($connection->get_request_all_where("firstname=flo"));
echo "ins: "; print_r($connection->insert_request(date("Y-m-d"), '2015-07-01', "DE", "Bayern", "Buba", "wwi12-05", "", "wwi12-05", "flo@gmx.de", "", "", false, NULL, "1", "test", "path", "ich"));
echo "<hr />";
echo "<h1>sans</h1>";
foreach($connection->get_sans_all() as $result) {
    print_r($result); 
}
echo "con: "; print_r($connection->get_sans_all_where("firstname=flo"));
echo "ins: "; print_r($connection->insert_sans(1, "sans-name"));

echo "</pre>";
?>

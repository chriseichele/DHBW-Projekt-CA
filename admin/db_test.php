<?php

include ("./admin/db.php");

$connection = new DBAccess();
echo "<pre>";
echo "<h1>role</h1>";
foreach ($connection->get_role_all() as $result) {
    print_r($result);
}
echo "con: ";
print_r($connection->get_role_all_where(array("name", "=", "'administrator'")));
echo "ins: ";
print_r($connection->insert_role("ich", true, 1, "1", 0, 0, 0));
echo "<hr />";
echo "<h1>user</h1>";
foreach ($connection->get_user_all() as $result) {
    print_r($result);
}
echo "con: ";
print_r($connection->get_user_all_where(array("firstname", "=", "'florian'")));
echo "ins: ";
print_r($connection->insert_user("testfjdlfksjlö@test.de", "flo", "saumweber", "1", "administrator"));
echo "<hr />";
echo "<h1>request</h1>";
foreach ($connection->get_request_all() as $result) {
    print_r($result);
}
echo "con: ";
print_r($connection->get_request_all_where(array("id", "=", "1")));
echo "ins: ";
print_r($connection->insert_request(
                date("Y-m-d"), //start
                '2015-07-01', //end
                "DE", //country
                "Bayern", //state
                "Buba", //city
                "wwi12-05", //org_name
                "wwi12-05", //common_name
                1, //status
                NULL, //org_unit_name
                NULL, //resp_mail
                NULL, //challenge_pw
                NULL, //optional_company_name
                NULL, //intermediate
                NULL, //verifier
                NULL)); //path
echo "<hr />";
echo "<h1>sans</h1>";
foreach ($connection->get_sans_all() as $result) {
    print_r($result);
}
echo "con: ";
print_r($connection->get_sans_all_where(array("request_id", "=", "1")));
echo "ins: ";
print_r($connection->insert_sans(1, "sans-name1"));



$connection->insert_sans(1, "sans-hallo");
$connection->insert_sans(2, "sans-welt");
$connection->insert_sans(2, "sans-alles");
echo "<br /><br /><br /><hr /><hr />update: ";
print_r($connection->update_request_dates(array("id", "=", 1), date('Y-m-d'), '2033-03-03'));
print_r($connection->update_request_intermediate(array("id", "=", 2), 1));
print_r($connection->update_request_path_csr(array("id", "=", 3), "test/../csr"));
print_r($connection->update_request_path_cer(array("id", "=", 3), "test/../cer"));
print_r($connection->update_request_status(array("id", "=", 4), 2));
print_r($connection->update_request_status(array("id", "=", 3), 3));
print_r($connection->update_request_verifier(array("id", "=", 5), "florian.saumweber.2012@student.dhbw-heidenheim.de"));
print_r($connection->update_role(array("name", "=", "'customer'"), 0,0,0,1,1,1,"customer"));
print_r($connection->update_sans(array("request_id", "=", 2, " and ", "name", "=", "'sans-name2'"), "update sans-name1"));
print_r($connection->update_user(array("email", "=", "'".$_SESSION['email']."'"), "icke", "nachname-icke", "halllo", "ich"));
echo "<br /><br /><br /><hr /><hr />delete: ";
echo "user: ";print_r($connection->delete_user("test11@test.de"));
echo "role: ";print_r($connection->delete_role("employee"));
echo "request: ";print_r($connection->delete_request(72));
echo "sans: ";print_r($connection->delete_sans(2, 'update sans-name1'));


var_dump($connection->reset_db());
echo "</pre>";
?>
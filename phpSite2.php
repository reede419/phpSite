<?php
if (!defined('READFILE')) {
    exit ("Error, wrong way to file.<br><a href=\"/\">Go to main</a>.");
}

function admin_get_system_unit($id) {
	$db = get_db_connection();
	$result = $db->query("SELECT * FROM systems WHERE id='$id'") or die(mysqli_error($db));
	$system = $result->fetch_object('AdminSystemDTO');
	$system = admin_system_unserialize_data($system);
	$result->close();
	$db->close();
	return $system;
}

function admin_get_all_system_units() {
	$systems = array();
	$db = get_db_connection();
	$result = $db->query("SELECT * FROM systems") or die(mysqli_error($db));
	while ($system = $result->fetch_object('AdminSystemDTO')) {
		$system = admin_system_unserialize_data($system);
		array_push($systems, $system);
	}
	$result->close();
	$db->close();
	return $systems;
}

function admin_system_property_byte_encode($arr) {
	$string = '';
	foreach ($arr as $item) {
		// if ($item == 'on') $string .= '1'; else $string .= '0';
		$string .= $item;
	}
	return $string;
}

function admin_system_property_byte_decode($string) {
	$arr = array();
	for ($i = 0; $i < strlen($string); $i++) {
		// if ($string[$i] == '1') array_push($arr, 'on'); else array_push($arr, 'off');
		array_push($arr, $string[$i]);
	}
	return $arr;
}

function admin_save_system($s) {
	$s->basehardware = serialize($s->basehardware);
	$s->hardware = serialize($s->hardware);
	$s->subsystems = serialize($s->subsystems);
	$s->formulae =  serialize($s->formulae);
	$db = get_db_connection();
	if (empty($s->id)) {
		$stmt = $db->prepare("INSERT INTO systems (name,status,description,purpose,classes,integration,basehardware,hardware,subsystems,image,icon,formulae) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->bind_param("sdsssdssssss", $s->name, $s->status, $s->description, $s->purpose, $s->classes, $s->integration, $s->basehardware, $s->hardware, $s->subsystems, $s->image, $s->icon, $s->formulae);
		$stmt->execute() or die(mysqli_error($db));
		$result = $db->insert_id;
	} else {
		$stmt = $db->prepare("UPDATE systems SET name=?,status=?,description=?,purpose=?,classes=?,integration=?,basehardware=?,hardware=?,subsystems=?,image=?,icon=?,formulae=? WHERE id=?");
		$stmt->bind_param("sdsssdssssssd", $s->name, $s->status, $s->description, $s->purpose, $s->classes, $s->integration, $s->basehardware, $s->hardware, $s->subsystems, $s->image, $s->icon, $s->formulae, $s->id);
		$result = $stmt->execute() or die(mysqli_error($db));
	}
	$db->close();
	return $result;
}

function admin_system_unserialize_data($system) {
	$system->basehardware = unserialize($system->basehardware);
	$system->hardware = unserialize($system->hardware);
	$system->subsystems = unserialize($system->subsystems);
	$system->formulae = unserialize($system->formulae);
	$classes = admin_system_property_byte_decode($system->classes);
	$system->classes = array();
	for ($i = 0; $i < count($classes); $i++) {
		$system->classes['class'.$i] = $classes[$i];
	}
	$purpose = admin_system_property_byte_decode($system->purpose);
	$system->purpose = array();
	for ($i = 0; $i < count($purpose); $i++) {
		$system->purpose['purpose'.$i] = $purpose[$i];
	}
	return $system;
}

function ajax_get_admin_systems() {
	$systems = admin_get_all_system_units();
	// print_r($systems);
	echo json_encode($systems);
	die();
}

function ajax_admin_save_system() {
	$data = json_decode($_POST['data']);
	// print_r($data);
	$data->purpose = admin_system_property_byte_encode($data->purpose);
	$data->classes = admin_system_property_byte_encode($data->classes);
	$system = new AdminSystemDTO;
	foreach ($data as $key => $value) {
		$system->$key = $value;
	}
	$result = admin_save_system($system);
	echo $result;
	die();
}

function ajax_admin_delete_system() {
	$id = $_POST['id'];
	$db = get_db_connection();
	$result = $db->query("DELETE FROM systems WHERE id='$id'") or die(mysqli_error());
	$db->close();
	echo $result;
	die();
}

function ajax_get_article() {
	$return = array();
	$input = clean_post_string($_POST['input']);
	$db = get_db_connection();
	$result = $db->query("SELECT article,item_name FROM pricelist WHERE article LIKE '%$input%' OR item_name LIKE '%$input%' LIMIT 10") or die(mysqli_error($db));
	while ($row = $result->fetch_assoc()) {
		array_push($return, $row);
	}
	$db->close();
	echo json_encode($return);
	die();
}

function ajax_admin_get_users() {
	$filters = $_POST['filters'];
	$args = array();
	// if ($filters[0] == 'false') {

	// }
	echo json_encode(admin_get_users($args));
}

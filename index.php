<?php


require "Routing.php";

$path = trim($_SERVER["REQUEST_URI"], "/");
$path = parse_url($path, PHP_URL_PATH);

Router::get("home", "DefaultController");
Router::get("new", "DefaultController");
Router::get("d", "DefaultController");
Router::get("group", "DefaultController");

Router::post("login", "SecurityController");
Router::post("logout","SecurityController");
Router::get("passwordTest","SecurityController");

Router::get("createGroup","FormController");
Router::get("joinGroup", "FormController");
Router::get("switchToGroup","FormController");
Router::get("changeGroupName", "FormController");
Router::get("kickMember","FormController");
Router::get("leaveGroup","FormController");
Router::get("regenInvite", "FormController");
Router::get("invite","FormController");
Router::post("createTask", "FormController");
Router::post("createNote", "FormController");
Router::post("editTask", "FormController");
Router::post("editNote", "FormController");
Router::post("deleteTask", "FormController");
Router::post("deleteNote", "FormController");
Router::get("toggleTaskState", "APIController");

Router::run($path);

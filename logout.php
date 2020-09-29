<?php
session_start();
unset($_SESSION['token']);
unset($_SESSION['refresh']);
unset($_SESSION['user']);
unset($_SESSION['groupId']);
header('location: /');
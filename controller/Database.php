<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

const host = 'localhost';
const user = 'root';
const password = 'password';
const database = 'student_management';

class Database
{
	public static function connectDB()
	{
		return new mysqli(host, user, password, database);
	}
}

<?php
session_start();
define('DB_SERVER','localhost');
define('DB_USER','root');
define('DB_PASS' ,'');
define('DB_NAME', 'oopscrud');
class database
{
	private $conn;
	function __construct()
	{
		$this->conn = new mysqli(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
		// Check connection
		if ($this->conn->connect_error) {
			die("Connection failed: " . $this->conn->connect_error);
		}
	}
	
	//Data Insertion Function
	public function insert($table, $data) {
        $columns = implode(", ", array_keys($data));  // Columns names
        $placeholders = implode(", ", array_fill(0, count($data), "?"));  // ? for each value
        
        // Prepare the SQL query
        $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            die("Error preparing statement: " . $this->conn->error);
        }

        // Create the parameter types string based on the data types in the array
        $types = $this->getParamTypes($data);
        $values = array_values($data);

        $stmt->bind_param($types, ...$values);

        // Execute the query and check for success
        if ($stmt->execute()) {
			$last_id = $stmt->insert_id;
			return $last_id;
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }
	// Dynamic fetch function with optional WHERE, ORDER BY, and LIMIT
    public function fetch($table, $columns = '*', $where = '', $orderBy = '', $limit = '') 
	{
        // Start building the SQL query
        $query = "SELECT $columns FROM $table";

        // Add WHERE clause if provided
        if (!empty($where)) {
            $query .= " WHERE $where";
        }

        // Add ORDER BY clause if provided
        if (!empty($orderBy)) {
            $query .= " ORDER BY $orderBy";
        }

        // Add LIMIT clause if provided
        if (!empty($limit)) {
            $query .= " LIMIT $limit";
        }

        // Prepare and execute the query
        $result = $this->conn->query($query);

        // Check for query success and fetch the result
        if ($result) {
            return $result;  // Return all rows as an associative array
        } else {
            // Handle error
            die("Error fetching data: " . $this->conn->error);
        }
    }

	public function fetchOneRecord($userid,$table) {
        // Prepare the query to select one record
        $query = "SELECT * FROM $table WHERE id = ?";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            die("Error preparing statement: " . $this->conn->error);
        }

        // Bind the parameter (user ID)
        $stmt->bind_param("i", $userid); // "i" stands for integer

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the record as an associative array
        $row = $result->fetch_assoc();

        // Close the statement
        $stmt->close();

        // Return the fetched record
        return $row;
    }

// Dynamic update function with prepared statements
public function update($table, $data = [], $where = '', $whereParams = [])
{
	// Start building the SQL query
	$query = "UPDATE $table SET ";
	$updateColumns = [];

	// Dynamically create the SET clause with placeholders (?)
	foreach ($data as $column => $value) {
		$updateColumns[] = "$column = ?";
	}

	// Join the update columns with commas
	$query .= implode(', ', $updateColumns);

	// Add WHERE clause if provided
	if (!empty($where)) {
		$query .= " WHERE $where";
	}

	// Prepare the SQL query
	$stmt = $this->conn->prepare($query);

	if (!$stmt) {
		die("Error preparing statement: " . $this->conn->error);
	}

	// Prepare the types for the bind_param method
	$updateValues = array_values($data); // Values to be updated
	$allParams = array_merge($updateValues, $whereParams); // Merge update values and where parameters
	$types = $this->getParamTypes($allParams); // Determine the types for bind_param

	// Bind parameters dynamically
	if (!empty($allParams)) {
		$stmt->bind_param($types, ...$allParams);
	}

	// Execute the statement
	if ($stmt->execute()) {
		return true; // Update successful
	} else {
		return false; // Update failed
	}

}

 // Dynamic delete function with prepared statements
 public function delete($table, $where = '', $whereParams = []) {
	// Start building the SQL query
	$query = "DELETE FROM $table";

	// Add WHERE clause if provided
	if (!empty($where)) {
		$query .= " WHERE $where";
	} else {
		die("Warning: You should not delete without a WHERE clause!");
	}

	// Prepare the SQL query
	$stmt = $this->conn->prepare($query);

	if (!$stmt) {
		die("Error preparing statement: " . $this->conn->error);
	}

	// Bind parameters dynamically if there are any
	if (!empty($whereParams)) {
		$types = $this->getParamTypes($whereParams); // Determine the types for bind_param
		$stmt->bind_param($types, ...$whereParams);  // Bind the WHERE parameters
	}

	// Execute the statement
	if ($stmt->execute()) {
		return true;  // Delete successful
	} else {
		return false; // Delete failed
	}

}


// Function to determine the parameter types for bind_param
private function getParamTypes($params) {
	$types = '';
	foreach ($params as $param) {
		if (is_int($param)) {
			$types .= 'i';  // Integer
		} elseif (is_double($param)) {
			$types .= 'd';  // Double (float)
		} elseif (is_string($param)) {
			$types .= 's';  // String
		} else {
			$types .= 'b';  // Blob (binary)
		}
	}
	return $types;
}


// Destructor to close the database connection
	public function __destruct() {
		$this->conn->close();
	}
}
$db = new Database();

// $data = [
//     'name' => 'John Doe',
//     'price' => '999',
//     'description' => 'kkkk'
// ];

// // Insert data dynamically into the 'crud' table
// $db->insert('products', $data);

// $rows = $db->fetch('products');
// $rowsWithCondition = $db->fetch('products', 'id,name',"", 'price DESC', '');
// print_r($rows);
// print_r($rowsWithCondition);



// Data to be updated
// $data = [
//     'price' => 70000
// ];
// // Update specific row with a WHERE clause
// $whereClause = 'id = ?';  // Specify condition for updating
// $whereParams = [1];       // ID of the row to update
// // Call the dynamic update function
// $updateSuccess = $db->update('products', $data, $whereClause, $whereParams);


// $whereClause = 'id = ?';  // Specify condition for deletion
// $whereParams = [1];       // ID of the row to delete

// // Call the dynamic delete function
// $deleteSuccess = $db->delete('employees', $whereClause, $whereParams);

// // Check if the delete was successful
// if ($deleteSuccess) {
//     echo "Record deleted successfully!";
// } else {
//     echo "Failed to delete record.";
// }
?>
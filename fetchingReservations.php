<?php
// Assuming you have already connected to your MySQL database
$query = "SELECT * FROM reserveTbl";
$result = mysqli_query($sellphone, $query);

// Check if the query was successful
if ($result) {
  // Fetch rows from the result set
  while ($row = mysqli_fetch_assoc($result)) {
    // Output data for each row
    echo "<tr>";
    echo "<td>" . $row['reserved'] . "</td>";
    echo "<td>" . $row['phoneId'] . "</td>";
    echo "<td>" . $row['reserveDate'] . "</td>";
    echo "<td>" . $row['pickupDate'] . "</td>";
    echo "</tr>";
  }
  // Free result set
  mysqli_free_result($result);
} else {
  // Handle errors if the query fails
  echo "Error: " . mysqli_error($connection);
}

// Close database connection
mysqli_close($sellphone);
?>

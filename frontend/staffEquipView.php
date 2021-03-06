<?php
//Setup
if (isset($_POST["staffid"])) {
  $staffidcookie = $_POST['staffid'];
}else{
  $staffidcookie = $_COOKIE["staffid"];
}
$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = OCILogon("ora_u3i0b", "a14691142", "dbhost.ugrad.cs.ubc.ca:1522/ug");
?>

<!-- Page title -->
<title>Hotel Ski Resort</title>
<p> Welcome staff id:<?php echo $staffidcookie; ?> </p>

<div style="display: flex;width: 100%;justify-content: space-between;">

  <!-- View table entries -->
  <div style="justify-content: flex-start;">
    <h3> Equipment Reservations: </h3>
    <?php
        $result = executePlainSQL("select e.equip_id, r.equip_type, r.rental_rate, e.c_id, c.c_name, e.start_date, e.end_date from equipReservation e, customer c, rentalEquip r where e.c_id = c.c_id and e.equip_id = r.equip_id");
        echo "<table>";
        echo "<tr><th>Equipment Id</th><th>Equipment Type</th><th>Rental Rate</th><th>Customer Id</th><th>Customer Name</th><th>Rental Start Date</th><th>Rental End Date</th></tr>";
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
          echo "<tr><td>" . $row["EQUIP_ID"] . "</td><td>" . $row["EQUIP_TYPE"] . "</td><td>" . $row["RENTAL_RATE"] . "</td><td>" . $row["C_ID"] . "</td><td>" . $row["C_NAME"] . "</td><td>" . $row["START_DATE"] . "</td><td>" . $row["END_DATE"] . "</td></tr>";
        }
        echo "</table>";
      ?>


    <div style="display: flex;width: 100%;justify-content: space-between;">
      <div style="justify-content: flex-start;">
        <h3> All Equipments: </h3>
        <?php
            $result = executePlainSQL("select * from rentalEquip");
            echo "<table>";
            echo "<tr><th>Equipment Id</th><th>Type</th><th>Rental Rate</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
              echo "<tr><td>" . $row["EQUIP_ID"] . "</td><td>" . $row["EQUIP_TYPE"] . "</td><td>" . $row["RENTAL_RATE"] . "</td></tr>";
            }
            echo "</table>";
          ?>
      </div>

      <div style="justify-content: flex-start;">
        <h3> Customers: </h3>
        <?php
            $result = executePlainSQL("select * from customer");
            echo "<table>";
            echo "<tr><th>Customer Id</th><th>Customer Name</th><th>E-mail</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
              echo "<tr><td>" . $row["C_ID"] . "</td><td>" . $row["C_NAME"] . "</td><td>" . $row["E_MAIL"] . "</td></tr>";
            }
            echo "</table>";
          ?>
        </div>

    </div>
  </div>

    <!-- Directory -->
  <div style="justify-content: flex-end;">
    <!-- Edit Profile-->
    <div style="background-color:lightGrey; width: 200px;padding-top: 20px;padding-bottom: 1px">
      <center>
        <form method="POST" action="staffDir.php">
        <input type="hidden" name="staffid" value="<?php echo $staffidcookie; ?>">
          <input type="submit" value="Back to Main Page" name="staffDir">
        </form>
      </center>
    </div>
  </div>
</div>

<div style="height: 10px;"></div>

<!-- Forms to add & update data -->
<center>
  <div style="display: flex;width: 100%;justify-content: space-around;">
    <div> <!-- Rental Equipment -->
      <div style="width: 300px; padding: 20px 20px 10px 20px; background-color: lightGrey; ">
        <center>Add new or update rental equipment: </center>
        <form method="POST" action="staffEquipView.php">
            <p align="left">Equipment id: <br> <input type="number" name="editEqId" size="6"> </p>
            <p align="left">Equipment Type: <br> <input type="text" name="editEqType" size="20"> </p>
            <p align="left">Rental Rate: <br> <input type="number" name="editEqRate" size="6"> </p>
          <center>
            <input type="submit" value="Add/Update" name="editEquip">
          </center>
        </form>
      </div>

      <div style="height: 10px;"></div>

      <!-- Delete an equipment -->
      <div>
        <div style="width: 300px;  padding: 30px 20px 10px 20px; background-color: lightGrey; ">
          <form method="POST" action="staffEquipView.php">
            <center>Delete an equipment: <br>
              Are you sure you want to delete this equipment? This action cannot be undone. Deletion will cause cascading through other functionalities.<br>
            </center>
            <p align="left"> Equipment id: <br> <input type="number" name="delID"></p>
            <center><input type="submit" value="Delete Equipment" name="deleteEq"></center>
          </form>
        </div>
      </div>
    </div>

    <div> <!-- Equip Reservations -->
      <div style="width: 300px; padding: 20px 20px 10px 20px; background-color: lightGrey; ">
        <center>Add new equipment reservation: </center>
        <form method="POST" action="staffEquipView.php">
            <p align="left">Equipment Id: <br> <input type="number" name="equipID" size="6"> </p>
            <p align="left">Customer Id: <br> <input type="number" name="custID" size="6"> </p>

            <p align="left">Start Date: (numbers only - yyyymmdd) <br> <input type="text" name="equipStartDate" size="8"> </p>
            <p align="left">End Date: (numbers only - yyyymmdd) <br> <input type="text" name="equipEndDate" size="8"> </p>
          <center>
            <input type="submit" value="Add Equipment Reservation" name="addEquipReservation">
          </center>
        </form>
      </div>

      <div style="height: 10px;"></div>

      <!-- Delete a reservation -->
      <div>
        <div style="width: 300px;  padding: 30px 20px 10px 20px; background-color: lightGrey; ">
          <form method="POST" action="staffEquipView.php">
            <center>Delete an Equipment Reservation: <br>
             <br> Are you sure you want to delete this equipment reservation? This action can't be undone.
              Deletion will cause cascading through other functionalities.<br><br>
            </center>
            <p align="left">Equipment ID: <br> <input type="number" name="delEquipID" size="6"> </p>
            <p align="left">Start Date: (numbers only - yyyymmdd) <br> <input type="text" name="delEquipSDate" size="8"> </p>
            <p align="left">End Date: (numbers only - yyyymmdd) <br> <input type="text" name="delEquipEDate" size="8"> </p>
            <center><input type="submit" value="Delete Equipment Reservation" name="deleteEquipReservation"></center>
          </form>
        </div>
      </div>
    </div>

      <div style="height: 10px;"></div>

      <!-- Delete a reservation -->
      <div>
        <div style="width: 300px;  padding: 30px 20px 10px 20px; background-color: lightGrey; ">
          <form method="POST" action="staffEquipView.php"> <!-- TODO: Add any SQL processing necessary & add form tag details-->
            <center>Update Equipment Reservation: <br>
            </center>
            <p align="left">Equipment ID: <br> <input type="number" name="prevEquipID" size="6"> </p>
            <p align="left">Previous Start Date: (numbers only - yyyymmdd) <br> <input type="text" name="prevSDate" size="8"> </p>
            <p align="left">Previous End Date: (numbers only - yyyymmdd) <br> <input type="text" name="prevEDate" size="8"> </p><br><br>


            <p align="left">New Equipment ID: <br> <input type="number" name="upEquipID" size="6"> </p>
            <p align="left">New Start Date: (numbers only - yyyymmdd) <br> <input type="text" name="upEquipSDate" size="8"> </p>
            <p align="left">New End Date: (numbers only - yyyymmdd) <br> <input type="text" name="upEquipEDate" size="8"> </p>
            <center><input type="submit" value="Update Equipment Reservation" name="updateEquipReservation"></center>
            <!-- check if reservation exists, if so, delete. refresh page.-->
          </form>
        </div>
      </div>
  </div>
</center>

<!--  Setup connection and connect to DB -->
<?php

function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
  //echo "<br>running ".$cmdstr."<br>";
  global $db_conn, $success;
  $statement = OCIParse($db_conn, $cmdstr); //There is a set of comments at the end of the file that describe some of the OCI specific functions and how they work

  if (!$statement) {
    echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
    $e = OCI_Error($db_conn); // For OCIParse errors pass the
    // connection handle
    echo htmlentities($e['message']);
    $success = False;
  }

  $r = OCIExecute($statement, OCI_DEFAULT);
  if (!$r) {
    echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
    $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
    echo htmlentities($e['message']);
    $success = False;
  } else {

  }
  return $statement;

}

function executeBoundSQL($cmdstr, $list) {
  global $db_conn, $success;
  $statement = OCIParse($db_conn, $cmdstr);

  if (!$statement) {
    echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
    $e = OCI_Error($db_conn);
    echo htmlentities($e['message']);
    $success = False;
  }

  foreach ($list as $tuple) {
    foreach ($tuple as $bind => $val) {
      //echo $val;
      //echo "<br>".$bind."<br>";
      OCIBindByName($statement, $bind, $val);
      unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype

    }
    $r = OCIExecute($statement, OCI_DEFAULT);
    if (!$r) {
      echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
      $e = OCI_Error($statement); // For OCIExecute errors pass the statement handle
      echo htmlentities($e['message']);
      echo "<br>";
      $success = False;
    }
  }
  return $statement;

}

if ($db_conn) {
  //edit or add equipement
  if (array_key_exists('editEquip', $_POST)) {
    $tuple = array (
      ":bind1" => $_POST['editEqId'],
      ":bind2" => $_POST['editEqType'],
      ":bind3" => $_POST['editEqRate']
      );
    $alltuples = array ($tuple);
    $result = executeBoundSQL("select * from rentalEquip where equip_id=:bind1", $alltuples);

    //check if id exists in rental equipment
    if($row = OCI_Fetch_Array($result, OCI_BOTH)){
        //update
      executeBoundSQL("update rentalEquip set equip_type=:bind2, rental_rate=:bind3 where equip_id=:bind1", $alltuples);

    } else {
        //insert new equipment
        //check if equipement exists
        $result2 = executeBoundSQL("select * from rentalEquip where equip_id=:bind1", $alltuples);
        if($row = OCI_Fetch_Array($result2, OCI_BOTH) ){
           //do nothing
        } else {
          //insert into rental equip rate
          executeBoundSQL("insert into rentalEquipRate values (:bind2, :bind3)", $alltuples);
        }
        //insert equip into rental equip
        executeBoundSQL("insert into rentalEquip values (:bind1, :bind2, :bind3)", $alltuples);
    }

    OCICommit($db_conn);
    if ($_POST && $success){
      setcookie("staffid", $staffidcookie);
      echo "<meta http-equiv='refresh' content='0'>";
    }

  }
  //delete equipment
  else if (array_key_exists('deleteEq', $_POST)){
    $tuple = array (
      ":bind1" => $_POST['delID']
      );
    $alltuples = array ($tuple);
    $result = executeBoundSQL("select * from rentalEquip where equip_id=:bind1", $alltuples);

    //check if id exists in rental equipment
    if($row = OCI_Fetch_Array($result, OCI_BOTH)){
        //delete it
      executeBoundSQL("delete from rentalEquip where equip_id=:bind1", $alltuples);
    }

    OCICommit($db_conn);
    if ($_POST && $success){
      setcookie("staffid", $staffidcookie);
      echo "<meta http-equiv='refresh' content='0'>";
    }

  }
  //edit equip reservation
  else if(array_key_exists('addEquipReservation', $_POST)){
    $tuple = array (
      ":bind1" => $_POST['equipID'],
      ":bind2" => $_POST['custID'],
      ":bind3" => $_POST['equipStartDate'],
      ":bind4" => $_POST['equipEndDate']
    );

    $alltuples = array (
      $tuple
    );

    $result = executeBoundSQL("insert into equipReservation values (:bind1, :bind2, :bind3, :bind4)", $alltuples);

    OCICommit($db_conn);

    if ($_POST && $success) {
      setcookie("staffid", $staffidcookie);
      echo "<meta http-equiv='refresh' content='0'>";
    }

  }
  //or update reservatiom
  else if(array_key_exists('deleteEquipReservation', $_POST)) {
    $tuple = array (
      ":bind1" => $_POST['delEquipID'],
      ":bind2" => $_POST['delEquipSDate'],
      ":bind3" => $_POST['delEquipEDate']
    );

    $alltuples = array (
      $tuple
    );

    $result = executeBoundSQL("select * from equipReservation where equip_id=:bind1 and start_date=:bind2 and end_date=:bind3", $alltuples);

    //if equipment reservation exists
    if($row = OCI_Fetch_Array($result, OCI_BOTH)) {
      //delete
      executeBoundSQL("delete from equipReservation where equip_id=:bind1 and start_date=:bind2 and end_date=:bind3", $alltuples);
    }
    OCICommit($db_conn);

    if ($_POST && $success) {
      setcookie("staffid", $staffidcookie);
      echo "<meta http-equiv='refresh' content='0'>";
    }
  } else if(array_key_exists('updateEquipReservation', $_POST)) {
   $tuple = array (
      ":bind1" => $_POST['prevEquipID'],
      ":bind2" => $_POST['prevSDate'],
      ":bind3" => $_POST['prevEDate'],
      ":bind4" => $_POST['upEquipID'],
      ":bind5" => $_POST['upEquipSDate'],
      ":bind6" => $_POST['upEquipEDate'],
    );

    $alltuples = array (
      $tuple
    );

    $result = executeBoundSQL("select * from equipReservation where equip_id=:bind1 and start_date=:bind2 and end_date=:bind3", $alltuples);

    if($row = OCI_Fetch_Array($result, OCI_BOTH)) {
      executeBoundSQL("update equipReservation set equip_id=:bind4, start_date=:bind5, end_date=:bind6 where equip_id=:bind1 and start_date=:bind2 and end_date=:bind3", $alltuples);
    }
    OCICommit($db_conn);

    if ($_POST && $success) {
      setcookie("staffid", $staffidcookie);
      echo "<meta http-equiv='refresh' content='0'>";
    }
  }

  OCILogoff($db_conn);

  } else {echo "cannot connect";
  $e = OCI_Error(); // For OCILogon errors pass no handle
  echo htmlentities($e['message']);
}
?>

<?php

/**
 * 
 * Controller class for admin panel
 * 
 */
class AdminController extends Controller {

    /**
     * IndexGET
     *
     * Index view of admin dashboard
     * 
     * @return void
     */
    function IndexGET() {

        if (!$this->CheckAuth()['dashboard']) // Check permission
            throw new AuthException();

        $Data = [
            'Title' => 'پنل مدیر',
        ];

        $this->Render('index', $Data);

    }

    /**
     * TrustChainGET
     *
     * Trust Chain Manager
     * 
     * @return void
     */
    function TrustChainGET() {

        $TrustChainModel= $this->CallModel("TrustChain");
        $TrustChain = $TrustChainModel->GetItems();

        $LagHash = 'IHN';
        $Verified = true;
        for ($i = 0 ; $i < count($TrustChain) ; $i ++)
        {
            if (!$Verified)
            {
                $TrustChain[$i]['Recalculated'] =    
                $TrustChain[$i]['Verified'] =    
                $TrustChain[$i]['Hash'] = 'err';

                continue;
            }

            $TrustChain[$i]['Recalculated'] =
                md5($LagHash.'#'.$TrustChain[$i]['Value']);

            $LagHash = $TrustChain[$i]['Hash'];
            $Verified = $TrustChain[$i]['Recalculated'] == $TrustChain[$i]['Hash'];

            $TrustChain[$i]['Verified'] = $Verified ? 'true' : 'false';
        }

        // reverse the array
        $TrustChain = array_reverse($TrustChain);

        $Data = [
            'Add' => false,
            'Title' => 'زنجیره‌ی اعتماد',
            'Model' => $TrustChain
        ];

        $this->Render('trustchain', $Data);
    }
    
    /**
     * TrustChainPOST
     *
     * Inserts new items in the block
     * 
     * @return void
     */
    function TrustChainPOST() {

        if (!$this->CheckAuth()['trustchainadd']) // Check permission
            throw new AuthException();

        $TrustChainModel = $this->CallModel("TrustChain");

        $LagHash = count($TrustChainModel->GetLastHash()) > 0 ? $TrustChainModel->GetLastHash()[0]['Hash'] : 'IHN'; // InHisName

        $Value = $_POST['Value'];
        $Value = StringFunctions::remove_all_non_alpha_numeric($Value);
        $Value = date("Y-m-d H:i:s") . '> ' . $Value;

        $Values = [
            'Value' => $Value,
            'Hash' => md5($LagHash.'#'.$Value)
        ];

        $TrustChain = $TrustChainModel->InsertItem($Values);
        
        $Data = [
            'Add' => true,
            'Title' => 'بلوک جدید اضافه شد',
            'Model' => $TrustChain[1]
        ];

        $this->Render('trustchain', $Data);
    }


    /**
     * StatisticsGET
     *
     * OpenData Statistics
     * 
     * @return void
     */
    function StatisticsGET($CLIENT_TRACK = null) {

        $Data = [
            'Title' => 'آمار داده باز',
        ];

        // Call the model
        $Model = $this->CallModel("Visit");

        // If specific user track was requested
        if (isset($CLIENT_TRACK))
        {
            if (!$this->CheckAuth()['statistics']) // Check permission
                throw new AuthException();

            // Get user story by cookie
            $Rows = $Model->UserStory([
                'CLIENT_TRACK' => $CLIENT_TRACK
            ]);
            $Data['UserStory'] = $Rows;

            $this->Render('UserStory', $Data);
            return;
        }

        // Today top users by cookies
        $Rows = $Model->TodayTopUsers();
        $Data['TodayTopUsers'] = $Rows;

        // Get top users by cookies
        $Rows = $Model->WeekTopUsers();
        $Data['WeekTopUsers'] = $Rows;

        // Get daily grouped count
        $Rows = $Model->DailyGroupedVisitCount();
        $Data['DailyGroupedVisitCountRows'] = $Rows;

        // Get grouped count
        $Rows = $Model->GroupedVisitCount();
        $Data['GroupedVisitCountRows'] = $Rows;

        // Get grouped visit count by agent
        $Rows = $Model->GroupedVisitCountByAgent();
        $Data['GroupedVisitCountByAgent'] = $Rows;

        // Get grouped visit count by agent
        $Rows = $Model->PostsVisitCountByAddress();
        $Data['PostsVisitCountByAddress'] = $Rows;

        $this->Render('Statistics', $Data);

    }


    /**
     * 
     * FilesGET
     * 
     * Embded Filemanager
     * 
     * 
     */
    function FilesGET() {
        if (!$this->CheckAuth()['file_manager']) // Check permission
            throw new AuthException();
        
        define('FM_EMBED', true);
        define('FM_SELF_URL', _Root . 'Admin/Files/'); // must be set if URL to manager not equal PHP_SELF
        require 'Libs/filemanager-master/filemanager.php';
    }
    /**
     * 
     * FilesPost
     * 
     * Embded Filemanager
     * 
     * 
     */
    function FilesPOST() {
        if (!$this->CheckAuth()['file_manager']) // Check permission
            throw new AuthException();

        define('FM_EMBED', true);
        define('FM_SELF_URL', _Root . 'Admin/Files/'); // must be set if URL to manager not equal PHP_SELF
        require 'Libs/filemanager-master/filemanager.php';
    }


    /**
     * ItemsGET
     *
     * List the posts
     * 
     * @return void
     */
    function ItemsGET($table = "Post", $Id = null) {

        if (!$this->CheckAuth()['items_'.strtolower($table)]) // Check permission
            throw new AuthException();

        // ========== Ask database for data
        $Model = $this->CallModel($table);
        $Rows = $Model->GetAdminPanelItems();
        
        // ========== Generate HTML table from SQL rows
        $HtmlTable = '';
        $field = null;
        if (count($Rows) > 0) 
        {
            $HtmlTable .= "<table class=\"table table-striped table-bordered table-sm\" id='gordarg-table'>";
            $HtmlTable .= "<thead> <tr>";
            $field = array_keys($Rows[0]);
            $fields = array();
            $j = 0;
            $HtmlTable .= "<th scope=\"col\">#</th>";
            foreach ($field as $col)
            {   
                $HtmlTable .= "<th scope=\"col\">".$col."</th>";
                array_push($fields, array(++$j, $col));
            }
            $HtmlTable .= "</tr></thead>";

            $HtmlTable .= "<tbody>";
            $j=0;
            foreach ($Rows as $row)
            {
                ++$j;
                $HtmlTable .= "<tr>";
                $HtmlTable .= "<th>" . $j . "</th>";
                for ($i=0 ; $i < sizeof($fields) ; $i++)
                {
                    $fieldname = $fields[$i][1];
                    $filedvalue = $row[$fieldname];

                    $HtmlTable .= "<td>" . $filedvalue . "</td>";
                }
                $HtmlTable .= "</tr>";
            }
            $HtmlTable .= "</tbody>";
            $HtmlTable .= "</table>";
        }
        else
        {
            $HtmlTable .= '
            <blockquote class="note note-info">
                <strong class="bq-title">Info!</strong> Nothing to show.
            </blockquote>
            ';
        }

        // ========== Generate HTML form from SQL table

        $form ='<div class="modal' . ((isset($Id) || isset($_GET['new'] )) ? ' show' : '') . '" id="basicExampleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-body">';
        
        $form .= "<form action=\"" . _Root . 'Admin/Items/' . $table . (isset($Id) ? "/" . $Id : '') . "\"
        class=\"gordarg-form\"
        method=\"post\">";
        
        // Is it insert or update form
        $mode = (isset($Id) ? 'update' : 'insert');

        // Get table structure
        $result = $Model->DescribeTable();

        if (count($result) > 0) {
            // output data of each row
            foreach ($result as $row) {

                if (!isset($Id)) {
                    $columns = array();
                    foreach ($result as $item)
                        array_push($columns, $item['Field']);
                    // Create an empty form
                    $values = array_fill_keys($columns, '');
                } else {
                    $values = $Model->GetItemByIdentifier(['Id'=>$Id])[0];
                }

                if ($row["Field"] != "Id") {

                    $form .= "<div class=\"form-group\">";

                    $type = $row['Type'];
                    if (strpos($type, "(") > 0)
                        $type = substr($type, 0, strpos($type, "("));

                    switch ($type){
                        case "longtext":
                            $form .= "<label for='" . $row["Field"] . "'>" . $row["Field"] . "</label>";
                            $form .= "<textarea class=\"html-editor\" type='text' name='" . $row["Field"] . "' >" . $values[$row["Field"]] . "</textarea>";
                            break;
                        case "text":
                        case "varchar":
                            $form .= "<label for='" . $row["Field"] . "'>" . $row["Field"] . "</label>";
                            $form .= "<input class=\"form-control\" type='text' name='" . $row["Field"] . "' value='" . $values[$row["Field"]] . "' >";
                            break;
                        case "int":
                            $form .= "<label for='" . $row["Field"] . "'>" . $row["Field"] . "</label>";
                            $form .= "<input class=\"form-control\" type='number' name='" . $row["Field"] . "' value='" . $values[$row["Field"]] . "' >";
                            break; 
                        case "bigint":
                            $form .= "<label for='" . $row["Field"] . "'>" . $row["Field"] . "</label>";
                            $form .= "<input class=\"form-control\" type='number' name='" . $row["Field"] . "' value='" . $values[$row["Field"]] . "' >";
                            break; 
                        case "tinyint":
                            $form .= "<label for='" . $row["Field"] . "'>" . $row["Field"] . "</label>";
                            $form .= "<input class=\"form-control\" type='number' name='" . $row["Field"] . "' value='" . $values[$row["Field"]] . "' >";
                            break; 
                        case "decimal":
                            $form .= "<label for='" . $row["Field"] . "'>" . $row["Field"] . "</label>";
                            $form .= "<input class=\"form-control\" type='number'  step='0.01' name='" . $row["Field"] . "' value='" . $values[$row["Field"]] . "' >";
                            break;
                        case "datetime":                          
                            $form .= "<label for='" . $row["Field"] . "'>" . $row["Field"] . "</label>";
                            $form .= "<input class=\"form-control\" type='datetime-local' name='" . $row["Field"] . "' value='" . date('Y-m-d\TH:i') . "' >";
                            break;
                        case "bit":
                            // echo "here " . $row["Field"] . ": " . $values[$row["Field"]]; exit; // Debug
                            $form .= '
                                <label class="form-check-label" for="' . $row["Field"] . '">
                                ' . $row["Field"] . '
                                </label>
                                <input class="form-check-input" type="checkbox" name="' . $row["Field"] . '" ' . ( $values[$row["Field"]] == '1' ? 'checked' : '' ) . '>
                            ';
                            break;
                        default :
                            $form .= "<label for='" . $row["Field"] . "'>" . $row["Field"] . "</label>";
                            $form .= "<input class=\"form-control\" type='text' name='" . $row["Field"] . "' value='" . $values[$row["Field"]] . "' >";
                    }

                    $form .= "</div>";
        
                }
            }
            if ($mode == 'insert')
            {
                $form .= "<input name='insert' type='submit' value='Insert' class='btn btn-primary btn-sm m-4'>";
                $form .= "</form>";
            }
            else if ($mode == 'update')
            {
                $form .= "<input name='update' type='submit' value='Update' class='btn btn-warning btn-sm m-4'>";
                $form .= "<input name='delete' type='submit' value='Delete' class='btn btn-danger btn-sm m-4'>";
                $form .= "<a href=\"../$table\" class='btn btn-default pink darken-1 btn-sm m-4'>Cancel</a>";
                $form .= "</form>";                
                $form .= "</div></div></div></div>";                
            }

        }


        // ========== Package the response and return view
        $Data = [
            'Title' => 'مدیریت آیتم‌ها',
            'Table' => $HtmlTable,
            'Form' => $form
        ];
        
        $this->Render('crud', $Data);   

        
    }

    /**
     * ItemsPOST
     * 
     * When a button on items form is clicked
     * 
     * 
     * @return void
     * 
     */
    function ItemsPOST($table = "Post", $Id = null) {
        if (!$this->CheckAuth()['items_'.strtolower($table)]) // Check permission
            throw new AuthException();

        // ========== Ask database for data
        $Model = $this->CallModel($table, false);
        $conn = $Model->GetConnection();

        // Plural table names
        switch ($table)
        {
            case "Post2":
            case "Post3":
            case "Post4":
                $table_id = "Id";
                $table_plural = "Posts";
                break;
            default:
                $table_id = "Id";
                $table_plural = $table . "s";
        }

        // If data was posted to update the record
        if (isset($_POST['update']))
        {
            // declare variables
            $update_query_key_values = '';

            // Get table structure
            // In this case: Potodosts table
            $result = $conn->query("DESCRIBE " . $table_plural);

            // output data of each row
            while ($row = $result->fetch_assoc()) {
                if ($row["Field"] != $table_id) { // because of auto increment
                    // dont insert comma for the last record
                    if ($update_query_key_values != '')
                        $update_query_key_values .= ', ';
                    
                    // apend keys to keys array
                    $update_query_key_values .= '`' . $row["Field"] . '` = ';

                    // Get field type
                    // if it was a stirng, add quote in first and last of value
                    $type = $row['Type'];
                    if (strpos($type, "(") > 0)
                        $type = substr($type, 0, strpos($type, "("));
                    $symbol = '';
                    if ($type == "varchar"
                    or $type == "datetime"
                    or $type == "text"
                    or $type == "longtext"
                    or $type == "char" ) {
                        $symbol = '\'';
                    }

                    // If it was a bit value
                    if ($type == "bit" and isset( $_POST[ $row["Field"] ] )
                        and $_POST[ $row["Field"] ] == "on")
                        $_POST[ $row["Field"] ] = "1" ;

                    
                    // If value is posted and its not null
                    if (! isset( $_POST[ $row["Field"] ] )
                        and ( $type == "bit" )
                    ) {
                        $update_query_key_values .= "0" ;
                    }
                    else if (! isset( $_POST[ $row["Field"] ] ) ) {
                        $update_query_key_values .= "NULL" ;
                    }
                    else if ($_POST[ $row["Field"] ] == ''
                        and ( $type == "int" or $type == "decimal" )
                    ) {
                        $update_query_key_values .= "NULL" ;
                    }
                    else {
                        // append value to values array
                        $update_query_key_values .= $symbol . $_POST[ $row["Field"] ]  . $symbol;
                    }

                }
            }

            // assemble final query
            $update_query  = 'UPDATE ' . $table_plural . ' SET ' . $update_query_key_values . ' WHERE ' . $table_id . ' = '. $Id ;
            
            // run the query !
            if (_Debug)
            mysqli_query($conn, $update_query)
            or trigger_error("Query Failed! $update_query - Error: ".mysqli_error($conn), E_USER_ERROR);
            else 
            mysqli_query($conn, $update_query);
        }
        else if (isset($_POST['insert']))
        {
            // declare variables
            $insert_query_keys = $insert_query_values = '';

            // Get table structure
            $result = $conn->query("DESCRIBE " . $table_plural);

            // output data of each row
            while ($row = $result->fetch_assoc()) {
                if ($row["Field"] != $table_id) { // because of auto increment
                    // dont insert comma for the last record
                    if ($insert_query_keys != '')
                        $insert_query_keys .= ', ';
                    
                    // apend keys to keys array
                    $insert_query_keys .= '`' . $row["Field"] . '`';

                    // Get field type
                    // if it was a stirng, add quote in first and last of value
                    
                    $type = $row['Type'];
                    if (strpos($type, "(") > 0)
                        $type = substr($type, 0, strpos($type, "("));
                    $symbol = '';
                    if ($type == "varchar"
                    or $type == "datetime"
                    or $type == "text"
                    or $type == "longtext"
                    or $type == "char" ) {
                        $symbol = '\'';
                    }

                    // If it was a bit value
                    if ($type == "bit" and isset( $_POST[ $row["Field"] ] )
                        and $_POST[ $row["Field"] ] == "on")
                        $_POST[ $row["Field"] ] = "1" ;
                    
                    // dont insert comma for the last record
                    if ($insert_query_values != '')
                        $insert_query_values .= ', ';
                    // If value is posted and its not null
                    if (! isset( $_POST[ $row["Field"] ] )
                        and ( $type == "bit" )
                    ) {
                        $insert_query_values .= "0" ;
                    }
                    else if (! isset( $_POST[ $row["Field"] ] ) ) {
                        $insert_query_values .= "NULL" ;
                    }
                    else if ($_POST[ $row["Field"] ] == ''
                        and ( $type == "int" or $type == "decimal" )
                    ) {
                        $insert_query_values .= "NULL" ;
                    }
                    else {
                        // append value to values array
                        $insert_query_values .= $symbol . $_POST[ $row["Field"] ]  . $symbol;
                    }
                }
            }

            // assemble final query
            $insert_query  = "INSERT INTO " . $table_plural . " (" 
            . $insert_query_keys
            . ") VALUES ("
            . $insert_query_values
            . ")" ;
            
            // run the query !
            if (_Debug)
            mysqli_query($conn, $insert_query)
            or trigger_error("Query Failed! $insert_query - Error: ".mysqli_error($conn), E_USER_ERROR);
            else 
            mysqli_query($conn, $insert_query);

        }
        else if (isset($_POST['delete']))
        {
            // assemble final query
            $delete_query  = 'DELETE FROM ' . $table_plural . ' WHERE ' . $table_id . ' = '. $Id ;

            // run the query !
            if (_Debug)
            mysqli_query($conn, $delete_query)
            or trigger_error("Query Failed! $delete_query - Error: ".mysqli_error($conn), E_USER_ERROR);
            else 
            mysqli_query($conn, $delete_query);
        }

        $this->RedirectResponse(_Root . "Admin/Items/" . $table);

    }

    /**
     * ServerGET
     *
     * Show the server details
     * 
     * @return void
     */
    function ServerGET() {

        if (!$this->CheckAuth()['server_resources']) // Check permission
            throw new AuthException();

        // Ask database for data

        // Package the response
        $Data = [
            'Title' => 'منابع سرور',
            // 'Model' => $Rows
        ];
        
        // Render the view
        $this->Render('server', $Data);   

        
    }


}
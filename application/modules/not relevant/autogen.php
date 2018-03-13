<?php
function autogen(){
    $mysql_query = "SHOW columns FROM store_book_detail";
    $query = $this->_custom_query($mysql_query);
    foreach ($query->result() as $row) {
        $title = $row->Field;
        if($title != 'id'){
            echo "$"."data['$title'] = "."$"."this->input->post('$title'); </br> ";
        }
    }
    echo "<hr>";
    foreach ($query->result() as $row) {
        $title = $row->Field;
        if($title != 'id'){
            echo "$"."data['$title'] = "."$"."row->$title; </br> ";
        }
    }
    echo "<hr>";
    foreach ($query->result() as $row) {
        $title = $row->Field;
        if($title != 'id'){
            $html = "
            <div class='control-group'>
            <label class='control-label' for='$title'>$title</label> 
            <div class='controls'> 
                <input type='text' class='span6' id='$title' name='$title' value='<?php echo $"."$title?>'> 
                </div>
            </div>
            
            ";

            echo htmlentities($html);
            echo "</br>";
        }
    }
}

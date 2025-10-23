  <?php
        function input_text($elementName, $strLabel, $elementType, $elementValue, $strGuide, $isReadOnly = false) {
        $readonly = $isReadOnly ? "readonly" : "";

        echo "<div class=\"mb-3 mt-3\">
                <label for=\"$elementName\" class=\"form-label\">$strLabel:</label>
                <input type=\"$elementType\" class=\"form-control\" id=\"$elementName\"   
                  placeholder=\"$strGuide\" name=\"$elementName\" value=\"$elementValue\" $readonly>
            </div>";
    }

    function dropdown_db($pdo,$elementName,$tbName,$fieldid,$fieldname){
        
      //echo "dropdown_db";
          $sql = "SELECT $fieldid as id , $fieldname as name  FROM $tbName";
          $stmt = $pdo->prepare($sql);
          $stmt->execute([]);
          $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  


        echo "<select class=\"form-select\" name=\"$elementName\">" ;
        foreach($rows as $row){
              $id = $row['id'];
              $name = $row['name'];
              echo "<option value= $id > $name</option>";
          
                }
        echo  "</select>";
      
    }

       function input_dropdown($pdo,$elementName,$strLabel,$tbName,$fieldID,$fieldName,$elementValue){
        $sql  = "SELECT $fieldID as id , $fieldName as name FROM $tbName ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<div class=\"mb-3 mt-3\">
            <label for=\"$elementName\" class=\"form-label\">$strLabel:</label>";
        echo "<select class=\"form-select\" name=\"$elementName\" id=\"$elementName\">" ;
        echo "<option value=\"\" >-- เลือก $strLabel--</option>";
        foreach($rows as $row){
            $id     = $row['id'];
            $name   = $row['name'];
            $opt    = ($elementValue == $id) ? " selected " : "" ;
            echo "<option value=$id $opt > $name </option>";
        }
        echo"</select>
        </div>" ;
      }


  



  ?>
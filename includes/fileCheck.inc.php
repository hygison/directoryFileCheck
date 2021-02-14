<?php


    include 'autoload.inc.php';

    

    if(isset($_POST['action'])){
        
        $files = new DirectoryFiles;

        if($_POST['action'] === 'scanDir'){

            $filePathArr = ($files->getDirPathArr($_SERVER['DOCUMENT_ROOT']));
            echo '<br><br>';
            echo '<p>All Files in the directory:</p>';
            foreach($filePathArr as $value){
                echo '<br>';
                echo $value;
            }
            echo '<hr>';

            echo '<p>New Files on the directory:</p>';
            $files->dirCheck($filePathArr);

            echo $files->getWarningMessage();

        }

        if($_POST['action'] === 'dbFiles'){

            $rows = $files->getRows();
            echo '<table class="table">
                    <tr class="text-center">
                        <th>Path</th>
                        <th>Date</th>
                        <th>Safe</th>
                        <th>View</th>
                    </tr>
            ';

            foreach($rows as $key => $dbRow){
                echo '<tr>';
                echo '<td>'.$dbRow['filePath'].'</td>';
                echo '<td class="text-center">'.$dbRow['date'].'</td>';
                if($dbRow['isSafe']){
                    echo '<td class="text-center">Yes</td>';
                }else{
                    echo '<td class="text-center bg-danger">No</td>';
                }
                
                echo '<td class="d-flex justify-content-center" >';
                echo "<i class='fa fa-eye viewDangerWords' data-json='".$dbRow['dangerWordsJSON']."' data-id='".$dbRow['id']."'></i>";
                echo '</td>';

                echo '</tr>';
            }

            echo '</table>';
        }


       
        


    }
?>
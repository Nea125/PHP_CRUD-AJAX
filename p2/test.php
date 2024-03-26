<?php

    $cn = new mysqli("localhost", "root", "", "learn_php");
    $cn->set_charset("utf8");// support font khmer
    $id = 1;
    $sql = "SELECT id FROM tbl_test1 ORDER BY id DESC LIMIT 0,1";
    $rs = $cn->query($sql);
    if($rs->num_rows>0)
    {
        $row = $rs->fetch_array();
        $id = $row[0]+1;
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project2</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script> -->
   
    <script src="jquery-3-7.min.js"></script>
   
</head>
<body>
   <center><h1>POST</h1></center>
   <form class="upl">
        <div class="frm">
            <input type="text" name="txt-edit-id" id="txt-edit-id" value="0" class="form-control txt-edit-id">
            <label for="">ID</label>
            <input type="text" name="txt-id" id="txt-id" value="<?php  echo $id ?>" class="form-control">
            <label for="">Name</label>
            <input type="text" name="txt-name" id="txt-name" class="form-control">
            <label for="">Price</label>
            <input type="text" name="txt-price" id="txt-price" class="form-control">
            <div class="img-box">   
              
                <input type="file" name="txt-file" id="txt-file" class="txt-file" >
                
               
            </div>
            <input type="text" name="txt-img" id="txt-img" class="txt-img">
            <div class="btnSave">
                Save
            </div>
           
           
        
        </div>
   </form>
   
   <h1></h1>
   <table class="table" id="tblData">
   
        <tr>
          
            <th >ID</th>
            <th >NAME</th>
            <th >PRICE</th>
            <th>PostDate</th>
            <th>Photo</th>
            <th>Action</th>
            
        </tr>
   
    
        <?php

          $sql = "SELECT *FROM tbl_test1 ORDER BY id DESC";
          $rs= $cn->query($sql);
          if($rs->num_rows>0)
          {
                while($row = $rs->fetch_array()){
                    ?>
                    <tr>
                        <td><?php  echo  $row[0];  ?></td>
                        <td><?php  echo  $row[1]; ?></td>
                        <td><?php  echo  $row[2]; ?></td>
                        <td><?php  echo  $row[3]  ?> </td>
                        <td>
                            <img src="img/<?php  echo  $row[4]  ?>" alt="<?php  echo  $row[4]  ?>">
                        </td>
                        <td>
                                <i class="fa-solid fa-pen-to-square btnEdit"></i>
                                <i class="fa-solid fa-trash btnDelete"></i>
                        </td>
        
                    </tr>

                <?php
                }

          }
        ?>

      
      

</table>


    
</body>
<script>
    $(document).ready(function(){
        var tbl = $("#tblData");
        var btnEdit=' <i class="fa-solid fa-pen-to-square btnEdit"></i>';
        var loading =' <div class="img-loading"></div>';
        var btnDelete=' <i class="fa-solid fa-trash btnDelete"></i>';
        var ind = 0;
        // Upload Image 
        $(".txt-file").change(function(){
            var eThis = $(this);
           

            var imgBox = $(".img-box");

            var frm = eThis.closest("form.upl");
            var frm_data = new FormData(frm[0]);

            $.ajax({
                url: 'upl-img.php',
                type: 'POST',
                data: frm_data,
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                beforeSend: function() {
                    imgBox.append(loading);
                },
                success: function(data) {
                imgBox.css({"background-image":"url(img/"+data["imgName"]+")"});
                imgBox.find(".img-loading").remove();
                // imgBox.find("txt-img").val(data["imgName"]); 
                $(".txt-img").val(data["imgName"]);   // throw img name to txt-img
               
                    
            }   
       });

        });
        // save data
   
    $(".btnSave").click(function(){
        var eThis = $(this);
       

        var id = $("#txt-id");
        var name = $("#txt-name");
        var price = $("#txt-price");
        var imgName = $(".txt-img");
        var imgBox= $(".img-box");
       
        if(name.val()=="")
        {
            alert("Please Input Name ....");
            name.focus();
            return;
        }
        if(price.val()=="")
        {
            alert("Please Input Price....");
            price.focus();
            return;
        }
        var frm = eThis.closest("form.upl");
        var frm_data = new FormData(frm[0]);

        $.ajax({
            url: 'save.php',
            type: 'POST',
            data: frm_data,
            contentType: false,
            cache: false,
            processData: false,
            dataType: "json",
            beforeSend: function() {
                eThis.html("Waiting......");
            },
            success: function(data) {
                if(data["dpl"]==true)
                {
                    alert("Duplicate Name....");
                }
                else if(data["edit"]==true)
                {
                    tbl.find("tr:eq("+ind+") td:eq(1)").text(name.val());
                    tbl.find("tr:eq("+ind+") td:eq(2)").text(price.val());
                    tbl.find("tr:eq("+ind+") td:eq(4) img").attr("src","img/"+imgName.val()+"");
                    tbl.find("tr:eq("+ind+") td:eq(4) img").attr("alt",""+imgName.val()+"");
                    name.val("");
                    price.val("");
                    name.focus();

                }
                else{   

                    var tr = `
                    <tr>
                        <td>${id.val()}</td>
                        <td>${name.val()}</td>
                        <td>${price.val()}</td>
                        <td>${data["date"]}</td>
                        <td><img src="img/${imgName.val()}" alt="${imgName.val()}"></td>
                        <td>${btnEdit} ${btnDelete}</td>
                       
                       
                    </tr>

                    `;
                    tbl.find("tr:eq(0)").after(tr);// add tr affter tr 0
                    //   tbl.append(tr); Append ខាងក្រោម
                    
                    name.val("");
                    price.val("");
                    name.focus();
                 
                    id.val(data["id"]+1);

                }
                imgBox.css({"background-image":"url(img/acc.png)"});
             
                    eThis.html("Save");
              
            }   
        });
    });
    // get edit date
    tbl.on("click","tr td .btnEdit",function(){
            
           var Parent = $(this).parents("tr");
          
           var id = Parent.find("td:eq(0)").text();
           var name = Parent.find("td:eq(1)").text();
           var price = Parent.find("td:eq(2)").text();
           var img = Parent.find("td:eq(4) img").attr("alt");
           ind = Parent.index();
        
         
           $("#txt-id").val(id);
           $("#txt-name").val(name);
           $("#txt-price").val(price);
           $("#txt-img").val(img);
           $(".img-box").css({"background-image":"url(img/"+img+")"});
           $("#txt-edit-id").val(id);


    });


    $(".btnDelete").click(function(){
        
        
            var eThis = $(this);
            var Parent = $(this).closest("tr");
            var id = Parent.find("td:eq(0)").text();
            // var tbl = $("#your_table_id"); // Update with your actual table ID

            $.ajax({
                url: 'delete.php',
                type: 'POST',
                data: { id: id}, // Send the ID of the record to be deleted
                dataType: "json",
                beforeSend: function() {
                    eThis.html("<h6>Waiting......</h6>");
                },
                success: function(data) {
                    if (data.success) {
                        // alert(data.success);
                        Parent.remove(); // Remove the row from the table
                    } else {
                        alert(data.error);
                    }
                },
                error: function() {
                    alert("An error occurred while deleting the record.");
                }
            });
        });
  
});
</script>
</html>
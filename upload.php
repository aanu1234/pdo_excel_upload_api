<?php require __DIR__ . '/init.php'; ?>
<!DOCTYPE html>
<html lang="en" >
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upload External Files</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
  </head>
  <body style="background: #efffee;">
    <div class="container">
      <div class="row">
        <section>
          <p>&nbsp;</p>
        </section>
      </div>
      <div class="row">
        <?php
        if (isset($_POST['uploadnow'])) {

          $allowedFileType = [
              'application/vnd.ms-excel',
              'text/xls',
              'text/xlsx',
              'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
          ];

          if (in_array($_FILES["docfile"]["type"], $allowedFileType)) {
            $file  = isset($_FILES['docfile']) ? $_FILES['docfile']['tmp_name'] : '';
             //$targetPath = 'uploads/' . $_FILES['docfile']['name'];
             //move_uploaded_file($_FILES['docfile']['tmp_name'], $targetPath);
            // file open and read
            /** Load $inputFileName to a Spreadsheet Object  **/
            $spreadSheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);

            //$spreadSheet = $Reader->load($file);
            $excelSheet = $spreadSheet->getActiveSheet();
            $spreadSheetAry = $excelSheet->toArray();
            $sheetCount = count($spreadSheetAry);

            for ($i = 1; $i <= $sheetCount; $i ++) {

              $regno = "";
              if (isset($spreadSheetAry[$i][0])) {
                $regno = $spreadSheetAry[$i][0];
              }
              $savings = "";
              if (isset($spreadSheetAry[$i][1])) {
                $savings = $spreadSheetAry[$i][1];
              }
              $transactiondate = "";
              if (isset($spreadSheetAry[$i][2])) {
                  $transactiondate = $spreadSheetAry[$i][2];
              }
              if ( $regno !== '' ) {
                $sql = "INSERT INTO phpxlsupload (regno, savings, transaction_date) VALUES (?, ?, ?)";
                $stmt = $db->prepare($sql);
                $response = $stmt->execute([$regno,$savings,$transactiondate]);
              }
            }
            if($response){
              $success = "Your file has been uploaded successfully";
              echo "<script>
    					setTimeout(function() {
    							window.location = '';
    					}, 500);
    					</script>";
              //header("location: ");
            }else{
              $error = "Sorry! There is a problem uploading your file";
            }

          }else{
            $error = "Sorry! This file you're trying to upload is not a valid excel file";
          }
        }
        ?>
        <div class="col-lg-8 col-md-8 col-sm-12">
          <div class="card">
            <div class="card-header">
              Make your upload here
            </div>
            <div class="card-body">
              <?php if (!empty($success)): ?>
                <div class="alert alert-info">
                  <?=$success?>
                </div>
              <?php endif; ?>
              <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                  <?=$error?>
                </div>
              <?php endif; ?>
              <form class="form_upload" method="post" enctype="multipart/form-data">
                <div class="form-group row">
                  <!-- <label for="docfile" class="col-md-3">Choose File to Upload<span class="text-danger">* </span></label> -->
                  <div class="col-md-9">
                    <input class="form-control" type="file" name="docfile" >
                  </div>
                  <div class="col-md-3">
                    <button type="submit" class="btn btn-primary" name="uploadnow">Upload Now</button>
                  </div>
                </div>
                <!-- <div class="form-group mt-2">
                  <label for="description" class="col-md-3">Description (Optional)</label>
                  <div class="col-md-9">
                    <textarea class="form-control" name="description" ></textarea>
                  </div>
                </div> -->
              </form>
            </div>

          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

    <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script> -->
  </body>
</html>

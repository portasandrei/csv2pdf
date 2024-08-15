<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="icon" type="image/x-icon" href="images/favicon.ico">
        <meta charset="UTF-8">
        <title>Test PDF</title>
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h1 class="display-3">Test PDF create</h1>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="col-md-12 border p-3">
                        <div class="alert alert-danger d-none" role="alert">
                            To continue you still need to choose a file!
                        </div>

                        <form method="post" class="row g-3" id="csvForm">
                            <div class="col-md-12">
                                <h5 class="display-6">Please select your file</h5>
                            </div>

                            <div class="col-md-12">
                                <label for="csvFile" class="form-label">*only csv format</label>
                                <input class="form-control form-control-lg" id="csvFile" name="csvFile" type="file">
                            </div>
                            <div class="col-md-12">
                                <input type="button" class="btn btn-primary" id="create_pdf" value="Create PDF">
                            </div>
                        </form>
                    </div>
                    <div class="col-md-12 border p-3 d-none" id="special_block">
                        <div class="col-md-6">
                            <a href="#" class="btn btn-info" id="preview_pdf" target="_blank">Preview PDF</a>

                            <a href="#" class="btn btn-secondary" id="download_pdf" download="">Download PDF</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

        <script>
            $(document).ready(function(){
                const pathName = window.location.pathname;

                $("#csvFile").click(function() {
                    $(".alert-danger").addClass('d-none');
                });

                $("#create_pdf").click(function() {
                    if ($("#csvFile").get(0).files.length !== 0) {
                        let formFile = new FormData($("#csvForm")[0]);
                        $.ajax({
                            url: "CreatePDF.php",
                            method: "POST",
                            data: formFile,
                            processData: false,
                            contentType: false,
                            success: function(response){
                                let fileName = JSON.parse(response).fileName;
                                $("#csvFile").val("");
                                $("#special_block").removeClass("d-none");
                                $(".alert-danger").addClass('d-none');
                                $("#preview_pdf").attr("href", pathName+"generated_pdf/"+fileName);
                                $("#download_pdf").attr("href", pathName+"generated_pdf/"+fileName);
                                $("#download_pdf").attr("download", fileName);
                            }
                        });
                    } else {
                        $(".alert-danger").removeClass('d-none');
                    }

                });
            });
        </script>
    </body>
</html>
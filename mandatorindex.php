<?php
$error = $listId = '';

if (isset($_GET['listid'])) {
    $listId = $_GET['listid'];
}

if (isset($_GET['error'])) {
    $error = urldecode($_GET['error']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Random List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="public/css/bootstrap.min.css" />
    <link rel="stylesheet" href="public/css/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="public/css/sort.css" />
</head>
<body>

    <?php if ('' == $listId): ?>

    <div class="container">
        <div style="clear: both; height: 20px;"></div>
        <div class="row">
            <h1>Upload the JSON file you want to sort randomly</h1>
        </div>
        <div class="row">
            <div class="col-lg-9"></div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <form method="post" action="upload.php" enctype="multipart/form-data">
                    <div class="input-group">
                        <label for="list-upload-email">Please enter your email address to receive the administration link</label>
                        <br />
                        <input style="width: 20em;" type="email" name="list-upload-email" id="list-upload-email" class="form-control" />
                    </div>
                    <div>&nbsp;</div>
                    <div class="input-group input-group-lg">
                        <input type="file" name="list-upload-file" id="list-upload-file" class="form-control" />
                        <span class="input-group-btn">
                            <input type="submit" class="btn btn-small upload-file" value="Upload" />
                        </span>
                </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-9">&nbsp;</div>
            <div class="col-lg-9">&nbsp;</div>
        </div>
    <?php endif; ?>

    <div class="container" id="daily-list-container">
        <div style="clear: both; height: 20px;"></div>
        <div class="row">
            <h1>Today's (<span class="todays-date"></span>) almost completely random list</h1>
        </div>
        <div class="row">
            <div class="col-lg-9"></div>
        </div>
        <div class="row">
            <div class="col-lg-9">
                <ul id="random-dev-list" class="list-group">
                    <!-- autofill -->
                </ul>
            </div>
            <div class="col-lg-3">
                <div class="input-group">
                    <input type="text" class="form-control input-small reset-input" />
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-small reset-button">Reset</button>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-danger error-dialog"></div>
    
    <script type="text/javascript" src="public/js/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="public/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        var date = new Date(),
            listId = '<?php echo $listId ?>',
            errorMsg = '<?php echo $error ?>',
            url = 'mandatorsort.php',
            uploadUrl = 'upload.php',
            listPart = '?listid=' + listId;

        url += (listId!== '') ? listPart : '';
        uploadUrl += (listId !== '') ? listPart : '';

        $(document).ready(function () {
            if (errorMsg) {
                $('.error-dialog').empty()
                    .append("<span>It did not work: " + errorMsg + "</span>")
                    .show();
            }
            if (listId) {
                $('#daily-list-container').show();
                $('.todays-date').text(date.toDateString());
                $('#random-dev-list').empty();
                $.getJSON(url, function(data) {
                    if (undefined !== data.error) {
                        $('.error-dialog').empty()
                            .append("<span>It did not work: " + data.error + "</span>")
                            .show();
                    } else {
                        $('.error-dialog').empty().hide();
                    }

                    for (var i = 0; i < data.length; i++) {
                        $('#random-dev-list')
                            .append("<li class=\"list-group-item\">"
                                + "<span class=\"name-text\">" + data[i] + "</span>"
                                + "<span class=\"remove-name glyphicon glyphicon-remove pull-right\" title=\"remove from list temporarily\"></span>"
                                + "</li>\n"
                            );
                    }

                    $('.remove-name').on('click', function() {
                        $(this).parent('.list-group-item').remove();
                    });
                });
            } else {
                $('#daily-list-container').hide();
            }
        });
    </script>
</body>
</html>
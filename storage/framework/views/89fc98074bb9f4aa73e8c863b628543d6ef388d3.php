<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($title); ?></title>

    <link rel="stylesheet" href="<?php echo e(asset('css/custom.css')); ?>">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">


</head>

<body class="antialiased">
    <div class="container">
        <div class="row justify-content-center mt-5">
            
            <div class="h1 font-weight-bold">
                Get Participants list
            </div>

            <div class="col-md-12">

                <?php if(isset($error)): ?>
                    <div id="errorAlert" class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo e($error); ?>

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('get.participants')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="form-row">
                        <div class="form-group col-md-12 my-1">
                            <label for="#JWTToken">Access Token</label>
                            <input type="text" id="JWTToken"
                                class="form-control w-100 <?php $__errorArgs = ['token'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6IlVVNzhVblVyUnAyQnhGN1lMNVVQcVEiLCJleHAiOjE2Mjg3MDY1NDAsImlhdCI6MTYyMDcxNTkzMX0.INVxF6VRUBlJhg5BZ2hC7Wdbs7P7e7PGUlq3Z1xQWHY"
                                name="token">
                            <?php $__errorArgs = ['token'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback">
                                    <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="form-group col-md-12 my-1">
                            <label for="#formDate">From Date</label>
                            <input type="date" id="formDate"
                                class="form-control w-100 <?php $__errorArgs = ['from_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="<?php if(isset($from_date)): ?><?php echo e($from_date); ?><?php endif; ?>" name="from_date">
                                <?php $__errorArgs = ['from_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback">
                                        <?php echo e($message); ?>

                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-12 my-1">
                                <label for="#toDate">To Date</label>
                                <input type="date" id="toDate" pattern="{yyyy-mm-dd}"
                                    class="form-control w-100 <?php $__errorArgs = ['to_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    value="<?php if(isset($to_date)): ?><?php echo e($to_date); ?><?php endif; ?>" name="to_date">
                                    <?php $__errorArgs = ['to_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback">
                                            <?php echo e($message); ?>

                                        </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="from-group col-md-12 mt-3">
                                    <input type="submit" id="getDate" class="btn btn-primary btn-block" value="Submit">
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-12 mt-5">
                        <div class="text-center">
                            <div class="spinner-border" id="loader" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <table id="particitants" class="table">

                        </table>
                    </div>
                </div>
            </div>
            <a href="<?php echo e(route('get')); ?>" target="_blank" class="sheetBtn btn btn-primary d-none">Google Sheet</a>
            <a href="<?php echo e(route('set.empty')); ?>" target="_blank" class="sheetBtn btn btn-primary d-none ml-1">Set Google Sheet
                Empty</a>
            <div class="overlay"></div>


            <script src="<?php echo e(asset('js/part.js')); ?>" defer></script>
            <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.js"></script>

            <script>
                $('#loader').hide();

                $('#getDate').on('click', function() {
                    $('#loader').show();
                    $('body').addClass('loader');
                });
            </script>

            <?php if(isset($response)): ?>

                <script>
                    function filterRow(items, row) {
                        let flag = true;
                        items.forEach(e => {
                            if (e[0] == row[0] && e[4] == row[4] && e[5] == row[5]) {
                                flag = false
                            }
                        })
                        return flag
                    }

                    $(document).ready(function() {

                        $('#loader').hide();
                        $('body').remove('loader');
                        let filteredArr = []
                        let row = <?php echo json_encode($response); ?>;
                        row.forEach(element => {

                            let no_exit = filterRow(filteredArr, element)
                            console.log(no_exit)
                            if (no_exit) {
                                filteredArr.push(element)
                            }
                        });

                        if (filteredArr) {
                            $("#particitants").DataTable({
                                dom: "Bfrtip",
                                processing: true,
                                bSort: true,
                                bPaginate: true,
                                buttons: ["copy", "excel", "pdf", "print"],
                                data: filteredArr,
                                columns: [{
                                        title: "Meeting Id"
                                    },
                                    {
                                        title: "Host Name"
                                    },
                                    {
                                        title: "Meeting Topic"
                                    },
                                    {
                                        title: "Start Time."
                                    },
                                    {
                                        title: "Participant Name"
                                    },
                                    {
                                        title: "Participant Email"
                                    },
                                ],
                            });

                            $(".dt-button").addClass("btn");
                            $(".dt-button").addClass("btn-primary");
                            let sheetBtn = $('.sheetBtn');
                            sheetBtn.removeClass('d-none');
                            $('.dt-buttons').append(sheetBtn)
                        }
                    });
                </script>
            <?php endif; ?>

        </body>

        </html>
<?php /**PATH /Users/mac/Desktop/Laravel Project/zoom-app/resources/views/welcome.blade.php ENDPATH**/ ?>
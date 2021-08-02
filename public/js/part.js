// // const { json } = require("body-parser");

// // const { event } = require("jquery");
// let loader = $("#loader");
// let errorAlert = $("#errorAlert");

// $(document).ready(function () {
//     loader.hide();
//     errorAlert.hide();
//     errorAlert.removeClass("d-none");

//     $(document).on({
//         ajaxStart: function () {
//             $("body").addClass("loading");
//         },
//         ajaxStop: function () {
//             $("body").removeClass("loading");
//         },
//     });
// });

// var userIds = [
//     "uw6GxruSQzu--z_Qf3T_lg",
//     "ZS5tHmgvRSqmm5WytjCUww",
//     "kgsgnW8uRg2CkX3k0C1FfA",
//     "7sg5YIdXSYeGlBJA2uulSg",
//     "DM470HcWTzmh32Fl9WzBfw",
// ];
// var meetings;
// var count = 0;
// var accessToket = "";
// var displayData = [];
// var row = [];
// var partReportsResults = [];
// var x = [];

// var scriptURL =
//     "https://script.google.com/macros/s/AKfycbwNAvJDQ7jy6n1A1pC1ViRrbdD5RvmCtsxAvpTQ1a8BphfAyr-_MTxqRNT7DANa7fx43Q/exec";

// var from = "";
// var to = "";
// function getUniqueListBy(arr, key) {
//     return [...new Map(arr.map((item) => [item[key], item])).values()];
// }

// $("#getData").click(function (event) {
//     event.preventDefault();
//     loader.show();
//     if ($("body")) {
//         $("body").addClass("loading");
//     }
//     if (partReportsResults.length > 0) {
//         partReportsResults = [];
//         row = [];
//         meetings = [];
//     }
//     accessToket = $("#JWTToken").val();
//     from = $("#formDate").val();
//     to = $("#toDate").val();
//     if (!accessToket) {
//         $("#JWTToken").addClass("is-invalid");
//     }

//     if (!from) {
//         $("#formDate").addClass("is-invalid");
//     }
//     if (!to) {
//         $("#toDate").addClass("is-invalid");
//     }
//     $.ajax({
//         type: "GET",
//         url: `https://mighty-bastion-54961.herokuapp.com/meetingsByDate/${accessToket}/past/${from}/${to}/500`,
//         data: { mail: accessToket },
//         async: false,
//         success: function (data) {
//             if (data.code == 124) {
//                 errorAlert.show();
//                 errorAlert.text(data.message);
//                 return;
//             }

//             meetings = data.meetings;
//             data.meetings.forEach((e) => {
//                 if (e.id && e.id != "") {
//                     let uid = encodeURIComponent(
//                         encodeURIComponent(encodeURIComponent(e.uuid))
//                     );

//                     $.ajax({
//                         type: "GET",
//                         url:
//                             `https://mighty-bastion-54961.herokuapp.com/partReports/${accessToket}/` +
//                             encodeURIComponent(encodeURIComponent(e.uuid)),
//                         data: { mail: accessToket },
//                         async: false,
//                         success: function (data) {
//                             partReportsResults.push(
//                                 getUniqueListBy(data.participants, "user_name")
//                             );
//                         },
//                         error: function (jqXHR, textStatus, err) {
//                             errorAlert.show();
//                             errorAlert.text(textStatus);
//                         },
//                     });
//                 }
//             });
//         },
//         error: function (jqXHR, textStatus, err) {
//             errorAlert.show();
//             errorAlert.text(textStatus);
//         },
//     });

//     console.log("partReportsResults", partReportsResults);
//     partReportsResults.forEach((e1, index) => {
//         e1.forEach((e2, i2) => {
//             var temp = [];
//             temp.push(meetings[index].id);
//             temp.push(meetings[index].host);
//             temp.push(meetings[index].topic);
//             let d = moment(meetings[index].start_time).format(
//                 "MM-DD-YYYY hh:ss A"
//             );
//             temp.push(d);
//             temp.push(e2.user_name);
//             e2.email && e2.email != "" ? temp.push(e2.email) : temp.push("-");
//             row.push(temp);
//         });
//     });

//     $(document).ready(function () {
//         // ***************logic****************
//         $.ajax({
//             url: `https://mighty-bastion-54961.herokuapp.com/savePartReport`,
//             type: "post",
//             dataType: "json",
//             data: JSON.stringify({ row: row }),
//             error: function (e) {
//                 console.log(e);
//             },
//             dataType: "json",
//             contentType: "application/json",
//             success: function (response) {
//                 console.log("savePartReport", response);
//             },
//         });
//         // ***************ends*****************
//         loader.hide();
//         if ($("body")) {
//             $("body").removeClass("loading");
//         }

//         $("#particitants").DataTable({
//             dom: "Bfrtip",
//             processing: true,
//             bSort: true,
//             bPaginate: true,
//             buttons: ["copy", "excel", "pdf", "print"],
//             data: row,
//             columns: [
//                 { title: "Meeting Id" },
//                 { title: "Host Name" },
//                 { title: "Meeting Topic" },
//                 { title: "Start Time." },
//                 { title: "Participant Name" },
//                 { title: "Participant Email" },
//             ],
//         });

//         $(".dt-button").addClass("btn");
//         $(".dt-button").addClass("btn-primary");
//     });
// });

<!-- Vendor js -->
 
<script src="<?php echo BASE_URL;?>/assets/js/vendor.min.js"></script>

<!-- App js -->
<script src="<?php echo BASE_URL;?>/assets/js/app.js"></script>
<script src="<?php echo BASE_URL;?>/assets/js/custom/bootstrap-datepicker.min.js"></script>
<!-- <script src="<?php echo BASE_URL;?>/assets/js/jquery-3.6.4.min.js"></script> -->

<script>
$(function () {
  $('.datepicker').datepicker({
      format: "dd-mm-yyyy",
      autoclose: true,
      todayHighlight: true,
      changeMonth: true,       // ✅ Month dropdown
      changeYear: true,        // ✅ Year dropdown
      yearRange: "1950:2050",   // ✅ Year scrolling rang
      orientation: "bottom auto"
  }).on('show', function () {

      // Add Today button once
      if ($(".datepicker .today-btn").length === 0) {
          setTimeout(function(){
              $(".datepicker").append(`
                <button type="button" class="btn btn-primary btn-sm today-btn">Today</button>
              `);

              // Click event
              $(".today-btn").on("click", function () {
                  $('.datepicker').datepicker("setDate", new Date()).datepicker("hide");
              });
          }, 10);
      }
  });

  // Default value today
//   $('.datepicker').datepicker("setDate", new Date());
});
</script>

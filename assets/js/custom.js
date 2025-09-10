$(document).ready(function () {
  $(".helpSelect").change(function () {
    let selectedValue = $(this).val();
    let inputTag = $(this).siblings(".otherServiceInput");

    if (selectedValue === "Other") {
      inputTag.toggle(); // Toggle the input tag's visibility
      $(this).toggle(); // Toggle the select tag's visibility
    } else {
      inputTag.hide(); // Hide the input tag
      $(this).show(); // Show the select tag
    }
  });
});
$(document).ready(function () {
  // Check if the current page is the homepage
  var pathname = window.location.pathname;
  var isHomepage = pathname == "/" || pathname.endsWith("index.html") || pathname.endsWith("/");
  if (isHomepage) {
      // Delay the modal show by 3 seconds
      setTimeout(function () {
          $(".welcomeModal .modal").modal('show');
      }, 1000); // Adjusted to actually wait for 3 seconds as mentioned
  }
});

$(document).ready(function () {
  function startTimer() {
    let hours = 6;
    let minutes = 0;
    let seconds = 0;

    const hoursInput = $('.hoursInput');
    const minutesInput = $('.minutesInput');
    const secondsInput = $('.secondsInput');

    function updateDisplay() {
      hoursInput.text(hours < 10 ? '0' + hours : hours);
      minutesInput.text(minutes < 10 ? '0' + minutes : minutes);
      secondsInput.text(seconds < 10 ? '0' + seconds : seconds);
    }

    const interval = setInterval(function () {
      if (seconds === 0) {
        if (minutes === 0) {
          if (hours === 0) {
            clearInterval(interval);
            return;
          }
          hours--;
          minutes = 59;
        } else {
          minutes--;
        }
        seconds = 59;
      } else {
        seconds--;
      }
      updateDisplay();
    }, 1000); // 1000 milliseconds = 1 second

    updateDisplay(); // Initial display
  }

  // Call the startTimer function when the modal opens
  // For example, you can bind it to a button click event
  // or wherever you are opening the modal.
  startTimer();
});


// offset page section start
// $(document).ready(function () {
// click on nav-link and menu bar disappear 
if ($(window).innerWidth() < 992) {
  $('.navLink').on("click", function () {
    menuToggle();
  });
}

function scrollToSection(target) {
  var $section = $(target);

  if ($section.length) {
    var sectionPosition = $section.offset().top - 110;

    if ($(window).innerWidth() < 568) {
      sectionPosition = $section.offset().top - 75;
    }

    $('html, body').animate({ scrollTop: sectionPosition }, 'slow'); // 'slow' for smooth scrolling
  }
}

$('.navLink').click(function (e) {
  var target = $(this).attr("href"); // Get the href attribute of the clicked link
  scrollToSection(target);
});
// });
// offset page section end
document.addEventListener("DOMContentLoaded", function () {
     // Grab sidebar links and content blocks
     const sidebarLinks = document.querySelectorAll(".sidebar a");
     const contentBlocks = document.querySelectorAll(".content-block");

     // Handle sidebar link clicks
     sidebarLinks.forEach(link => {
          link.addEventListener("click", event => {
               const targetBlock = document.querySelector(link.getAttribute("href"));
               activateSection(targetBlock);
          });
     });

     // Handle clicks (or pointer downs) directly on content blocks
     contentBlocks.forEach(block => {
          block.addEventListener("pointerdown", () => {
               activateSection(block);
          });
     });

     // Function to handle sidebar and card-label activation
     function activateSection(targetCard) {
          // Remove 'active' class from all sidebar links and card labels
          sidebarLinks.forEach(link => link.classList.remove("active"));

          if (targetCard) {
               // Activate corresponding sidebar link
               const targetSidebarLink = document.querySelector(`.sidebar a[href="#${targetCard.id}"]`);
               if (targetSidebarLink) targetSidebarLink.classList.add("active");
          }
     }
});
<!DOCTYPE html>
<html>
    <head>
        <title>Image Gallery</title>
        <style>
            /* Some basic CSS to style the gallery and modal */
            .gallery {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                grid-gap: 20px;
            }
            .gallery img {
                max-width: 100%;
                height: auto;
                cursor: pointer;
            }
            .modal {
                display: none;
                position: fixed;
                z-index: 1;
                padding-top: 100px;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0, 0, 0, 0.9);
            }
            .modal-content {
                margin: auto;
                display: block;
                max-width: 80%;
                max-height: 80%;
            }
            .modal-content img {
                width: 100%;
                height: auto;
            }
            .close {
                position: absolute;
                top: 15px;
                right: 15px;
                color: white;
                font-size: 30px;
                font-weight: bold;
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <h1>Certificates</h1>
        <div class="gallery">
            <?php
                $files = scandir("gallery");
                for ($a = 2; $a < count($files); $a++) {
                    echo "<img src='gallery/" . $files[$a] . "' onclick='openModal(this)'>";
                }
            ?>
        </div>

        <div class="modal">
            <span class="close" onclick="closeModal()">&times;</span>
            <img class="modal-content">
        </div>

        <script>
            // Get the modal and modal image elements
            var modal = document.querySelector('.modal');
            var modalImg = document.querySelector('.modal-content');

            // Function to open the modal and display the clicked image
            function openModal(img) {
                modal.style.display = 'block';
                modalImg.src = img.src;
            }

            // Function to close the modal
            function closeModal() {
                modal.style.display = 'none';
            }

            // Close the modal when the user clicks outside of it
            window.onclick = function(event) {
                if (event.target == modal) {
                    closeModal();
                }
            }
        </script>
    </body>
</html>

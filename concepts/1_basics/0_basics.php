<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo "Basic PHP"; ?></title>
    <style>
        body {
            background-color:#010024;
            color: white;
        }
        #btn1 {
            background-color:rgb(124, 168, 250); 
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        #btn1:hover {
            background-color:rgb(91, 147, 250);
        }
    </style>
</head>
<body>
    
    <h1>
    <?php 
    echo "Basics of PHP!"
    ?>
    </h1>
    <button id="btn1">🌞</button>
    <ul>
        <li>PHP is a server side scripting language which gets embedded in HTML</li>
        <li>PHP returns rendered HTML, CSS, JS by compiling it.</li>
    </ul>
    <img src="/akash-php-notes/concepts/1_basics/img/php-architect.png" 
    alt="PHP architecture" 
    style="max-width: 80%; height: 80%; border: 2px solid white; border-radius: 10px; margin-top: 20px;">
    <br>
    

    <script>
        const btn = document.querySelector("#btn1");
        const body = document.querySelector("body")
        let isDarkTheme = true;

        btn.addEventListener("click", () => {
            if (isDarkTheme) {
                body.style.backgroundColor = "#f5f5ff";
                body.style.color = "#000000";
                btn.textContent = "🌜";
            } else {
               body.style.backgroundColor = "#010024";
               body.style.color = "#ffffff";
               btn.textContent = "🌞";
            }
            isDarkTheme = !isDarkTheme;
        });
    </script>
</body>
</html>
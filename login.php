<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upgrade : The Scheduling platform that works</title>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Roboto:300&display=swap');

        * {
            font-family: 'Roboto', sans-serif;
            box-sizing: border-box;
        }

        body {
            background: url('https://socialmediaweek.org/wp-content/blogs.dir/1/files/collage.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        aside {
            background-color: rgba(11, 1, 11, 0.9);
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 12rem;
            color: white;
            padding: 1.5rem 1rem;
            display: flex;
            flex-flow: column;
        }

        .primary-header {
            font-size: 20px;
        }

        .subtitle {
            font-size: 12px;
            padding-top: 5%;
            padding-bottom: 8%;
        }

        .img-bubble {
            margin: 1rem 0;
            border-radius: 50%;
            height: 5rem;
            width: 5rem;
            background-color: grey;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .img-bubble>img {
            height: 80%;
            width: 80%;
        }

        .welcome-text>.subtitle {
            font-size: 14px;
            font-weight: 600;
        }

        .login-box {
            position: relative;
            margin-top: 15%;
        }

        .login-button {
            border: 1px solid white;
            margin: 1rem;
            text-align: center;
            padding: .5rem 0;
            cursor: pointer;
            transition: .2s all;
        }

        .login-button:hover {
            border: 1px solid grey;
            color: grey;
            transition: .2s all;
        }

        .centerFix {
            position: fixed;
            top: 0;
            left: 12rem;
            width: calc(100% - 12rem);
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-form {
            background-color: rgba(11, 1, 11, 0.9);
            border-radius: 5px;
            display: flex;
            flex-flow: column;
            text-align: center;
            padding: 1.25rem;
            color: white;
            opacity: 0;
            transition: all .2s ease;
        }

        .inputbox {
            border-bottom: 1px solid white;
            display: flex;
            margin: .25rem 1rem;
        }

        .inputbox>.material-icons {
            color: white;
            padding: .2rem;
        }

        input {
            font-size: 18px;
            background: transparent;
            border: none;
            vertical-align: middle;
            color: #fff;
        }
    </style>
</head>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<body>
    <aside>
        <header class="primary-header">
            <strong>Upgrade </strong>
            <br />
            <div id="Placement" class="subtitle">
                The Scheduling software that upgrades for your needs
            </div>
        </header>
        <div class="img-bubble">
            <img src="http://www.clipartbest.com/cliparts/9Tp/dBG/9TpdBGjTE.png" alt="ServerIcon">
        </div>
        <div class="welcome-text">
            <h2>Welcome!</h2>
            <span class='subtitle'>Upgrade: A modular platform for all your scheduling needs.</span>
        </div>
        <div class="login-box">
            <div class="login-button" onClick="openLoginBox()">
                LOGIN
            </div>
        </div>
    </aside>
    <div class="centerFix">
        <div id="login-form" class="login-form">
            <h2>Login</h2>
            <div class="inputbox">
                <span class="material-icons">person_outline</span>
                <input id="username"type="text" placeholder="Username">
            </div>
            <div class="inputbox">
                <span class="material-icons">lock</span>
                <input id="password" type="password" placeholder="Password">
            </div>
        </div>
    </div>
    <script src="./js/db.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function(){
            var pass = document.getElementById("password");
            pass.addEventListener("keyup", function(event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                    requestInfo("./api.php", {
                        fn: 'login',
                        username: document.getElementById("username").value,
                        password: document.getElementById("password").value
                    }).then(function(res) {
                        if(res == 1)
                        {
                            window.location.reload();
                        } else {
                            alert("No Account Found");
                        }
                    })
                }
            })
        })
        function openLoginBox() {
            document.getElementsByClassName('login-form')[0].style.opacity = 1;
        }
    </script>
</body>

</html>
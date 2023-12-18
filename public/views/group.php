<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskmate</title>

    <link rel="stylesheet" href="/public/css/main.css">
    <link rel="stylesheet" href="/public/css/main-layout.css">
    <link rel="stylesheet" href="/public/css/group.css">

    <script src="https://kit.fontawesome.com/00b5fcc6a2.js" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <img src="/public/img/logo.png" alt="Logo">
        <button type="button" id="HeaderUserButton" title="User">
            <i class="fa-regular fa-user"></i>
        </button>
    </header>
    <main>
        <nav>
            <a href="/d">
                <i class="fa-solid fa-list"></i>
                <span>Tasks & notes</span>
            </a>
            <a href="/dashboard">
                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </a>
            <a href="#" class="active">
                <i class="fa-solid fa-user-group"></i>
                <span>My group</span>
            </a>
        </nav>
        <section class="MainContent">
            <h2>Your group</h2>
            <section class="UserList">
                <div class="UserPanel">
                    <div class="UserImage">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <div class="PanelBody">
                        <h3>You (organizer)</h3>
                    </div>
                </div>
                <div class="UserPanel">
                    <div class="UserImage">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <div class="PanelBody">
                        <h3>Camila</h3>
                        <h6>camila@gmail.com</h6>
                        <button>Kick</button>
                    </div>
                </div>
                <div class="UserPanel">
                    <div class="UserImage">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <div class="PanelBody">
                        <h3>Robert</h3>
                        <h6>robert@gmail.com</h6>
                        <button>Kick</button>
                    </div>
                </div>
            </section>
            <section class="JoinSection">
                <h4>Want to add more people?</h4>
                <h6>Just send them this link</h6>
                <div class="InviteLinkHolder">
                    <h6>https://taskmate.com/invite?code=gG2vc</h6>
                    <button><i class="fa-regular fa-copy"></i></button>
                </div>
                <h6 class="RegenerateCodePrompt">Want new one? <a href="#">Regenerate</a></h6>
            </section>
        </section>
    </main>
</body>
</html>
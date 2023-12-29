<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskmate</title>

    <link rel="stylesheet" href="/public/css/main.css">
    <link rel="stylesheet" href="/public/css/form-controls.css">
    <link rel="stylesheet" href="/public/css/fresh-start.css">

    <script src="/public/js/main.js" defer></script>

    <script src="https://kit.fontawesome.com/00b5fcc6a2.js" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <a href="/dashboard">
            <img src="/public/img/logo.png" alt="Logo">
        </a>
        <button type="button" id="HeaderUserButton" title="User">
            <i class="fa-regular fa-user"></i>
        </button>
    </header>
    <main>
        <h2 class="title">Hello Damian</h2>
        <h5 class="subtitle">You don't belong to any group yet.</h5>

        <div class="SectionsWrapper">
            <section>
                <h3>Join existing group</h3>
                <form>
                    <label for="joinCodeInput">Code</label>
                    <input id="joinCodeInput" name="code" type="text">
                    <button type="button">Join</button>
                </form>
            </section>
            <h3 class="SectionDivider">Or</h3>
            <section>
                <h3>Create new group</h3>
                <form>
                    <label for="groupNameInput">Group name</label>
                    <input id="groupNameInput" name="name" type="text">
                    <button type="button">Create</button>
                </form>
            </section>
        </div>
    </main>
    <div id="AccountPopup">
        <div class="PopupHeader">
            <i class="far fa-user"> </i>
            <span>Damian</span>
        </div>
        
        <h4>Your groups</h4>
        <div class="GroupsHolder">
            <a href="#" class="active">
                Test group 1
                <i class="fas fa-check"></i>
            </a>
            <a href="#">
                Test group 2
                <i class="fas fa-check"></i>
            </a>
        </div>
        <a href="/logout">Logout </a>
    </div>
</body>
</html>
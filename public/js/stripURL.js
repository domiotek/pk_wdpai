const search = new URLSearchParams(window.location.search);

if(search.has("r")) {
    window.history.replaceState({}, "Taskmate", window.location.pathname);
}